
/**
 * @file
 * Written by Henri MEDOT <henri.medot[AT]absyx[DOT]fr>
 * http://www.absyx.fr
 *
 * Portions of code:
 * Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

(function() {

	var drupal_items = [];

  // Get a CKEDITOR.dialog.contentDefinition object by its ID.
  var getById = function(array, id, recurse) {
    for (var i = 0, item; (item = array[i]); i++) {
      if (item.id == id) return item;
      if (recurse && item[recurse]) {
        var retval = getById(item[recurse], id, recurse);
        if (retval) return retval;
      }
    }
    return null;
  };

  var initAutocomplete = function(input, uri) {
/*    input.setAttribute('autocomplete', 'OFF');
    new Drupal.jsAC(input, new Drupal.ACDB(uri));*/
    	alert(input);
      input.html('hi');
  };

  var extractPath = function(value) {
    value = CKEDITOR.tools.trim(value);
    var match;
    match = /\(([^\(]*?)\)$/i.exec(value);
    if (match && match[1]) {
      value = match[1];
    }
    var basePath = '/';
    if (value.indexOf(basePath) == 0) {
      value = value.substr(basePath.length);
    }
    if (/^node\/\d+$/.test(value)) {
      return value;
    }
    return false;
  };

  CKEDITOR.plugins.add('paths', {

    init: function(editor, pluginPath) {
      CKEDITOR.on('dialogDefinition', function(e) {
        if ((e.editor != editor) || (e.data.name != 'link')) return;

        // Overrides definition.
        var definition = e.data.definition;
        definition.onFocus = CKEDITOR.tools.override(definition.onFocus, function(original) {
          return function() {
            original.call(this);
            if (this.getValueOf('info', 'linkType') == 'drupal') {
              this.getContentElement('info', 'paths').select();
            }
          };
        });

        // Overrides linkType definition.
        var infoTab = definition.getContents('info');
        var content = getById(infoTab.elements, 'linkType');
        content.items.unshift(['Interne', 'interne']);
        content['default'] = 'interne';
        infoTab.elements.push({
          type: 'vbox',
          id: 'drupalOptions',
          children: [{

			type : 'select',
			id : 'paths',
			label : editor.lang.link.title,
			'default' : '',
			items : [],
			setup : function(data) {
				this.clear();
				for (i in site_menus) {
					this.add(site_menus[i], i);
				}
				
            }
          }]
        });
        content.onChange = CKEDITOR.tools.override(content.onChange, function(original) {
          return function() {
            original.call(this);
            var dialog = this.getDialog();
            
            var element = dialog.getContentElement('info', 'drupalOptions').getElement().getParent().getParent();
            if (this.getValue() == 'interne') {
              element.show();
              if (editor.config.linkShowTargetTab) {
                dialog.showPage('target');
              }
              var uploadTab = dialog.definition.getContents('upload');
              if (uploadTab && !uploadTab.hidden) {
                dialog.hidePage('upload');
              }
            }
            else {
              element.hide();
            }
          };
        });
        content.setup = function(data) {
          if (!data.type || (data.type == 'url') && !data.url) {
            data.type = 'drupal';
          }
          else if (data.url && !data.url.protocol && data.url.url) {
            var path = extractPath(data.url.url);
            if (path) {
              data.type = 'drupal';
              data.paths = path;
              delete data.url;
            }
          }
          this.setValue(data.type);
        };
        content.commit = function(data) {
          data.type = this.getValue();
          if (data.type == 'interne') {
            data.type = 'url';
            var dialog = this.getDialog();
            dialog.setValueOf('info', 'protocol', '');
            dialog.setValueOf('info', 'url', dialog.getValueOf('info', 'paths'));
          }
        };
      });
    }
  });
})();
CKEDITOR.editorConfig = function( config )
{
    config.language = 'fr';
	
	config.forcePasteAsPlainText = true;
	
	config.toolbar = 'Ideva';
	
	config.format_tags = 'h2;h3;h4;h5;h6;p;pre;div';

    config.toolbar_Ideva =
    [
    ['Source','-','Templates'],
    ['Cut','Copy','PasteText'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','HorizontalRule','SpecialChar'],
    ['Format'],
    ['Maximize', 'ShowBlocks','-','About', 'Video']
    ];
    
	config.filebrowserBrowseUrl =  '/lib/js/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = '/lib/js/ckfinder/ckfinder.html';
	config.filebrowserFlashBrowseUrl = '/lib/js/ckfinder/ckfinder.html?Type=Flash';

	config.templates_files =
    [
        '/application/views/ckeditor/templates.js'
    ];
    
    config.contentsCss = '/application/views/ckeditor/contents.css';
	config.scayt_autoStartup = false;

	config.indentClasses = ['indent1', 'indent2', 'indent3', 'indent4'];

	config.extraPlugins = 'paths';
	

};

CKEDITOR.plugins.addExternal('paths', '/lib/js/ckeditor_plugins/link/');


/* 'Paste','PasteFromWord'  'Styles',,'PageBreak'*/
/*     ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],'/',
*/
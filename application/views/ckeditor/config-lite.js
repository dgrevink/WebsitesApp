CKEDITOR.editorConfig = function( config )
{
    config.language = 'fr';
	config.uiColor = '#eee';
	
	config.forcePasteAsPlainText = true;
	
	config.toolbar = 'Ideva';
	
	config.format_tags = 'h2;h3;h4;h5;p';

    config.toolbar_Ideva =
    [
    ['Bold','Italic','Underline','RemoveFormat'],
    ['Format'],
    ];
    
//    config.contentsCss = '/application/lib/css/ckeditor/contents.css';
    config.contentsCss = '/application/views/ckeditor/contents.css';
	config.scayt_autoStartup = false;

	config.indentClasses = ['indent1', 'indent2', 'indent3', 'indent4'];

	config.extraPlugins = 'video,paths';
	

};

CKEDITOR.plugins.addExternal('paths', '/lib/js/ckeditor_plugins/link/');
CKEDITOR.plugins.addExternal('video', '/lib/js/ckeditor_plugins/video/');


/* 'Paste','PasteFromWord'  'Styles',,'PageBreak'*/
/*     ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],'/',
*/

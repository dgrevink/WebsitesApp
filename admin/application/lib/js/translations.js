// This class supports translations for javascript items in the CMS

function Translations(language) {
	this.language = language;
	this.items = {
		'en' : {
			'FILES_RENAME_NEWNAME' : "Enter the new name:",
			'FILES_FILE_CREATE' : "Enter the name of the file:",
			'FILES_DIR_CREATE' : "Enter the name of the directory:",
			'FILES_COMMENT_DELETE' : "Really delete this comment ?",
			'FILES_COMMENT_NEW' : "Comment:",
			'FILES_NORMALIZE_ITEM' : "About to normalize this item. Normalization strips all accents from filenames and replaces all spaces by dashes. Careful !!! If this element is used in your pages, you will have to change them. Are you sure ?",
			'FILES_NORMALIZE_DIR' : "About to normalize all the files in this directory. Normalization strips all accents from filenames and replaces all spaces by dashes. Careful !!! If any of those elements are used in your pages, you will have to change them. Are you sure ?",
			'FILES_DELETE_ITEM' : "This will delete this item. Careful !!! If the element is used in your pages, you will have to change them. Are you sure ?",
			'FILES_DELETE_ITEMS' : "This will delete all the selected items. Careful !!! If those elements are used in your pages, you will have to change them. Are you sure ?",
			'FILES_MOVE_ITEMS_PART1' : "Select a target directory: ",
			'FILES_MOVE_ITEMS_PART2' : "<br/>Careful !<br/>This will move all those elements and could overwrite existing files. Operation cannot be undone. Are you sure ?",
			'FILES_TABLE_LOAD' : "Loading...",
			'FILES_TABLE_FIRST' : "First",
			'FILES_TABLE_PREVIOUS' : "Previous",
			'FILES_TABLE_NEXT' : "Next",
			'FILES_TABLE_LAST' : "Last",
			'FILES_TABLE_INFO_FILTERED' : "(For a total of _MAX_ entries)",
			'FILES_TABLE_LENGTH_MENU' : "Display _MENU_ files at a time",
			'FILES_TABLE_ZERO_RECORDS' : "No data.",
			'FILES_TABLE_ZERO_RECORDS' : "No data.",
			'FILES_TABLE_INFO' : "Showing _START_ to _END_ entries of _TOTAL_ entries. | <label><input type='checkbox' id='file-toggle-all' /> Select all </label><span id='file-action-controls' style='display:none;'> | <select id='file-action'><option value='nothing'>Choose an action...</option><option value='move'>Move...</option><option value='delete'>Delete...</option></select></span>",
			'FILES_CANCEL' : "Cancel",
			'FILES_OK' : "Cancel"
		}
		,
		'fr' : {
			'FILES_RENAME_NEWNAME' : "Entrez le nouveau nom:",
			'FILES_FILE_CREATE' : "Enter the name of the file:",
			'FILES_DIR_CREATE' : "Entrez le nom de votre r&eacute;pertoire:",
			'FILES_COMMENT_DELETE' : "Vraiment enlever ce commentaire ?",
			'FILES_COMMENT_NEW' : "Commentaire:",
			'FILES_NORMALIZE_ITEM' : "Ceci va normaliser le nom de cet &eacute;l&eacute;ment. Attention !!! Si cet &eacute;l&eacute;ment est utilis&eacute; dans vos pages, il faudra veiller &agrave; y changer la r&eacute;f&eacute;rence. &Ecirc;tes-vous certain ?",
			'FILES_NORMALIZE_DIR' : "Ceci va normaliser les noms de tous les fichier de ce repertoire. Si les fichiers sont utilises dans vos pages, il faudra veiller a y changer leurs references. Etes-vous certain ?",
			'FILES_DELETE_ITEM' : "Ceci va effacer cet element. Attention, il ne sera pas possible d'annuler cette operation. &Ecirc;tes-vous certain ?",
			'FILES_DELETE_ITEMS' : "Ceci va effacer tous ces elements. Attention, il ne sera pas possible d'annuler cette operation. &Ecirc;tes-vous certain ?",
			'FILES_MOVE_ITEMS_PART1' : "Sélectionnez un répertoire de destination: ",
			'FILES_MOVE_ITEMS_PART2' : "<br/>Attention !<br/>Ceci va deplacer tous ces elements et cela risque d'écraser des fichiers existants en destination. Il ne sera pas possible d'annuler cette operation. &Ecirc;tes-vous certain ?",
			'FILES_TABLE_LOAD' : "Chargement...",
			'FILES_TABLE_FIRST' : "Premier",
			'FILES_TABLE_PREVIOUS' : "Pr&eacute;c&eacute;dent",
			'FILES_TABLE_NEXT' : "Suivant",
			'FILES_TABLE_LAST' : "Dernier",
			'FILES_TABLE_INFO_FILTERED' : "(Pour un total de _MAX_ enregistrements)",
			'FILES_TABLE_LENGTH_MENU' : "Afficher _MENU_ fichiers par page",
			'FILES_TABLE_ZERO_RECORDS' : "Aucune donn&eacute;es.",
			'FILES_TABLE_NO_DATA' : "Pas de donn&eacute;es.",
			'FILES_TABLE_INFO' : "Donn&eacute;es _START_ &agrave; _END_ affich&eacute;es sur _TOTAL_ enregistements. | <label><input type='checkbox' id='file-toggle-all' /> Tout sélectionner </label><span id='file-action-controls' style='display:none;'> | <select id='file-action'><option value='nothing'>Choisissez une action...</option><option value='move'>Déplacer...</option><option value='delete'>Effacer...</option></select></span>",
			'FILES_CANCEL' : "Annuler",
			'FILES_OK' : "Cancel"
		}
	}
}

Translations.prototype.get = function get(item) {
	return eval('this.items.' + this.language + '.' + item);
}
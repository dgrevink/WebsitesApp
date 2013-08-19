










function change_language() {
	document.language.submit();
}

function jQuerySubmit(id) {
	el = $(id);
	$('textarea.form_textarea').each(function() {
		var oEditor = FCKeditorAPI.GetInstance(this.id);
		if (oEditor) {
			$('#' +this.id).val(oEditor.GetXHTML());
		}
	});
	el.ajaxSubmit(function(data) {
		showNotify(data);
	});
}

function updateselect( table, id, field, value ) {
	$.get('/admin/tables/updatefield/' + table + '/' + id + '/' + field + '/' + value, function(data){
		showNotify(data);
	});
}

function updatecheckbox( table, id, field, value ) {
	if (value == true) {
		value = 1;
	}
	else {
		value = 0;
	}

	$.get('/admin/tables/updatefield/' + table + '/' + id + '/' + field + '/' + value, function(data){
		showNotify(data);
		});
}


function deleterecord( table, id ) {
	$('#dialog-text').html("Voulez-vous vraiment effacer &laquo; " + $('#row-' + id ).html() +  " &raquo; ?");
	$("#dialog-text").dialog({
		resizable: false,
		width: 440,
		modal: true,
		buttons: {
			'OK': function() {
				$.get('/admin/tables/deleterecord/' + table + '/' + id,
					function (data) {
						showNotify(data);
						oTableMain.fnDraw(oTableMain.fnSettings());
					}
				);
				$(this).dialog('close');
			},
			'Annuler': function() {
				$(this).dialog('close');
			}
		}
	});
}

function duplicaterecord( table, id ) {
	$('#dialog-text').html("Voulez-vous vraiment dupliquer &laquo; " + $('#row-' + id ).html() +  " &raquo; ?");
	$("#dialog-text").dialog({
		resizable: false,
		width: 440,
		modal: true,
		buttons: {
			'OK': function() {
				$.get('/admin/tables/duplicaterecord/' + table + '/' + id,
					function (data) {
						showNotify(data);
						oTableMain.fnDraw(oTableMain.fnSettings());
					}
				);
				$(this).dialog('close');
			},
			'Annuler': function() {
				$(this).dialog('close');
			}
		}
	});
}






function createrecord( language, table, action, id, caller_table, caller_id ) {
	$('#dialog-text').load("/admin/tables/getaddform/" + language + "/" + table + "/" + action + "/" + id + "/" + caller_table + "/" + caller_id, function(){
		$("#dialog-text").dialog({
			width: 870,
			maxHeight: 500,
			modal: true,
			open: function(event, ui) {
				$(this).css({'max-height': 500, 'overflow-y': 'auto'}); 
		    },
			buttons: {
				'Sauvegarder': function() {
					$(this).dialog('close');
				},	
				'Annuler': function() {
					$(this).dialog('close');
				}
			}
		});
	});
}










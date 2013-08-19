var sCurrentLanguage = 'fr';


$(document).ready(function(){

	$('#add-newsletter').click( addNews );

			oTableMain = $('#table-main').dataTable( {
				"sPaginationType": "full_numbers",
				"bStateSave": true,
				"iDisplayLength": "25",
				"bAutoWidth": false,
				"bInfo": true,
				"bJQueryUI": false,
				"oLanguage": {
					"sProcessing": "Chargement...",
					"sLengthMenu": "Afficher _MENU_ envois par page ",
					"sZeroRecords": "Aucune donn&eacute;es.",
					"sInfo": "Donn&eacute;es _START_ &agrave; _END_ affich&eacute;es sur _TOTAL_ enregistements.",
					"sInfoEmpty": "Pas de donn&eacute;es.",
					"sInfoFiltered": "(Pour un total de _MAX_ enregistrements)",
					"sSearch": "",
					"oPaginate": {
						"sFirst":    "Premier",
						"sPrevious": "Pr&eacute;c&eacute;dent",
						"sNext":     "Suivant",
						"sLast":     "Dernier"
					}
				}
			} );

});

function deleteNews( id ) {
		$('#dialog-text').html("Cette op&eacute;ration ne peut pas &ecirc;tre annul&eacute;e. Voulez-vous proc&eacute;der ?");
		$("#dialog-text").dialog({
			resizable: false,
			width: 440,
			modal: true,
			buttons: {
				'OK': function() {
					jQuery.ajax({
						type: "GET",
						url: '/admin/newsletters/delete/' + id,
						success: function(msg) {
							if (msg) {
								$('#' + msg).remove();
							}
						}
					});
					$(this).dialog('close');
				},
				'Annuler': function() {
					$(this).dialog('close');
				}
			}
		});

}

function viewNews( id ) {
	//console.log('view: ' + id);
}

function ppNews( id ) {
	jQuery.ajax({
		type: "GET",
		url: '/admin/newsletters/toggle_status/' + id
	});
	redraw();
}

function redraw() {
	$('td.status').each( function(e) {
		$(this).load( '/admin/newsletters/status/' + $(this).parent().attr('id') );
	});
	$('td.completion').each( function(e) {
		$(this).load( '/admin/newsletters/completion/' + $(this).parent().attr('id') );
	});
	$('td.ddate').each( function(e) {
		$(this).load( '/admin/newsletters/ddate/' + $(this).parent().attr('id') );
	});
}


/*
function addNews() {
    news = {
    	title: 			$('#title').val(),
    	block: 			$('#block').val(),
    	filters:		$('#filters').val()
    };
    
	$.ajax({
    	type: "POST",
    	url: '/admin/newsletters/create/',
    	data: news,
    	success: function(msg) {
    		window.location.reload();
    	}
    });

}
*/


function addNews() {
	$('#dialog-text').html(newsletter_html_code);
	$("#dialog-text").dialog({
		resizable: false,
		width: 440,
		modal: true,
		open: function(event, ui){
			$('#ws-input-text-data').focus();
			$('#ws-input-text-data').keypress(function(event){
				if (event.keyCode == '13') {
					$('.ui-dialog-buttonpane > button:first').focus().click();
				}
			});
		},
		buttons: {
			'OK': function() {
				ntitle = $('#ws-input-text-data').val();
				if (ntitle == '') {
					$('#dialog-text').html("Opération annulée, il faut donner un titre.");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								$(this).dialog('close');
							}
						}
					});
				}
				else {
				    news = {
				    	title: 			$('#ws-input-text-data').val(),
				    	block: 			$('#block').val(),
				    	filters:		$('#filters').val()
				    };
				    
					$.ajax({
				    	type: "POST",
				    	url: '/admin/newsletters/create/',
				    	data: news,
				    	success: function(msg) {
				    		//alert(msg);
				    		window.location.reload();
				    	}
				    });
				}
			},
			'Annuler': function() {
				$(this).dialog('close');
			}
		}
	});
};

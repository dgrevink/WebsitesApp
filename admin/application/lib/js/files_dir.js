	function renameFile( dir, file ) {
		$('#dialog-text').html("<p>Entrez le nouveau nom:</p><input id='ws-input-text-data' type='text' value='" + file + "'/>");
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
					nfile = $('#ws-input-text-data').val();
					$(this).dialog('close');
					if (nfile != '') {
						$.ajax({
							type: "POST",
							url: "/admin/files/rename/",
							data: "dir=" + dir + "&old=" + file + "&new=" + nfile,
							success: function(msg){
								if (msg == 'OK') {
									window.location.href=window.location.href;
									document.location.reload();
								}
							}
					 	});
					}
				},
				'Annuler': function() {
					$(this).dialog('close');
				}
			}
		});
	}
	
	function createFile( dir ) {
		nfile = prompt('Entrez le nom de votre fichier:');
		if (nfile != null) {
			$.ajax({
				type: "POST",
				url: "/admin/files/create_file/",
				data: "dir=" + dir + "&filename=" + nfile,
				success: function(msg){
					if (msg == 'OK') {
						window.location.href=window.location.href;
						document.location.reload();
					}
				}
		 	});
 		}
	}
	
	function createDir( dir ) {
		$('#dialog-text').html("<p>Entrez le nom de votre répertoire:</p><input id='ws-input-text-data' type='text' value='Nouveau repertoire'/>");
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
					nfile = $('#ws-input-text-data').val();
					$(this).dialog('close');
					if (nfile != '') {
						$.ajax({
							type: "POST",
							url: "/admin/files/createdir/",
							data: "dir=" + dir + "&dirname=" + nfile,
							success: function(msg){
								if (msg == 'OK') {
									window.location.href=window.location.href;
									document.location.reload();
								}
							}
					 	});
					}
				},
				'Annuler': function() {
					$(this).dialog('close');
				}
			}
		});
	}
	
	function removeCommentDir( dir ) {
		$('#dialog-text').html("Vraiment enlever ce commentaire ?");
		$("#dialog-text").dialog({
			resizable: false,
			width: 440,
			modal: true,
			buttons: {
				'OK': function() {
					$.ajax({
						type: "POST",
						url: "/admin/files/removecommentdir/",
						data: "dir=" + dir,
						success: function(msg){
							if (msg == 'OK') {
								$('#comment .text').html('');
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
	
	function commentDir( dir ) {
		$.ajax({
			type: "POST",
			url: "/admin/files/getcommentdir/",
			data: "dir=" + dir,
			success: function(msg){
				$('#dialog-text').html("<p>Commentaire:</p><input id='ws-input-text-data' type='text' value='" + msg + "'/>");
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
							nfile = $('#ws-input-text-data').val();
							$(this).dialog('close');
							if (nfile != '') {
								$.ajax({
									type: "POST",
									url: "/admin/files/commentdir/",
									data: "dir=" + dir + "&comment=" + nfile,
									success: function(msg){
										if (msg == 'OK') {
											window.location.href=window.location.href;
											document.location.reload();
										}
									}
							 	});
							}
						},
						'Annuler': function() {
							$(this).dialog('close');
						}
					}
				});
			}
	 	});
	}

function commentFile( dir ) {
	$.ajax({
		type: "POST",
		url: "/admin/files/getcommentfile/",
		data: "dir=" + dir,
		success: function(msg){
			$('#dialog-text').html("<p>Commentaire:</p><input id='ws-input-text-data' type='text' value='" + msg + "'/>");
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
						nfile = $('#ws-input-text-data').val();
						$(this).dialog('close');
						$.ajax({
							type: "POST",
							url: "/admin/files/commentfile/",
							data: "dir=" + dir + "&comment=" + nfile,
							success: function(msg){
								if (msg == 'OK') {
									window.location.href=window.location.href;
									document.location.reload();
								}
							}
					 	});
					},
					'Annuler': function() {
						$(this).dialog('close');
					}
				}
			});
		}
 	});
}

	
	function normFile( dir, file, key ) {
		$('#dialog-text').html("Ceci va normaliser le nom de cet &eacute;l&eacute;ment. Attention !!! Si cet &eacute;l&eacute;ment est utilis&eacute; dans vos pages, il faudra veiller &agrave; y changer la r&eacute;f&eacute;rence. &Ecirc;tes-vous certain ?");
		$("#dialog-text").dialog({
			resizable: false,
			width: 440,
			modal: true,
			buttons: {
				'OK': function() {
					$.ajax({
						type: "POST",
						url: "/admin/files/normalize/",
						data: "dir=" + dir + "&old=" + file + '&key=' + key,
						success: function(msg){
							if (msg == 'OK') {
								window.location.href=window.location.href;
								document.location.reload();
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
	
	function duplFile( dir, file ) {
		$.ajax({
			type: "POST",
			url: "/admin/files/duplicate/",
			data: "dir=" + dir + "&old=" + file,
			success: function(msg){
				if (msg == 'OK') {
					window.location.href=window.location.href;
					document.location.reload();
				}
			}
	 	});
	}
	
	function delFile( dir, file ) {
		$('#dialog-text').html("Ceci va effacer cet element. Attention, il ne sera pas possible d'annuler cette operation. &Ecirc;tes-vous certain ?");
		$("#dialog-text").dialog({
			resizable: false,
			width: 440,
			modal: true,
			buttons: {
				'OK': function() {
					$.ajax({
						type: "POST",
						url: "/admin/files/delete/",
						data: "dir=" + dir + "&old=" + file,
						success: function(msg){
							if (msg == 'OK') {
								window.location.href=window.location.href;
								document.location.reload();
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

	function normDir( dir ) {
		$('#dialog-text').html("Ceci va normaliser les noms de tous les fichier de ce repertoire. Si les fichiers sont utilises dans vos pages, il faudra veiller a y changer leurs references. Etes-vous certain ?");
		$("#dialog-text").dialog({
			resizable: false,
			width: 440,
			modal: true,
			buttons: {
				'OK': function() {
					$.ajax({
						type: "POST",
						url: "/admin/files/normalize_dir/",
						data: "dir=" + dir,
						success: function(msg){
							if (msg == 'OK') {
								window.location.href=window.location.href;
								document.location.reload();
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





$(document).ready(function(){

			oTableMain = $('#table-main').dataTable( {
				"sPaginationType": "full_numbers",
				"bStateSave": true,
				"iDisplayLength": "25",
				"bAutoWidth": false,
				"bInfo": true,
				"bJQueryUI": false,
				"oLanguage": {
					"sProcessing": "Chargement...",
					"sLengthMenu": "Afficher _MENU_ fichiers par page",
					"sZeroRecords": "Aucune donn&eacute;es.",
					"sInfo": "Donn&eacute;es _START_ &agrave; _END_ affich&eacute;es sur _TOTAL_ enregistements. | <label><input type='checkbox' id='file-toggle-all' /> Tout sélectionner </label><span id='file-action-controls' style='display:none;'> | <select id='file-action'><option value='nothing'>Choisissez une action...</option><option value='move'>Déplacer...</option><option value='delete'>Effacer...</option></select></span>",
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
			
			$('img.table-thumb').parent().fancybox();
			
			
			$('a.thumb-image-setter').click(function(){
				$.cookie('websites-thumb-size', $(this).text());
				window.location.href=window.location.href;
				document.location.reload();
				return false;
			});
			
			$('#file-toggle-all').change(function(){
				$('.file-toggle').attr('checked', $('#file-toggle-all').attr('checked'));
				$('#file-action-controls').hide();
				$('.file-toggle:checked').each(function(){
					$('#file-action-controls').show();
				});
			});
			$('.file-toggle').change(function(){
				$('#file-action-controls').hide();
				$('.file-toggle:checked').each(function(){
					$('#file-action-controls').show();
				});
			});
			$('#file-action').change(function(){
				action = $(this).val();
				if (action == 'delete') {
					$('#dialog-text').html("Ceci va effacer tous ces elements. Attention, il ne sera pas possible d'annuler cette operation. &Ecirc;tes-vous certain ?");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								files = '';
								$('.file-toggle:checked').each(function(){
									files = files + $(this).attr('rel') + ',';
								});
								$.ajax({
									type: "POST",
									url: "/admin/files/deleteall/",
									data: 'files=' + files,
									success: function(msg){
										if (msg == 'OK') {
											window.location.href=window.location.href;
											document.location.reload();
										}
									}
							 	});
								$(this).dialog('close');
							},
							'Annuler': function() {
								$(this).dialog('close');
								$('#file-action').val('nothing');
							}
						}
					});
				} // action == 'delete'
				if (action == 'move') {
					$.get('/admin/files/getdirs/', function(data){
						var items = [];
						dirs = data.split(',');
						dirselect = '';
						for(i=0;i<dirs.length;i++) {
							dirselect = dirselect + "<option value='" + dirs[i] + "'>" + dirs[i] + "</option>";
						}
						dirselect = "<br/>&nbsp;<select id='destination-dir'>" + dirselect + "</select><br/>";
						$('#dialog-text').html("Sélectionnez un répertoire de destination: " + dirselect + "<br/>Attention !<br/>Ceci va deplacer tous ces elements et cela risque d'écraser des fichiers existants en destination. Il ne sera pas possible d'annuler cette operation. &Ecirc;tes-vous certain ?");
						$("#dialog-text").dialog({
							resizable: false,
							width: 440,
							modal: true,
							buttons: {
								'OK': function() {
									files = '';
									dest = $('#destination-dir').val();
									$('.file-toggle:checked').each(function(){
										files = files + $(this).attr('rel') + ',';
									});
									$.ajax({
										type: "POST",
										url: "/admin/files/move/",
										data: 'dest=' + dest + '&files=' + files,
										success: function(msg){
											if (msg == 'OK') {
												window.location.href=window.location.href;
												document.location.reload();
											}
										}
								 	});
									$(this).dialog('close');
								},
								'Annuler': function() {
									$(this).dialog('close');
									$('#file-action').val('nothing');
								}
							}
						});
					});
				} // action == 'move'
			});


});

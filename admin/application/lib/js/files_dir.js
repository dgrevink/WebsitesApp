l = new Translations(sCurrentLanguage);

	function renameFile( dir, file ) {
		$('#dialog-text').html("<p>" + l.get('FILES_RENAME_NEWNAME') + "</p><input id='ws-input-text-data' type='text' value='" + file + "'/>");
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
		nfile = prompt(l.get('FILES_FILE_CREATE'));
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
		$('#dialog-text').html("<p>" + l.get('FILES_DIR_CREATE') + "</p><input id='ws-input-text-data' type='text'/>");
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
		$('#dialog-text').html(l.get('FILES_COMMENT_DELETE'));
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
				$('#dialog-text').html("<p>" + l.get('FILES_COMMENT_NEW') + "</p><input id='ws-input-text-data' type='text' value='" + msg + "'/>");
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
			$('#dialog-text').html("<p>" + l.get('FILES_COMMENT_NEW') + "</p><input id='ws-input-text-data' type='text' value='" + msg + "'/>");
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
		$('#dialog-text').html(l.get('FILES_NORMALIZE_ITEM'));
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
		$('#dialog-text').html(l.get('FILES_DELETE_ITEM'));
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
		$('#dialog-text').html(l.get('FILES_NORMALIZE_DIR'));
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
					"sProcessing": l.get('FILES_TABLE_LOAD'),
					"sLengthMenu": l.get('FILES_TABLE_LENGTH_MENU'),
					"sZeroRecords": l.get('FILES_TABLE_ZERO_RECORDS'),
					"sInfo": l.get('FILES_TABLE_INFO'), // careful a little HTML code in translations.js
					"sInfoEmpty": l.get('FILES_TABLE_NO_DATA'),
					"sInfoFiltered": l.get('FILES_TABLE_INFO_FILTERED'),
					"sSearch": "",
					"oPaginate": {
						"sFirst":    l.get('FILES_TABLE_FIRST'),
						"sPrevious": l.get('FILES_TABLE_PREVIOUS'),
						"sNext":     l.get('FILES_TABLE_NEXT'),
						"sLast":     l.get('FILES_TABLE_LAST')
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
					$('#dialog-text').html(l.get('FILES_DELETE_ITEMS'));
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
						$('#dialog-text').html(l.get('FILES_MOVE_ITEMS_PART1') + dirselect + l.get('FILES_MOVE_ITEMS_PART2'));
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

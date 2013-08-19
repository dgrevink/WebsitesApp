	function delForm( id ) {
		$('#dialog-text').html("Ceci va effacer cet élément. Attention, il ne sera pas possible d'annuler cette operation. Etes-vous certain ?");
		$("#dialog-text").dialog({
			resizable: false,
			width: 440,
			modal: true,
			buttons: {
				'OK': function() {
					$.ajax({
						type: "POST",
						url: "/admin/forms/delete/",
						data: "id=" + id,
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
	
	function dupForm( id, lang ) {
		$('#dialog-text').html("Ceci va dupliquer cet élément. Etes-vous certain ?");
		$("#dialog-text").dialog({
			resizable: false,
			width: 440,
			modal: true,
			buttons: {
				'OK': function() {
					$.ajax({
						type: "POST",
						url: "/admin/forms/duplicate/",
						data: "id=" + id + "&language=" + lang + "",
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
	
	function createForm( language ) {
		$('#dialog-text').html("<p>Entrez le nom de votre formulaire:</p><input id='ws-input-text-data' type='text' value=''/>");
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
							url: "/admin/forms/create/",
							data: "language=" + language + "&name=" + nfile,
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
	
	/*
	** Submits the save language form
	*/
	function change_language() {
		document.language.submit();
	}

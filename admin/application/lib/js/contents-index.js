var orderer = false;
var sortablemenu = null;
jQuery.curCSS = jQuery.css;

function addMenu(id) {
	$('#dialog-text').html("<p>Entrez le titre de votre menu.</p><input id='ws-input-text-data' type='text' />");
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
				$(this).dialog('close');
				if (ntitle == '') {
					$('#dialog-text').html("Veuillez donner un titre.");
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
					$.ajax({
						type: "POST",
						url: "/admin/content/addmenu/",
						data: "title=" + ntitle + "&parent=" + id + "&language=" + sCurrentLanguage,
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
};

function deleteMenu(id) {
	$('#dialog-text').html("Ceci va effacer cet element et TOUS ses descendants. Attention, il ne sera pas possible d'annuler cette operation. Etes-vous certain ?");
	$("#dialog-text").dialog({
		resizable: false,
		width: 440,
		modal: true,
		buttons: {
			'OK': function() {
				$.ajax({
					type: "POST",
					url: "/admin/content/deletemenu/",
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

$(document).ready(function(){
	$('a.menu-orderer').click(function(){
		sortablemenu = $('#menus ul').nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			items: 'li',
			opacity: .6,
			placeholder: 'placeholder',
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div',
			listType: 'ul'
		});
		$('a.menu-orderer').hide();
		$('a.menu-orderer-saver').show();
		$('a.menu-orderer-cancel').show();
		$('.disposable').hide();
	});
	$('a.menu-orderer-cancel').click(function(){
		location.reload();
	});
	$('a.menu-orderer-saver').click(function(){
		serialized = $('#menus ul').nestedSortable('serialize');
	    $.ajax({
	        url: '/admin/Content/savemenu/',
	        type: 'post',
	        data: { data: serialized },
	        success: function(data) {
	        	if (data == 'OK') {
	        		location.reload();
	        	}
	        	else {
	        		alert('Erreur à la sauvegarde.');
	        	}
	        }
		})
	});
});

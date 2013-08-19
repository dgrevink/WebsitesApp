<div id='ws-profile-form'>
<div id='ws-profile-form-results' style='display: none;'>&nbsp;</div>
{$form_code}
</div>

{literal}
<script type="text/javascript" src="../../../lib/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../../lib/js/jquery/jquery.password_strength.js"></script>
<script>
	$(document).ready(function(){
		$('#ws-profile-submit').click(function(){
			var options = {
				 beforeSubmit: function(formData, jqForm, options) {
					$('html,body').animate({scrollTop: $('#ws-profile-form').offset().top-50},500);
					$('#ws-profile-form-results').fadeOut();
				 },
				 success: function(responseText, statusText, xhr, $form) {
				 		var disp = $('#ws-profile-form-results');
				 		if (responseText == 'OK') {
				 			disp.html("Votre profil a été sauvegardé !");
				 			disp.removeClass('ws-profile-form-errors');
				 			disp.addClass('ws-profile-form-ok');
				 		}
				 		else {
				 			switch (responseText) {
				 				case 'NO_OR_BAD_EMAIL':
				 					disp.html("Votre courriel n'est pas valide,<br/>votre profil n'a pas été sauvegardé.");
				 				break;
				 				case 'BAD_AUTH':
				 				case 'BAD_USERNAME':
				 				case 'NO_RECORD':
				 				default:
				 					disp.html("Une erreur s'est produite et votre profil n'a pas été sauvegardé.");
				 				break;
				 			}
				 			disp.addClass('ws-profile-form-errors');
				 			disp.removeClass('ws-profile-form-ok');
				 		}
				 		disp.fadeIn();
				 }
			};
			$('#ws-profile-form form').ajaxSubmit(options);
		});
	});
</script>
{/literal}

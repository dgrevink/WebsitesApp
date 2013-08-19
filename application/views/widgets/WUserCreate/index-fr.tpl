<div id='ws-profile-form'>
<div id='ws-profile-form-results' style='display: none;'>&nbsp;</div>
{$form_code}
</div>
<br/>
<br/>
<br/>

{literal}
<script type="text/javascript" src="../../../lib/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../../lib/js/jquery/jquery.password_strength.js"></script>
<script>
	var submit_ok = false;

	function check_form(){
		// Check username
		var username = $('#username').val();
		if (username.length < 3) {
			$('#ws-profile-usertaken').html("Ce nom d'utilisateur est trop court.");
			submit_ok = false;
		}
		else {
			$.get('/WUserCreate/userexists/' + username,
				function(data) {
					if (data == '1') {
						$('#ws-profile-usertaken').html("Ce nom d'utilisateur existe.");
						submit_ok = false;
					}
					else {
						$('#ws-profile-usertaken').html('Ce nom est disponible !');
						submit_ok = true;
					}
				}
			);
		}
		
		// Check password
		var password = $('#password').val();
		var password_CONFIRM = $('#password_CONFIRM').val();
		if ( password != password_CONFIRM ) {
			submit_ok = false;
		}
		if ( password.length == 0) {
			submit_ok = false;
		}
		
		// Check email
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		if (reg.test($('#email').val()) == false) {
			submit_ok = false;
			$('#ws-profile-invalidemail').html('Ce courriel est invalide.');
		}
		else {
			$('#ws-profile-invalidemail').html('Courriel valide !');
		}

	}

	$(document).ready(function(){
		$('#ws-profile-submit').click(function(){
			var options = {
				 beforeSubmit: function(formData, jqForm, options) {
					$('html,body').animate({scrollTop: $('#ws-profile-form').offset().top-50},500);
					$('#ws-profile-form-results').fadeOut();
					$('#ws-profile-form').hide();
				 },
				 success: function(responseText, statusText, xhr, $form) {
				 		var disp = $('#ws-profile-form-results');
				 		if (responseText == 'OK') {
				 			$('#ws-profile-form').html("Votre compte a été créé, un courriel de confirmation a été envoyé à " + $('#email').val() + " !");
							$('#ws-profile-form').show();
				 			disp.hide();
				 		}
				 		else {
							$('#ws-profile-form').show();
				 			switch (responseText) {
				 				case 'NO_OR_BAD_EMAIL':
				 					disp.html("Votre courriel n'est pas valide,<br/>votre profil n'a pas été sauvegardé.");
				 				break;
				 				case 'USER_TAKEN':
				 					disp.html("Nom d'utilisateur pris.");
				 				break;
				 				case 'BAD_AUTH':
				 				case 'BAD_USERNAME':
				 				case 'NO_RECORD':
				 				default:
				 					alert(responseText);
				 					disp.html("Une erreur s'est produite et votre profil n'a pas été sauvegardé.");
				 				break;
				 			}
				 			disp.addClass('ws-profile-form-errors');
				 			disp.removeClass('ws-profile-form-ok');
				 			
				 		}
				 		disp.fadeIn();
				 }
			};
			check_form();
			if (!submit_ok) {
				alert("Vérifiez votre nom d'utilisateur ( il doit être disponible ), votre mot de passe ainsi que votre courriel SVP.");
			}
			else {
				$('#ws-profile-form form').ajaxSubmit(options);
			}
		});
		$("<div id='ws-profile-usertaken'>Ce nom d'utilisateur est trop court.</div>").insertAfter('#username');
		$("<div id='ws-profile-invalidemail'>Ce courriel est invalide.</div>").insertAfter('#email');
		$('#username').keyup(check_form);
		$('#password').keyup(check_form);
		$('#password_CONFIRM').keyup(check_form);
		$('#email').keyup(check_form);
		
		
		// Add here any customisations
		// ACEF: IF passed GROUP = 7 ( association )
		if ($('#default_g').val() == 7) {
			$('#matierescolaire_container').hide();
			$('#nbjeunes_container').hide();
			$('#users_id_container').hide();
		}

	});
</script>
{/literal}

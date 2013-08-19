<div id='ws-profile-form'>
<div id='ws-profile-form-results' style='display: none;'>&nbsp;</div>
{$form_code}
</div>

{literal}
<script type="text/javascript" src="../../../lib/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../../lib/js/jquery/jquery.password_strength.js"></script>
<script>
	var submit_ok = false;

	function check_form(){
		// Check username
		var username = $('#username').val();
		if (username.length < 3) {
			$('#ws-profile-usertaken').html("That username is too short.");
			submit_ok = false;
		}
		else {
			$.get('/WUserCreate/userexists/' + username,
				function(data) {
					if (data == '1') {
						$('#ws-profile-usertaken').html("That username is already taken.");
						submit_ok = false;
					}
					else {
						$('#ws-profile-usertaken').html('Username available !');
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
			$('#ws-profile-invalidemail').html('Invalid email.');
		}
		else {
			$('#ws-profile-invalidemail').html('Email is valid !');
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
				 			$('#ws-profile-form').html("Your user account has been created and a confirmation email has been sent to " + $('#email').val() + " !");
							$('#ws-profile-form').show();
				 			disp.hide();
				 		}
				 		else {
							$('#ws-profile-form').show();
				 			switch (responseText) {
				 				case 'NO_OR_BAD_EMAIL':
				 					disp.html("Your email was not valid and your user account has not been created.");
				 				break;
				 				case 'USER_TAKEN':
				 					disp.html("Username already taken.");
				 				break;
				 				case 'BAD_AUTH':
				 				case 'BAD_USERNAME':
				 				case 'NO_RECORD':
				 				default:
				 					alert(responseText);
				 					disp.html("An error has occurred and your user account has not been created.");
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
				alert("Please check that the username is valid, that you have a password and a valid email.");
			}
			else {
				$('#ws-profile-form form').ajaxSubmit(options);
			}
		});
		$("<div id='ws-profile-usertaken'>That username is too short.</div>").insertAfter('#username');
		$("<div id='ws-profile-invalidemail'>Email invalid.</div>").insertAfter('#email');
		$('#username').keyup(check_form);
		$('#password').keyup(check_form);
		$('#password_CONFIRM').keyup(check_form);
		$('#email').keyup(check_form);
	});
</script>
{/literal}

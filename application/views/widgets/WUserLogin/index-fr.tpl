<div class='ws-login-form-container'>
<form method='post' action='{$action}#comment'>

	<div class='ws-login-username-container'>
		<label for='ws-login-username'>Utilisateur: </label>
		<input type='text' value='' name='username' id='ws-login-username'/></label>
	</div>

	<div class='ws-login-password-container'>
		<label for='ws-login-password'>Mot de passe: </label>
		<input type='password' name='password' id='ws-login-password'/>
	</div>
<div style='clear: both; margin:0; padding:0; height:1px;'>&nbsp;</div>

{if $error_message == 'NO-PAGE-ACCESS'}<span class='ws-login-form-error'>Vous n'avez pas le droit d'acc&eacute;der &agrave; cette page et avez été déconnecté.</span>{/if}
{if $error_message == 'BAD-LOGIN-OR-PASSWORD'}<span class='ws-login-form-error'>Login ou mot de passe erron&eacute; !</span>{/if}
{if $error_message == 'IDLED'}<span class='ws-login-form-error'>Votre session a expiré et vous êtes déconnecté.</span>{/if}
<br/>	<input type='submit' value='Continuer'  id='ws-login-submit'  class='button bigrounded bbblue' />
</form>
</div>

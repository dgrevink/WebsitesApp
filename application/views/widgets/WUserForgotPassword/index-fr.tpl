
{if $showform}
<div class='ws-forgot-password-intro'>
<p>Vous vous apprêtez à faire une demande de changement de mot de passe. Veuillez fournir le courriel que vous avez utilisé lors de votre inscription, et nous vous enverrons un nouveau mot de passe.</p>
</div>
<div class='ws-forgot-password-form-container'>
	<form method='post' action='{$action}'>
		<div class='ws-forgot-password-email-container'>
			<label for='ws-login-username'>Courriel: </label>
			<input type='text' value='' name='email' id='ws-forgot-password-email'/></label>
		</div>
		<input type='submit' value='Envoyer la demande'  id='ws-login-submit' class='button bigrounded bbblue'/>
	
	</form>
</div>
{/if}

{if $showretrieved}
<div class='ws-forgot-password-retrieved'>
<p>
Un nouveau mot de passe a été créé et envoyé au courriel que vous avez fournit.
</p>
</div>
{/if}

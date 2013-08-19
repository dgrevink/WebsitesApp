{if $showform}
<div class='ws-forgot-password-intro'>
<p>In order to retrieve a new password, please provide the email address associated with the account you want to retrieve. We will send you the new password at that email address.</p>
</div>
<div class='ws-forgot-password-form-container'>
	<form method='post' action='{$action}'>
	
		<div class='ws-forgot-password-email-container'>
			<label for='ws-login-username'>Email: </label>
			<input type='text' value='' name='email' id='ws-forgot-password-email'/></label>
		</div>
		
		<input type='submit' value='Retrieve new password'  id='ws-login-submit'/>
	
	</form>
</div>
{/if}

{if $showretrieved}
<div class='ws-forgot-password-retrieved'>
A new password has been sent at the provided email address.
</div>
{/if}

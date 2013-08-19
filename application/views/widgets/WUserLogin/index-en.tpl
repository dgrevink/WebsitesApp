<div class='ws-login-form-container'>
<form method='post' action='{$action}'>

	<div class='ws-login-username-container'>
		<label for='ws-login-username'>User: </label>
		<input type='text' value='' name='username' id='ws-login-username'/></label>
	</div>

	<div class='ws-login-password-container'>
		<label for='ws-login-password'>Password: </label>
		<input type='password' name='password' id='ws-login-password'/>
	</div>

	<input type='submit' value='Continue'  id='ws-login-submit'/>

</form>
{if $error_message == 'NO-PAGE-ACCESS'}<span class='ws-login-form-error'>You are not allowed to access this page !</span>{/if}
{if $error_message == 'BAD-LOGIN-OR-PASSWORD'}<span class='ws-login-form-error'>Wrong login or password !</span>{/if}
{if $error_message == 'IDLED'}<span class='ws-login-form-error'>Your session has expired and you have been disconnected.</span>{/if}
</div>

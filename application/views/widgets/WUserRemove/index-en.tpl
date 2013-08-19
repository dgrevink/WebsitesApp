<div class='ws-remove-form-container'>
{if !$removed}
<form method='post' id='ws-remove-form' action='{$action}'>
	<span id='ws-user-longname'><span>Connected as:</span> {$user_longname}, your username: {$username}</span>
	<input id='ws-remove-button' name='ws-remove-button' type='submit' value='Remove me' />
	<input type='hidden' name='remove' value='true' />
</form>
{else}
<span class='ws-removed-message'>You profile is now deactivated and you have been disconnected from the site ! <a href='/en/'>Clic here to get back to the homepage.</a></span>
{/if}
</div>
{literal}
<script>
	$('#ws-remove-button').click(function(){
		return (confirm("Do you really want to remove your profile from the site ? This operation cannot be cancelled !"));
	});
</script>
{/literal}

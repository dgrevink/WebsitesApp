<div class='ws-remove-form-container'>
{if !$removed}
<form method='post' id='ws-remove-form' action='{$action}'>
	<span id='ws-user-longname'><span>Connecté en tant que:</span> {$user_longname}, votre code utilisateur est: {$username}</span>
	<input id='ws-remove-button' name='ws-remove-button' type='submit' value='Me désinscrire' />
	<input type='hidden' name='remove' value='true' />
</form>
{else}
<span class='ws-removed-message'>Vous êtes maintenant désinscrit et déconnecté du site ! <a href='/'>Cliquez ici pour revenir à l'accueil.</a></span>
{/if}
</div>
{literal}
<script>
	$('#ws-remove-button').click(function(){
		return (confirm("Voulez-vous vraiment vous désinscrire de ce site ? L'opération ne pourra pas être annulée !"));
	});
</script>
{/literal}

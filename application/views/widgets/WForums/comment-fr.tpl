<span class='comment level-{$comment->level}'>
	<p>{$comment->content}</p>
	<p class='info'>Posté par <a href='{$comment->profile_url}'>{$comment->username}</a> le {$comment->postdate|date_format:"%A %e %B à %H:%M:%S"}<a href='#' class='buttonreact' rel='{$comment->id}'>{if !$connected}Connectez-vous pour réagir !{else}Réagir{/if}</a></p>
	{if $connected}
	<div class='subcommentform' style='display: none;' id='replyform-{$comment->id}'>
		<form method='post' action='#comment'>
			<textarea name='comment' class='textareacomment'></textarea><br/>
			<input type="hidden" name="posted" value="posted" />
			<input type="hidden" name="title" class='title' value="{$comment->thread_title}" />
			<input type="hidden" name="relatedpostid" class='relatedpostid' value="{$comment->id}" />
			<input type="hidden" name="threadid" class='threadid' value="{$comment->thread_id}" />
			<input type="submit" class='buttonsubmit' value="Envoyer"/>
		</form>
	</div>
	{/if}
</span>

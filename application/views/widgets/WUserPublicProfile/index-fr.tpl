<div class='ws-public-profile'>
	<h3>{$user->title}</h3>
	<p><em>Membre de {$sitename} depuis le {$user->membersince|date_format:"%A %e %B à %H:%M:%S"}</em></p>
	<p>{$user->bio|nl2br}</p>
	<hr/>
	<div class='comments'>
		{section name=comments loop=$comments}
			<span class='comment'>
				<p>{$comments[comments].content}</p>
				<p class='info'>Posté le {$comments[comments].postdate|date_format:"%A %e %B à %H:%M:%S"} à propos de <a href='{$comments[comments].thread_url}'>{$comments[comments].thread_page_title}</a></p>
			</span>
		{/section}
	</div>
</div>

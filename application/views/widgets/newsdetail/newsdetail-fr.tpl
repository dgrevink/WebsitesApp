{if $notitleseo == 'true'}
<div class="info-box">
	<div class="heading">
		<a href="/fr/a-propos/nouvelles/" class="more">Autres nouvelles</a>
		<h2>Nouvelles</h2>
	</div>
	<span class="title">{$news->title}</span>
	{$news->minicontents}
	<a href="/fr/news-details/+{$news->titleseo}" class="more">D&eacute;tails...</a>
</div>
{else}
<div class='ws-news-item'>
	<h3>
			{$news->title}
	</h3>
	<span class='contents'>
		{$news->contents}
	</span>
</div>
{/if}

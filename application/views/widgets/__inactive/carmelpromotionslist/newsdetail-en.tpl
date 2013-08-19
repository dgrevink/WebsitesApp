{if $notitleseo == 'true'}
<div class="info-box">
	<div class="heading">
		<a href="/en/about-us/news/" class="more">More news</a>
		<h2>News</h2>
	</div>
	<span class="title">{$news->title}</span>
	{$news->minicontents}
	<a href="/en/news-details/+{$news->titleseo}" class="more">Details...</a>
</div>
{else}
<div class='ws-news-item'>
	<h2>
		<a href='/en/about-us/news/'>
			{$news->title}
		</a>
	</h2>
	<em>{$news->ddate}</em>
	<span class='contents'>
		{$news->contents}
	</span>
</div>
{/if}

{section name=news loop=$news}
<div class='ws-news-menu-item'>
	<h3><span class='token'>&nbsp;</span>
		<a href='/en/news-details/+{$news[news].titleseo}'>
			{$news[news].title}
		</a>
		- <em>{$news[news].ddate}</em>
	</h3>
</div>
{/section}

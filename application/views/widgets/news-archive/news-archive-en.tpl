<br/>
{section name=news loop=$news}
	<h4>{$news[news].title}</h4>
	<p><em>Posted: {$news[news].ddate}</em></p>
	{$news[news].blurb}
	<p><a href="/en/news-detail/+{$news[news].titleseo}" title="Read more...">Read more...</a></p>
	{if !$smarty.section.news.last}<hr />{/if}
{sectionelse}
<h4>No news for this year.</h4>
{/section}
<br/>
<br/>
<br/>
<br/>

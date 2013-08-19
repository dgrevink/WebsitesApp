<br/>
{section name=news loop=$news}
	<h4>{$news[news].title}</h4>
	<p><em>Postée: {$news[news].ddate}</em></p>
	{$news[news].blurb}
	<p><a href="/fr/nouvelles-detail/+{$news[news].titleseo}" title="Lire la suite...">Lire la suite...</a></p>
	{if !$smarty.section.news.last}<hr />{/if}
{sectionelse}
<h4>Pas de nouvelles pour cette année.</h4>
{/section}
<br/>
<br/>
<br/>
<br/>

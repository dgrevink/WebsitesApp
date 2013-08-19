<div id='news'>
	<div class='slides_container'>
	
		{section name=news loop=$news}
		<div>
			<img class='image' src='{$news[news].image}' />
			<h3>{$news[news].title}</h3>
			<p>{$news[news].blurb}</p>
			<p><a class="next-link" href="/fr/nouvelles-detail/+{$news[news].titleseo}" title="Lire la suite...">Lire la suite...</a></p>
		</div>
		{/section}
	
	</div>
</div>
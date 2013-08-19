<ul class='ws-portfolio'>
{section name=portfolio loop=$portfolio}
<li class='ws-portfolio-item {if $smarty.section.portfolio.iteration % 3 == 1}ws-portfolio-item-first-column{/if}'>
	<a href='{$portfolio[portfolio].image1}' class='ws-portfolio-item-link gal-{$portfolio[portfolio].id}' title="{$portfolio[portfolio].image1caption}">
		<img src='{$portfolio[portfolio].thumb}' title="{$portfolio[portfolio].title}" />
		<div class='ws-portfolio-item-hover'>&nbsp;</div>
	</a>
	{if $portfolio[portfolio].image2 != ''}<a href='{$portfolio[portfolio].image2}' class='ws-portfolio-item-link gal-{$portfolio[portfolio].id}' title="{$portfolio[portfolio].image2caption}" style='display: none;'>&nbsp;</a>{/if}
	{if $portfolio[portfolio].image3 != ''}<a href='{$portfolio[portfolio].image3}' class='ws-portfolio-item-link gal-{$portfolio[portfolio].id}' title="{$portfolio[portfolio].image3caption}" style='display: none;'>&nbsp;</a>{/if}
	{if $portfolio[portfolio].image4 != ''}<a href='{$portfolio[portfolio].image4}' class='ws-portfolio-item-link gal-{$portfolio[portfolio].id}' title="{$portfolio[portfolio].image4caption}" style='display: none;'>&nbsp;</a>{/if}
	{if $portfolio[portfolio].image5 != ''}<a href='{$portfolio[portfolio].image5}' class='ws-portfolio-item-link gal-{$portfolio[portfolio].id}' title="{$portfolio[portfolio].image5caption}" style='display: none;'>&nbsp;</a>{/if}
	{if $portfolio[portfolio].image6 != ''}<a href='{$portfolio[portfolio].image6}' class='ws-portfolio-item-link gal-{$portfolio[portfolio].id}' title="{$portfolio[portfolio].image6caption}" style='display: none;'>&nbsp;</a>{/if}
	<h4>{$portfolio[portfolio].title}</h4>
	<p>{$portfolio[portfolio].intro}</p>
</li>
{/section}
</ul>

		<div class='clearing'>&nbsp;</div>

<script>
	{literal}
	$(document).ready(function(){
		$('a.ws-portfolio-item-link').hover(
			function() {
				$(this).children('.ws-portfolio-item-hover').stop(true,true).fadeIn();
			},
			function() {
				$(this).children('.ws-portfolio-item-hover').stop(true,true).fadeOut();
			}
		);
		{/literal}
{section name=portfolio loop=$portfolio}
		$('a.gal-{$portfolio[portfolio].id}').lightBox();
{/section}
		{literal}
	});
	{/literal}
</script>
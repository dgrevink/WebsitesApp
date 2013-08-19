{if $news}
<div class='ws-news-item'>
	<h3 style='padding-top: 16px;'>{$news->title}</h3>
	<h5><a href='/fr/tarifs/promotions/' class='return'>Retourner à nos promotions</a></h5>
	<h4>{$news->promotext}: {$news->promoprice}</h4>
	<span class='contents'>
		<img src='{$news->image}' class='alignleft' title='{$news->title}'/>
		{$news->description}
	</span>
</div>
<div class="fb-like" data-href="http://www.skimontcarmel.com/" data-send="true" data-show-faces="true" data-width="450">
	&nbsp;</div>
<br/><br/><br/>
{/if}

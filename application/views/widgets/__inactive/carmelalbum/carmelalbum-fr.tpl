{if $mode == 'list'}
<h2>Nos albums photos</h2>
<ul class="albumlist">
{section name=photos loop=$photos}
	<li class="{cycle values='odd,even'}">
		<a href="/fr/bienvenue/photos/+{$photos[photos].titleseo}">
			<img class="photo" src="../../..{$photos[photos].thumbnail}" alt="{$photos[photos].title}" />
			<span class="description"><strong>{$photos[photos].ddate}</strong>{$photos[photos].title}</span>
		</a>
	</li>
{/section}
</ul>
<br/><br/><br/>
{/if}


{if $mode == 'album'}
<span class='ws-albums'>
<h3>{$album->title}</h3>
<h5><a href='/fr/bienvenue/photos/' class='return'>Retourner aux albums</a></h5>
<p>{$album->ddate}</p>
<div id='photoholder'><img src='{$album->first}' img-ref='1'>
</div>
<br/>
<div class='ws-album-controls'>
<a href='#' id='ws-album-prec'>&larr;</a>&nbsp;&nbsp;&nbsp;<a href='#' id='ws-album-next'>&rarr;</a>
</div>
<div style='clear: both'></div>
<ul class="photolist">
{section name=photos loop=$photos}
	<li>
		<a href="#" img-link="../../../{$photos[photos].image}" img-ref="{$photos[photos].id}">
			<img src="../../../{$photos[photos].thumb}" />
		</a>
	</li>
{/section}
</ul>
<script>
var max_items = {$max_items};
{literal}
$(document).ready(function(){
	$('ul.photolist li a').click(function(){
		$('ul.photolist li a img').removeClass('highlighted');
		$(this).children().addClass('highlighted');
		$('#photoholder img').attr('src', $(this).attr('img-link')).attr('img-ref', $(this).attr('img-ref'));
		return false;
	});
	$('#ws-album-prec').click(function(){
		var ref = $('#photoholder img').attr('img-ref');
		ref = parseInt(ref) - 1;
		if (ref < 1) ref = max_items;
		$('ul.photolist li a img').removeClass('highlighted');
		thumb = $('ul.photolist li a[img-ref=' + ref + ']');
		thumb.children().addClass('highlighted');
		$('#photoholder img').attr('src', thumb.attr('img-link')).attr('img-ref', thumb.attr('img-ref'));
		
	});
	$('#ws-album-next').click(function(){
		var ref = $('#photoholder img').attr('img-ref');
		ref = parseInt(ref) + 1;
		if (ref > max_items) ref = 1;
		$('ul.photolist li a img').removeClass('highlighted');
		thumb = $('ul.photolist li a[img-ref=' + ref + ']');
		thumb.children().addClass('highlighted');
		$('#photoholder img').attr('src', thumb.attr('img-link')).attr('img-ref', thumb.attr('img-ref'));
		
	});
});
</script>
{/literal}
<br/><br/><br/>
</span>
{/if}
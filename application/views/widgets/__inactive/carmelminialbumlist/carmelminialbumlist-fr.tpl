<!-- ALBUMS PHOTOS -->
<div class="box">
	<div class="heading">
		<h3>DERNIERS ALBUMS PHOTOS</h3>
	</div>
	<ul class="list">
{section name=photos loop=$photos}
		<li class="{cycle values=',blue'}">
			<a href="/fr/bienvenue/photos/+{$photos[photos].titleseo}">
				<img class="photo" src="../../..{$photos[photos].thumbnail}" width="76" height="80" alt="{$photos[photos].title}" />
				<span class="description"><strong>{$photos[photos].ddate}</strong>{$photos[photos].title}</span>
			</a>
		</li>
{/section}
	</ul>
</div>

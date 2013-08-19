<section class='site-item'>
<section class='title'>
	<h3>Article Blog du moment</h3>
	<h5><a href='http://blog.starplace.org'>posterous.com</a></h5>
</section>
<section class='meat'>
<div class='ws-posterous'>
{section name=posts loop=$posts}
<div class='ws-posterous-entry'>
<h4><a href='{$posts[posts].link}' title='{$posts[posts].title}'>{$posts[posts].title}</a></h4>
{$posts[posts].body}
</div>
{/section}
</div>
</section>
</section>






<link href="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>


<ul class='ws-photogallery'>
{section name=photos loop=$photos}
<li class='ws-photogallery-item'>
	<a rel='grouped' href='{$photos[photos].photo}' title="{$photos[photos].title}">
		<div class='holder-mini-image'><img src='{$photos[photos].thumb}' alt="{$photos[photos].title}" /></div>
	</a>
</li>
{/section}
</ul>

		<div class='clearing'>&nbsp;</div>

<script>
	{literal}
	$(document).ready(function(){
		$('ul.ws-photogallery li a').fancybox({
			'hideOnContentClick': true,
			'titlePosition' : 'over'
		});
	});
	{/literal}
</script>
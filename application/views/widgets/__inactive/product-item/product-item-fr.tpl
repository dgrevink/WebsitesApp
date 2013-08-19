<!--container-->
<div class="container">
	<!--image holder-->
	{if $product->image != ''}
	<div class="image-holder">
		<img src="{$product->image}" alt="{$product->title}" />
	</div>
	{/if}
	<!--text holder-->
	<div class="text-holder">
		{$product->contents}
		<!--visual holder-->
		{if $product->media != ''}
		<div class="visual-holder">
			<div class="visual">
				{$product->media}
			</div>
			<div class="box-info">
				<div class="box-info-holder">
					{$product->mediadescription}
				</div>
			</div>
		</div>
		{/if}
	</div>
</div>
<!--area-->
<div class="area">
	<!--tabs area-->
	<div class="tabs-area">
		<!--tabset-->
		<ul class="tabset">
			{if $product->titre1 != ''}<li><a href="#" id='1' class="tab active">{$product->titre1}</a></li>{/if}
			{if $product->titre2 != ''}<li><a href="#" id='2' class="tab">{$product->titre2}</a></li>{/if}
			{if $product->titre3 != ''}<li><a href="#" id='3' class="tab">{$product->titre3}</a></li>{/if}
			{if $product->titre4 != ''}<li><a href="#" id='4' class="tab">{$product->titre4}</a></li>{/if}
		</ul>
		<!--tab content-->
		<div class="tab-content" id="tab1">
			{$product->titre1text}
		</div>
		<div class="tab-content hidden" id="tab2">
			{$product->titre2text}
		</div>
		<div class="tab-content hidden" id="tab3">
			{$product->titre3text}
		</div>
		<div class="tab-content hidden" id="tab4">
			{$product->titre4text}
		</div>
	</div>
	<!--user menu-->
	<ul class="user-menu">
		<li><a href="#" class="print" id='printpagebutton' title='Print page...'>print</a></li>
		<li><a href="#" class="mail" id='mailpagebutton' title='Mail page...'>mail</a></li>
	</ul>
</div>
<div id='sendform' style='display: none;'>
<h3>Envoyer cette page &agrave; quelqu'un...</h3>
<form id='mailpage' name='mailpage'>
<p><label>Votre courriel:<br/><input type='text' name='emailfrom' id='formemailfrom' class='ws-mailpage-text' /></label></p>
<p><label>Courriel de destination:<br/><input type='text' name='emailto' id='formemailto' class='ws-mailpage-text' /></label></p>
<p><label>Sujet:<br/><input type='text' name='subject' id='formsubject' class='ws-mailpage-text' /></label></p>
<p><label>Message:<br/><textarea name='content' id='formcontent' class='ws-mailpage-content'>Bonjour,

Je vous invite à vous balader sur cette page:

{$page_link}
</textarea></p>
<input class='ws-submit-button' name='submit' value='Send' type='submit' />
</form>
</div>

{literal}
<script>
$(document).ready(function(){
	$('a.tab').click(function(){
		id = $(this).attr('id');
		$('a.tab').removeClass('active');
		$(this).addClass('active');
		$('.tab-content').hide(0,function(){
				$('#tab' + id).show(0);
			}
		);
		return false;
	});
	$.fn.media.defaults.flvPlayer = '/lib/flash/mediaplayer-317.swf';
	$.fn.media.defaults.flashvars = {
		autostart: 'false',
		usefullscreen: 'false',
		backcolor: '0xf3cb0f',
		frontcolor: '#00ff00',
		lightcolor: '#ffffff',
		screencolor: '0xf3cb0f'
	} ;
	$('a.media').media();
	$('a#printpagebutton').click(function(){
		window.print();
		return false;
	});
	$("a#mailpagebutton").modalBox({
		getStaticContentFrom : "#sendform"
	});
	$('#mailpage').live('submit', function() {
		$.post('/WProduct/sendpage/', {
				emailfrom: $('.modalBoxBodyContent #formemailfrom').val(),
				emailto: $('.modalBoxBodyContent #formemailto').val(),
				subject: $('.modalBoxBodyContent #formsubject').val(),
				content: $('.modalBoxBodyContent #formcontent').val()
			},
			function(data) {
				if (data != 'OK') {
					alert('Veillez à remplir ce formulaire avec soin.');
				}
				else {
					$('.modalBoxBodyContent').html("<span class='endmessage'>Message envoyé !</span><input id='mailpageclose' type='button' value='Close' />");
	  				$('.result').html(data);
				}
			}
		);
		return false;
	});
	$('#mailpageclose').live('click', function() {
		jQuery.fn.modalBox.close();
		return false;
	});
});
</script>
{/literal}

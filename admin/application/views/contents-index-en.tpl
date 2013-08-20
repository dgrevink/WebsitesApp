<!-- ============== BEGIN list ============== -->
{if $listing_type == 'list'}

<script>
	var current_language = '{$current_language}';
</script>


<div class='uk-article'>
	<h2 class='uk-article-title'>Pages</h2>
	<div class='uk-alert'>Ce module va vous permettre de g&eacute;rer le contenu g&eacute;n&eacute;ral de vos pages.</div>
	<div class='uk-float-right uk-margin-bottom'>
		{$buttons_code}
	</div>

	<br/><br/>

	{$menu_code}

	<div class='uk-panel uk-panel-box uk-margin-top'>
		Total pages: {$page_total}
	</div>
</div>

<script language="javascript" src='/admin/application/lib/js/content_list.js'></script>

{/if}

<!-- ============== END list ============== -->

















<!-- ============== BEGIN detail ============== -->
<div id='dialog-text' style='display: none;'>&nbsp;</div>
{if $listing_type == 'detail'}
	<div id='status'></div>
	<form name='contentform' id='contentform' action='/admin/content/save/' method='post' class='uk-form uk-form-horizontal'>
	{$id}
	{$language}

<div class='uk-article'>
	<h2 class='uk-article-title' id='sub-page-title'>{$titledisplay}</h2>

	<div class='uk-alert'>Page creation or modification.</div>

		<div class='uk-float-right'>
			{if ($WSR_CONTENTS_VERSIONS)}
				<span id='history' class='uk-form uk-hidden-small'>&nbsp;</span>
			{/if}
			<a href='#' class='uk-button uk-button-primary' id='save-page'>Sauvegarder</a></li>
		</div>


		<ul class="uk-tab" data-uk-tab="{literal}{connect:'#tabs'}{/literal}">
			{if ($WSR_CONTENTS_CONTENT) }
			<li class='uk-active'><a href="#tab1">Textes</a></li>
			{/if}
			{if ($WSR_CONTENTS_ACCESS) }
			<li><a href="#tab2">Acc&egrave;s</a></li>
			{/if}
			{if $WSR_CONTENTS_LAYOUT}
			<li><a href="#tab3">Layout</a></li>
			{/if}
			{if $WSR_CONTENTS_METADATA}
			<li><a href="#tab4">Metadata</a></li>
			{/if}
		</ul>
		
		<ul id="tabs" class="uk-switcher uk-margin">
			<li>
				{if ($WSR_CONTENTS_CONTENT) }
					<h3>Texte 1 <a href='#' class='toggle-content-2'>+</a></h3>
					<div id='editor1' style='width: 100%;'>
						<textarea name='fckeditor1' id='fckeditor1' class='form_textarea'>{$content_1}</textarea><br/>
					</div>
					<div class='content-2-holder' style='display: none;'>
						<h3>Texte 2 <a href='#' class='toggle-content-3'>+</a></h3>
						<div id='editor2' style='width: 100%;'>
							<textarea name='fckeditor2' id='fckeditor2' class='form_textarea'>{$content_2}</textarea><br/>
						</div>
					</div>
					<div class='content-3-holder' style='display: none;'>
						<h3>Texte 3 <a href='#' class='toggle-content-4'>+</a></h3>
						<div id='editor3' style='width: 100%;'>
							<textarea name='fckeditor3' id='fckeditor3' class='form_textarea'>{$content_3}</textarea><br/>
						</div>
					</div>
					<div class='content-4-holder' style='display: none;'>
						<h3>Texte 4 <a href='#' class='toggle-content-5'>+</a></h3>
						<div id='editor4' style='width: 100%;'>
							<textarea name='fckeditor4' id='fckeditor4' class='form_textarea'>{$content_4}</textarea><br/>
						</div>
					</div>
					<div class='content-5-holder' style='display: none;'>
						<h3>Texte 5</h3>
						<div id='editor5' style='width: 100%;'>
							<textarea name='fckeditor5' id='fckeditor5' class='form_textarea'>{$content_5}</textarea><br/>
						</div>
					</div>
				{/if}
			</li>
			<li>
				{if ($WSR_CONTENTS_ACCESS) }
					<h3>Titres</h3>
					{$title}
					{$titleshort}
					<h3>Chemin</h3>
					{$path}
					<h3>Sitemap &amp; Menus</h3>
					{$sitemap}
					{$menus}
					{$language_page_fr}
					{$language_page_en}
					{$language_page_es}
					{$language_page_nl}
					{$language_page_zh}
					{$language_page_ko}
					{$language_page_ja}
					<h3>Avanc&eacute;</h3>
					{$params}
					{$cached}
					{$hidden}
					{$access}
				{/if}
			</li>
			<li>
				{if ($WSR_CONTENTS_LAYOUT) }
					<h3>Layout</h3>
					<div id='layout'>
					{$layout}
					</div>
				{/if}
			</li>
			<li>
				{if ($WSR_CONTENTS_METADATA) }
					{if $WSR_CONTENTS_SEO}
					<h3>SEO</h3>
					{$seodescription}
					{$seokeywords}
			
					{/if}
					<h3>R&eacute;serv&eacute; Administrateur</h3>
					<div id='editor' style='width: 100%'>
						<textarea name='fckeditor_comment' id='fckeditor_comment' class='form_textarea'>{$comment}</textarea>
					</div>
			
					
					<p class='page-infos'>
						Cr&eacute;&eacute; par <strong>{$creator}</strong> le {$create_date}.<br/>
						Derni&egrave;re date de modification: {$modify_date} par <strong>{$modifier}</strong>.
					</p>
				{/if}
			</li>
		</ul>

				
		
	</div>
	</form>


<script type="text/javascript">
	var recordid = {$recordid};
	var complete_path = "{$complete_path}";
</script>

<script language="javascript" src='/admin/application/lib/js/content_detail.js'></script>
{/if}
<!-- ============== END detail ============== -->

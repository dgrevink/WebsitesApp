<!-- ============== BEGIN list ============== -->
{if $listing_type == 'list'}

<script>
	var current_language = '{$current_language}';
</script>


<div class='uk-article'>
	<h2 class='uk-article-title'>Pages</h2>
	<div class='uk-alert'>This module is used to organise and edit your site pages.</div>
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
			<a href='#' class='uk-button uk-button-primary' id='save-page'>Save</a></li>
		</div>


		<ul class="uk-tab" data-uk-tab="{literal}{connect:'#tabs'}{/literal}">
			{if ($WSR_CONTENTS_CONTENT) }
			<li class='uk-active'><a href="#tab1">Texts</a></li>
			{/if}
			{if ($WSR_CONTENTS_ACCESS) }
			<li><a href="#tab2">Access</a></li>
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
					<h3>Text 1 <a href='#' class='toggle-content-2'>+</a></h3>
					<div id='editor1' style='width: 100%;'>
						<textarea name='fckeditor1' id='fckeditor1' class='form_textarea'>{$content_1}</textarea><br/>
					</div>
					<div class='content-2-holder' style='display: none;'>
						<h3>Text 2 <a href='#' class='toggle-content-3'>+</a></h3>
						<div id='editor2' style='width: 100%;'>
							<textarea name='fckeditor2' id='fckeditor2' class='form_textarea'>{$content_2}</textarea><br/>
						</div>
					</div>
					<div class='content-3-holder' style='display: none;'>
						<h3>Text 3 <a href='#' class='toggle-content-4'>+</a></h3>
						<div id='editor3' style='width: 100%;'>
							<textarea name='fckeditor3' id='fckeditor3' class='form_textarea'>{$content_3}</textarea><br/>
						</div>
					</div>
					<div class='content-4-holder' style='display: none;'>
						<h3>Text 4 <a href='#' class='toggle-content-5'>+</a></h3>
						<div id='editor4' style='width: 100%;'>
							<textarea name='fckeditor4' id='fckeditor4' class='form_textarea'>{$content_4}</textarea><br/>
						</div>
					</div>
					<div class='content-5-holder' style='display: none;'>
						<h3>Text 5</h3>
						<div id='editor5' style='width: 100%;'>
							<textarea name='fckeditor5' id='fckeditor5' class='form_textarea'>{$content_5}</textarea><br/>
						</div>
					</div>
				{/if}
			</li>
			<li>
				{if ($WSR_CONTENTS_ACCESS) }
					<h3>Titles</h3>
					{$title}
					{$titleshort}
					<h3>Path</h3>
					{$path}
					<h3>Sitemap &amp; Menus</h3>
					{$sitemap}
					{$menus}
					{if isset($language_page_fr)}{$language_page_fr}{/if}
					{if isset($language_page_en)}{$language_page_en}{/if}
					{if isset($language_page_es)}{$language_page_es}{/if}
					{if isset($language_page_nl)}{$language_page_nl}{/if}
					{if isset($language_page_zh)}{$language_page_zh}{/if}
					{if isset($language_page_ko)}{$language_page_ko}{/if}
					{if isset($language_page_ja)}{$language_page_ja}{/if}
					<h3>Advanced</h3>
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
						<ul id='layout-selector'>
							{foreach name=layouts item=item from=$layouts}
							<li>
								<label for='{$item.id}'><img src='{$item.imagename}' alt='{$item.id}' title='{$item.id}' /></label>
								<input id='{$item.id}' name='layout' type='radio' class='layout-selector' {if $current_page_layout == $item.id}checked='checked'{/if} value='{$item.id}' title='{$item.id}'  />
							</li>
							{/foreach}
						</ul>
						<h3>Contenants</h3>

						{for $i=1 to 9}
							<div id='placeholder_{$i}' class='placeholder' >
								<h4>{$i}</h4>
								<select name='placeholder_" . $i . "_type' id='placeholder_" . $i . "_type' class='placeholder_selector' \">
								<option value='empty'>" . WSDTranslations::getLabel('CONTENT_PLACEHOLDER_NOTHING') . "</option>
								<optgroup label='" . WSDTranslations::getLabel('CONTENT_PLACEHOLDER_MAIN') . "'>
								$content_available = array( 1, 2, 3, 4, 5);
								foreach($content_available as $content) {
									<option value='content-{$content}'>" . WSDTranslations::getLabel('CONTENT_PLACEHOLDER_MAIN_TEXT') . " {$content}</option>
								}
								</optgroup>
								
								if (count($blocks) > 0) {
									<optgroup label='" . WSDTranslations::getLabel('CONTENT_PLACEHOLDER_BLOCKS') . "'>
									foreach($blocks as $record) {
										<option value='block-" . $record->id . "'>" . $record->title . "</option>
									}
									</optgroup>
								}
								if (count($ads) > 0) {
									<optgroup label='" . WSDTranslations::getLabel('CONTENT_PLACEHOLDER_BANNERS') . "'>
									foreach($ads as $record) {
										<option value='ad-" . $record->id . "'>" . $record->title . "</option>
									}
									</optgroup>
								}
								if (count($forms) > 0) {
									<optgroup label='" . WSDTranslations::getLabel('CONTENT_PLACEHOLDER_FORMS') . "'>
									foreach($forms as $record) {
										<option value='form-" . $record->id . "'>" . $record->title . "</option>
									}
									</optgroup>
								}
								if (count($widgets) > 0) {
									<optgroup label='" . WSDTranslations::getLabel('CONTENT_PLACEHOLDER_WIDGETS') . "'>
									foreach($widgets as $record) {
										if ($record['active']) {
											<option value='widget-" . $record['id'] . "' title=\"" . $record['note'] . ' (' . $record['version'] . ')' .  "\">" . $record['rname'] . "</option>
										}
									}
									</optgroup>
								}
								</select>&nbsp;&rang;&nbsp;
								<span id='placeholder_" . $i . "_div'>&nbsp</span>
							</div>
						{/for}

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
					<h3>Site Admin only</h3>
					<div id='editor' style='width: 100%'>
						<textarea name='fckeditor_comment' id='fckeditor_comment' class='form_textarea'>{$comment}</textarea>
					</div>
			
					
					<p class='page-infos'>
						Created by <strong>{$creator}</strong> on {$create_date}.<br/>
						Last modification date: {$modify_date} by <strong>{$modifier}</strong>.
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

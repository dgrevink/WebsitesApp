<script>
	var sCurrentLanguage = 'en';
</script>

<!-- ============== BEGIN list ============== -->
{if $listing_type == 'list'}

<script>
	var current_language = '{$current_language}';
</script>


<div class='uk-article'>
	<h2 class='uk-article-title'>Pages</h2>
	<div class='uk-alert'>This module is used to organise and edit your site pages.</div>
	<div class='uk-float-right uk-margin-bottom'>
		{if isset($WSR_CONTENTS_ADD)}
			<a href='#' class='uk-button uk-button-primary disposable' onclick='javascript:addMenu(0); return false;'>Create...</a>
		{/if}
		{if isset($WSR_CONTENTS_ORDER)}
			<a href='#' class='uk-button uk-hidden-small menu-orderer'>Reorder</a> 
			<a href='#' style='display: none;' class='uk-button menu-orderer-saver'>Save order</a> 
			<a href='#' style='display: none;' class='uk-button menu-orderer-cancel'>Cancel</a>
		{/if}
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
			{if isset($WSR_CONTENTS_VERSIONS)}
				<span id='history' class='uk-form uk-hidden-small'>&nbsp;</span>
			{/if}
			<a href='#' class='uk-button uk-button-primary' id='save-page'>Save</a></li>
		</div>


		<ul class="uk-tab" data-uk-tab="{literal}{connect:'#tabs'}{/literal}">
			{if isset($WSR_CONTENTS_CONTENT) }
			<li class='uk-active'><a href="#tab1">Texts</a></li>
			{/if}
			{if isset($WSR_CONTENTS_ACCESS) }
			<li><a href="#tab2">Access</a></li>
			{/if}
			{if isset($WSR_CONTENTS_LAYOUT)}
			<li><a href="#tab3">Layout</a></li>
			{/if}
			{if isset($WSR_CONTENTS_METADATA)}
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
				{if isset($WSR_CONTENTS_ACCESS) }
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
				{if isset($WSR_CONTENTS_LAYOUT) }
					<h3>Layout</h3>
					<div id='layout'>
						<ul id='layout-selector'>
							{foreach name=layouts item=item from=$layouts}
							<li>
								<label for='{$item.id}'><img src='{$item.imagename}' alt='{$item.id}' title='{$item.id}' /></label>
								<input id='{$item.id}' name='layout' type='radio' class='layout-selector' {if $current_page->layout == $item.id}checked='checked'{/if} value='{$item.id}' title='{$item.id}'  />
							</li>
							{/foreach}
						</ul>
						<h3>Placeholders</h3>

						{for $i=1 to 9}
							<div id='placeholder_{$i}' class='placeholder' >
								<h4>{$i}</h4>
								<select name='placeholder_{$i}_type' id='placeholder_{$i}_type' class='placeholder_selector'>
								<option value='empty'>Nothing</option>
								<optgroup label='Texts'>
									{for $content=1 to 5}
										<option value='content-{$content}'>Text {$content}</option>
									{/for}
								</optgroup>
								
								{if !empty($blocks)}
									<optgroup label='Blocks'>
										{foreach name=blocks item=block from=$blocks}
											<option value='block-{$block.id}'>{$block.title}</option>
										{/foreach}
									</optgroup>
								{/if}
								{if !empty($ads)}
									<optgroup label='Ads'>
										{foreach name=ads item=ad from=$ads}
											<option value='ad-{$ad.id}'>{$ad.title}</option>
										{/foreach}
									</optgroup>
								{/if}
								{if !empty($forms)}
									<optgroup label='Forms'>
										{foreach name=forms item=form from=$forms}
											<option value='form-{$form.id}'>{$form.title}</option>
										{/foreach}
									</optgroup>
								{/if}
								{if !empty($widgets)}
									<optgroup label='Widgets'>
										{foreach name=widgets item=widget from=$widgets}
											<option value='widget-{$widget.id}' title="{$widget.note} ({$widget.version})">{$widget.rname}</option>
										{/foreach}
									</optgroup>
								{/if}

								</select>&nbsp;&rang;&nbsp;
								<span id='placeholder_{$i}_div'>&nbsp</span>
							</div>
						{/for}

						<script>
							var phlist = [];
							{foreach name=layouts item=item from=$layouts}
								phlist["{$item.id}"] = [{foreach name=placeholders item=ph from=$item.placeholders}{$smarty.foreach.placeholders.index+1}{if !$smarty.foreach.placeholders.last},{/if}{/foreach}];
							{/foreach}

							var phoptions = [];
							phoptions["placeholder_1_type"] = '{$current_page->placeholder_1}';
							phoptions["placeholder_2_type"] = '{$current_page->placeholder_2}';
							phoptions["placeholder_3_type"] = '{$current_page->placeholder_3}';
							phoptions["placeholder_4_type"] = '{$current_page->placeholder_4}';
							phoptions["placeholder_5_type"] = '{$current_page->placeholder_5}';
							phoptions["placeholder_6_type"] = '{$current_page->placeholder_6}';
							phoptions["placeholder_7_type"] = '{$current_page->placeholder_7}';
							phoptions["placeholder_8_type"] = '{$current_page->placeholder_8}';
							phoptions["placeholder_9_type"] = '{$current_page->placeholder_9}';

							var phparams = [];
							phparams["placeholder_1_param"] = '{$current_page->placeholder_1_param}';
							phparams["placeholder_2_param"] = '{$current_page->placeholder_2_param}';
							phparams["placeholder_3_param"] = '{$current_page->placeholder_3_param}';
							phparams["placeholder_4_param"] = '{$current_page->placeholder_4_param}';
							phparams["placeholder_5_param"] = '{$current_page->placeholder_5_param}';
							phparams["placeholder_6_param"] = '{$current_page->placeholder_6_param}';
							phparams["placeholder_7_param"] = '{$current_page->placeholder_7_param}';
							phparams["placeholder_8_param"] = '{$current_page->placeholder_8_param}';
							phparams["placeholder_9_param"] = '{$current_page->placeholder_9_param}';

							function create_empty(el, name) { el.html('Vide.'); };
							function create_content(el, name) { el.html('Texte principal.'); };
							function create_widget(el, name) { el.html("Widget. Param&egrave;tre: <input type='text' id='" + name + "_param' name='" + name + "_param' /><span class='widgets-lister-detail'>&nbsp;</span>");}
							function create_block(el, name) { el.html('Bloc de texte.'); };
							function create_ad(el, name) { el.html('Banni&egrave;re.'); };
							function create_form(el, id) { el.html('Formulaire.'); };
						</script>
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

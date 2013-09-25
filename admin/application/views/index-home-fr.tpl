<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="refresh" content="{$timeout_sessions}; url=/admin/site/logout/fr/timeout/">
	<meta name="author" content="{$author}" />
	<meta name="version" content="{$version} {$release}" />
	<meta name="author" content="{$author}" />
	<meta name = "viewport" content = "width = device-width, initial-scale = 1.0, user-scalable = no" />

	<title>{$current_short_page_title} - {$company}</title>

	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
	<!--[if lt IE 8]><style type="text/css" media="all">@import url("{$html_app}/lib/css/ie.css");</style><![endif]-->

	<script type="text/javascript"	src="/lib/js/jquery/jquery-1.8.2.min.js"></script>

	<script type="text/javascript"	src="/lib/js/jquery/jquery.form.js"></script>

	<script type="text/javascript"	src="/lib/js/jquery/jquery.blockui.js"></script>

	<script type="text/javascript"	src="/lib/js/jquery/ui/jquery-ui-1.8.1.min.js"></script>
	<script type="text/javascript"	src="/lib/js/jquery/ui/jquery-ui-i18n.js"></script>
	<link	rel="stylesheet"		type="text/css" href="{$html_lib}/js/jquery/ui/css/aristo/jquery-ui-1.8.1.custom.css"/>

	<link	rel="stylesheet"		href="/lib/js/jquery/jgrowl/jquery.jgrowl.css" type="text/css">
	<script type="text/javascript"	src="/lib/js/jquery/jgrowl/jquery.jgrowl_minimized.js"></script>

	<script type="text/javascript"	src="/lib/js/jquery/nestedsortable-1.2.1/jquery.ui.nestedSortable.js"></script>

	<script type="text/javascript"	src="/lib/js/jquery/chosen/chosen.jquery.js"></script>
	<link	rel="stylesheet"       	href="/lib/js/jquery/chosen/chosen.css" type="text/css">


	<script type="text/javascript"	src="/lib/js/ckeditor/ckeditor.js"></script>
	<script type="text/javascript"	src="/lib/js/ckfinder/ckfinder.js"></script>

	<link 	rel="stylesheet" 		href="/lib/css/uikit/css/uikit.almost-flat.min.css" type="text/css">
	<script type="text/javascript"	src="/lib/css/uikit/js/uikit.min.js"></script>


	<link rel="stylesheet" href="{$html_app}/lib/css/websitesapp.css" type="text/css">
	<script type="text/javascript" src="{$html_app}/lib/js/translations.js"></script>
	<script type="text/javascript" src="{$html_app}/lib/js/site.js"></script>

	<script>
		var site_menus = {$site_menus};
		var sUserLanguage = '{$user_language}';
		var sCurrentLanguage = '{$current_language}';
		var l = new Translations(sUserLanguage);
	</script>

</head>

	<body id='{$current_page}' class='ws-background' {$maintenance_style}>

	<div class="ws-navbar uk-navbar uk-navbar-attached uk-grid">
		<div class='uk-width-large-2-10 uk-width-medium-1-6 uk-width-2-3'>
			<a href="#tm-offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
			<a href="/" target='_blank' class='uk-navbar-brand'><img src='/admin/application/lib/images/logo.png' style='height: 50px;' />{$company}</a>
		</div>
		<div class='uk-width-large-8-10 uk-width-medium-5-6 uk-width-1-3' style='line-height: 40px; text-align: right;'>

			<span class='uk-hidden-small'>
				<a href='/admin/fr/tables/users/edit/{$username_id}' class='ws-username'>{$username}</a>
				<span class='uk-form'><select name='current_language' id='current_language' class='ws-language-switcher'>
					{section name=languages loop=$languages}
						<option value="{$languages[languages].code}" {$languages[languages].selected}>{$languages[languages].label}</option>
					{/section}
				</select></span>
			</span>

			<span class='uk-hidden-large uk-hidden-medium'>
				<a href='/admin/fr/tables/users/edit/{$username_id}' class='uk-button'><i class='uk-icon-home' style='padding:1px;'></i></a>
			</span>
			<a href='/admin/site/logout/{$language}/' class='uk-button'><i class='uk-icon-off' style='padding:1px;'></i></a>&nbsp;

		</div>

	</div>

	<div class="ws-middle" {$maintenance_style}>

		{if ($db_ok == '0')}
		<div class='uk-alert uk-alert-danger'>
			<p>Le syst&egrave;me n'est pas connect&eacute; &agrave; une base de donn&eacute;es. V&eacute;rifiez les param&egrave;tres de connection.</p>
		</div>
		{/if}

		<!--[if lte IE 7]>
		<div class='uk-alert uk-alert-danger'>
			<p>Vous devriez mettre &agrave; jour votre browser vers <a href="http://www.apple.com/safari/" title="T&eacute;l&eacute;charger Safari sur Apple.com">Safari</a>, <a href="http://www.mozilla.com/firefox/" title="T&eacute;l&eacute;charger Firefox sur Mozilla.com">Firefox</a> ou <a href="http://www.opera.com/download/" title="T&eacute;l&eacute;charger Opera sur Opera.com">Opera</a> pour un meilleur rendu du CMS.
			</p>
		</div>
		<![endif]-->


		<div class="uk-grid ws-container-large">

			<div class='ws-sidebar uk-width-large-2-10 uk-width-medium-1-6 uk-width-1-1 uk-hidden-small'>

				<ul class="ws-nav uk-nav">
					{foreach name=outerfullmenu item=item from=$menu1}
						{if $item.path == '#'}
							<li class="uk-nav-header"><i class="uk-{$item.icon}"></i> {$item.name}</li>
							{foreach name=innerfullmenu item=subitem from=$item.children}
								<li class='{$subitem.state}'><a href="{$subitem.path}/" title="{$subitem.name}">{if $subitem.system == 1}<i class="uk-icon-warning-sign"></i>{/if} {$subitem.name}</a></li>
							{/foreach}
						{else}
							<li class="{if $item.children}uk-parent uk-nav-parent-icon{/if} {$item.state}">
								<a href="{$item.path}/" title="{$item.label}"><i class="uk-{$item.icon}"></i> {$item.name}</a>
								{foreach name=innerfullmenu item=subitem from=$item.children}
									{if $smarty.foreach.innerfullmenu.first}<ul class='uk-nav-sub'>{/if}
									<li class='{$subitem.state}'><a href="{$subitem.path}/" title="{$subitem.name}">{if $subitem.system == 1}<i class="uk-icon-warning-sign"></i>{/if}{$subitem.name}</a></li>
									{if $smarty.foreach.innerfullmenu.last}</ul>{/if}
								{/foreach}
							</li>
						{/if}
					{/foreach}
					<li><a href='/admin/site/logout/{$language}/' title='D&eacute;connexion'><i class="uk-icon-off"></i> D&eacute;connexion</a></li>
				</ul>

			</div>

			<div class='uk-width-large-8-10 uk-width-medium-5-6 uk-width-1-1 '>
				{$contents}
			</div>

		</div>
	</div>











	<div class='ws-footer'>
	
		<p>
			<span class="uk-float-left"><a href='http://websitesapp.com'>Websites CMS</a> Version {$version} {$release} - &copy; 2007-{$smarty.now|date_format:"%Y"} par <a href='{$brand_website}' title='{$brand_website_text}'>{$brand}</a></span>
			<span class="uk-float-right"><a href="/admin/">{$deployment}:{$database}</a> - {$module_name} {$module_version}</span>
		</p>
		
	</div>


	<div id='dialog-text' style='display: none;'>&nbsp;</div>


<div id="tm-offcanvas" class="uk-offcanvas">

			<div class="uk-offcanvas-bar">

				<div class='ws-language-switcher-docked'><select name='current_language' id='current_language' class='ws-language-switcher'>
					{section name=languages loop=$languages}
						<option value="{$languages[languages].code}" {$languages[languages].selected}>{$languages[languages].label}</option>
					{/section}
				</select></div>

				<ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav="{literal}{multiple:true}{/literal}">
					{foreach name=outerfullmenu item=item from=$menu1}
					<li class="{if $item.children}uk-parent uk-nav-parent-icon{/if} {$item.state}">
						<a href="{$item.path}" title="{$item.label}"><i class="uk-{$item.icon}"></i> {$item.name}</a>
						{foreach name=innerfullmenu item=subitem from=$item.children}
							{if $smarty.foreach.innerfullmenu.first}<ul class='uk-nav-sub'>{/if}
							<li><a href="{$subitem.path}/" class="{$subitem.state}" title="{$subitem.name}">{if $subitem.system == 1}<i class="uk-icon-warning-sign"></i>{/if} {$subitem.name}</a></li>
							{if $smarty.foreach.innerfullmenu.last}</ul>{/if}
						{/foreach}
					</li>
					{/foreach}
				</ul>

			</div>

		</div>

	</body>	
</html>

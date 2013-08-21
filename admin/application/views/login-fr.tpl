<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="author" content="{$author}" />
	<meta name="version" content="{$version} {$release}" />
	<meta name="author" content="{$author}" />
	<meta name = "viewport" content = "width = device-width, initial-scale = 1.0, user-scalable = no" />

	<title>{$company} - {$current_page_title}</title>

    <style type="text/css" media="all">
		@import url("{$html_app}/lib/css/uikit.min.css");
		@import url("{$html_app}/lib/css/uikit.almost-flat.min.css");
		@import url("{$html_app}/lib/css/websitesapp.css");
    </style>
	
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
	<!--[if lt IE 8]><style type="text/css" media="all">@import url("{$html_app}/lib/css/ie.css");</style><![endif]-->
	<!--[if IE]><script type="text/javascript" src="{$html_app}/lib/js/excanvas.js"></script><![endif]-->

	{if isset($js)}{$js}{/if}

	<script type="text/javascript" src="/lib/js/jquery/jquery-1.8.2.js"></script>
	<script type="text/javascript" src="{$html_app}/lib/js/uikit.min.js"></script>
	<script type="text/javascript" src="/lib/js/encoder.js"></script>
	
</head>

<body id='{$current_page}' {$maintenance_style}>


<div class="uk-width-1-1 uk-width-medium-2-3 uk-large-1-3 uk-container-center ws-login-screen">
		<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-panel-header uk-margin-top">
			<div class="uk-panel-badge uk-badge"><a href="/">retour au site</a></div>
			<h3 class="uk-panel-title">{$company} - CMS</h3>
			{if isset($db_ok)}
				{if (!$db_ok == '0')}
					<div class="uk-alert-danger"><p>Le système n'est pas connecté à une base de données. Vérifiez les paramètres de connection.</p></div>
				{/if}
			{/if}
			{if isset($status)}
				<div class="uk-alert uk-alert-warning" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>{$status}</p></div>
			{else}
				<div class="uk-alert" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>Veuillez donner votre courriel<br/> ainsi que votre mot de passe pour vous connecter.</p></div>
			{/if}
			
			<form name='login_form' id='login_form' method='post' accept-charset="UTF-8" class='uk-form uk-form-horizontal' {if $smarty.server.REQUEST_URI == '/admin/site/logout/fr/'}action='/admin/'{/if}{if $smarty.server.REQUEST_URI == '/admin/site/logout/fr/timeout/'}action='/admin/'{/if}>

				<div class="uk-grid">
					<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-5">
						<label class="uk-form-label" for="username"  class="uk-width-1-1">Courriel:</label>
					</div>
					<div class="uk-width-1-1 uk-width-medium-2-3 uk-width-large-4-5">
						<input type="text" class="uk-width-1-1" value="" id='username' name="username"/><br/><br/>
					</div>
					<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-5">
						<label class="uk-form-label" for="password"  class="uk-width-1-1">Mot de passe:</label>
					</div>
					<div class="uk-width-1-1 uk-width-medium-2-3 uk-width-large-4-5">
						<input type="password" class="uk-width-1-1" value="" id='password' name="password" /><br/><br/>
					</div>
				
					<div class="uk-hidden-small uk-width-medium-1-2 uk-width-large-1-2">&nbsp;</div>
					<div class="uk-width-1-1 uk-width-medium-1-2 uk-width-large-1-2">
						<input type="submit" id='submit_button' class="submit uk-button uk-width-1-1" value="Se connecter..." /> &nbsp; 
						<!-- <input type="checkbox" class="checkbox" checked="checked" id="rememberme" /> <label for="rememberme">Remember me</label> -->
					</div>
				</div>
	
				
			</form>
			<div class='copyright-notice'><a href='http://websitesapp.com'>Websites CMS</a> Version {$version} {$release} - &copy; 2007-{$smarty.now|date_format:"%Y"} par <a href='{$brand_website}' title='{$brand_website_text}'>{$brand}</a></div>
		</div>
</div>

<script>
{literal}
$(document).ready(function(){
	$('#username').focus();
	$('#login_form').submit(function(){
		$('.message').removeClass('errormsg').addClass('info').html('Connection en cours...');
		$('#login_form').hide();
		Encoder.EncodeType = "entity";
		var encoded = Encoder.htmlEncode($('#username').val());
		$('#username').val(encoded);
		return true;
	});
});
{/literal}
</script>


</body>
</html>


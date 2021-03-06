<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="author" content="{$author}" />
	<meta name="version" content="{$version} {$release}" />
	<meta name="author" content="{$author}" />
	<meta name = "viewport" content = "width = device-width, initial-scale = 1.0, user-scalable = no" />

	<title>{$company} - {$current_page_title}</title>

	<link 	rel="stylesheet" 		href="/lib/css/uikit/css/uikit.almost-flat.min.css" type="text/css">
	<script type="text/javascript"	src="/lib/css/uikit/js/uikit.min.js"></script>
	<link rel="stylesheet" href="{$html_app}/lib/css/websitesapp.css" type="text/css">

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
			<div class="uk-panel-badge uk-badge"><a href="/">back to site</a></div>
			<h3 class="uk-panel-title">{$company} - CMS</h3>
			{if isset($db_ok)}
				{if (!$db_ok == '0')}
					<div class="uk-alert-danger"><p>The system is not connected to a database. Check your connection parameters.</p></div>
				{/if}
			{/if}
			{if isset($status)}
				<div class="uk-alert uk-alert-warning" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>{$status}</p></div>
			{else}
				<div class="uk-alert" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>Please provide your email and password to connect.</p></div>
			{/if}
			
			<form name='login_form' id='login_form' method='post' accept-charset="UTF-8" class='uk-form uk-form-horizontal' {if $smarty.server.REQUEST_URI == '/admin/site/logout/en/'}action='/admin/'{/if}{if $smarty.server.REQUEST_URI == '/admin/site/logout/en/timeout/'}action='/admin/'{/if}>

				<div class="uk-grid">
					<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-5">
						<label class="uk-form-label" for="username"  class="uk-width-1-1">Email:</label>
					</div>
					<div class="uk-width-1-1 uk-width-medium-2-3 uk-width-large-4-5">
						<input type="text" class="uk-width-1-1" value="" id='username' name="username"/><br/><br/>
					</div>
					<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-5">
						<label class="uk-form-label" for="password"  class="uk-width-1-1">Password:</label>
					</div>
					<div class="uk-width-1-1 uk-width-medium-2-3 uk-width-large-4-5">
						<input type="password" class="uk-width-1-1" value="" id='password' name="password" /><br/><br/>
					</div>
				
					<div class="uk-hidden-small uk-width-medium-1-2 uk-width-large-1-2">&nbsp;</div>
					<div class="uk-width-1-1 uk-width-medium-1-2 uk-width-large-1-2">
						<input type="submit" id='submit_button' class="submit uk-button uk-width-1-1" value="Connect..." /> &nbsp; 
						<!-- <input type="checkbox" class="checkbox" checked="checked" id="rememberme" /> <label for="rememberme">Remember me</label> -->
					</div>
				</div>
	
				
			</form>
			<div class='copyright-notice'><a href='http://websitesapp.com'>Websites CMS</a> Version {$version} {$release} - &copy; 2007-{$smarty.now|date_format:"%Y"} by <a href='{$brand_website}' title='{$brand_website_text}'>{$brand}</a></div>
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


<div class='uk-article'>
	<h2 class='uk-article-title'>Paramètres</h2>

		<span class='uk-float-right'>
			<a href='#' onclick="jQuerySubmit('#params_base'); return false;" class='uk-button uk-button-primary'>Sauvegarder</a>
		</span>
		
		<ul class="uk-tab" data-uk-tab="{literal}{connect:'#tabs'}{/literal}">
			<li class='uk-active'><a href="#params-tab">Paramètres</a></li>
			<li><a href="#widgets-tab"><span>Widgets</span></a></li>
			<li><a href="#tools-tab"><span>Outils</span></a></li>
			<li><a href="#tools-advanced-tab"><span>Outils avanc&eacute;s</span></a></li>
			<li><a href="#logs-tab"><span>Logs</span></a></li>
		</ul>
	

	<ul id="tabs" class="uk-switcher uk-margin">
		<li>
			<form name='params_base' id='params_base' class='uk-form uk-form-horizontal' action="/admin/parameters/save_site/" method='post' enctype="multipart/form-data;charset=UTF-8">
				<h3>Information de contact</h3>
				{$company}
				{$author}
				{$contactmail}
	
				<h3>Version du site</h3>
				{$version}
				{$release}
	
				<h3>SEO</h3>
				{$uacct}
	
				<h3>Langues du Site</h3>
				{$default_language}
				{$languages}
				
				<h3>Contenu</h3>
				{$maintenance}
				{$maintenance_text}
				{$menu_name_1}
				{$menu_name_2}
				{$menu_name_3}
				{$menu_name_4}
				{$template_page_id}
	
				<h3>Headers META</h3>
				{$headers}
				
				<h3>Recaptcha</h3>
				{$recaptcha_public}
				{$recaptcha_private}
				{$recaptcha_theme}
				
				<h3>Routes</h3>
				{$routes}
				
				<h3>Performance</h3>
				{$speedup}
				{$caching}
				{$cache_lifetime}
	
				<h3>S&eacute;curit&eacute;</h3>
				{$security}
				{$security_session}
				{$security_table}
	
				<h3>Timeouts</h3>
				{$timeout_history}
				{$timeout_logs}
				{$timeout_sessions}
	
				<h3>Debugging</h3>
				{$debug}</span>
				{$debug_log}</span>
	
			</form>
		</li>
		<li>
				<div class='uk-alert uk-alert-danger' >Attention ! Si vous &ecirc;tes programmeur, cet &eacute;cran va modifier les fichier source de vos widgets pour les activer/d&eacute;sactiver. Veillez &agrave; bien tout sauvegarder avant d'activer un widget !</div><br/>

				{section name=widgets loop=$widget_list}
					<div class='uk-grid'>
						<div class='uk-width-1-1 uk-width-medium-1-2 uk-width-large-1-2'>
							<!--{if $widget_list[widgets].active}ACTIF{/if}-->
							<label>
							<input id="{$widget_list[widgets].id}" name="{$widget_list[widgets].id}" type="checkbox" class='widget' {$widget_list[widgets].checked}/>
							{$widget_list[widgets].rname}</label>
						</div>
						<div class='uk-width-1-1 uk-width-medium-1-2 uk-width-large-1-2'>
							{$widget_list[widgets].note} - 
							{$widget_list[widgets].version}
							{if $widget_list[widgets].init != ''} - <a href='#' rel='{$widget_list[widgets].init}' class='uk-button uk-button-mini uk-button-danger init-widget'>INIT</a> <a href='#' rel='{$widget_list[widgets].init}' class='uk-button uk-button-mini uk-button-danger clean-widget'>CLEAN</a>{/if}
						</div>
					</div>
				{/section}
			<script>
				{literal}
				var current_widget = '';
				$('input.widget').click(function(){
					current_widget = $(this).attr('id');
					/*alert(current_widget + ' -- ' + $('input#' + current_widget).attr('checked'));*/
					$.ajax({
						url: '/admin/Parameters/set_widgets/',
						context: document.body,
						type: "POST",
						data: "id=" + $(this).attr('id') + "&value=" + $('input#' + current_widget).attr('checked')
					});
				});
				$('a.init-widget').click(function(){
					if (confirm("Cette opération ne peut pas être annulée. Toutes les données associées à ce widget vont être détruites. Voulez-vous continuer ?"))  {
						$.ajax({
							url: '/admin/Parameters/init_widget/',
							context: document.body,
							type: "POST",
							data: "url=" + $(this).attr('rel') + "&init=0",
							success: function(data) {
								alert(data);
							}
						});
					}
					return false;
				});
				$('a.clean-widget').click(function(){
					if (confirm("Cette opération ne peut pas être annulée. Toutes les données associées à ce widget vont être détruites. Voulez-vous continuer ?")) {
						$.ajax({
							url: '/admin/Parameters/init_widget/',
							context: document.body,
							type: "POST",
							data: "url=" + $(this).attr('rel') + "&init=1",
							success: function(data) {
								alert(data);
							}
						});
					}
					return false;
				});
				{/literal}
			</script>
	</li>
	<li>
		<a class='uk-button uk-button-success uk-margin-bottom' href='javascript:tools_erasecache();'>Effacer Cache</a>
		<a class='uk-button uk-margin-bottom' href='javascript:tools_erasestats();'>Effacer Statistiques</a>
		<a class='uk-button uk-margin-bottom' href='javascript:tools_erasehistory();'>Effacer Historique</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_phpminiadmin();'>Database Manager</a>
		<a class='uk-button uk-margin-bottom' href='javascript:tools_showresources();'>Utilisation</a>
		<a class='uk-button uk-button-success uk-margin-bottom' href='javascript:tools_phpinfo();'>PHPINFO</a>
			
		<iframe id='tools-display' style='width: 100%; height: 600px; margin-top: 10px;'>
				Upgradez votre navigateur.
		</iframe>
	</li>
	<li>
		<!--<a class='uk-button' href='javascript:tools_dumpdatabase();'>Database Dump</a>-->
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_statify();'>Statifier le site</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_cleanstatify();'>D&eacute;statifier le site</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_import_db();'>Table Import</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_table_accents_processor();'>Table Accents Processor</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_importschema();'>Database Schema Import</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_exportschema();'>Database Schema Export</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_duplicatecontent();'>Dupliquer contenu</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_relinklanguages();'>Reconnection des langues</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_touchtable();'>Retoucher table</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_duplicatetabledata();'>Dupliquer table</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_resetsite();'>Reset Site (DANGER)</a>
			
		<iframe id='tools-display2' style='width: 100%; height: 600px; margin-top: 10px;'>
				Upgradez votre navigateur.
		</iframe>
	</li>
	<li>
		<a class='uk-button' href='javascript:logs_admin();'>Admin - Securit&eacute;</a>
		<a class='uk-button' href='javascript:logs_site();'>Site - Debug Log</a>
		<a class='uk-button' href='javascript:logs_database();'>Database - Debug Log</a>

    	<iframe id='logs-display' style='width: 100%; height: 600px; margin-top: 10px;'>
       		Upgradez votre navigateur.
    	</iframe>
	</li>
</ul>


</div>




<script>
{literal}

	function jQuerySubmit(id) {
		el = $(id);

    $('#uacct').val(base64_encode($('#uacct').val()));
    $('#maintenance_text').val(base64_encode($('#maintenance_text').val()));
    $('#headers').val(base64_encode($('#headers').val()));

		el.ajaxSubmit(function(data) {
		    $('#uacct').val(base64_decode($('#uacct').val()));
		    $('#maintenance_text').val(base64_decode($('#maintenance_text').val()));
		    $('#headers').val(base64_decode($('#headers').val()));
			showNotify(data);
//			$('#status').html(data).fadeIn(1).fadeTo(5000, 1).fadeOut(1000);
		});
	}
	
	function tools_erasecache() {
		if (confirm('Etes-vous certain ?')) {
			$('#tools-display').attr('src', '/admin/tools/erasecache/');
		}
	}
	
	function tools_erasehistory() {
		if (confirm('Etes-vous certain ?')) {
			$('#tools-display').attr('src', '/admin/tools/erasehistory/');
		}
	}
	
	function tools_statify() {
		if (confirm("Etes-vous certain ? Une statification va grandement accélérer votre site, mais les changements faits dans le CMS ne seront pris en compte qu'après une restatification, ou une déstatification. La statification va créer/écraser le fichier /index.html et créer une arborescence dans les répertoires de langues de votre site (/en/, /fr/, ...).")) {
			$('#tools-display2').attr('src', '/admin/tools/statify/');
		}
	}
	
	function tools_cleanstatify() {
		if (confirm('Etes-vous certain ? Cette action va détruire TOUT ce qui se trouve dans les répertoires de langues de votre site !!! (/en/, /fr/, ...) ainsi que le fichier /index.html !!!!!!!')) {
			$('#tools-display2').attr('src', '/admin/tools/destatify/');
		}
	}
	
	function tools_erasesafeties() {
		$('#tools-display2').attr('src', '/admin/tools/erasesafeties/');
	}

	function tools_duplicatecontent() {
		$('#tools-display2').attr('src', '/admin/tools/duplicatecontent/');
	}

	function tools_duplicatetabledata() {
		$('#tools-display2').attr('src', '/admin/tools/duplicatetabledata/');
	}

	function tools_touchtable() {
		$('#tools-display2').attr('src', '/admin/tools/touchtable/');
	}

	function tools_table_accents_processor() {
			$('#tools-display2').attr('src', '/admin/tools/tableaccentsprocessor/');
	}


	function tools_relinklanguages() {
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS'))) {
			$('#tools-display2').attr('src', '/admin/tools/relinklanguages/');
		}
	}


	function tools_erasestats() {
		if (confirm('Etes-vous certain ?')) {
			$('#tools-display').attr('src', '/admin/tools/erasestats/');
		}
	}

	function tools_dumpdatabase() {
		$('#tools-display2').attr('src', '/admin/tools/dumpdatabase/');
	}

	function tools_phpinfo() {
		$('#tools-display').attr('src', '/admin/tools/phpinfo/');
	}

	function tools_resetsite() {
		if (confirm('Etes-vous diablement certain ?')) {
			if (confirm('Absolument ? Cela va TOUT detruire sur le site !')) {
				$('#tools-display2').attr('src', '/admin/tools/resetsite/');
			}
		}
	}

	function tools_phpminiadmin(){
		$('#tools-display').attr('src', '/admin/phpminiadmin.php?vdssdv=websites');
	}

	function tools_showresources(){
		$('#tools-display').attr('src', '/admin/tools/showresources');
	}

	function tools_import_db(){
		$('#tools-display2').attr('src', '/admin/tools/import_table_definition/');
	}

	function tools_importschema(){
		$('#tools-display2').attr('src', '/admin/tools/importschema/');
	}

	function tools_exportschema(){
		$('#tools-display2').attr('src', '/admin/tools/exportschema/');
	}

	function logs_admin() {
		$('#logs-display').attr('src', '/admin/logs/admin/');
	}

	function logs_site() {
		$('#logs-display').attr('src', '/admin/logs/site/');
	}

	function logs_database() {
		$('#logs-display').attr('src', '/admin/logs/database/');
	}


	function base64_encode (data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Bayron Guevara
  // +   improved by: Thunder.m
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Pellentesque Malesuada
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Rafał Kukawski (http://kukawski.pl)
  // *     example 1: base64_encode('Kevin van Zonneveld');
  // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
  // mozilla has this native
  // - but breaks in 2.0.0.12!
  //if (typeof this.window['btoa'] === 'function') {
  //    return btoa(data);
  //}
  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = "",
    tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);

}

function base64_decode (data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Thunder.m
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Onno Marsman
  // +   bugfixed by: Pellentesque Malesuada
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
  // *     returns 1: 'Kevin van Zonneveld'
  // mozilla has this native
  // - but breaks in 2.0.0.12!
  //if (typeof this.window['atob'] === 'function') {
  //    return atob(data);
  //}
  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = "",
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do { // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return dec;
}

	
  $(document).ready(function(){
    $("#tabs").tabs();
    $('#uacct').val(base64_decode($('#uacct').val()));
    $('#maintenance_text').val(base64_decode($('#maintenance_text').val()));
    $('#headers').val(base64_decode($('#headers').val()));
  });
{/literal}
</script>

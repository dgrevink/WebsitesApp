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
		<a class='uk-button uk-button-success' href='javascript:tools_erasecache();'>Effacer Cache</a>
		<a class='uk-button' href='javascript:tools_erasestats();'>Effacer Statistiques</a>
		<a class='uk-button' href='javascript:tools_erasehistory();'>Effacer Historique</a>
		<a class='uk-button uk-button-danger' href='javascript:tools_phpminiadmin();'>Database Manager</a>
		<a class='uk-button uk-button-success' href='javascript:tools_phpinfo();'>PHPINFO</a>
			
		<iframe id='tools-display' style='width: 100%; height: 600px; margin-top: 10px;'>
				Upgradez votre navigateur.
		</iframe>
	</li>
	<li>
		<!--<a class='uk-button' href='javascript:tools_dumpdatabase();'>Database Dump</a>-->
		<a class='uk-button uk-button-danger' href='javascript:tools_statify();'>Statifier le site</a>
		<a class='uk-button uk-button-danger' href='javascript:tools_cleanstatify();'>D&eacute;statifier le site</a>
		<a class='uk-button uk-button-danger' href='javascript:tools_import_db();'>Database Import</a>
		<a class='uk-button uk-button-danger' href='javascript:tools_duplicatecontent_fr_en();'>Dupliquer contenu</a>
		<a class='uk-button uk-button-danger' href='javascript:tools_duplicatetabledata();'>Dupliquer table</a>
		<a class='uk-button uk-button-danger' href='javascript:tools_resetsite();'>Reset Site (DANGER)</a>
			
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
		el.ajaxSubmit(function(data) {
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

	function tools_duplicatecontent_fr_en() {
		if (confirm('Etes-vous certain ? Ceci va remplacer tous les menus EN par une copie de FR')) {
			$('#tools-display2').attr('src', '/admin/tools/duplicatecontent_fr_en/');
		}
	}

	function tools_duplicatetabledata() {
		$('#tools-display2').attr('src', '/admin/tools/duplicatetabledata/');
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

	function tools_import_db(){
		$('#tools-display2').attr('src', '/admin/tools/import_table_definition/');
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
	
  $(document).ready(function(){
    $("#tabs").tabs();
  });
{/literal}
</script>

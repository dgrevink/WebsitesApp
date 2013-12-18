<div class='uk-article'>
	<h2 class='uk-article-title'>Parameters</h2>

		<span class='uk-float-right'>
			<a href='#' onclick="jQuerySubmit('#params_base'); return false;" class='uk-button uk-button-primary'>Save</a>
		</span>

		<ul class="uk-tab" data-uk-tab="{literal}{connect:'#tabs'}{/literal}">
			<li class='uk-active'><a href="#params-tab">Parameters</a></li>
			<li><a href="#widgets-tab"><span>Widgets</span></a></li>
			<li><a href="#tools-tab"><span>Tools</span></a></li>
			<li><a href="#tools-advanced-tab"><span>Advanced</span></a></li>
			<li><a href="#logs-tab"><span>Logs</span></a></li>
		</ul>


	<ul id="tabs" class="uk-switcher uk-margin">
		<li>
			<form name='params_base' id='params_base' class='uk-form uk-form-horizontal' action='/admin/parameters/save_site/' method='post' enctype="multipart/form-data;charset=UTF-8">
				<h3>Contact information</h3>
				{$company}
				{$author}
				{$contactmail}

				<h3>Site versions</h3>
				{$version}
				{$release}

				<h3>SEO</h3>
				{$uacct}

				<h3>Site languages</h3>
				{$default_language}
				{$languages}

				<h3>Content</h3>
				{$maintenance}
				{$maintenance_text}
				{$menu_name_1}
				{$menu_name_2}
				{$menu_name_3}
				{$menu_name_4}
				{$template_page_id}

				<h3>META</h3>
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

				<h3>Security</h3>
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
				<div class='uk-alert uk-alert-danger' >Careful ! This tool will modify the widget source files in order to activate/deactivate them. Please make sure you saved everything before activating/deactivating !</div><br/>

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
					if (confirm(l.get('PARAMETERS_INIT_WIDGET')))  {
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
					if (confirm(l.get('PARAMETERS_CLEAN_WIDGET'))) {
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
		<a class='uk-button uk-button-success uk-margin-bottom' href='javascript:tools_erasecache();'>Delete Cache</a>
		<a class='uk-button uk-margin-bottom' href='javascript:tools_erasestats();'>Delete Stats</a>
		<a class='uk-button uk-margin-bottom' href='javascript:tools_erasehistory();'>Delete History</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_phpminiadmin();'>Database Manager</a>
		<a class='uk-button uk-margin-bottom' href='javascript:tools_showresources();'>Utilisation</a>
		<a class='uk-button uk-button-success uk-margin-bottom' href='javascript:tools_phpinfo();'>PHPINFO</a>

		<iframe id='tools-display' style='width: 100%; height: 600px; margin-top: 10px;'>
				Upgrade your browser.
		</iframe>
	</li>
	<li>
		<!--<a class='uk-button' href='javascript:tools_dumpdatabase();'>Database Dump</a>-->
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_statify();'>Statify site</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_cleanstatify();'>Destatify site</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_import_db();'>Table Import</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_importschema();'>Database Schema Import</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_exportschema();'>Database Schema Export</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_duplicatecontent();'>Content duplication</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_relinklanguages();'>Language linker</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_touchtable();'>Touch table</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_duplicatetabledata();'>Duplicate table</a>
		<a class='uk-button uk-button-danger uk-margin-bottom' href='javascript:tools_resetsite();'>Reset Site (DANGER)</a>

		<iframe id='tools-display2' style='width: 100%; height: 600px; margin-top: 10px;'>
				Upgrade your browser.
		</iframe>
	</li>
	<li>
		<a class='uk-button' href='javascript:logs_admin();'>Admin - Security</a>
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
		uacct = $('#uacct').val(); $('#uacct').val(btoa(uacct));
		headers = $('#headers').val(); $('#headers').val(btoa(headers));
		el.ajaxSubmit(function(data) {
			showNotify(data);
			$('#uacct').val(uacct);
			$('#headers').val(headers);
//			$('#status').html(data).fadeIn(1).fadeTo(5000, 1).fadeOut(1000);
		});
	}

	function tools_erasecache() {
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS'))) {
			$('#tools-display').attr('src', '/admin/tools/erasecache/');
		}
	}

	function tools_erasehistory() {
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS'))) {
			$('#tools-display').attr('src', '/admin/tools/erasehistory/');
		}
	}

	function tools_statify() {
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS_STATIFY'))) {
			$('#tools-display2').attr('src', '/admin/tools/statify/');
		}
	}

	function tools_cleanstatify() {
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS_STATIFY_CLEAN'))) {
			$('#tools-display2').attr('src', '/admin/tools/destatify/');
		}
	}

	function tools_erasesafeties() {
		$('#tools-display2').attr('src', '/admin/tools/erasesafeties/');
	}

	function tools_duplicatecontent() {
			$('#tools-display2').attr('src', '/admin/tools/duplicatecontent/');
	}

	function tools_touchtable() {
			$('#tools-display2').attr('src', '/admin/tools/touchtable/');
	}

	function tools_relinklanguages() {
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS'))) {
			$('#tools-display2').attr('src', '/admin/tools/relinklanguages/');
		}
	}

	function tools_duplicatetabledata() {
		$('#tools-display2').attr('src', '/admin/tools/duplicatetabledata/');
	}

	function tools_erasestats() {
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS'))) {
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
		if (confirm(l.get('PARAMETERS_CONFIRMATION_TOOLS'))) {
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

	function tools_importschema(){
		$('#tools-display2').attr('src', '/admin/tools/importschema/');
	}

	function tools_exportschema(){
		$('#tools-display2').attr('src', '/admin/tools/exportschema/');
	}



	function tools_showresources(){
		$('#tools-display').attr('src', '/admin/tools/showresources');
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

<script type="text/javascript" src="/lib/js/jquery/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin/application/lib/js/tables_list.js"></script>

<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_page.css" type="text/css">
<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_table.css" type="text/css">


<script type="text/javascript" src="/lib/js/jquery/jquery.timers.js"></script>

<script type="text/javascript" src="/admin/application/lib/js/newsletters.js"></script>

<script>
	var newsletter_html_code = "<p style='padding: 0;'>Entrez le nom de votre newsletter.</p><input id='ws-input-text-data' type='text' />";
	newsletter_html_code = newsletter_html_code + "<p style='padding: 5px 0 0 0;'>Sélectionnez le texte à envoyer</p>";
	newsletter_html_code = newsletter_html_code + "<select id='block' style='width: 410px;'>"
		{section name=blocks loop=$blocks}
				+ '<option value="{$blocks[blocks].id}">{$blocks[blocks].language|upper} - {$blocks[blocks].title}</option>'
		{/section}
		;
	newsletter_html_code = newsletter_html_code + "</select>";
	newsletter_html_code = newsletter_html_code + "<p style='padding: 5px 0 0 0;'>Sélectionnez les destinataires</p>";
	newsletter_html_code = newsletter_html_code + "<select id='filters' style='width: 410px;'>"
		{section name=filters loop=$filters}
			+ '<option value="{$filters[filters].id}">{$filters[filters].title}</option>'
		{/section}
		;
	newsletter_html_code = newsletter_html_code + "</select>";
</script>

<div class='uk-article'>
	<h2 class='uk-article-title'>Newsletters</h2>
	
	<div class='uk-alert'>Ce module va vous permettre de g&eacute;rer les envois de vos newsletters.</div>
	
	<div class='uk-float-right uk-margin-bottom'>
		<a href="#" class='uk-button uk-button-primary' id='add-newsletter'>Créer un envoi...</a>
	</div>
	
	<div id='newsletter-statuses'>
		<div id='tables'>
			<table class="display" id="table-main">
				<thead>
					<tr>
						<th width='40%'>Newsletter</th>
						<th width='25%'>Texte</th>
						<th width='20%'>Dest. (Lu/Env.)</th>
						<th width='25%'>Lanc&eacute; le...</th>
						<th width='30px' >%</th>
						<th width='60px'>Etat</th>
					</tr>
				</thead>
					{section name=news loop=$news}
					<tr id='{$news[news].id}'>
						<td><a title='Visualiser la newsletter...' href='/Newsletters/display/{$news[news].id}' target='_blank'>{$news[news].title}</a>
								<div class='row-controls' style='width: 280px;'>
									<ul>
							{if $WSR_NEWSLETTERS_SEND}<li><a title="Arr&ecirc;te ou relance l'envoi..." href='#' onclick="ppNews( '{$news[news].id}' ); return false;"><img src='/admin/application/lib/images/icons/icon-playpause.gif'/> Arr&ecirc;ter ou relancer l'envoi...</a></li>{/if}
							<li><a title='Exporter les contact de cette newsletter...' href='/admin/Newsletters/getcontacts/{$news[news].id}'><img src='/admin/application/lib/images/icons/icon-norm.gif'/> Exporter les contacts...</a></li>
							<li><a title='Exporter les contacts avec erreurs de cette newsletter...' href='/admin/Newsletters/getfailedcontacts/{$news[news].id}'><img src='/admin/application/lib/images/icons/icon-rename.gif'/> Exporter les contacts avec erreurs...</a></li>
							<li><a title='Visualiser la newsletter...' href='/Newsletters/display/{$news[news].id}' target='_blank'><img src='/admin/application/lib/images/icons/icon-view.gif'/> Visualiser...</a></li>
							<li><a title='Effacer la newsletter et les contacts directement associ&eacute;s...' href='#' onclick="deleteNews( '{$news[news].id}' ); return false;"><img src='/admin/application/lib/images/icons/icon-remove.gif'/> Effacer...</a></li>
									</ul>
								</div>
						</td>
						<td>{$news[news].block}</td>
						<td><a title='Exporter les contact de cette newsletter...' href='/admin/Newsletters/getcontacts/{$news[news].id}'>{$news[news].filter} ({$news[news].filter_total})</a></td>
						<td id='{$news[news].id}_ddate' class='ddate'>{$news[news].ddate}</td>
						<td id='{$news[news].id}_completion' class='completion'>{$news[news].completion}</td>
						<td id='{$news[news].id}_status' class='status'>{$news[news].status_text}</td>
					</tr>
					{/section}
			</table>
		</div>
	<div style='clear: both;'></div>
</div>

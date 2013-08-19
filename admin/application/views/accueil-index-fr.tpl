<div class='uk-grid' data-uk-grid-margin>

	{foreach item=stat from=$stats key=lang}
		<div class="uk-width-medium-1-2">
		<div class=" uk-panel uk-panel-box">
		{if $WSR_CONTENTS || $WSR_BLOCS || $WSR_FORMS || $WSR_NEWSLETTERS || $WSR_DATA || $admin}
				<h3 class="uk-panel-title">{$stat.language}</h3>

					{if $WSR_CONTENTS}
					<p><b>Totaux des pages</b></p>
					<ul>
						<li><b>Publi&eacute;es: </b><em>{$stat.pages_total}</em></li>
						<li><b>En brouillons: </b><em>{$stat.pages_scrap}</em></li>
						<li><b>Cach&eacute;es: </b><em>{$stat.pages_hidden}</em></li>
						<li><b>Acc&eacute;d&eacute;es &agrave; ce jour: </b><em>{$stat.pages_hits}</em></li>
					</ul>
					<p><b>Page populaires</b></p>
					{$stat.graph}
					{/if}
					{if $WSR_BLOCKS}
					<p><b>Blocs: </b><em>{$stat.blocks}</em></p>
					{/if}
					{if $WSR_DATA}
					<p><br/><b>Données: </b></p>
					{$stat.tables}
					{/if}
					{if $WSR_FORMS}
					<p><b>Formulaires: </b><em>{$stat.forms}</em></p>
					{/if}
					{if $WSR_NEWSLETTERS}
					<p><b>Newsletters</b></p>
					<ul>
						<li><b>Total: </b><em>{$stat.news}</em></li>
						<li><b>Lanc&eacute;es: </b><em>{$stat.news_running}</em></li>
						<li><b>Arr&ecirc;t&eacute;es: </b><em>{$stat.news_stopped}</em></li>
					</ul>
					{/if}
		{/if}
		</div>
		</div>
	{/foreach}


	{if $admin}
		<div class="uk-width-medium-1-1">
			<div class='uk-panel uk-panel-box uk-panel-box-secondary'>
			<h2> S&eacute;curit&eacute; </h2>	

	    	<ul>
	    		<li><b>Utilisateurs: </b>{$admin_users}</li>
	    		<li><b>Groupes: </b>{$admin_groups}</li>
	    		<li><b>D&eacute;ploiement: </b>{$admin_database_deployment}</li>
	    		<li><b>Database: </b>{$admin_database}</li>
	    		<li><b>Database User: </b>{$admin_database_user}</li>
	    		<li><b>Database Server: </b>{$admin_database_server}</li>
	    	</ul>
	    	</div>
	    </div>
	{/if}

</div>

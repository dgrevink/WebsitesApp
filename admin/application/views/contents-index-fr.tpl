<div class='uk-article'>
	<h2 class='uk-article-title'>Pages</h2>
	<div class='uk-alert'>This module is used to organise and edit your site pages.</div>
	<div class='uk-float-right uk-margin-bottom'>
		{if isset($WSR_CONTENTS_ADD)}
			<a href='#' class='uk-button uk-button-primary disposable' onclick='javascript:addMenu(0); return false;'>Nouvelle page...</a>
		{/if}
		{if isset($WSR_CONTENTS_ORDER)}
			<a href='#' class='uk-button uk-hidden-small menu-orderer'>Ordonner</a> 
			<a href='#' style='display: none;' class='uk-button menu-orderer-saver'>Sauver ordre</a> 
			<a href='#' style='display: none;' class='uk-button menu-orderer-cancel'>Annuler</a>
		{/if}
	</div>

	<br/><br/>

	{$menu_code}

	<div class='uk-panel uk-panel-box uk-margin-top'>
		Total pages: {$page_total}
	</div>
</div>

<script language="javascript" src='/admin/application/lib/js/contents-index.js'></script>

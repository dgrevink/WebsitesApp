<script type="text/javascript" src="/lib/js/jquery/jquery.cookie.js"></script>

<script type="text/javascript" src="/lib/js/jquery/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin/application/lib/js/tables_list.js"></script>

<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_page.css" type="text/css">
<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_table.css" type="text/css">

<script src="/lib/js/jquery/uploadifive/jquery.uploadifive.js" type="text/javascript"></script>
	

<script type="text/javascript" src="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script type="text/javascript" src="/admin/application/lib/js/files-index.js"></script>

<div class='uk-article'>
	<h2 class='uk-article-title'>Media {$current_page_title}</h2>
		
	<div id='comment'>
		<div class='uk-alert'>{if isset($comment)}{$comment}{/if}&nbsp;
			<div class='uk-float-right uk-hidden-small'>
				{if isset($WSR_FILES_COMMENT_DIR)}
					<a href='#' title='Add comment...' class='uk-button uk-button-primary uk-button-mini' onclick="commentDir( '{$wdir}' ); return false;"><i class="uk-icon-comment"></i><span style="margin-left: -4px;">&nbsp;</span></a>
					<a href='#' title='Remove comment...' class='uk-button uk-button-primary uk-button-mini' onclick="removeCommentDir( '{$wdir}' ); return false;"><i class="uk-icon-remove"></i><span style="margin-left: -4px;">&nbsp;</span></a>
				{/if}
			</div>
		</div>
	</div>

	{if isset($RIGHTS_WARNING)}
	<div class='uk-alert uk-alert-warning'>
		<p>Warning, you dont have write rights in this directory. You will not be able to put files in there or modify them. Contact your hosting company.</p>
	</div>
	{/if}

	<div class='uk-float-right uk-margin-bottom uk-hidden-small'>
			{if isset($WSR_FILES_NORMALIZE)}<a href='#' class='uk-button' onclick="normDir( '{$wdir}' ); return false;">Normalize file names...</a>{/if}
			{if isset($WSR_FILES_CREATE_DIR)}<a href='#'  class='uk-button' onclick="createDir( '{$wdir}' ); return false;">Create a directory...</a>{/if}
			{if isset($WSR_FILES_CREATE_FILE)}<a href='#'  class='uk-button' onclick="createFile( '{$wdir}' ); return false;">Create a file...</a>{/if}
			{if isset($WSR_FILES_UPLOAD)}<input id="file_upload" type="file" name="file_upload" />{/if}
	</div>

		<div id='tables'>
		<table class="display" id="table-main">
			<thead>
				<tr>
					<th style='min-width: 400px;'>File</th>
					<th style='width: 150px;'>Size</th>
					<!--<th>Permissions</th>-->
					<th style='width: 100px;'>Modified on</th>
				</tr>
			</thead>
			<tbody>
				{section name=files loop=$files}
				<tr>
					<td>
						{if !$files[files].nodelete}<input type='checkbox' class='file-toggle' rel='{$files[files].fullpath}'/>{/if}
						{if $files[files].type == 'dir'}
							<a href='{$files[files].name}' class='' style='font-weight: bold;' rel='{$files[files].fullpath}' rel2='{$files[files].type}'>
						{else}
							{if $files[files].img}
							<a href='{$files[files].fullpath}' class='table-thumb-holder uk-overlay uk-thumbnail uk-thumbnail-mini'>
								<img src='{$files[files].fullpath}' class='table-thumb'/><div class="uk-overlay-area"></div>
							</a>



							<a href='{$files[files].name}'>
							{elseif $files[files].type == 'txt'}
								<a href='{$files[files].name}' class='' rel='' rel2=''>
							{elseif $files[files].type == 'css'}
								<a href='{$files[files].name}' class='' rel='' rel2=''>
							{elseif $files[files].type == 'html'}
								<a href='{$files[files].name}' class='' rel='' rel2=''>
							{elseif $files[files].type == 'htm'}
								<a href='{$files[files].name}' class='' rel='' rel2=''>
							{elseif $files[files].type == 'tpl'}
								<a href='{$files[files].name}' class='' rel='' rel2=''>
							{elseif $files[files].type == 'php'}
								<a href='{$files[files].name}' class='' rel='' rel2=''>
							{else}
								<a href='#' class='' rel='' rel2=''>
							{/if}
						{/if}
							<span style='display:inline-block;'>
								{$files[files].name}
								{if $files[files].comment != ''}
								<br/><span style='color: #999; font-size: 10px; font-style: italic;'>{$files[files].comment}</span>
								{/if}
							</span>
						</a>
							
						<div class='row-controls'>
							<ul>
		{if $WSR_FILES_DOWNLOAD}
			{if $files[files].type != 'dir'}<li><a title="Download..." href='/{$files[files].wdir}{$files[files].name}' target='_blank'><i class="uk-icon-cloud-download"></i> Download...</a></li>{/if}
			{if $files[files].type == 'dir'}{if isset($ZIP_SUPPORTED)}<li><a title="T&eacute;l&eacute;charger un zip de ce r&eacute;pertoire..." href='/admin/Files/zip/{$files[files].wdir}{$files[files].name}'><i class="uk-icon-truck"></i>&nbsp; T&eacute;l&eacute;charger un ZIP...</a></li>{/if}{/if}
		{/if}
		{if $WSR_FILES_RENAME}<li><a title="Commenter..." href='#' onclick="commentFile( '{$files[files].wdir}' + '{$files[files].name}' ); return false;"><i class="uk-icon-comment"></i>&nbsp;Comment...</a></li>{/if}
		{if $WSR_FILES_RENAME}<li><a title="Renommer..." href='#' onclick="renameFile( '{$files[files].wdir}', '{$files[files].name}' ); return false;"><i class="uk-icon-edit"></i>&nbsp; Rename...</a></li>{/if}
		{if $WSR_FILES_NORMALIZE}<li><a title="Normaliser..." href='#' onclick="normFile( '{$files[files].wdir}', '{$files[files].name}', '{$key}' ); return false;"><i class="uk-icon-magic"></i>&nbsp; Normalize...</a></li>{/if}
		{if $WSR_FILES_DUPLICATE}<li><a title="Dupliquer..." href='#' onclick="duplFile( '{$files[files].wdir}', '{$files[files].name}' ); return false;"><i class="uk-icon-copy"></i>&nbsp; Duplicate...</a></li>{/if}
		{if $WSR_FILES_DELETE}<li><a title="Effacer..." href='#' onclick="delFile( '{$files[files].wdir}', '{$files[files].name}' ); return false;"><i class="uk-icon-eraser"></i>&nbsp; Delete...</a></li>{/if}
							</ul>
						</div>
						</td>
					<td class='data'>{$files[files].size}</td>
					<td class='data'>{$files[files].date}</td>
				</tr>
				{/section}
			</tbody>
		</table>

		<br/><br/><br/>
		<div class='uk-panel uk-panel-box'>
			Total: {$dir_size} in {if $dir_count > 0}{$dir_count} directorie(s){/if}{if $file_count > 0}, {$file_count} file(s){/if} <br/>
			Upload limit: {$upload_max_filesize}
		</div>
			{if $WSR_FILES_UPLOAD}
			<!--<div id='uploadify'>&nbsp;</div>&nbsp;-->
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			{literal}
			<script language="javascript">
				if ($.browser.msie) {
					$('#dialog-text').html("You need to another browser to send files !");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								$(this).dialog('close');
							}
						}
					});
				}
				else {
					$("#file_upload").uploadifive({
						'buttonText': 'Upload...',
						'buttonClass' : 'uk-button uk-button-primary',
						'uploadScript': '/admin/Files/send/',
						'multi':true,
						'auto':true,
						'onQueueComplete': function(uploads){
							$('#dialog-text').html(uploads.successful + ' files sent.');
							$("#dialog-text").dialog({
								resizable: false,
								width: 440,
								modal: true,
								buttons: {
									'OK': function() {
										window.location.href=window.location.href;
										document.location.reload();
										$(this).dialog('close');
									}
								}
							});
						},
						'formData': {
							'folder': '/{/literal}{$wdir}{literal}',
							'key': '{/literal}{$key}{literal}'
						}
						
					});
				}
			</script>
			{/literal}
			
			{/if}

		</div>
		
		
</div>
	



<div id='text-remove' style='display: none;'>...</div>

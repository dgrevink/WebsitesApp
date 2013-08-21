<script>
	var sCurrentLanguage = 'fr';
</script>


{if $listing_type == 'dir'}
<script type="text/javascript" src="/lib/js/jquery/jquery.cookie.js"></script>

<script type="text/javascript" src="/lib/js/jquery/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin/application/lib/js/tables_list.js"></script>

<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_page.css" type="text/css">
<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_table.css" type="text/css">

<link rel="stylesheet" type="text/css" href="/lib/js/jquery/uploadifive/uploadifive.css">
<script src="/lib/js/jquery/uploadifive/jquery.uploadifive.js" type="text/javascript"></script>
	

<script type="text/javascript" src="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />


<script type="text/javascript" src="/admin/application/lib/js/files_dir.js"></script>

<div class='uk-article'>
	<h2 class='uk-article-title'>Media {$current_page_title}</h2>
		
	<div id='comment'>
		<div class='uk-alert'>{$comment}&nbsp;
			<div class='uk-float-right uk-hidden-small'>
				{if isset($WSR_FILES_COMMENT_DIR)}
					<a href='#' title='Commenter...' class='uk-button uk-button-primary uk-button-mini' onclick="commentDir( '{$wdir}' ); return false;"><i class="uk-icon-comment"></i><span style="margin-left: -4px;">&nbsp;</span></a>
					<a href='#' title='Effacer le commentaire...' class='uk-button uk-button-primary uk-button-mini' onclick="removeCommentDir( '{$wdir}' ); return false;"><i class="uk-icon-remove"></i><span style="margin-left: -4px;">&nbsp;</span></a>
				{/if}
			</div>
		</div>
	</div>

	{if isset($RIGHTS_WARNING)}
	<div class='uk-alert uk-alert-warning'>
		<p>{$RIGHTS_WARNING}</p>
	</div>
	{/if}

	<div class='uk-float-right uk-margin-bottom uk-hidden-small'>
			{if isset($WSR_FILES_NORMALIZE)}<a href='#' class='uk-button' onclick="normDir( '{$wdir}' ); return false;">Normaliser...</a>{/if}
			{if isset($WSR_FILES_CREATE_DIR)}<a href='#'  class='uk-button' onclick="createDir( '{$wdir}' ); return false;">Cr&eacute;er un r&eacute;pertoire...</a>{/if}
			{if isset($WSR_FILES_UPLOAD)}<a href='#'  class='uk-button uk-button-primary' onclick="">Ajouter...</a>{/if}
	</div>

		<div id='tables'>
		<table class="display" id="table-main">
			<thead>
				<tr>
					<th style='min-width: 400px;'>Fichier</th>
					<th style='width: 150px;'>Taille</th>
					<!--<th>Permissions</th>-->
					<th style='width: 100px;'>Modifi&eacute; le</th>
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
								<img src='{$files[files].fullpath}' /><div class="uk-overlay-area"></div>
							</a>
							<a href='{$files[files].name}'>
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
							
						<div class='row-controls' style='width: 150px; right: 0;'>
							<ul>
		{if $WSR_FILES_DOWNLOAD}
			{if $files[files].type != 'dir'}<li><a title="T&eacute;l&eacute;charger..." href='/{$files[files].wdir}{$files[files].name}' target='_blank'><img src='/admin/application/lib/images/icons/icon-view.gif'/> T&eacute;l&eacute;charger...</a></li>{/if}
			{if $files[files].type == 'dir'}{if $ZIP_SUPPORTED}<li><a title="T&eacute;l&eacute;charger un zip de ce r&eacute;pertoire..." href='/admin/Files/zip/{$files[files].wdir}{$files[files].name}'><img src='/admin/application/lib/images/icons/icon-view-zip.gif'/> T&eacute;l&eacute;charger un ZIP...</a></li>{/if}{/if}
		{/if}
		{if $WSR_FILES_RENAME}<li><a title="Commenter..." href='#' onclick="commentFile( '{$files[files].wdir}' + '{$files[files].name}' ); return false;"><img src='/admin/application/lib/images/icons/icon-plus.gif'/> Commenter...</a></li>{/if}
		{if $WSR_FILES_RENAME}<li><a title="Renommer..." href='#' onclick="renameFile( '{$files[files].wdir}', '{$files[files].name}' ); return false;"><img src='/admin/application/lib/images/icons/icon-rename.gif'/> Renommer...</a></li>{/if}
		{if $WSR_FILES_NORMALIZE}<li><a title="Normaliser..." href='#' onclick="normFile( '{$files[files].wdir}', '{$files[files].name}', '{$key}' ); return false;"><img src='/admin/application/lib/images/icons/icon-norm.gif'/> Normaliser...</a></li>{/if}
		{if $WSR_FILES_DUPLICATE}<li><a title="Dupliquer..." href='#' onclick="duplFile( '{$files[files].wdir}', '{$files[files].name}' ); return false;"><img src='/admin/application/lib/images/icons/icon-duplicate.gif'/> Dupliquer...</a></li>{/if}
		{if $WSR_FILES_DELETE}<li><a title="Effacer..." href='#' onclick="delFile( '{$files[files].wdir}', '{$files[files].name}' ); return false;"><img src='/admin/application/lib/images/icons/icon-remove.gif'/> Effacer...</a></li>{/if}
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
			{$directory_info}<br/>
			Limite upload d&eacute;tect&eacute;e: {$upload_max_filesize}
		</div>
			{if $WSR_FILES_UPLOAD}
			<!--<div id='uploadify'>&nbsp;</div>&nbsp;-->
			<br/>
			<br/>
			<br/>
			<input id="file_upload" type="file" name="file_upload" />
			<br/>
			<br/>
			<br/>
			{literal}
			<script language="javascript">
				if ($.browser.msie) {
					$('#dialog-text').html("Vous devez utiliser Chrome, Safari ou Firefox pour envoyer des fichiers !");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK je vais changer de navigateur !': function() {
								$(this).dialog('close');
							}
						}
					});
				}
				else {
					$("#file_upload").uploadifive({
						'buttonText': 'S&eacute;lectionner des fichiers &agrave; envoyer...',
						'width': '200',
						'uploadScript': '/admin/Files/send/',
						'multi':true,
						'auto':true,
						'onQueueComplete': function(uploads){
							$('#dialog-text').html(uploads.successful + ' fichiers envoy&eacute;s.');
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
	
{/if}




{if $listing_type == 'image'}
<script src="/lib/js/jquery/jcrop/js/jquery.Jcrop.pack.js"></script>
<link rel="stylesheet" href="/lib/js/jquery/jcrop/css/jquery.Jcrop.css" type="text/css" />

<div class='uk-article'>
	<h2 class='uk-article-title'>Media {$current_page_title}</h2>

	<div class='uk-panel uk-panel-box'>
		<span id='image-size'>Taille: {$fileinfo}</span><br/>
		<span id='image-selection' class='uk-hidden-small'>S&eacute;lection: <span id='image-selection-size'>&nbsp;</span></span>
		<span id='image-coords' class='uk-hidden-small'>Coordonnées: <span id='completecoords' style='display: inline !important;'>&nbsp;</span></span>
	</div>

	<div class='uk-grid uk-margin-top'>
		<div class='uk-width-medium-1-4 uk-width-1-1 uk-form'>
			<input id='filename' type='hidden' value='{$wfile}' />
			<div id='status'></div>
					<span id='c-data' style='display: none;'>
						<span>
							<label>X1 <input type="text" size="4" id="x" name="x" /></label>
						    <label>Y1 <input type="text" size="4" id="y" name="y" /></label>
						    <label>X2 <input type="text" size="4" id="x2" name="x2" /></label>
						    <label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>
							<label>W  <input type="text" size="4" id="w" name="w" /></label>
							<label>H  <input type="text" size="4" id="h" name="h" /></label>
					    </span>
					</span>

				<fieldset>
					<span class='uk-hidden-small'>
						<legend>Dimensions</legend>
						<div class="uk-form-row">
							<input class='uk-form-small uk-width-100' type='text' name='width' id='width' value='' placeholder='Largeur'/><br/>
						</div>
						<div class="uk-form-row">
							<input class='uk-form-small uk-width-100' type='text' name='height' id='height' value='' placeholder='Hauteur' />
						</div>
						<div class="uk-form-row">
							<input class='uk-button uk-button-small' type='button' name='setaspect' id='setaspect' value='Prop.' />
							<input class='uk-button uk-button-small' type='button' name='crop' id='crop' value='D&eacute;couper' />
							<input class='uk-button uk-button-small' type='button' name='pad' id='pad' value='Padder' />
						</div>
					</span>
					<div class="uk-form-row"></div>
					<div class="uk-form-row"><legend>Orientation:</legend></div>
					<select class='uk-form-small' id='angle' name='angle'>
						<option id='gauche' name='gauche' value="90">Gauche (-90&deg;)</option>
						<option id='droite' name='droite' value="-90">Droite (+90&deg;)</option>
						<option id='rot180' name='rot180' value="180">180&deg;</option>
						<option id='flipvert' name='flipvert' value="flipver">Sym&eacute;trie</option>
						<option id='fliphor' name='fliphor' value="fliphor">Miroir</option>
					</select>
					<input class='uk-button uk-button-small' type='button' name='rotate' id='rotate' value='Go' />
					<div class="uk-form-row"></div>
					<div class="uk-form-row"><legend>Filtres:</legend></div>
					<select class='uk-form-small'  id='filtre' name='filtre'>
						<option value="BlurSelective">Blur sélectif</option>
						<option value="BlurGaussian">Blur gaussien</option>
						<option value="Sharpen">Sharpen</option>
						<option value="EdgeDetect">Edge Detect</option>
						<option value="Emboss">Emboss</option>
						<option value="Grayscale">Grayscale</option>
						<option value="MeanRemoval">MeanRemoval</option>
						<option value="Negative">Negative</option>
						<option value="Sepia">Sepia</option>
						<option value="HistogramStretch">Auto Contrast</option>
						<option value="HistogramStretch2">Auto Contrast 2</option>
						<option value="BrightnessMore">Luminosité +</option>
						<option value="BrightnessLess">Luminosité -</option>
						<option value="ContrastMore">Contraste +</option>
						<option value="ContrastLess">Contraste -</option>
						<option value="GammaMore">Gamma +</option>
						<option value="GammaLess">Gamma -</option>
						<option value="SaturationMore">Saturation +</option>
						<option value="SaturationLess">Saturation -</option>
						<option value="Smooth">Smooth</option>
					</select>
					<input class='uk-button uk-button-small' type='button' name='rotate' id='filter-go' value='Go' />
					<div class="uk-form-row"></div>
					<div class="uk-form-row"><legend>Actions:</legend></div>
					<input class='uk-button uk-button-small' type='button' name='halve' id='halve' value='Diminuer image de moiti&eacute;' />
					<div class="uk-form-row"></div>
					<div class="uk-form-row"></div>
				</fieldset>
		</div>
		<div class='uk-width-medium-3-4 uk-width-1-1'>
			<img src='/{$wfile}' id='cropbox'/>
		</div>
	</div>


		
</div>

	
	<script type="text/javascript" src="/admin/application/lib/js/files_image.js"></script>

{/if}

{if $listing_type == 'media'}
	<script src="/lib/js/jquery/jquery.metadata.js"></script>
	<script src="/lib/js/jquery/jquery.media.js"></script>
	<a href="/{$wfile}" class='media'></a>
	<script language="Javascript">
	{literal}
		$('a.media').media();
	{/literal}
	</script>
{/if}

{if $listing_type == 'file'}
	<input id='filename' type='hidden' value='{$wfile}' />
	<div id='fe-controls'>
		<input type='button' id='save' value='Sauvegarder' /> <span>{$fileinfo}</span>
	</div>
	<textarea id='filecontent'>{$wfilecontent}</textarea>
	<script language="Javascript">
	{literal}
		$('#save').click(function(e){
			form = new Object();
			form.filecontent = $('#filecontent').val();
			form.filename = $('#filename').val();
			
			$.ajax({
				type: "POST",
				url: '/admin/files/save/',
				data: form,
				success: function(msg) {
		    		$('#status').html(msg).fadeIn(1).fadeTo(5000, 1).fadeOut(1000);
				}
			});
		});
	{/literal}
	</script>
{/if}







<div id='text-remove' style='display: none;'>Go fug yourself !</div>

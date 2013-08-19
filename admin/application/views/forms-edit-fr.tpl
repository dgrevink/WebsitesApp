<!-- JS Scripts -->
<script type="text/javascript" src="/lib/js/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="/lib/js/jquery/jquery.select.js"></script>
<script type="text/javascript" src="/lib/js/jquery/jquery.scrollTo-min.js"></script>

<script type="text/javascript" src="/lib/js/templating.js"></script>

<script type="text/javascript" src="/admin/application/lib/js/forms_edit.js"></script>

<!-- JS Templates -->
{literal}
<script type="text/html" id="separatortpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="separator"/>

		<input type="hidden" id="<%=name%>_mandatory" name="<%=name%>_mandatory" value="false"/>

		<input type="hidden" id="<%=name%>_error" name="<%=name%>_error" value=""/>

		<input type="hidden" id="<%=name%>_values" name="<%=name%>_values" value=""/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>S&eacute;paration</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/>

		<br/>

		<label>Texte:</label><br/>
		<textarea name="<%=name%>_comment" id="<%=name%>_comment" cols="80" rows="10"><%=comment%></textarea>
	</li>
</script>

<script type="text/html" id="hiddentpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="hidden"/>

		<input type="hidden" id="<%=name%>_mandatory" name="<%=name%>_mandatory" value="false"/>

		<input type="hidden" id="<%=name%>_error" name="<%=name%>_error" value=""/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />

		<h3>Cach&eacute;</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>

		<label>Valeur par d&eacute;faut:</label>
		<input type="text" id="<%=name%>_values" value="<%=values%>"/>

		<br/>

		<input type="hidden" id="<%=name%>_error" name="<%=name%>_comment" value=""/>
	</li>
</script>

<script type="text/html" id="texttpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="text"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Zone de texte</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>

		<input type="hidden" id="<%=name%>_values" name="<%=name%>_values" value=""/>
<!--
		<label>Valeur par d&eacute;faut:</label>
		<input type="text" id="<%=name%>_values" value="<%=values%>"/>
-->
		<br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />


	</li>
</script>

<script type="text/html" id="passwordtpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="password"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Mot de passe</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>

		<input type="hidden" id="<%=name%>_values" name="<%=name%>_values" value=""/>
<!--
		<label>Valeur par d&eacute;faut:</label>
		<input type="text" id="<%=name%>_values" value="<%=values%>"/>
-->
		<br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />


	</li>
</script>

<script type="text/html" id="longtexttpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="longtext"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Zone de texte longue</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>

		<input type="hidden" id="<%=name%>_values" name="<%=name%>_values" value=""/>
<!--
		<label>Valeur par d&eacute;faut:</label>
		<input type="text" id="<%=name%>_values" value="<%=values%>"/>
-->

		<br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />


	</li>
</script>
	
<script type="text/html" id="emailtpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="email"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Courriel</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>

		<input type="hidden" id="<%=name%>_values" name="<%=name%>_values" value=""/>
<!--
		<label>Valeur par d&eacute;faut:</label>
		<input type="text" id="<%=name%>_values" value="<%=values%>"/>
-->

		<br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />


	</li>
</script>

<script type="text/html" id="tablelinktpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="tablelink"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Champs d'une table</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>

		<br/><label>Table:</label>
		<input type="text" id="<%=name%>_params1" name="<%=name%>_params2" value="<%=params1%>"/>

		<br/><label>Condition:</label>
		<input type="text" id="<%=name%>_params2" name="<%=name%>_params2" value="<%=params2%>"/>

		<br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />


	</li>
</script>
	
<script type="text/html" id="selecttpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="select"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>S&eacute;lecteur</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>
		
		<br/>

		<label>Valeurs possibles:</label>
		<br/>
		<div class="ws-forms-textarea-container">
			<textarea id="<%=name%>_values" class="ws-forms-textarea"><% for ( var i = 0; i < values.length; i++ ){%><%=values[i]%>\n<%}%></textarea>
		</div>

		<br/><br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />

		<br/><label style="width: 600px;">Pour rendre la question obligatoire, cochez "Obligatoire", de plus, sp&eacute;cifiez en premi&egrave;re valeur un texte par d&eacute;faut commen&ccedil;ant par deux tirets, par exemple: --S&eacute;lectionnez une valeur--</label>

	</li>
</script>
	
<script type="text/html" id="radiotpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="radio"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Radio</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>
		
		<br/>

		<label>Valeurs possibles:</label>
		<br/>
		<div class="ws-forms-textarea-container">
			<textarea id="<%=name%>_values" class="ws-forms-textarea"><% for ( var i = 0; i < values.length; i++ ){%><%=values[i]%>\n<%}%></textarea>
		</div>

		<br/><br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />


	</li>
</script>

<script type="text/html" id="multicheckboxtpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="multicheckbox"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Question &agrave; choix multiples</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>
		
		<br/>

		<label>Valeurs possibles:</label>
		<br/>
		<div class="ws-forms-textarea-container">
			<textarea id="<%=name%>_values" class="ws-forms-textarea"><% for ( var i = 0; i < values.length; i++ ){%><%=values[i]%>\n<%}%></textarea>
		</div>

		<br/><br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />


	</li>
</script>


<script type="text/html" id="attachmenttpl">
	<li class="uk-panel uk-panel-box" id="<%=name%>">
		<input type="hidden" id="<%=name%>_type" name="<%=name%>_type" value="attachment"/>

		<img src="/admin/application/lib/images/close.png" class="close-button"  id="<%=name%>_remove" />
		<h3>Fichier</h3>
		<label>Libell&eacute;:</label>
		<input type="text" id="<%=name%>_label" value="<%=label%>"/> <span class="smallname">( <%=name%> )</span>

		<br/><label>Chemin pour le stockage:</label>
		<input type="text" id="<%=name%>_params1" name="<%=name%>_params1" value="<%=params1%>"/>

		<input type="hidden" id="<%=name%>_params2" name="<%=name%>_params2" value="<%=params2%>"/>

		<!--
		<br/><label>Condition:</label>
		<input type="text" id="<%=name%>_params2" name="<%=name%>_params2" value="<%=params2%>"/>
		-->

		<br/>

		<label>Commentaire:</label>
		<input type="text" id="<%=name%>_comment" value="<%=comment%>"/>

		<label>Message d'erreur:</label>
		<input type="text" id="<%=name%>_error" value="<%=error%>"/>

		<br/>

		<label>Obligatoire:</label>
		<input type="checkbox" id="<%=name%>_mandatory" <% if (mandatory) { %> checked="checked" <% } %> />
		
		<br/>
		<br/>
		<label style="width: 500px;">Spécifiez le message d'erreur, le système refusera tout autre fichier autre que les types suivants: txt, jpg, jpeg, gif, png, pdf, doc, xls, docx, xlsx</label>


	</li>
</script>
	
{/literal}








<div class="block">

<form name='forms_form' class='uk-form' id='forms_form' action='/admin/forms/save/' method='post' enctype='UTF-8'>
<input type='hidden' id='form_id' name='id' value='{$id}'/>
<input type='hidden' id='form_language' name='language' value='{$language}'/>


<div id='status'></div>

		
<div class='uk-article'>
	<h2 class='uk-article-title' id='sub-page-title'>{$current_page_title}</h2>

	<div class='uk-alert'>Création/modification de formulaire.</div>

		<div class='uk-float-right uk-margin-bottom'>
			<a class='uk-button uk-button-primary save-form' href='javascript:void(0)'>Sauvegarder</a>
		</div>
		
		<ul class="uk-tab" data-uk-tab="{literal}{connect:'#tabs'}{/literal}">
			{if $WSR_FORMS_CREATE}
			<li class='uk-active'><a href="#parameters"><span>Param&egrave;tres</span></a></li>
			{/if}
			{if $WSR_FORMS_QUESTIONS}
			<li><a href="#questions"><span>Questions</span></a></li>
			{/if}
			{if $WSR_FORMS_TEXTS}
			<li><a href="#texts"><span>Textes</span></a></li>
			{/if}
			{if $WSR_FORMS_CONSULT}
			<li><a href="#data"><span>Donn&eacute;es</span></a></li>
			{/if}
		</ul>
	
		<ul id="tabs" class="uk-switcher uk-margin">
			<li>
				{if $WSR_FORMS_CREATE}
					{$title}
					{$type}
					{$emailsender}
					{$emailadmin}
					{$userquestions}
					{$emailextra}
					{$max_questions}
					{$tabledefinitions_id}
					{$usecaptcha}
					{$locked}
				{/if}
			</li>
			<li>
				{if $WSR_FORMS_QUESTIONS}
					<div class='adder'>
					
						<h3>Ajouter une question</h3>
						
						<label>Nom:</label>
						<input type="text" id="label_title" />
					
						<label>Type:</label>
						<select id="label_type" name="label_type">
							<optgroup label='Simples'>
							    <option value="text"			>Texte sur une ligne</option>
							    <option value="email"			>Texte sur une ligne - Courriel</option>
							    <option value="longtext"		>Texte sur plusieurs lignes</option>
							    <option value="attachment"		>Fichier</option>
						    </optgroup>
						    <optgroup label='&agrave; listes'>
							    <option value="select"			>Liste d&eacute;roulante</option>
							    <option value="tablelink"		>Liste d&eacute;roulante &agrave; partir d'une table</option>
							    <option value="radio"			>Question &agrave; choix unique (pour sondages)</option>
							    <option value="multicheckbox"	>Question &agrave; choix multiples</option>
						    <optgroup>
						    <optgroup label='Avanc&eacute;s / Sp&eacute;ciaux'>
							    <option value="separator"		>S&eacute;paration (Avec possibilit&eacute; de titre et contenu)</option>
							    <option value="password"		>Mot de passe</option>
							    <option value="hidden"			>Champ cach&eacute; avec valeur par d&eacute;faut (Avanc&eacute;)</option>
						    <optgroup>
						</select>
					
						<a href='javascript:void(0)' class='uk-button'>Ajouter</a>
					
					</div>
			
					<div id='questions'>
						<ul>
						</ul>
					</div>
				{/if}
			</li>
			<li>
				{if $WSR_FORMS_TEXTS}
					<div class='uk-grid'>
					<div class='uk-width-1-1 uk-width-medium-1-4 uk-width-large-2-6'>
						<ul class="uk-tab uk-tab-left" data-uk-tab="{literal}{connect:'#subtabs'}{/literal}">
							<li class='uk-active'><a href="#sb1">Texte introductif</a></li>
							<li><a href="#sb2">Remerciements</a></li>
							<li><a href="#sb3">Erreurs</a></li>
							<li><a href="#sb4">Courriel utilisateur</a></li>
							<li><a href="#sb5">Courriel administrateur</a></li>
							<li><a href="#sb6">Concours: déjà participé</a></li>
						</ul>
					</div>
					<div class='uk-width-1-1 uk-width-medium-3-4 uk-width-large-4-6'>
					
						<ul id="subtabs" class="uk-switcher uk-margin">
							<li id='sb1'>
								<textarea name='formintroduction' id='formintroduction' class='form_textarea'>{$introduction}</textarea>
							</li>
							<li id='sb2'>
									<textarea name='formthanks' id='formthanks' class='form_textarea'>{$thanks}</textarea>
							</li>
							<li id='sb3'>
									<textarea name='formerrors' id='formerrors' class='form_textarea'>{$errors}</textarea>
							</li>
							<li id='sb4'>
									<textarea name='formmailuser' id='formmailuser' class='form_textarea'>{$mailuser}</textarea>
							</li>
							<li id='sb5'>
									<textarea name='formmailadmin' id='formmailadmin' class='form_textarea'>{$mailadmin}</textarea>
							</li>
							<li id='sb6'>
									<textarea name='formcontesterror' id='formcontesterror' class='form_textarea'>{$contesterror}</textarea>
							</li>
						</ul>
					</div>
				{/if}
			</li>
			<li>
				{if $WSR_FORMS_CONSULT}
					<a class='button2' href='javascript:tools_eraseresults( {$id} );'>Effacer R&eacute;sultats</a><br/><br/><br/>
					<div id='formresults'>
					<table class='formresults'>
					{foreach name=outer key=outerkey item=outer from=$responses}
						{if $smarty.foreach.outer.first}
						<tr>
						{foreach key=key item=item from=$outer name=inner}
							{if $smarty.foreach.inner.first}
							<td class='hed'>{$key}</td>
							{else}
							<td class='hed'>{$key}</td>
							{/if}
						{/foreach}
						</tr>
						{/if}
						<tr>
						{foreach key=key item=item from=$outer}
							<td>{$item}</td>
						{/foreach}
						</tr>
					{foreachelse}
						<tr>
							<td>
								Le formulaire n'a jamais &eacute;t&eacute; rempli.
							</td>
						</tr>
					{/foreach}
					</table>
					</div>
				{/if}
			</li>
		</ul>


	

</form>

	<div class="bendl"></div>
	<div class="bendr"></div>
	
</div>


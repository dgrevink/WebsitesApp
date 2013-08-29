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

<script type="text/javascript" src="/admin/application/lib/js/files-image.js"></script>


<div id='text-remove' style='display: none;'>...</div>

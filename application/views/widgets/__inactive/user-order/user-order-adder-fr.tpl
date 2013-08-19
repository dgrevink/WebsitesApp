<div id='ws-of-adder'>
<form action='/fr/commande/+afficher+{$order->id}#ws-order-form' method='post'>
<input type="radio" name="by" value="reference" class='by' id='byreference'/>
Par # de référence: 
<input type="text" name="reference" class='reference' id='reference' value="" />
<br/>


<input type="radio" name="by" value="selection" class='by' id='byselection'/>
Par sélection:
	<select name='category' id='category'>
	{foreach name=cats item=cats from=$categories}
		<option value='{$cats.id}'>{$cats.title}</option>
	{/foreach}
	</select>

{foreach name=categories2 item=categories key=category from=$categories}
	<div class='tabcontent' id='tab-{$categories.id}'>
		<select size='12' name='selection' class='selection'>
		{foreach name=products item=product from=$products}
			{if $product.productcategories_id == $categories.id}
				<option value='{$product.id}' rel='{$product.title}'>{$product.name} {$product.contents}</option>
			{/if}
		{/foreach}
		</select>
	</div>
{/foreach}
<div style='clear: both;'>&nbsp;</div>

<input type='submit' name='add' value='Ajouter' id='do-add' class='button medium paquet'/>
<a href='+afficher+{$order->id}#ws-order-form' id='do-cancel' class='button medium paquet'>Annuler</a>
</form>
<br/>
<br/>
</div>

<script>
{literal}
$(document).ready(function(){

	$('.tabcontent').hide();
	$('#tab-' + $('#category').val()).show();
	
	$('#reference').focus(function(){
		$('#byreference').attr('checked', true);
	});
	
	$('#category').focus(function(){
		$('#byselection').attr('checked', true);
	});
	
	$('.selection').focus(function(){
		$('#byselection').attr('checked', true);
	});

	$('.selection').click(function(){
		product_ref = $('.selection option[value=' + $(this).val() + ']').attr('rel');
		$('#reference').val(product_ref);
	});
	
	$('#category').change(function(){
		$('.tabcontent').hide();
		$('#tab-' + $('#category').val()).show();
	});
	
});
{/literal}
</script>

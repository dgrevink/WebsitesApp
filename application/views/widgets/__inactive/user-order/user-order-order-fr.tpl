<div id='ws-of-order'>

{if $order->orderstates_id != 1}<a href='+afficher' class='button medium paquet' style='float: left;' title='Revenir à toutes les commandes'>&larr;</a>{/if}
<h3>Commande {$order->title}</h3>
<h4>{$order->ddate} - {$order->state_title}</h4>
<br/>
<div id='theader'>
	<div class='telement paquet'>
	<div class='tproduct'>Produit</div>
	<div class='tquantity'>Quantité</div>
	{if $order->orderstates_id == 1}<div class='taction'>&nbsp;</div>{/if}
	</div>
</div>
<div id='tbody'>
{section name=orderdetails loop=$orderdetails}
<div class='telement {if $smarty.section.orderdetails.index is even}teven{else}todd{/if}' rel='{$orderdetails[orderdetails].products_id}'>
	<div class='tproduct'>
		<span class='tfirst'>{$orderdetails[orderdetails].product_category_title} {$orderdetails[orderdetails].product_brand_title}</span><br/>
		<span class='tsecond'>{$orderdetails[orderdetails].product_title} {$orderdetails[orderdetails].product_name} - {$orderdetails[orderdetails].product_contents}</span>
	</div>
	<div class='tquantity'>
		{$orderdetails[orderdetails].quantity}
	</div>
	{if $order->orderstates_id == 1}
		<div class='taction'>
			<a href='#' class='ws-add-item'><img src='/public/images/ui/add.png' alt='Changer'/></a>
		</div>
	{/if}
</div>
{sectionelse}
<h3 style='padding: 15px;'>Il n'y a pas de produits dans votre commande ! Veuillez sélectionner "Ajouter un produit..." pour commencer !</h3>
{/section}
</div>
<br/>
<div style='width: 100%;'>
	{if $order->orderstates_id == 1}
	<br/>
	<label>Remarques concernant cette commande:<br/>
	<textarea name='ccomment' id='ws-cmd-comment' class='form_textarea' style='width: 80%;' rows='8'></textarea>
	</label><br/>
	<a href='+ajouter+{$order->id}#ws-order-form' class='button medium paquet' id='do-add-product'>Ajouter un produit...</a>
	{if $smarty.section.orderdetails.total > 0}<a href='+afficher+{$order->id}+passer#ws-order-form' class='button medium paquet' id='do-order'>Passer la commande...</a>{/if}
	<a href='+afficher+{$order->id}+annuler#ws-order-form' class='button medium paquet' id='do-cancel'>Annuler la commande...</a>
	{$error_reason}
	{else}
	{if $order->ccomment != ''}<em>Commentaire:</em><br/>{$order->ccomment}<br/><br/>{/if}
	{if $order->orderstates_id != 1 AND $can_order == true}<a href='+importer+{$order->id}' class='button medium paquet' />Créer une nouvelle commande à partir de celle-ci.</a>{/if}
	{/if}
</div>
</div>

<script>
{literal}
$(document).ready(function(){
	$('#do-order').click(function(){
		if (confirm("Voulez-vous vraiment passer la commande ?")) {
			alert("La commande va être envoyée à l'instant. Vous devriez avoir une réponse dans les prochains jours.");
			$.cookie('ccomment', $('#ws-cmd-comment').val());
			return true;
		}
		else {
			return false;
		}
	});

	$('#do-add-product').click(function(){
			$.cookie('ccomment', $('#ws-cmd-comment').val());
	});

	$('#do-cancel').click(function(){
		if (confirm("Ceci va annuler la commande ! Êtes-vous certain ?")) {
			$.cookie('ccomment', '');
			return true;
		}
	});
	if ($.cookie('ccomment') != '') {
		$('#ws-cmd-comment').val($.cookie('ccomment'));
	}
	
	$('.ws-add-item').click(function(){
		var product_id = $(this).parent().parent().attr('rel');
		var product_quantity = $.trim($(this).parent().parent().children('.tquantity').html());
		product_quantity = prompt('Entrez la quantité désirée (0 ou rien pour effacer):', product_quantity);
		
		$.cookie('ccomment', $('#ws-cmd-comment').val());
		
		window.location = '+afficher+{/literal}{$order->id}{literal}+set+' + product_id + '+' + product_quantity + '#ws-order-form';

		return false;
	});
});
{/literal}
</script>
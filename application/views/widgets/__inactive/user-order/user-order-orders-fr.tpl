{if $total_orders == 0}
<p>
Vous n'avez pas encore commandé de produits chez nous !
<br/>Nous vous proposons de le faire en cliquant sur le bouton "Créer une nouvelle commande..."<br/><br/></p>
{else}

{if isset($active_order)}

<div id='theader'>
	<div class='telement paquet'>
		<div class='tproduct'>La commande que vous préparez...</div>
	</div>
</div>
<div id='tbody'>
<div class='telement tmini todd'>
	<div class='tproduct'>
	<a href='/fr/commande/+afficher+{$active_order->id}#ws-order-form'>{$active_order->title} - {$active_order->ddate}</a><br/>
	</div>
</div>
</div>
<br/>


{/if}

<!-- Old orders -->
{if isset($orders)}
<div id='theader'>
	<div class='telement paquet'>
		<div class='tproduct'>Commandes en attente de réponse</div>
	</div>
</div>
<div id='tbody'>
{section name=orders loop=$orders}
<div class='telement tmini {if $smarty.section.orders.index is even}teven{else}todd{/if}'>
	<div class='tproduct'>
		<a href='/fr/commande/+afficher+{$orders[orders].id}#ws-order-form'>{$orders[orders].title} - {$orders[orders].ddate}</a>
	</div>
</div>
{/section}
</div>
<br/>
{/if}

<!-- Old orders -->
{if isset($porders)}
<div id='theader'>
	<div class='telement paquet'>
		<div class='tproduct'>Commandes précédentes</div>
	</div>
</div>
<div id='tbody'>
{section name=porders loop=$porders}
<div class='telement tmini {if $smarty.section.porders.index is even}teven{else}todd{/if}'>
	<div class='tproduct'>
		<a href='/fr/commande/+afficher+{$porders[porders].id}#ws-order-form'>{$porders[porders].title} - {$porders[porders].ddate}</a>
	</div>
</div>
{/section}
</div>
<br/>
{/if}



{/if}



{if $can_order}
	<a href='/fr/commande/+creer' class='button medium paquet'>Créer une nouvelle commande...</a>
{else}
	<a href='/fr/commande/+afficher+{$active_order->id}#ws-order-form' class='button medium paquet'>Compl&eacute;ter ma commande...</a>
{/if}









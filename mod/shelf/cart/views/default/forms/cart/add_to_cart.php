<?php

use hypeJunction\Payments\ProductInterface;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ProductInterface) {
//    return;
}

elgg_require_js('cart/add_to_cart');

echo elgg_view('input/hidden', [
	'name' => 'guid',
	'value' => $entity->guid
]);

$quantity = elgg_view('input/text', [
	'name' => 'quantity',
	'value' => 1,
	'max' => 1,
	'maxlength' => 3
		]);

$submit = elgg_view('input/submit', [
	'value' => elgg_echo('cart:add_to_cart'),
		]);
?>

<div class="cart-add-to-cart">
 	<div class="cart-product-price">0
	</div>
	<div class="cart-product-quantity">
		<?= $quantity ?>
	</div>
	<div class="cart-product-button">
		<?= $submit ?>
	</div>
</div>
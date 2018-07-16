<?php

//use hypeJunction\Payments\ProductInterface;

$entity = elgg_extract('entity', $vars);
/*
if (!$entity instanceof ProductInterface) {
//    return;
}*/

elgg_require_js('shelf/load');

echo elgg_view('input/hidden', [
	'name' => 'guid',
	'value' => $entity->guid
]);
echo elgg_view('input/hidden', [
	'name' => 'access_id',
	'value' => 0
]);

$quantity = elgg_view('input/text', [
	'name' => 'quantity',
	'value' => 1,
	'max' => 1,
	'maxlength' => 3
		]);

$submit = elgg_view('input/submit', [
	'value' => elgg_echo('shelf:load'),
		]);
?>

<div class="shelf-load">
	<div class="shelf-product-quantity">
		<?= $quantity ?>
	</div>
	<div class="shelf-product-button">
		<?= $submit ?>
	</div>
</div>
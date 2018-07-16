<?php

use hypeJunction\Payments\ProductInterface;

$params = new stdClass();

$input_keys = array_keys((array) elgg_get_config('input'));
$request_keys = array_keys((array) $_REQUEST);
$keys = array_unique(array_merge($input_keys, $request_keys));
foreach ($keys as $key) {
	if ($key) {
		$params->$key = get_input($key);
	}
}

$params->quantity = (int) $params->quantity;
$params->product = get_entity($params->guid);
/*
if (!$params->product instanceof ProductInterface) {
	register_error(elgg_echo('cart:add_to_cart:error:invalid_product'));
	forward(REFERRER);
}*/
if ($params->quantity < 0) {
	register_error(elgg_echo('cart:add_to_cart:error:negative_quantity'));
	forward(REFERRER);
}

cart()->add($params->product, $params->quantity);
cart()->save();

if (elgg_is_xhr()) {
	echo json_encode([
		'totalLines' => cart()->countLines(),
		'totalProducts' => cart()->count(),
	]);
}

system_message(elgg_echo('cart:add_to_cart:success'));
forward(REFERRER);


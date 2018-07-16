<?php

use hypeJunction\Cart\Cart;
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

$cart = cart()->get($params->hash);

if (!$cart instanceof Cart) {
	register_error(elgg_echo('cart:checkout:error:not_found'));
	forward(REFERRER);
}

foreach ($params->items as $guid => $quantity) {
	$product = get_entity($guid);
	if (!$product instanceof ProductInterface) {
		continue;
	}
	$cart->update($product, (int) $quantity);
}

$cart->set('payment_method', $params->payment_method);
$cart->set('shipping_address', $params->shipping_address);

cart()->set($params->hash, $cart);
cart()->save();

forward(REFERER);
<?php

use hypeJunction\Cart\CartStorage;
use hypeJunction\Cart\Db;
use hypeJunction\Cart\Session;
use hypeJunction\Cart\ShoppingCart;

$plugin_root = __DIR__;
if (file_exists("{$plugin_root}/vendor/autoload.php")) {
	// check if composer dependencies are distributed with the plugin
	require_once "{$plugin_root}/vendor/autoload.php";
}

/**
 * Returns cart singleton
 *
 * @staticvar CartCollection $cart Cart collection
 * @return ShoppingCart
 */
function cart() {
	static $cart;
	if (!isset($cart)) {
		$storage = new CartStorage();
		$db = new Db();
		$session = new Session();
		$cart = new ShoppingCart($storage, $db, $session);
		$cart->restore();
	}
	return $cart;
}
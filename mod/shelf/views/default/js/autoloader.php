<?php

use shelf\ShelfStorage;
use shelf\Db;
use shelf\Session;
use shelf\ShoppingCart;

$plugin_root = __DIR__;
if (file_exists("{$plugin_root}/vendor/autoload.php")) {
	// check if composer dependencies are distributed with the plugin
	require_once "{$plugin_root}/vendor/autoload.php";
}

/**
 * Returns shelf singleton
 *
 * @staticvar CartCollection $cart Cart collection
 * @return ShoppingCart
 */
function shelf() {
	static $shelf;
	if (!isset($shelf)) {
		$storage = new ShelfStorage();
		$db = new Db();
		$session = new Session();
		$shelf = new ShoppingCart($storage, $db, $session);
		$shelf->restore();
	}
	return $shelf;
}
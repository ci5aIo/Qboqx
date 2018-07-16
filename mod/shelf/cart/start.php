<?php

/**
 * Shopping Cart
 *
 * @package hypeJunction
 * @subpackage cart
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'cart_init');

/**
 * Initialize
 * @return void
 */
function cart_init() {

	elgg_register_page_handler('cart', 'cart_page_handler');

	elgg_register_action('cart/add_to_cart', __DIR__ . '/actions/cart/add_to_cart.php', 'public');
	elgg_register_action('cart/empty', __DIR__ . '/actions/cart/empty.php', 'public');
	elgg_register_action('cart/checkout', __DIR__ . '/actions/cart/checkout.php');

	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'cart_set_public_pages');

	elgg_extend_view('css/elgg', 'cart.css');

	elgg_register_menu_item('topbar', [
		'name' => 'cart',
		'href' => 'cart',
		'text' => elgg_view_icon('shop-cart'),
		'data-cart-indicator' => cart()->countLines(),
		'priority' => 800,
	]);
}

/**
 * Handles cart pages
 * /cart
 *
 * @param array $segments URL segments
 * @return boolean
 */
function cart_page_handler($segments) {

	$page = array_shift($segments);

	switch ($page) {
		default :
			echo elgg_view('resources/cart/index', [
				'hash' => elgg_extract(0, $segments),
			]);
			return true;
	}

	return false;
}

/**
 * Add cart pages to WG public pages list
 *
 * @param string $hook   "public_pages"
 * @param string $type   "walled_garden"
 * @param array  $return Public pages
 * @param array  $params Hook params
 * @return array
 */
function cart_set_public_pages($hook, $type, $return, $params) {
	$return[] = "cart/.*";
	return $return;
}

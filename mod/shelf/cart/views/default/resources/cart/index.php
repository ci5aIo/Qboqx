<?php

elgg_push_breadcrumb(elgg_echo('cart:mine'), '/cart');

$title = elgg_echo('cart:mine');
$content = '';

if (cart()->countLines()) {
	elgg_register_menu_item('title', [
		'name' => 'cart:empty',
		'text' => elgg_echo('cart:empty'),
		'href' => '/action/cart/empty',
		'confirm' => true,
		'is_action' => true,
		'link_class' => 'elgg-button elgg-button-delete',
	]);
}

$hashes = (array) elgg_extract('hash', $vars);
if (empty($hashes)) {
	$hashes = cart()->hashes();
}

if (empty($hashes)) {
	$content = elgg_format_element('p', ['class' => 'elgg-no-results'], elgg_echo('cart:is_empty'));
} else {
	foreach ($hashes as $hash) {
		$content .= elgg_view_form('cart/checkout', [
			'class' => 'cart-form'
				], [
			'cart' => cart()->get($hash),
		]);
	}
}

if (elgg_is_xhr()) {
	echo $content;
} else {
	$layout = elgg_view_layout('content', [
		'title' => $title,
		'content' => $content,
		'filter' => false,
		'class' => 'cart-layout',
	]);

	echo elgg_view_page($title, $layout);
}

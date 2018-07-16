<?php
/**
 * Landr custom index page
 * 
 */

$params = array(
	'login' => $login,
);

$body = elgg_view_layout('landr', $params);

echo elgg_view_page('', $body);

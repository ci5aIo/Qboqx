<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

$marketguid = (int) get_input('guid');
$element_type = get_input('element_type');
$item = get_entity(get_input('container_guid'));

$owner = elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

$marketpost = get_entity($marketguid);
$market_img = elgg_view('output/url', array(
		    'text' => elgg_view('market/thumbnail', array(
									'marketguid' => $marketguid,
									'size' => 'master',
						            )),
			));
$content = $market_img;

$body = elgg_view_layout('image', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));
$title = 'GUID: '.$marketguid;
 
echo elgg_view_module('popup', $title, $body);

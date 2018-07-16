<?php
elgg_gatekeeper();
elgg_group_gatekeeper();

$imageguid = (int) get_input('guid');
$element_type = get_input('element_type');

$marketpost = get_entity($marketguid);
$item = get_entity(get_input('container_guid'));
$owner = elgg_get_page_owner_entity();

$title = $marketpost->title;

$content = elgg_view('output/url', array(
		    'text' => elgg_view('market/thumbnail', array(
								'marketguid' => $imageguid,
								'size' => 'master',
						        )),
			));
 
$body = elgg_view_layout('image', ['content' => $content]);
echo elgg_view_module('popup', $title, $body);


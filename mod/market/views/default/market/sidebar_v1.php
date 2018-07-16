<?php
/**
 * Market sidebar
 */

echo elgg_view('page/elements/comments_block', array(
	'subtypes' => 'market',
	'owner_guid' => elgg_get_page_owner_guid(),
));

echo elgg_view('page/components/tagcloud_block', array(
	'subtypes' => array('market', 'item'),
//	'subtypes' => 'market',
	'owner_guid' => elgg_get_page_owner_guid(),
));


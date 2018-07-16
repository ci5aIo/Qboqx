<?php
/**
 * Market sidebar
 * v2
 */

echo elgg_view('page/components/tagcloud_block', array(
	'subtypes' => array('market', 'item'),
	'owner_guid' => elgg_get_page_owner_guid(),
));


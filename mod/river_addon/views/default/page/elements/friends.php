<?php
/**
 * Friends module
 *
 */

$user = elgg_get_logged_in_user_entity();
$count = $user->getFriends(array('count' => TRUE));

$title = elgg_view('output/url', array(
	'href' => "/friends/$user->username",
	'text' => elgg_echo('friends'),
	'is_trusted' => true,
));

$num = (int) elgg_get_plugin_setting('num_friends', 'river_addon');

$options = array(
	'type' => 'user',
	'limit' => $num,
	'offset' => 0,
	'relationship' => 'friend',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'inverse_relationship' => false,
	'full_view' => false,
	'pagination' => false,
	'list_type' => 'gallery',
	'no_results' => elgg_echo('friends:none'),
	'order_by' => 'rand()' 
);
$content = elgg_list_entities_from_relationship($options);

$title .= '<span> (' . $count . ')</span>';
echo elgg_view_module('featured', $title, $content, array('class' => 'elgg-module-friends'));

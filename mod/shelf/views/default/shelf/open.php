<?php
/**
 * Forked from likes button: likes/views/default/likes/button.php
 *
 * @uses $vars['entity']
 */
/*
if (!isset($vars['entity'])) {
	return true;
}*/
$entity = $vars['entity']; 
$entity_guid = $entity->getGUID();                     $display .= '$entity_guid '.$entity_guid.'<br>'; 
$owner_guid  = elgg_get_logged_in_user_guid();
$url = elgg_add_action_tokens_to_url("action/shelf/packable?guid=$entity_guid");
$params = array(
	'href' => $url,
	'is_action' => true,
	'is_trusted' => true,
);

// check to see if the container is already open
if (elgg_is_logged_in()) {
	if (!shelf_container_is_open($entity_guid, $owner_guid)) {
		$params['text'] = 'Open';
		$params['title'] = 'Open this as a container for packing';
	} else {
		$params['text'] = 'Close';
		$params['title'] = 'Packing is complete. Close this container';
	}
}

echo elgg_view('output/url', $params);
//echo $display;
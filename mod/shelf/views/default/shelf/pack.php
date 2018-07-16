<?php
/**
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
$container_guid = (int) shelf_get_open_container($owner_guid);
$container = get_entity($container_guid);
$url = elgg_add_action_tokens_to_url("action/shelf/pack?guid=$entity_guid&container_guid=$container_guid");
$params = array(
	'href' => $url,
	'is_action' => true,
	'is_trusted' => true,
);

// check to see if a container is open and this item is not in the open container
if (elgg_is_logged_in() && $container_guid > 0) {
	if ($entity_guid <> $container_guid && $entity->container_guid <> $container_guid) {
		$params['text'] = 'Pack';
		$params['title'] = "Place this item into $container->title";
		echo elgg_view('output/url', $params);
	} 
}


//echo $display;
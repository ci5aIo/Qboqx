<?php
/**
 * Exerpt from /engine/lib/navigation.php
 * Functions for managing menus and other navigational elements
 */

/**
 * Convenience function for registering a button to title menu
 *
 * The URL must be $handler/$name/$guid where $guid is the guid of the page owner.
 * The label of the button is "$handler:$name" so that must be defined in a
 * language file.
 *
 * This is used primarily to support adding an add content button
 *
 * @param string $handler The handler to use or null to autodetect from context
 * @param string $name    Name of the button
 * @return void
 * @since 1.8.0
 */

namespace market;
 
 function quebx_register_title_button($handler = null, $name = 'add') {
	if (elgg_is_logged_in()) {

		if (!$handler) {
			$handler = elgg_get_context();
		}

		$owner = elgg_get_page_owner_entity();
		if (!$owner) {
			// no owns the page so this is probably an all site list page
			$owner = elgg_get_logged_in_user_entity();
		}
		if ($owner && $owner->canWriteToContainer()) {
			$guid = $owner->getGUID();
			elgg_register_menu_item('title', array(
				'name' => $name,
				'href' => "$handler/$name/$guid",
				'text' => elgg_echo("$handler:$name"),
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}
}

//elgg_register_event_handler('init', 'system', 'elgg_nav_init');

/**
 * Produce the navigation tree
 *
 * @param ElggEntity $container Container entity for the pages
 *
 * @return array
 */
function quebx_get_navigation_tree($container) {
	if (!elgg_instanceof($container)) {
		return;
	}

	$top_pages = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'market',
		'container_guid' => $container->getGUID(),
		'limit' => false,
	));

	/* @var ElggBatch $top_pages Batch of top level pages */

	$tree   = array();
	$depths = array();
	$stack  = array();

	foreach ($top_pages as $page) {
		$tree[] = array(
			'guid' => $page->getGUID(),
			'title' => $page->title,
			'url' => $page->getURL(),
			'depth' => 0,
		);
		$depths[$page->guid] = 0;

		array_push($stack, $page);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$item_guid = $parent->getGUID();
			$documents = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'document',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
			));
			$tasks = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'task',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
				));
			$components = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'component',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => true,
				'limit' => false,
			));
			$accessories = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'accessory',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => true,
				'limit' => false,
			));
			$parent_items = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'accessory',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => false,
				'limit' => false,
			));
			$containers = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'component',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => false,
				'limit' => false,
			));
			$issues = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'issue',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
			));
			$observations = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'observation',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
			));
			
/*
 * Build the tree 
 */	
			if($components){
				$header = $tree[] = array(
						'title'  => 'Components',
						'depth'  => 1,
						'header' => true,
						);
//				array_push($stack, $header);
				foreach ($components as $i) {
					$tree[] = array(
						'guid' => $i->getGUID(),
						'title' => $i->title,
						'url' => $i->getURL(),
						'parent_guid' => $item_guid,
						'header'      => false,
						'depth' => 2,
					    );
					$depths[$i->guid] = 2;
					array_push($stack, $i);
				}
			}
			if($accessories){
				$header = $tree[] = array(
						'title'  => 'Accessories',
						'depth'  => 1,
						'header' => true,
						);
//				array_push($stack, $header);
				foreach ($accessories as $i) {
					$tree[] = array(
						'guid' => $i->getGUID(),
						'title' => $i->title,
						'url' => $i->getURL(),
						'parent_guid' => $item_guid,
						'header'      => false,
						'depth' => 2,
					    );
					$depths[$i->guid] = 2;
					array_push($stack, $i);
				}
			}
 			if($documents){
				$header = $tree[] = array(
						'section'  => 'Documents',
						'depth'  => $depths[$parent->guid] + 1,
						'header' => true,
						);
//				array_push($stack, $header);
				foreach ($documents as $i) {
					$tree[] = array(
						'guid'        => $i->getGUID(),
						'title'       => $i->title,
						'url'         => $i->getURL(),
						'parent_guid' => $item_guid,
						'section'     => 'Documents',
						'header'      => false,
					    'depth'       => $depths[$parent->guid] + 2,
					    );
					$depths[$i->guid] = 2;
					array_push($stack, $i);
				}
			}
			
			if($observations){
				$header = $tree[] = array(
						'section'       => 'Observations',
						'depth'       => $depths[$parent->guid] + 1,
						'header'      => true,
						);
//				array_push($stack, $header);
				foreach ($observations as $i) {
					$tree[] = array(
						'guid'        => $i->getGUID(),
						'title'       => $i->title,
						'url'         => $i->getURL(),
						'parent_guid' => $item_guid,
						'section'     => 'Observations',
						'header'      => false,
					    'depth'       => $depths[$parent->guid] + 2,
					    );
					$depths[$i->guid] = $depths[$parent->guid] + 2;
					array_push($stack, $i);
				}
			}
		}
	}
	return $tree;
}

/**
 * Register the navigation menu
 *
 * @param ElggEntity $container Container entity for the pages
 */
function quebx_register_navigation_tree($container) {
	$pages = quebx_get_navigation_tree($container);
	if ($pages) {
		foreach ($pages as $page) {
			if ($page->header = true){
//				elgg_echo ($page->title);
		     }
			else {
				elgg_register_menu_item('items_nav', array(
					'name' => $page['guid'],
					'text' => $page['title'],
					'href' => $page['url'],
					'parent_name' => $page['parent_guid'],
				));
			}
		}
	}
}


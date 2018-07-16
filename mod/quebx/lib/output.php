<?php

/**
 * Preps an associative array for use in {@link elgg_format_attributes()}.
 *
 * Removes all the junk that {@link elgg_view()} puts into $vars.
 * Maintains backward compatibility with attributes like 'internalname' and 'internalid'
 *
 * @note This function is called automatically by elgg_format_attributes(). No need to
 *       call it yourself before using elgg_format_attributes().
 *
 * @param array $vars The raw $vars array with all it's dirtiness (config, url, etc.)
 *
 * @return array The array, ready to be used in elgg_format_attributes().
 * @access private
 */
/*
 * 2017-10-05 - SAJ - Acquired from elgg v 1.12.16.  Not available in elgg 2.x core
 */
function _elgg_clean_vars(array $vars = array()) {
	unset($vars['config']);
	unset($vars['url']);
	unset($vars['user']);
	
	// backwards compatibility code
	if (isset($vars['internalname'])) {
		if (!isset($vars['__ignoreInternalname'])) {
			$vars['name'] = $vars['internalname'];
		}
		unset($vars['internalname']);
	}
	
	if (isset($vars['internalid'])) {
		if (!isset($vars['__ignoreInternalid'])) {
			$vars['id'] = $vars['internalid'];
		}
		unset($vars['internalid']);
	}
	
	if (isset($vars['__ignoreInternalid'])) {
		unset($vars['__ignoreInternalid']);
	}
	
	if (isset($vars['__ignoreInternalname'])) {
		unset($vars['__ignoreInternalname']);
	}
	
	return $vars;
}
function quebx_list_attributes($options){
	$guid          = elgg_extract('guid'         , $options);
	$attribute     = elgg_extract('attribute'    , $options);
	$cascade       = elgg_extract('cascade'      , $options, false);
	$cascade_depth = elgg_extract('cascade_depth', $options, 0);
	$limit         = 0;
	
	$entity = get_entity($guid);
	$options = ['type'     => $entity->getType(),
			    'joins'    => ['JOIN elgg_objects_entity e2 on e.guid = e2.guid'],
				'wheres'   => ["e.container_guid = $guid"],
	            'order_by' => 'e2.title',
	            'limit'    => $limit,];
	
	$attributes = elgg_get_entities_from_metadata($options);
	return $attributes;
}
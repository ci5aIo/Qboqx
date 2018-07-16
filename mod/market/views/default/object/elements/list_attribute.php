<?php
$guid = elgg_extract('guid', $vars);
$attribute = elgg_extract('attribute', $vars);
$cascade = elgg_extract('cascade', $vars, false);
$cascade_depth = elgg_extract('cascade_depth', $vars, 0);
$limit = 0;

$entity = get_entity($guid);
$options = ['type'     => $entity->getType(),
		    'joins'    => ['JOIN elgg_objects_entity e2 on e.guid = e2.guid'],
			'wheres'   => ["e.container_guid = $item_guid"],
            'order_by' => 'e2.title',
            'limit'    => $limit,];

$attributes = elgg_get_entities_from_metadata($options);

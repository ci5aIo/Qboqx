<?php

$guid= get_input('guid');
$item = get_entity($guid);

$subtype = 'market';
$subtype_id = (int)get_subtype_id('object', $subtype);
$db_prefix = elgg_get_config('dbprefix');

update_data("UPDATE {$db_prefix}entities
             SET subtype = $subtype_id WHERE guid = $guid");

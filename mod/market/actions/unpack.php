<?php

$guid= get_input('guid');
$item = get_entity($guid);
$owner_guid = elgg_get_logged_in_user_guid();
$item['container_guid']=$owner_guid;                 //$display .= '$item[container_guid]: '.$item->container_guid.'<br'; goto eof;

$item->save();
forward($item->getURL());

eof:
register_error($display);
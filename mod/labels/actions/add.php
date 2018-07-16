<?php

/*namespace Quebx\Labels;*/

// get our variables
$user            = elgg_get_logged_in_user_entity();
$item_guid       = get_input('item_guid');
$labels_list     = get_input('rtags');
$existing_labels = get_input('existing_rtag');
$approve         = get_input('approve');
$subscriptions   = get_input('subscriptions');

$item = get_entity($item_guid);

labels_collections_update_II($user, $item, $labels_list, $existing_labels);

system_message(elgg_echo('labels:updated'));

//forward(REFERER);






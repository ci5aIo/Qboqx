<?php
$entity_guid = get_input('guid');           $display .= '$entity_guid: '.$entity_guid.'<br>';
$entity      = get_entity($entity_guid);
$packable    = shelf_container_is_open($entity_guid, elgg_get_logged_in_user_guid());

if ($packable){
    $entity->container_state = 'closed';
}
else {
    // Close any open container
    $open_container_guid             = shelf_get_open_container(elgg_get_logged_in_user_guid());    //$display.= '$open_container_guid: '.$open_container_guid.'<br>'; goto eof;
    $open_container                  = get_entity($open_container_guid);
    if ($open_container > 0){
        $open_container->container_state = 'closed';
        $open_container->save();
    }    
    $entity->container_state = 'open';
}
if ($entity->save()){
    if ($packable){
        system_message("$entity->title closed");
    }
    else {
        system_message("$entity->title open.  Ready to pack.");
    }
}
eof:
//register_error($display);
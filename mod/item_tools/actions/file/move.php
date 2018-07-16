<?php

$file_guid 		= (int) get_input("file_guid", 0);
$folder_guid 	= (int) get_input("folder_guid", 0);

if (!empty($file_guid)) {
	if ($file = get_entity($file_guid)) {
		$container_entity = $file->getContainerEntity();
		
		if (($file->canEdit() || (elgg_instanceof($container_entity, "group") && $container_entity->isMember()))) {
			if (elgg_instanceof($file, "object", "file")) {
				// check if a given guid is a folder
				if (!empty($folder_guid)) {
					if (!($folder = get_entity($folder_guid)) || !elgg_instanceof($folder, "object", item_tools_SUBTYPE)) {
						unset($folder_guid);
					}
				}
				
				// remove old relationships
				remove_entity_relationships($file->getGUID(), item_tools_RELATIONSHIP, true);
				
				if (!empty($folder_guid)) {
					add_entity_relationship($folder_guid, item_tools_RELATIONSHIP, $file_guid);
				}
				
				system_message(elgg_echo("item_tools:action:move:success:file"));
				
			} elseif (elgg_instanceof($file, "object", item_tools_SUBTYPE)) {
				$file->parent_guid = $folder_guid;
				
				system_message(elgg_echo("item_tools:action:move:success:folder"));
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward(REFERER);
<?php

$items     =        elgg_extract("items", $vars, array());
$folder    =        elgg_extract("folder", $vars);
$show_more = (bool) elgg_extract("show_more", $vars, false);
$limit     = (int)  elgg_extract("limit", $vars, item_tools_get_list_length());
$offset    = (int)  elgg_extract("offset", $vars, 0);

// only show the header if offset == 0
$folder_content = "";
if (empty($offset)) {
	$folder_content = elgg_view("item_tools/breadcrumb", array("entity" => $folder));
	
	if (!($sub_folders == item_tools_get_sub_folders($folder))) {
//@EDIT 2016-03-12 - SAJ		
//	if (!($sub_folders = item_tools_get_sub_folders($folder))) {
		$sub_folders = array();
	}
	
	$entities = array_merge($sub_folders, $items);
} else {
	$entities = $items;
}

if (!empty($entities)) {
	$params = array(
		"full_view" => false,
		"pagination" => false,
		"class"      => "item-tools-item",
	);
	
	elgg_push_context("item_tools_selector");
	
	$files_content = elgg_view_entity_list($entities, $params);
	
	elgg_pop_context();
}

if (empty($files_content)) {
	$files_content = elgg_echo("item_tools:list:files:none");
} else {
	if ($show_more) {
		$files_content .= "<div class='center' id='item-tools-show-more-wrapper'>";
		$files_content .= elgg_view("input/button", array(
			"value" => elgg_echo("item_tools:show_more"),
			"class" => "elgg-button-action",
			"id" => "item-tools-show-more-files",
		));
		$files_content .= elgg_view("input/hidden", array("name" => "offset", "value" => ($limit + $offset)));
		if (!empty($folder)) {
			$files_content .= elgg_view("input/hidden", array("name" => "folder_guid", "value" => $folder->getGUID()));
		} else {
			$files_content .= elgg_view("input/hidden", array("name" => "folder_guid", "value" => "0"));
		}
		$files_content .= "</div>";
	}
	
	// only show selectors on the first load
	if (empty($offset)) {
		$files_content .= "<div class='clearfix'>";
		
		if (elgg_get_page_owner_entity()->canEdit()) {
			$files_content .= '<a id="item_tools_action_bulk_delete" href="javascript:void(0);">' . elgg_echo("item_tools:list:delete_selected") . '</a> | ';
		}
		
		$files_content .= "<a id='item_tools_action_bulk_download' href='javascript:void(0);'>" . elgg_echo("item_tools:list:download_selected") . "</a>
				           <a id='item_tools_select_all' class='float-alt' href='javascript:void(0);'>
								<span>" . elgg_echo("item_tools:list:select_all") . "</span>
								<span class='hidden'>" . elgg_echo("item_tools:list:deselect_all") . "</span>
							</a>
						</div>";
	}
}

// show the listing
echo "<div id='item_tools_list_items'>
			<div id='item_tools_list_items_overlay'></div>
			$folder_content
			$files_content".
            elgg_view("graphics/ajax_loader")."
      </div>";

$page_owner = elgg_get_page_owner_entity();

if ($page_owner->canEdit() || (elgg_instanceof($page_owner, "group") && $page_owner->isMember())) {
echo 'draggable/droppable';
?>
<script type="text/javascript">

	$(function(){
		
		elgg.item_tools.initialize_file_draggable();
		elgg.item_tools.initialize_folder_droppable();
		
	});

</script>
<?php
}

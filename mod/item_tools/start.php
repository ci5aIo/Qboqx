<?php

define("item_tools_SUBTYPE", 		"folder");
//define("item_tools_SUBTYPE", 		"place");
//define("item_tools_item_SUBTYPE", 	"item");
define("item_tools_item_SUBTYPE", 	"market");
//define("item_tools_item_SUBTYPE", 	"file");
define("item_tools_RELATIONSHIP", 	"location");
//define("item_tools_RELATIONSHIP", 	"folder_of");
define("item_tools_BASEURL", 		elgg_get_site_url() . "item_tools/");

require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/events.php");
require_once(dirname(__FILE__) . "/lib/hooks.php");
require_once(dirname(__FILE__) . "/lib/page_handlers.php");

// register default Elgg events
elgg_register_event_handler("init", "system", "item_tools_init");
elgg_register_event_handler("pagesetup", "system", "item_tools_pagesetup");

/**
 * Called during system initialization
 *
 * @return void
 */
function item_tools_init() {
	// extend CSS
	elgg_extend_view("css/elgg", "css/item_tools/site");
	
	// extend js
	elgg_extend_view("js/elgg", "js/item_tools/site");
	
	// register JS libraries
	$vendors = elgg_get_site_url() . "mod/item_tools/vendors/";
	elgg_register_js("jquery.uploadify", $vendors . "uploadify/jquery.uploadify-3.1.min.js");
	elgg_register_simplecache_view("css/uploadify/uploadify");
	elgg_register_css("jquery.uploadify", elgg_get_simplecache_url("css", "uploadify/uploadify"));
	
	elgg_register_js("jquery.tree", $vendors . "jstree/jquery.tree.min.js");
	elgg_register_css("jquery.tree", $vendors . "jstree/themes/default/style.css");
	
	elgg_register_js("jquery.hashchange", $vendors . "hashchange/jquery.hashchange.js");
	
	// extend views
	elgg_extend_view("groups/edit", "item_tools/group_settings");
	
	// register page handler for nice URL's
	elgg_register_page_handler("item_tools", "item_tools_page_handler");
	
	// make our own URLs for folder icons
	elgg_register_plugin_hook_handler("entity:icon:url", "object", "item_tools_folder_icon_hook");
	
	// register group option to allow management of file tree structure
	add_group_tool_option("item_tools_structure_management", elgg_echo("item_tools:group_tool_option:structure_management"));
	
	// register widgets
	// add folder widget
	// need to keep file_tree for the widget name to be compatible with previous filetree plugin users
	elgg_register_widget_type("file_tree", elgg_echo("widgets:file_tree:title"), elgg_echo("widgets:file_tree:description"), array("dashboard", "profile", "groups"), true);
	
	// group files
	elgg_register_widget_type("group_files", elgg_echo("file:group"), elgg_echo("widgets:group_files:description"), array("groups"));
	
	// index files
	elgg_register_widget_type("index_file", elgg_echo("file"), elgg_echo("widgets:index_file:description"), array("index"), true);
	
	// register events
	elgg_register_event_handler("create", "object", "item_tools_object_handler");
	elgg_register_event_handler("update", "object", "item_tools_object_handler");
	elgg_register_event_handler("delete", "object", "item_tools_object_handler_delete");
	
	// register hooks
	elgg_register_plugin_hook_handler("register", "menu:entity", "item_tools_entity_menu_hook");
	elgg_register_plugin_hook_handler("permissions_check:metadata", "object", "item_tools_can_edit_metadata_hook");
// 	elgg_register_plugin_hook_handler("access:collections:write", "all", "item_tools_write_acl_plugin_hook", 550);
//	elgg_register_plugin_hook_handler("route", "market", "item_tools_file_route_hook");
	elgg_register_plugin_hook_handler("route", "item", "item_tools_file_route_hook");
//  2016-03-07 - SAJ
	elgg_register_plugin_hook_handler("route", "file", "item_tools_file_route_hook");
	elgg_register_plugin_hook_handler("widget_url", "widget_manager", "item_tools_widget_url_hook");
	elgg_register_plugin_hook_handler("entity:url", "object", "item_tools_folder_url_handler");
	
	elgg_register_plugin_hook_handler("register", "menu:item_tools_folder_breadcrumb", "item_tools_folder_breadcrumb_hook");
	elgg_register_plugin_hook_handler("register", "menu:item_tools_folder_sidebar_tree", "item_tools_folder_sidebar_tree_hook");
	
	// register actions
	elgg_register_action("item_tools/groups/save_sort", dirname(__FILE__) . "/actions/groups/save_sort.php");
	elgg_register_action("item_tools/folder/edit", dirname(__FILE__) . "/actions/folder/edit.php");
	elgg_register_action("item_tools/folder/delete", dirname(__FILE__) . "/actions/folder/delete.php");
	elgg_register_action("item_tools/folder/reorder", dirname(__FILE__) . "/actions/folder/reorder.php");
	elgg_register_action("item_tools/upload/zip", dirname(__FILE__) . "/actions/upload/zip.php");
	elgg_register_action("item_tools/folder/delete", dirname(__FILE__) . "/actions/folder/delete.php");
	elgg_register_action("item_tools/file/hide", dirname(__FILE__) . "/actions/file/hide.php");
	
	elgg_register_action("file/move", dirname(__FILE__) . "/actions/file/move.php");
	elgg_register_action("file/bulk_delete", dirname(__FILE__) . "/actions/file/bulk_delete.php");
	
}

/**
 * Called during pagesetup
 *
 * @return void
 */
function item_tools_pagesetup() {
	$page_owner = elgg_get_page_owner_entity();
	
	if (empty($page_owner) || !elgg_instanceof($page_owner, "group")) {
		return;
	}
/*	
	// check if the group hase files enabled
	if ($page_owner->files_enable == "no") {
		// no, so remove the widgets
		elgg_unregister_widget_type("file_tree");
		elgg_unregister_widget_type("group_files");
	}
*/	
}
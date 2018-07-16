<?php
/**
 * Landr initialize
 * 
 */

elgg_register_event_handler('init', 'system', 'landr_init');

function landr_init() {

	elgg_register_page_handler('', 'landr');
	elgg_register_ajax_view('landr/upload');
	
}

function landr() {
	if (!include_once(dirname(__FILE__) . "/index.php")) {
		return false;
	}

	return true;
}
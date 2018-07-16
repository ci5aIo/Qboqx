<?php
/**
 * Exerpt from /engine/lib/navigation.php
 * Functions for managing menus and other navigational elements
 */

/**
 * Convenience function for registering a button to title menu
 *
 * The URL must be $handler/$name/$guid where $guid is the guid of the page owner.
 * The label of the button is "$handler:$name" so that must be defined in a
 * language file.
 *
 * This is used primarily to support adding an add content button
 *
 * @param string $handler The handler to use or null to autodetect from context
 * @param string $name    Name of the button
 * @return void
 * @since 1.8.0
 */
function jot_register_title_button($handler = null, $name = 'add') {
	if (elgg_is_logged_in()) {

		if (!$handler) {
			$handler = elgg_get_context();
		}

		$owner = elgg_get_page_owner_entity();
		if (!$owner) {
			// no owns the page so this is probably an all site list page
			$owner = elgg_get_logged_in_user_entity();
		}
		if ($owner && $owner->canWriteToContainer()) {
			$guid = $owner->getGUID();
			elgg_register_menu_item('title', array(
				'name' => $name,
				'href' => "$handler/$name/$guid",
				'text' => elgg_echo("$handler:$name"),
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}
}

//elgg_register_event_handler('init', 'system', 'elgg_nav_init');

function jot_issue_tabs($vars, $selected = 'summary') {
	$selected = $vars['this_section'];
	$title     = $vars['title'];
	$this_guid = $vars['guid'];

	//set the url
	$url = elgg_get_site_url() . "jot/issue";
	
	$sections = array();
	$sections[] = 'Summary';
	$sections[] = 'Details';
	$sections[] = 'Documentation';
	$sections[] = 'Ownership';
	$sections[] = 'Discussion';
	$sections[] = 'Gallery';
	$sections[] = 'Dependencies';
	$sections[] = 'Reports';
	
	$tabs = array();
	
	foreach ($sections as $section) {
		$tabs[] = array(
			'title' => elgg_echo("$section"),
			'url' => "$url/$this_guid/$section",
			'selected' => $section == $selected,
		);
	}
	return $tabs;
}

function jot_observation_tabs($vars, $selected = 'Summary') {
	$selected = $vars['this_section'];
//	$title     = $vars['title'];
	$this_guid = $vars['guid'];

	//set the url
	$url = elgg_get_site_url() . "jot/observation";
	
	$sections = array();
	$sections[] = 'Summary';
	$sections[] = 'Details';
	$sections[] = 'Resolution';
	$sections[] = 'Ownership';
	$sections[] = 'Discussion';
	$sections[] = 'Gallery';
	$sections[] = 'Reports';
	
	$tabs = array();
	
	foreach ($sections as $section) {
		$tabs[] = array(
			'title' => elgg_echo("$section"),
			'url' => "$url/$this_guid/$section",
			'selected' => $section == $selected,
		);
	}
	return $tabs;
}

function jot_insight_tabs($vars, $selected = 'Summary') {
	$selected = $vars['this_section'];
//	$title     = $vars['title'];
	$this_guid = $vars['guid'];

	//set the url
	$url = elgg_get_site_url() . "jot/insight";
	
	$sections = array();
	$sections[] = 'Summary';
	$sections[] = 'Details';
	$sections[] = 'Discussion';
	$sections[] = 'Gallery';
	$sections[] = 'Reports';
	
	$tabs = array();
	
	foreach ($sections as $section) {
		$tabs[] = array(
			'title' => elgg_echo("$section"),
			'url' => "$url/$this_guid/$section",
			'selected' => $section == $selected,
		);
	}
	return $tabs;
}

function jot_cause_tabs($vars, $selected = 'Summary') {
	$selected = $vars['this_section'];
//	$title     = $vars['title'];
	$this_guid = $vars['guid'];

	//set the url
	$url = elgg_get_site_url() . "jot/cause";
	
	$sections = array();
	$sections[] = 'Summary';
	$sections[] = 'Details';
	$sections[] = 'Discussion';
	$sections[] = 'Gallery';
	$sections[] = 'Reports';
	
	$tabs = array();
	
	foreach ($sections as $section) {
		$tabs[] = array(
			'title' => elgg_echo("$section"),
			'url' => "$url/$this_guid/$section",
			'selected' => $section == $selected,
		);
	}
	return $tabs;
}

function jot_tabs($vars, $selected = 'summary') {
	$selected = $vars['this_section'];
	$this_guid = $vars['guid'];
	$aspect    = $vars['aspect'];
	$sections  = array();

	Switch ($aspect){
		case 'issue':
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Documentation';
			$sections[] = 'Ownership';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Dependencies';
			$sections[] = 'Reports';
			break;
		case 'observation':
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Ownership';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Timeline';
			break;
		case 'insight':
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Reports';
			break;
		case 'cause':
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Reports';
			break;
		case 'effort':
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Test';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Reports';
			break;
		case 'experience':              // No tabs for an experience
		    break;
		default:
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Ownership';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Timeline';
			break;
	}

	$tabs = array();
	
	foreach ($sections as $section) {
		$tabs[] = array(
			'title' => elgg_echo("$section"),
			'url' => "jot/view/$this_guid/$section",
//			'url' => "jot/$aspect/$this_guid/$section",
			'selected' => $section == $selected,
		);
	}
	return $tabs;
}

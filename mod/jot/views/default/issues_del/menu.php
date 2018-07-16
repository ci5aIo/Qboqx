<?php
/**
 * Issue sections pages menu
 *
 * @uses $vars['section']
 */

$selected  = $vars['this_section'];
$title     = $vars['title'];
$this_guid = $vars['guid'];

if (empty($selected)) {
	 $selected = 'summary';
}

$tabs = jot_issue_tabs($vars, $selected);
echo elgg_view('navigation/tabs', array('tabs' => $tabs));




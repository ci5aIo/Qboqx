<?php
/**
 * Section views menu
 *
 * @uses $vars['section']
 */

$selected  = $vars['this_section'];

if (empty($selected)) {$selected = 'summary';}
$tabs = place_tabs($vars, $selected);

echo $selected;
echo elgg_view('navigation/tabs', array('tabs' => $tabs));
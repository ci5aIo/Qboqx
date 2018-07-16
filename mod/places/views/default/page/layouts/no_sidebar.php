<?php
/**
 * Layout for main column with no sidebar
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']   Optional title for main content area
 * @uses $vars['content'] Content HTML for the main column
 * @uses $vars['sidebar'] Optional content that is added to the sidebar
 * @uses $vars['nav']     Optional override of the page nav (default: breadcrumbs)
 * @uses $vars['header']  Optional override for the header
 * @uses $vars['footer']  Optional footer
 * @uses $vars['class']   Additional class to apply to layout
 */
/*
$class = 'elgg-layout elgg-layout-no-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
*/
		echo $vars['content'];
?>

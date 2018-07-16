<?php
/**
 * Show the unlimited list of related items
 *
 */

$place_guid    = $place[1];
$place         = get_entity($place_guid);

$owner = elgg_get_page_owner_entity();
$spaces = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'place',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $place_guid,
				'limit' => false,
			));

elgg_gatekeeper();
elgg_group_gatekeeper();

$title = elgg_echo($place->title);

$content = "<table width = 100%>";
foreach ($spaces as $i) {
	$content .= '<tr class="highlight">
		      <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "places/view/$i->guid/$i->title/Details")).'</td>
	      </tr>';
}
$content .= "</table>";

$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));
$title = '';
$header = '';
$show_inner = false;

/**
 * Elgg module element
 *
 * @uses $vars['type']         The type of module (main, info, popup, aside, etc.)
 *                              - defined as classes in views\default\css\elements\modules.php
 * @uses $vars['title']        Optional title text (do not pass header with this option)
 * @uses $vars['header']       Optional HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       Optional HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */
$module_type = 'popup';
 
echo elgg_view_module($module_type, $title, $body);

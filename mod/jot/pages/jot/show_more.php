<?php
/**
 * Show the unlimited list of related items
 *
 */

$item_guid    = $page[2];
$relationship = $page[1];
/*
$item_guid    = (int) get_input('item');
$relationship =       get_input('relationship');
*/
$owner = elgg_get_page_owner_entity();

$related_items = elgg_get_entities_from_relationship(array(
	'type'                 => 'object',
	'relationship'         => $relationship,
	'relationship_guid'    => $item_guid,
    'inverse_relationship' => true,
	'limit'                => false,
));

elgg_gatekeeper();
elgg_group_gatekeeper();

$title = elgg_echo($relationship);

$content = "<table width = 100%>";
foreach ($related_items as $i) {
	$content .= '<tr class="highlight">
		      <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid/Details")).'</td>
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

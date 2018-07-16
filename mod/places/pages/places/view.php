<?php
/**
 * View a single place
 *
 * @package Elggplaces
 */

$guid        = get_input('guid');
$title       = get_input('title');
if (isset($place[3])) {
    $section    = $place[3];
}
else {
	$section = 'Summary';
}

elgg_entity_gatekeeper($guid, 'object');

$place       = get_entity($guid);
$subtype     = $place->getSubtype();
if (!places_is_place($place)) {
	forward('', '404');
}

elgg_set_page_owner_guid($place->getContainerGUID());

elgg_group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
	forward(REFERER);
}

$tabs = elgg_view('places/menu', array('guid' =>$guid, 'this_section' => $section)); // path: mod\places\views\default\places\menu.php

$title = $place->title;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "places/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "places/owner/$container->username");
}
places_prepare_parent_breadcrumbs($place);
elgg_push_breadcrumb($title);

    $items_on_shelf = shelf_count_items();
	$view_menu[12] = ElggMenuItem::factory(array('name'               => '000shelf',
	                                             'text'               => "Shelf ($items_on_shelf)", 
        								         'link_class'              => 'elgg-lightbox',
        								         'data-colorbox-opts' => '{"width":500, "height":525}',
        					                     'href'               => "shelf/show/$guid",
	));
	elgg_register_menu_item('page', $view_menu[12]);

$view = "object/$subtype";
$content = elgg_view($view, array('entity'=>$place, 
		                          'full_view'=>true, 
		                          'this_section'=>$section,
		                    ));
//$content = elgg_view_entity($place, array('full_view' => true, 'this_section'=>$section));
$content .= elgg_view_comments($place);
//$content .= '$guid: '.$guid;

// can add interior space if can edit this place and write to container (such as a group)
if ($place->canEdit() && $container->canWriteToContainer(0, 'object', 'place')) {
	$url = "places/add/$place->guid";
	elgg_register_menu_item('title', array(
			'name' => 'subplace',
			'href' => $url,
			'text' => elgg_echo('places:newchild'),
			'link_class' => 'elgg-button elgg-button-action',
	));
}

$body = elgg_view_layout('content', array(
	'filter'  => $tabs,
	'content' => $content,
	'title'   => $title,
	'sidebar' => elgg_view('places/sidebar/navigation'),
));

echo elgg_view_page($title, $body);

<?php
$issue = $vars['entity'];
$section = $vars['this_section'];
$issue_guid = $issue->guid;

/**/
$galleries = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'gallery',
	'relationship_guid' => $issue_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

foreach ($galleries as $gallery){
	hypeJunction\Gallery\register_entity_title_buttons($gallery);
	$content = elgg_view_entity($gallery, array(
									'full_view' => true,
									'list_type' => 'list'
			                       ),
						   $bypass = false,
						   $debug  = true);
    
    $layout = elgg_view_layout('one_column_no_breadcrumb_footer', array(
	'content' => $content,
	'filter' => false
		));

	echo $layout;

/*	echo elgg_view_page($title, $layout, 'default', array(
		'entity' => $entity
	));
*/		
    }

if (!$galleries){
	$title = $issue->title;
	echo elgg_view('output/url', array(
			'text' => '[create new gallery]', 
			"class" => 'elgg-button-submit-element',
			'href' => elgg_add_action_tokens_to_url("action/edit/object/hjalbum?owner_guid=$issue->owner_guid&container_guid=$issue_guid&title=$title")));
}

<?php
$entity = $vars['entity'];
$item_guid = $entity->guid;

/**/
$galleries = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'gallery',
	'relationship_guid' => $item_guid,
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
	$title = $entity->title;
	echo elgg_view('output/url', array(
			'text' => '[create new gallery]', 
			"class" => 'elgg-button-submit-element',
			'href' => elgg_add_action_tokens_to_url("action/edit/object/hjalbum?owner_guid=$entity->owner_guid&container_guid=$item_guid&title=$title")));
}
echo elgg_view('input/dropzone', array('name' => 'upload_guids[]',
		  'accept' => 'image/*',
		  'max' => 25,
		  'multiple' => false,
		  'item_guid' => $item_guid,
		));

$guid = 1081;
$entity = get_entity($guid);
$limit = get_input('limit', 20);
$offset = get_input("offset-images-$entity->guid", 0);


	$options = array(
		'types' => 'object',
		'subtypes' => 'hjalbumimage',
		'container_guids' => $entity->guid,
		'limit' => $limit,
		'offset' => $offset,
		'count' => true,
		'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer'),
		'list_type' => get_input('list_type', 'list'),
//		'list_type' => get_input('list_type', 'gallery'),
        'list_type_toggle' => true,
		'gallery_class' => 'gallery-photostream',
		'full_view' => false,
		'pagination' => true,
		'offset_key' => "offset-images-$entity->guid",
			
		'relationship' => 'image_of',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => true,
	);

	$body = elgg_list_entities_from_relationship($options);
	$body .= elgg_view('gallery_field/images_list', array(
		'entity' => $entity,
	));	

echo $body;
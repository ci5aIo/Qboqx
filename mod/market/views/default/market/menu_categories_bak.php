<?php
/**
 * Market Categories pages menu
 *
 * @uses $vars['type']
 */

$type = $vars['category'];

if (empty($type)) {
	 $type = 'all';
}

//set the url
$url = elgg_get_site_url() . "categories/view/";
//$url = elgg_get_site_url() . "market/category/";

/******************************/
//@EDIT - 2016-09-23 - SAJ - Incorporating site-wide categories into navigation tabs
$container = elgg_get_site_entity();

$limit_setting = elgg_get_plugin_setting('num_display', PLUGIN_ID);
$limit = (int) get_input('limit', (!is_null($limit_setting)) ? $limit_setting : 0);
$offset = (int) get_input('offset', 0);
$count = hypeJunction\Categories\get_subcategories($container->guid, array(
	'count' => true,
		));

$categories = hypeJunction\Categories\get_subcategories($container->guid, array(
	'limit' => $limit,
	'offset' => $offset,
		));

    $items = elgg_get_entities(array('type'=>'object',
                                     'subtype'=> 'market',
                                     'limit' => 0,
                             ));
    $categorized_items = array();
    foreach($items as $item){
        $item_categories= hypeJunction\Categories\get_entity_categories($item->guid);
        $item_category_guids[] = $item_categories[0]['guid'];
    }
    foreach($item_category_guids as $key=>$value){
        if ($value == ''){
            unset($item_category_guids[$key]);
        }
    }
    $occupied_categories = array_unique($item_category_guids);
    foreach($occupied_categories as $key => $leaf){                      $display .= '$occupied_categories['.$key.'] = '.$leaf.'<br>';  
        $tree[] = hypeJunction\Categories\get_hierarchy($leaf, true, true);
    }
    foreach($tree as $branch){
        $root[] = $branch[0];
    }
    $roots = array_unique($root);
    
    $dbprefix = elgg_get_config('dbprefix');
    $options = array(
      'guids'    => $roots,
      'joins' => array("INNER JOIN {$dbprefix}objects_entity o ON (e.guid = o.guid)"),
      'order_by' => 'o.title'
    );
  
    $categories  = elgg_get_entities_from_metadata($options);
    
$tabs = array();
foreach ($categories as $category) {
	$tabs[] = array(
		'title' => $category->title,
		'url' => $url . $category->guid,
		'selected' => $category->title == $type,
	);
}

/******************************/
/*
$cats = elgg_get_plugin_setting('market_categories', 'market');
$categories = string_to_tag_array($cats);
array_unshift($categories, "all");
*/
/*
$tabs = array();
foreach ($categories as $category) {
	$tabs[] = array(
		'title' => elgg_echo("market:category:{$category}"),
		'url' => $url . $category,
		'selected' => $category == $type,
	);
}*/

echo elgg_view('navigation/tabs', array('tabs' => $tabs));




<?php
/**
 * Market Categories pages sidebar menu
 *
 * @uses $vars['type']
 */

elgg_load_library('elgg:quebx:navigation');
$selected_category = elgg_extract('selected_category', $vars);
$page_owner     = elgg_get_page_owner_entity();
$selected_owner = elgg_extract('selected_owner', $vars, $page_owner);
$list_type = get_input('list_type', 'list'); 

/******************************/
//@EDIT - 2016-09-23 - SAJ - Incorporating site-wide categories into navigation tabs

//$categories = quebx_get_occupied_subcategories_v2(elgg_get_site_entity());

/*    
echo '<div id="cssmenu">';
foreach ($categories as $category) {
    echo elgg_view_menu('categories', array(
    	'entity' => $category,
    	'sort_by' => 'priority',
        'show_toggle' => false,
        'show_section_headers' => false,
        'collapse' => false,
        'class' =>'',
    ));
}
echo '</div>';*/
echo 'Categories';
echo elgg_view('navigation/flyout', 
                array('root'       => elgg_get_site_entity(), 
                      'menu'       => 'categories',
                      'name'       => 'things',
                      'orientation'=> 'vertical',
                      'selected'   => $selected_category,
                      'cascade'    => true,
                      'show_empty' => false,
                      'show_tally' => true,
                      'owner'      => $selected_owner, 
                      'list_type' => $list_type,
                      )
              );

echo '<hr>Galleries';

echo elgg_view('navigation/flyout', 
                array('root'       => elgg_get_site_entity(), 
                      'menu'       => 'gallery',
                      'name'       => 'things',
                      'orientation'=> 'vertical',
                      'selected'   => $selected_category,
                      'cascade'    => true,
                      'show_empty' => false,
                      'show_tally' => true,
                      'owner'      => $selected_owner, 
                      'list_type' => $list_type,
                      )
              );


/*function quebx_get_occupied_subcategories($root) {*************/
/*
$root = get_entity(1);
if (!empty($root)){
        $container = $root;
    }
    else {
        $container = elgg_get_site_entity();
    }

    $categories = hypeJunction\Categories\get_subcategories($container->guid);
    foreach ($categories as $subcategory){
        $subcategories[] = array('guid'  => (int) $subcategory->guid,
                                 'items' => (int) hypeJunction\Categories\get_filed_items($subcategory->guid, 
                                                                                    array('count' => true,
                                                                                          'container_guids' => (HYPECATEGORIES_GROUP_CATEGORIES && elgg_instanceof($page_owner, 'group')) ? $page_owner->guid : null
                                                                                         ))
        );
    }
    foreach ($subcategories as $key=>$subcategory){
        if ($subcategory['items'] == 0){
            unset($subcategories[$key]);
        }
    }
    goto here;
    echo elgg_dump($subcategories); goto eof;

    // get all items
    $items = elgg_get_entities(array('type'=>'object',
                                     'subtype'=> 'market',
                                     'limit' => 0,
                             ));
    // determine the unique categories that the items occupy
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
    foreach($occupied_categories as $key => $leaf){  
        $tree[] = hypeJunction\Categories\get_hierarchy($leaf, true, true);
    }
    foreach($tree as $key1=>$branch){                    //$display .= '$branch'.$branch.'<br>';
        foreach ($branch as $key2=>$guid){               //$display .='$branch['.$key2.']: '.$branch[$key2].'<br>';
        }
        unset($key3);
        $key3 = in_array($container->guid, $branch);         
        if ($key3){                                                                        
            $branches[] = $branch;                          $display .='$branch[0]: '.$branch[0].'<br>';
        }
//        $roots[] = $branch[0];
    }                                                       $display .= 'branches: '.count($branches).'<br>';
    $roots = array_unique($roots);                       $display .= 'roots: '.count($roots).'<br>';    
    $dbprefix = elgg_get_config('dbprefix');
    $options = array(
      'guids'    => $roots,
      'joins' => array("INNER JOIN {$dbprefix}objects_entity o ON (e.guid = o.guid)"),
      'order_by' => 'o.title'
    );

    $categories  = elgg_get_entities_from_metadata($options);
echo $display;
foreach ($categories as $category) {
    echo $category['title'];
}
here:
//echo '<div id="cssmenu">';
echo '<ul>';
echo '<li>'.$root->title.'</li>';
echo '<ul>';
foreach ($subcategories as $key=>$menu_item){
    echo '<li>'.get_entity($menu_item['guid'])->title.'('.$menu_item['items'].')</li>';
}
echo '</ul></ul>';
echo '<hr>';
$subcategories = quebx_get_occupied_subcategories_v2($root);
foreach ($subcategories as $subcategory){
    unset($items);
    $items = (int) hypeJunction\Categories\get_filed_items($subcategory['guid'], 
                    array('count' => true,
                          'container_guids' => (HYPECATEGORIES_GROUP_CATEGORIES && elgg_instanceof($page_owner, 'group')) ? $page_owner->guid : null
                         ));
    echo get_entity($subcategory['guid'])->title.'('.$items.')<br>';
}*/

//echo '</div>';
/*echo '<div id="cssmenu">';
foreach ($categories as $category) {
    echo elgg_view_menu('categories', array(
    	'entity' => $category,
    	'sort_by' => 'priority',
        'show_toggle' => false,
        'show_section_headers' => false,
        'collapse' => false,
        'class' =>'',
    ));
}
echo '</div>';
  */  
/*
$tabs = array();
foreach ($categories as $category) {
    $item_count = NULL;
    $item_count = hypeJunction\Categories\get_filed_items($category->guid, array(
    	'count' => true,
    	'container_guids' => (HYPECATEGORIES_GROUP_CATEGORIES && elgg_instanceof($page_owner, 'group')) ? $page_owner->guid : null
    ));
    if ($item_count){$item_count_label = " ($item_count)";}

	$tabs[] = array(
		'title' => $category->title.$item_count_label,
		'url' => $url . $category->guid,
		'selected' => $category->title == $type,
	);
}

echo '<div id="cssmenu">'.elgg_view('navigation/tabs_flyout', array('tabs' => $tabs, 'type'=>'vertical')).'</div>';
//echo elgg_view('navigation/tabs', array('tabs' => $tabs, 'type'=>'vertical'));
*/
  eof:
<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Get the item
$item_guid   = (int) get_input('item');
$title       = get_input('title');
$section     = get_input('compartment');

$item        = get_entity($item_guid);
$guid        = $item->guid;
$owner_guid  = $item->owner_guid;
$parent_guid = $item->container_guid;
$parent_item = get_entity($parent_guid);
$owner_item  = get_entity($owner_guid);
//@TODO:  Create a view system for 'item' subtypes.

// Pull originating filter
	$filter = new ElggFile;
	$filter->owner_guid = elgg_get_site_entity()->guid;
	$filter->setFilename("/quebx/"._elgg_services()->session->getId()."_filter.json");
	if ($filter->exists()) {
		$filter->open('read');
		$json = $filter->grabFile();
		$filter->close();
	}

	$this_filter = json_decode($json, true);

// Determine where this item is in the $list_navigation
// Pull navigation list
	$file = new ElggFile;
	$file->owner_guid = elgg_get_site_entity()->guid;
	$file->setFilename("/quebx/"._elgg_services()->session->getId()."_nav.json");
	if ($file->exists()) {
		$file->open('read');
		$json = $file->grabFile();
		$file->close();
	}

	$list_navigation = json_decode($json, true);
	if (!is_array($list_navigation)){$list_navigation = array($list_navigation);}
$this_key = array_search($item_guid, $list_navigation);
end($list_navigation);
$last_key = key($list_navigation);
if ($this_key == 0 && $this_key != $last_key){ // This is the first item in the list and there are others
    $previous_guid = $list_navigation[$last_key];
    $next_guid     = $list_navigation[1];
}
elseif ($this_key == 0 && $this_key == $last_key){ // This is the only item in the list
    $previous_guid = NULL;
    $next_guid     = NULL;
}
elseif ($this_key != 0 && $this_key == $last_key){ // This is the last item in the list and there are others
    $previous_guid = $list_navigation[$this_key - 1];
    $next_guid     = $list_navigation[0];
}
else {
    $previous_guid = $list_navigation[$this_key-1];
    $next_guid     = $list_navigation[$this_key+1];
}
$this_position = $this_key+1;
$last_position = $last_key+1;
$previous_item = get_entity($previous_guid);
$next_item     = get_entity($next_guid);
if ($last_position > 1){
    $this_locator = elgg_view('output/url', array('text' => "[$this_position of $last_position]", 'href'=>$this_filter));
}
if ($previous_item && $last_position > 1){
    $previous = '<span title="'.$previous_item->title.'">'.elgg_view('output/url', array('text' => '<< Previous', 'href'=>$previous_item->getURL().'/'.$section)).'</span>';
}
if ($next_item && $last_position > 1){
    $next     = '<span title="'.$next_item->title.'">'.elgg_view('output/url',     array('text' => 'Next >>',     'href'=>$next_item->getURL().'/'.$section)).    '</span>';
}

elgg_set_context('view_item');

if ($item && empty($section) && elgg_entity_exists($item_guid)) {
    if ($item->canEdit()){
        $section = 'Summary';
    }
    else {
        $section = 'Profile';
    }
}
// view path: mod\quebx\views\default\items\menu.php
/****** Tabs *******/
$tabs = elgg_view('items/menu', ['guid' =>$item_guid, 'this_section' => $section]);
/****** Header *******/
$header .= "$previous $this_locator $next";
/*
$header .= "<div aspect='jot' panel='experience' id='add_experience_panel' class='elgg-head' style='display:none'>";
    $body_vars = array('element_type'   => 'jot',
                       'aspect'         => 'experience',
                       'selected'       => 'Things',
                       'entity'         => $item,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => $guid,
                       'action'         => 'add',
                       'panel'          => 'experiences',
                       'title'          => 'Add an experience',
                       'do'             => 'add_item');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";
$header .= "<div aspect='jot' panel='receipt' id='add_receipt_panel' class='elgg-head' style='display:none'>";
    $body_vars = array('element_type'   => 'transfer',
                       'aspect'         => 'receipt',
                       'presentation'   => 'compact',
                       'selected'       => 'Things',
                       'entity'         => $item,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => $guid,
                       'action'         => 'add',
                       'panel'          => 'receipts',
                       'title'          => 'Add a receipt',
                       'do'             => 'add_receipt');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";
$header .= "<div aspect='jot' panel='supply' class='elgg-head' style='display:none'>";
    $body_vars = array('element_type'   => 'jot',
                       'aspect'         => 'supply',
                       'presentation'   => 'compact',
                       'entity'         => $item,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => $guid,
                       'action'         => 'add',
                       'panel'          => 'supplies',
                       'title'          => 'Add supplies',
                       'do'             => 'add_supplies');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";
$header .= "<div aspect='jot' panel='part' class='elgg-head' style='display:none'>";
    $body_vars = array('element_type'   => 'jot',
                       'aspect'         => 'part',
                       'presentation'   => 'compact',
                       'entity'         => $entity,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => $guid,
                       'action'         => 'add',
                       'panel'          => 'parts',
                       'title'          => 'Add parts',
                       'do'             => 'add_parts');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";
$header .= "<div aspect='jot' panel='support' class='elgg-head' style='display:none'>";
    $body_vars = array('element_type'   => 'jot',
                       'aspect'         => 'support',
                       'presentation'   => 'compact',
                       'entity'         => $item,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => $guid,
                       'action'         => 'add',
                       'panel'          => 'support_groups',
                       'title'          => 'Add support groups',
                       'do'             => 'add_support_group');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";
$header .= "<div aspect='jot' panel='component' class='elgg-head' style='display:none'>";
    $body_vars = array('element_type'   => 'item',
                       'aspect'         => 'component',
                       'presentation'   => 'compact',
                       'entity'         => $item,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => $guid,
                       'action'         => 'add',
                       'panel'          => 'components',
                       'title'          => 'Add components',
                       'do'             => 'add_components');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";
*/
if ($item) {
    $subtype     = $item->getSubtype();
    	
	$category = $item->category ?: $item->marketcategory;                                        $display .= '$category: '.$category.'<br>';
    $category_name = get_entity($category)->title ?: elgg_echo("market:category:{$category}");                                      
    $category_set = hypeJunction\Categories\get_hierarchy($category, true, true);

/****** Menu *******/
    if ($section != 'Profile'){
    	if ($section == 'Inventory' || $section == 'Summary'){
    		$view_menu[] = ElggMenuItem::factory(array('name'           => '00supply',        											'aspect'         => 'jot',
    		                                        'panel'          => 'supply',
    												'text'               => 'Add supply ...', 
//     											    'class'              => 'elgg-lightbox',
//     											    'data-colorbox-opts' => '{"width":500, "height":525}',
//     								                'href'               => "jot/box/$item_guid/supply/add",
    								                'href'               => '#',
    								                        ));
    		
    		$view_menu[] =  ElggMenuItem::factory(array('name'           => '01part',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'part',
    												'text'               => 'Add part ...', 
//     											    'class'              => 'elgg-lightbox',
//     											    'data-colorbox-opts' => '{"width":500, "height":525}',
//     								                'href'               => "jot/box/$item_guid/part/add"
    								                'href'               => "#",
    								                        ));
    
    		$view_menu[] = ElggMenuItem::factory(array('name'            => '02receipt',
		                                           'id'             => 'add_receipt_tab',
            										'aspect'         => 'jot',
            										'panel'          => 'receipt',
    												'text'               => 'Add receipt ...', 
//            						                'href'               => "jot/edit/$item_guid/receipt",
            						                'href'               => '#'
            						                        ));
            		
    /*		$view_menu[2] = ElggMenuItem::factory(array('name'           => '02receipt',
    												'text'               => 'Add receipt ...', 
    											    'class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":525}',
    								                'href'               => "jot/box/$item_guid/receipt/add"));
    		elgg_register_menu_item('page', $view_menu[2]);
    */	}
    	if ($section == 'Maintenance' || $section == 'Summary'){
    		$view_menu[] = ElggMenuItem::factory(array('name'           => '00support',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'support',
    												'text'               => 'Pick support groups ...', 
//     											    'class'              => 'elgg-lightbox',
//     											    'data-colorbox-opts' => '{"width":500, "height":525}',
//     										        'href'               => "market/groups/support/$item_guid/2",
    										        'href'               => "#"
    										                ));
    		
/*    		elgg_register_menu_item('page', ElggMenuItem::factory(array('name'           => '01observation',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'observation',
    												'text'               => 'Add observation ...', 
    												'class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":325}',
    												'href'               => "jot/box/$item_guid/observation/add")));
*/    		
    		$view_menu[] =  ElggMenuItem::factory(array('name'           => '02experience',
    		                                        'id'             => 'add_experience_tab',
    		                                        'aspect'         => 'jot',
    		                                        'panel'          => 'experience',
    												'text'               => 'Add experience ...', 
//    												'class'              => 'elgg-lightbox',
//    											    'data-colorbox-opts' => '{"width":500, "height":325}',
//    												'href'               => "jot/box/$item_guid/experience/add",
    												'href'               => '#'
    												        ));
    	}
    
    	if ($section == 'Maintenance'){
    		$view_menu[] = ElggMenuItem::factory(array('name'           => '02scheduled',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'schedule',
    												'text'               => 'Add scheduled maintenance ...', 
    												'link_class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":325}',
    												'href'               => "jot/box/$item_guid/schedule/add"));
    	}
    	/*Defaults*/
    	$view_menu[] = ElggMenuItem::factory(array('name'           => '02experience',
		                                         'id'             => 'add_experience_tab',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'experience',
    												'text'               => 'Add experience ...', 
//        											'class'              => 'elgg-lightbox',
//        										    'data-colorbox-opts' => '{"width":500, "height":325}',
//        											'href'               => "jot/box/$item_guid/experience/add"
                                                    'href'               => '#',
                                                            ));
        $view_menu[] = ElggMenuItem::factory(array('name'               => '05component',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'component',
    												'text'               => 'Add component ...', 
//     												'class'              => 'elgg-lightbox',
//     											    'data-colorbox-opts' => '{"width":500, "height":325}',
//     			                                    'href'               => "jot/box/$item_guid/component/add"
    	                                            'href'               => "#",		                                            
    	));
    		
        $view_menu[] = ElggMenuItem::factory(array('name'               => '06accessory',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'accessory',
    												'text'               => 'Add accessory ...', 
    												'link_class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":325}',
    			                                    'href'               => "jot/box/$item_guid/accessory/add"));
    		
        $view_menu[] = ElggMenuItem::factory(array(
    	                                            'name'               => '07document',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'document',
    												'text'               => 'Add document ...', 
    											    'link_class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":525}',
    								                'href'               => "jot/attach?element_type=document&container_guid=$item_guid"));
    		
/*    	elgg_register_menu_item('page', ElggMenuItem::factory(array(
    	                                            'name'               => '08observation',
    												'text'               => 'Add observation ...', 
    												'class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":325}',
       												'href'               => "jot/box/$item_guid/observation/add")));
*/    		
        $view_menu[] = ElggMenuItem::factory(array('name'               => '09insight',
        											'aspect'         => 'jot',
    		                                        'panel'          => 'insight',
    		                                        'text'               => 'Add insight ...',
    		                                        'link_class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":325}',
    												'href'               => "jot/box/$item_guid/insight/add"));
    		
    	$view_menu[] = new ElggMenuItem('10issue', 'Add issue ...', "jot/add/$item_guid/issue");
    		
    	$view_menu[] = new ElggMenuItem('11task', 'Add task ...', "tasks/add/$owner_guid?element_type=task&container_guid=$item_guid");
    	
    	$items_on_shelf = shelf_count_items();
    	$view_menu[] = ElggMenuItem::factory(array('name'               => '000shelf',
    	                                             'text'               => "Shelf ($items_on_shelf)", 
            								         'link_class'              => 'elgg-lightbox',
            								         'data-colorbox-opts' => '{"width":500, "height":525}',
            					                     'href'               => "shelf/show/$item_guid",));
    }
	
    foreach($view_menu as $menu_item){
//@EDIT_TEMP - 2017-11-07 - SAJ - Remove to troubleshoot javascript issues 
//    	elgg_register_menu_item('page', $menu_item);
    }
	elgg_push_breadcrumb(elgg_echo('market:title'), "queb");
	
    if ($owner_item->getType() == 'user'){
		elgg_push_breadcrumb($owner_item->name." Things", "queb?z=$owner_guid");
	}
	elseif ($owner_item->getType() == 'group' ){
		elgg_push_breadcrumb($owner_item->name." Things", "queb?z=$owner_guid");
	}

    foreach ($category_set as $category_guid){
        $this_category = get_entity($category_guid);
        elgg_push_breadcrumb($this_category->title, $this_category->getURL());
    }

	if ($parent_item->getType() == 'object' ){
		  if ($parent_item->getSubtype() == 'place'){
		  	elgg_push_breadcrumb($parent_item->title, "places/view/".$parent_guid);
		  }
		  else {
		  	elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid);
		  }
	}
	elgg_push_breadcrumb($item->title);

/****** Content *******/
    $content = '<!--Page: market\pages\market\view.php-->';
    $content .= "<!-- subtype: $subtype -->";                         $display .= '$classname: '.get_subtype_class_from_id(28).'<br>';
//    $content .= $display;
    $content .= elgg_view('object/market', array('entity'=>$item, 'full_view'=>true, 'compartment'=>$section));
//	$content = elgg_view_list_item($item, array('full_view' => true, 'this_section'=>$section));
//	$content = elgg_view_entity($item, array('full_view' => false));
    $add_comment = true;
    $params = array("class"=>"jot_input");
	if (elgg_get_plugin_setting('market_comments', 'market') == 'yes') {
		$content .= elgg_view_comments($item, $add_comment, $params);
	}

	// Set the title appropriately
	$title = elgg_echo("market:title") . ":" . elgg_echo("market:{$category}"). ":".elgg_echo($item->title.":type:".$item->type.":".":subtype:".$item->getSubtype());

} else {

	// Display the 'item not found' page instead
	$content = elgg_view_title(elgg_echo("market:notfound"));
	$title = elgg_echo("market:notfound");

}

// Show market sidebar
/* Reference works.  Replaced by page_menu above.
 * $sidebar = elgg_view("market/sidebar_item"); // sidebar for individual item
 */
/****** Right Sidebar *******/
$sidebar    = elgg_view("market/sidebar");     // sidebar for all items

/****** Left Sidebar *******/
$sidebar_alt = elgg_view('market/sidebar/navigation');

$params = array(
		'filter'      => $tabs,
        'header'      => $header,
		'content'     => $content,
		'title'       => $title,
		'sidebar'     => $sidebar,
		'sidebar_alt' => $sidebar_alt,
		);

//$body = elgg_view_layout('one_column', $params);
//$body = elgg_view_layout('no_sidebar', $params);
// Note: 'content' layout overridden by 
//$body = 'page context: '.elgg_get_context();
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);


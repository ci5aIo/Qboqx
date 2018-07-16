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

// Get input
$selected_category = get_input('cat');
$selected_owner    = get_input('owner');
$selected_queb     = get_input('label');
$category = $selected_category;
$url      = elgg_get_site_url(). "queb?";
$referrer = current_page_url();
$list_type = get_input('list_type', 'list'); 
if (!empty($list_type)){
    $list_type_filter_1 = "?list_type=$list_type";
    $list_type_filter_2 = "&list_type=$list_type";
}

/*
if ($selected_category == 'all') {
	$category = '';
} elseif ($selected_category == '') {
	$category = '';
	$selected_category = 'all';
} else {
	$category = $selected_category;
}*/
elgg_set_context('market');

elgg_pop_breadcrumb();

//elgg_register_title_button();

$tabs = elgg_view('market/menu', array('this_owner' => $selected_owner, 'this_category' => $selected_category, 'this_label'=>$selected_queb, 'list_type'=> $list_type));
//@EDIT - 2016-12-10 - SAJ - replaced horizontal tabs with menu sidebar tabs
$menu_sidebar = elgg_view('market/menu_sidebar', array('selected_owner' => $selected_owner, 'selected_category' => $selected_category, 'selected_label'=>$selected_queb));

$footer .= '<script>
$(document).ready(function(){ 
        $("#add_something_tab").click(function(){
        $("#add_experience_panel").fadeOut("slow");
        $("#add_experience_tab").removeClass("elgg-state-selected");
        $("#add_something_panel").fadeToggle("slow");
        $("#add_something_tab").addClass("elgg-state-selected");
        $("#add_receipt_panel").fadeOut("slow");
        $("#add_receipt_tab").removeClass("elgg-state-selected");
    });
    $("#add_something_else_tab").click(function(){
        $("#add_something_panel").fadeOut("slow");
        $("#add_something_tab").removeClass("elgg-state-selected");
        $("#add_experience_panel").fadeToggle("slow");
        $("#add_experience_tab").addClass("elgg-state-selected");
        $("#add_receipt_panel").fadeOut("slow");
        $("#add_receipt_tab").removeClass("elgg-state-selected");        
    });
    $("#add_receipt_tab").click(function(){
        $("#add_something_panel").fadeOut("slow");
        $("#add_something_tab").removeClass("elgg-state-selected");
        $("#add_experience_panel").fadeToggle("slow");
        $("#add_experience_tab").addClass("elgg-state-selected");
        $("#add_receipt_panel").fadeToggle("slow");
        $("#add_receipt_tab").addClass("elgg-state-selected");        
    });
    $("#filter_queb").change(function() {
       if($(this).prop("checked", false)) {
          $(location).attr("href", "'.$url.'x=&y='.$selected_category.'&z='.$selected_owner.'");
          return;
       }
    });
    $("#filter_category").change(function() {
       if($(this).prop("checked", false)) {
          $(location).attr("href", "'.$url.'x='.$selected_queb.'&y=&z='.$selected_owner.'");
          return;
       }
    });
    $("#filter_owner").change(function() {
       if($(this).prop("checked", false)) {
          $(location).attr("href", "'.$url.'x='.$selected_queb.'&y='.$selected_category.'&z=");
          return;
       }
    });
    $("#filter_queb2").change(function() {
       if($(this).prop("checked", false)) {
          $(location).attr("href", "'.$url.'x=&y='.$selected_category.'&z='.$selected_owner.'");
          return;
       }
    });
    $("#filter_category2").change(function() {
       if($(this).prop("checked", false)) {
          $(location).attr("href", "'.$url.'x='.$selected_queb.'&y=&z='.$selected_owner.'");
          return;
       }
    });
    $("#filter_owner2").change(function() {
       if($(this).prop("checked", false)) {
          $(location).attr("href", "'.$url.'x='.$selected_queb.'&y='.$selected_category.'&z=");
          return;
       }
    });
});
</script>

<style>
#add_experience_panel{
	display: none;
    padding:0px 0px 12px 0px;
}
</style>
';
$header .= "<div id='add_something_panel' class='elgg-head'>";
    $form      = 'market/add_now';
    $form_vars = array('name'    => 'jotForm', 
                       'enctype' => 'multipart/form-data',
                       'action'  => 'action/market/edit',
    				  );
    $body_vars = array('category'=>$selected_category, 
                       'label'   =>$selected_queb,
                       'title'   => 'Add something',
                       'do'      => 'add_item');
$header .= elgg_view_form($form, $form_vars, $body_vars)
        ."</div>";
$header .= "<div id='add_experience_panel' class='elgg-head'>";
    $body_vars = array('element_type'   => 'item',
                       'aspect'         => 'experience',
                       'selected'       => 'Things',
                       'entity'         => $entity,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => elgg_get_logged_in_user_guid(),
                       'action'         => 'add',
                       'panel'          => 'experiences',
                       'title'          => 'Add an experience',
                       'do'             => 'add_item');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";
$header .= "<div id='add_receipt_panel' class='elgg-head'>";
    $body_vars = array('element_type'   => 'item',
                       'aspect'         => 'experience',
                       'selected'       => 'Things',
                       'entity'         => $entity,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => elgg_get_logged_in_user_guid(),
                       'action'         => 'add',
                       'panel'          => 'experiences',
                       'title'          => 'Add an experience',
                       'do'             => 'add_item');
    $view      = "forms/jot/elements";
$header .= elgg_view($view, $body_vars);	
$header .= "</div>";

if (!empty($category) || !empty($selected_owner) || !empty($selected_queb)){
    $filter .= 'Filters: ';
}
if (!empty($selected_queb)){
    $filter2 .= elgg_view('input/checkbox', array('id' => 'filter_queb2', 'checked'=>'checked'));
    $filter  .= 'Label = ';
    $filter2 .= 'Label: ';
    $filter  .= $selected_queb.' ';
    $filter  .= elgg_view('input/checkbox', array('id' => 'filter_queb', 'checked'=>'checked'));
    $filter2 .= $selected_queb.'<br>';
}
if (!empty($category)){
    $filter2 .= elgg_view('input/checkbox', array('id' => 'filter_category2', 'checked'=>'checked'));
    $filter  .= 'Category = ';
    $filter2 .= 'Category: ';
    $filter  .= get_entity($category)->title.' ';
    $filter  .= elgg_view('input/checkbox', array('id' => 'filter_category', 'checked'=>'checked'));
    $filter2 .= get_entity($category)->title.'<br>';
}
if (!empty($selected_owner)){
    $filter2 .= elgg_view('input/checkbox', array('id' => 'filter_owner2', 'checked'=>'checked'));
    $filter  .= 'Owner = ';
    $filter2 .= 'Owner: ';
    $filter  .= get_entity($selected_owner)->name;
    $filter  .= elgg_view('input/checkbox', array('id' => 'filter_owner', 'checked'=>'checked'));
    $filter2 .= get_entity($selected_owner)->name;
}

$header .= $filter;

$num_items = 0;  // 0 = Unlimited
$dbprefix = elgg_get_config('dbprefix');

$options = array(
	'types' => 'object',
	'subtypes' => 'market',
	'limit' => $num_items,
//	'full_view' => true,  // default = false
	'pagination' => true, // default = false
	'list_type_toggle' => true, // default = false
	'view_type'=> $list_type,
//	'view_type' => 'list',   // custom option
    'wheres'    => array("NOT EXISTS (SELECT *
	                                  FROM {$dbprefix}metadata md
            	                      JOIN {$dbprefix}metastrings ms1 ON ms1.id = md.name_id
            	                      JOIN {$dbprefix}metastrings ms2 ON ms2.id = md.value_id
            	                      WHERE ms1.string = 'visibility_choices'
            	                        AND ms2.string = 'hide_in_catalog'
            	                        AND e.guid = md.entity_guid)"),
            
//* hypelist options *//
	'lazy_load'       => 1,            // Number of pages to lazy load
	'pagination_type' => 'infinite',
	'show_search'     => true,
	'show_filter'     => true,
	'reversed'        => true,
	'position'        => 'both',
);
/*
	return elgg_get_entities(array(
		'type' => 'user',
		'joins' => array(
			"JOIN {$dbprefix}users_entity ue ON ue.guid = e.guid",
		),
		'wheres' => array(
			"ue.username LIKE '%{$q}%' OR ue.name LIKE '%{$q}%'",
		),
		'order_by' => 'ue.name ASC'
	));
*/

elgg_push_breadcrumb(elgg_echo('market:title'), "queb".$list_type_filter_1);
if (!empty($selected_queb)){
    $options['joins'][]  =  "JOIN {$dbprefix}metadata    t1 on t1.entity_guid = e.guid";
    $options['joins'][]  =  "JOIN {$dbprefix}metastrings t2 on t1.value_id    = t2.id";
    $options['joins'][]  =  "JOIN {$dbprefix}metastrings t3 on t1.name_id     = t3.id";
    $options['wheres'][] =  "t3.string in ('tags')";
    $options['wheres'][] =  "t2.string = '{$selected_queb}'";
    $options['wheres'][] =  "e.subtype in (11, 47)";
}
if (!empty($selected_owner)){
    elgg_push_breadcrumb(get_entity($selected_owner)->name." Things", $url."z=$selected_owner".$list_type_filter_2);
    $options['wheres'][] = "e.owner_guid = {$selected_owner}";
}
if (!empty($category)) {
	elgg_push_breadcrumb(elgg_echo('market:title'), $url."x=$selected_queb&z=$selected_owner".$list_type_filter_2);
    $category_set = hypeJunction\Categories\get_hierarchy($category, true, true);
    foreach ($category_set as $category_guid){ 
        $this_category = get_entity($category_guid);
        elgg_push_breadcrumb($this_category->title, $url."x=$selected_queb&y=$category_guid&z=$selected_owner".$list_type_filter_2);
    }
	$items = hypeJunction\Categories\get_filed_items($category, $options);
/*	$items_list = elgg_view_entity_list($items, array(
		'full_view' => false
	));*/
	if (!empty($items)){
    	foreach ($items as $item){$items_guids[] = $item->guid;}
    	$options['wheres'][] = "e.guid in (".implode(',', $items_guids).")";
	}
	else {
	    $options['wheres'][] = "e.guid is NULL";
	} 
} else {
	$items = elgg_get_entities($options);
}

$items_list = elgg_list_entities($options);

if (!empty($items)){
    foreach ($items as $item) {
        $list_navigation[] = $item->guid;
    }
}
// Push navigation list
	$file = new ElggFile;
	$file->owner_guid = elgg_get_site_entity()->guid;
	$file->setFilename("/quebx/"._elgg_services()->session->getId()."_nav.json");
	$file->open('write');
	$file->write(json_encode($list_navigation));
	$file->close();
//echo elgg_dump($list_navigation);
//$vars['list_navigation'] = $list_navigation;
// Store filter
	$file = new ElggFile;
	$file->owner_guid = elgg_get_site_entity()->guid;
	$file->setFilename("/quebx/"._elgg_services()->session->getId()."_filter.json");
	$file->open('write');
	$file->write(json_encode($referrer));
	$file->close();
	
$content .= $items_list;

if (empty($items_list)) {
	$content .= "No items found for:<br>$filter2";
}
if (elgg_is_logged_in()){
		$view_menu[] = ElggMenuItem::factory(array('name'           => '01receipt',
		                                           'id'             => 'add_receipt_tab',
												'text'               => 'Add receipt ...', 
//								                'href'               => "jot/edit/0/receipt"
		));
		elgg_register_menu_item('page', $view_menu[0]);
		
		$view_menu[0] = ElggMenuItem::factory(array('name'           => '001a_item',
												'text'               => 'Add something ...', 
											    'link_class'              => 'elgg-lightbox',
											    'data-colorbox-opts' => '{"width":500, "height":525}',
								                'href'               => "jot/box/0/item/add"));
//		elgg_register_menu_item('page', $view_menu[0]);

		$items_on_shelf = shelf_count_items();
    	$view_menu[12] = ElggMenuItem::factory(array('name'               => '000shelf',
    	                                             'text'               => "Shelf ($items_on_shelf)", 
//            								         'class'              => 'elgg-lightbox',
//            								         'data-colorbox-opts' => '{"width":500, "height":525}',
            					                     'href'               => "shelf",
    	));
    	elgg_register_menu_item('page', $view_menu[12]);
    	
		$view_menu = ElggMenuItem::factory(array('name'           => '001b_item',
		                                         'id'             => 'add_something_tab',
												'text'               => 'Add something ...',
								                'href'               => "#"));
		elgg_register_menu_item('page', $view_menu);
		$view_menu = ElggMenuItem::factory(array('name'           => '001c_item',
		                                         'id'             => 'add_something_else_tab',
												'text'               => 'Say something ...',
								                'href'               => "#"));
		elgg_register_menu_item('page', $view_menu);
}
    		
// Show market sidebar
$sidebar  = elgg_view("market/sidebar", array('list_type'=> $list_type));
//@EDIT - 2015-05-23 - SAJ - Experimental
//$sidebar_alt = $menu_sidebar. elgg_view('market/sidebar/navigation');
//EDIT - 2016-12-10 - SAJ - removed market/sidebar/navigation
$sidebar_alt = $menu_sidebar;

$params = array(
		'filter' => $tabs,
        'header' => $header,
		'footer' => $footer,
        'navigation'=>$list_navigation,
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		'sidebar_alt' => $sidebar_alt,
		);

//$body = elgg_view_layout('items', $params);
$body = elgg_view_layout('content', $params);
//$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);
/*********************************/

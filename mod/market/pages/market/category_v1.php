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

if ($selected_category == 'all') {
	$category = '';
} elseif ($selected_category == '') {
	$category = '';
	$selected_category = 'all';
} else {
	$category = $selected_category;
}
elgg_set_context('market');

elgg_pop_breadcrumb();

elgg_register_title_button();

/* @TODO change the input button to automatically add an item in the selected category
 
//quebx_register_title_button(null, $selected_category);
//$button_name = elgg_echo("{$selected_category}");

$button_name = $selected_category;
elgg_register_title_button('add', $button_name);




$tabs = elgg_view("input/text", array(
				"name" => "item_title",
				"value" => $title,
				));
$tabs .= elgg_view('input/submit', array(
               'href' => $vars['url'] . "action/add/now",
               'text' => elgg_echo('market:add'), 
//               'text' => elgg_echo('quebx:add'), 
               'class' => 'elgg-button-action',
			   ));
 */			   
$tabs = elgg_view('market/menu', array('category' => $selected_category));
//@EDIT - 2016-12-10 - SAJ - replaced horizontal tabs with menu sidebar tabs
$menu_sidebar = elgg_view('market/menu_sidebar', array('selected' => $selected_category));

$header .= $selected_category;
// header to add a quick add form to the category page
//$header = elgg_view_form('market/add_now');
$header .= '<script> 
$(document).ready(function(){
    $("#add_something_tab").click(function(){
        $("#add_something_else_panel").fadeOut("slow");
        $("#add_something_else_tab").removeClass("elgg-state-selected");
        $("#add_something_panel").fadeToggle("slow");
        $("#add_something_tab").addClass("elgg-state-selected");
    });
    $("#add_something_else_tab").click(function(){
        $("#add_something_panel").fadeOut("slow");
        $("#add_something_tab").removeClass("elgg-state-selected");
        $("#add_something_else_panel").fadeToggle("slow");
        $("#add_something_else_tab").addClass("elgg-state-selected");
        
    });
});
</script>

<style>
#add_something_else_panel{
	display: none;
}
</style>
';

//set market title
//$title = sprintf(elgg_echo('market:category:title'), elgg_echo("market:category:{$selected_category}"));

$num_items = 0;  // 0 = Unlimited
$dbprefix = elgg_get_config('dbprefix');

$options = array(
	'types' => 'object',
	'subtypes' => 'market',
	'limit' => $num_items,
//	'full_view' => true,  // default = false
	'pagination' => true, // default = false
	'list_type_toggle' => true, // default = false
	'view_type' => 'list',   // custom option
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
$form      = 'market/add_now';
$form_vars = array('name'    => 'jotForm', 
                   'enctype' => 'multipart/form-data',
                   'action'  => 'action/market/edit',
				  );
$body_vars = NULL;//jot_prepare_form_vars($aspect, null, $jot_guid, $referrer, $description); 

$content .= "<div id='add_something_panel' class='elgg-head'>";
        $body_vars['title'] = 'Add something';
        $body_vars['do']    = 'add_item';
$content .= elgg_view_form($form, $form_vars, $body_vars)
        ."</div>
        <div id='add_something_else_panel' class='elgg-head'>
        add something else ...
        </div>";
// Get a list of market posts in a specific category
if (!empty($category)) {
	elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
	elgg_push_breadcrumb(elgg_echo("market:category:{$category}"));
	$options['metadata_name'] = "marketcategory";
	$options['metadata_value'] = $selected_category;
	$content .= elgg_list_entities_from_metadata($options);
} else {
	elgg_push_breadcrumb(elgg_echo('market:title'));
	$content .= elgg_list_entities($options);
}

if (!$content) {
	$content = elgg_echo('market:none:found');
}
		$view_menu[] = ElggMenuItem::factory(array('name'           => '01receipt',
												'text'               => 'Add receipt ...', 
								                'href'               => "jot/edit/0/receipt"));
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
//            								         'link_class'              => 'elgg-lightbox',
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
												'text'               => 'Add something else ...',
								                'href'               => "#"));
//		elgg_register_menu_item('page', $view_menu);
		
    		
// Show market sidebar
$sidebar  = elgg_view("market/sidebar");
//@EDIT - 2015-05-23 - SAJ - Experimental
//$sidebar_alt = $menu_sidebar. elgg_view('market/sidebar/navigation');
//EDIT - 2016-12-10 - SAJ - removed market/sidebar/navigation
$sidebar_alt = $menu_sidebar;

$params = array(
		'filter' => $tabs,
		'header' => $header,
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		'sidebar_alt' => $sidebar_alt,
		);

//$body = elgg_view_layout('items', $params);
$body = elgg_view_layout('content', $params);
//$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);


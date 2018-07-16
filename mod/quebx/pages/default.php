<?php
/**
 * Elgg Quebx Plugin
 * @package quebx
 */

if (!elgg_is_logged_in()){
    forward(_elgg_walled_garden_init());
}

// Get input
$selected_album    = get_input('album');
$selected_place    = get_input('place');
$selected_task     = get_input('task');
$selected_category = get_input('cat');
$selected_owner    = get_input('owner');
$selected_queb     = get_input('label');
$list_type         = get_input('list_type', 'list');
$url               = elgg_get_site_url(). "queb?";
$referrer          = current_page_url(); 
if (!empty($list_type)){
    $list_type_filter_1 = "?list_type=$list_type";
    $list_type_filter_2 = "&list_type=$list_type";
}

// defaults
$collection = $selected_category;
$collection_label = 'Category';
$dimension  = 'market';
elgg_set_context('market');

if (!empty($selected_album)){
    $collection = $selected_album;
    $collection_label = 'Album';
    $dimension  = 'gallery';
//    elgg_set_context('gallery');
//    elgg_pop_breadcrumb();
}
elseif (!empty($selected_place)){
    $collection = $selected_place;
    $collection_label = 'Place';
    $dimension  = 'place';
}
elseif (!empty($selected_task)){
    $collection = $selected_task;
    $collection_label = 'Que';
    $dimension  = 'task';
}
/*
Switch($dimension){
    case 'market':
        elgg_pop_breadcrumb();
        
        //$menu_sidebar = elgg_view('quebx/menu_sidebar', array('selected_owner' => $selected_owner, 'selected_category' => $selected_category, 'selected_label'=>$selected_queb, 'dimension'=>'market'));
        
        $sidebar  = elgg_view("market/sidebar", array('list_type'=> $list_type));
                
        break;
    case 'gallery':        
        
        $sidebar      = elgg_view("market/sidebar"    , array('list_type'         => $list_type));
        
        break;
    case 'task':
        
        break;
    case 'place':
        
        break;
    default:
        
        break;
}*/

$tabs         = elgg_view('quebx/menu'         , array('this_owner'        => $selected_owner, 
                                                       'this_collection'   => $collection,
                                                       'this_label'        => $selected_queb, 
                                                       'list_type'         => $list_type));

$menu_sidebar = '
<div id="accordian">
	<ul>
		<li class="active">
			<h3><span style="cursor:pointer">Categories</span></h3>
			<div id=dimension>'.
                elgg_view('quebx/menu_sidebar' , array('selected_owner'    => $selected_owner, 
                                                       'selected_category' => $selected_category, 
                                                       'selected_label'    => $selected_queb, 
                                                       'dimension'         => 'market')).'
            </div>
         </li>
         <li>
            <h3><span style="cursor:pointer">Albums</span></h3>
            <div id=dimension>'.
                elgg_view('quebx/menu_sidebar' , array('selected_owner'    => $selected_owner, 
                                                       'selected_category' => $selected_category, 
                                                       'selected_label'    => $selected_queb, 
                                                       'dimension'         => 'gallery')).'
            </div>
        </li>
    </ul>
</div>';

$footer .= '<script>

require(["elgg", "jquery"], function (elgg, $) {
	$(document).ready(function(){
	    $("#filter_queb").change(function() {
	       if($(this).prop("checked", false)) {
	          $(location).attr("href", "'.$url.'x=&y='.$selected_category.'&z='.$selected_owner.'");
	          return;
	       }
	    });
	    $("#filter_collection").change(function() {
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
	    $("#filter_collection2").change(function() {
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
	    $("#accordian h3").click(function(){
	        $(this).next().slideToggle("fast");
		})
	})
});
</script>
<style>
#add_experience_panel, #add_receipt_panel{display: none;}
</style>
';
    $form      = 'market/add_now';
    $form_vars = ['name'    => 'jotForm', 
                  'enctype' => 'multipart/form-data',
                  'action'  => 'action/market/edit'];
    $body_vars = ['category'=>$selected_category, 
                  'label'   =>$selected_queb,
                  'title'   => 'New item',
                  'do'      => 'add_item'];
    $controls = elgg_view('output/div', ["class"  =>"elgg-widget-handle clearfix",
                                         "content"=>"<h3 class='elgg-widget-title'>Add Something</h3>
                										<ul class='elgg-menu elgg-menu-widget elgg-menu-hz elgg-menu-widget-default'>
                											<li class='elgg-menu-item-collapse'>
                												<a href='#'
                                                                   title='hide'
                                                                   aspect='panel'
                                                                   panel = 'thing'
                                                                   rel='toggle'
                                                                   class='elgg-menu-content quebx-panel-collapse-button'>
                												</a>
                											</li>
                											<li class='elgg-menu-item-delete'>
                												<a href='#' 
                												   title='close'
                                                                   aspect='module'
                                                                   panel='thing'
                												   class='elgg-menu-content quebx-module-close'>".
                                                                 quebx_view_icon('windowshade')
                												."
                												</a>
                											</li>
                										</ul>"]);
$module_add_thing =   elgg_view('output/div',['class'=>'quebx-module-add',
                                     "options"=>['aspect'=>'module',
                                                 'panel' =>'thing',
                                                 'style' => 'display:none'],
                                     'content'=> elgg_view('output/div',['class'=>'elgg-head',
                                                                         'content'=>$controls]).
                                                 elgg_view('output/div', ['class'=>'elgg-body',
                                                                         'options'=>['aspect'=>'panel',
                                                                                     'panel' =>'thing',
                                                                                     'id'    =>'add_something_panel'],
                                                                          'content'=>elgg_view_form($form, $form_vars, $body_vars)])]);
    $body_vars = array('element_type'   => 'item',
                       'aspect'         => 'experience',
                       'selected'       => 'Things',
                       'entity'         => $entity,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => elgg_get_logged_in_user_guid(),
                       'action'         => 'add',
                       'presentation'   => 'full',
                       'panel'          => 'experiences',
                       'title'          => 'Add an experience',
                       'do'             => 'add_item');
    $view      = "forms/jot/elements";
    $controls = elgg_view('output/div', ["class"  =>"elgg-widget-handle clearfix",
                                         "content"=>"<h3 class='elgg-widget-title'>Add Experience</h3>
                										<ul class='elgg-menu elgg-menu-widget elgg-menu-hz elgg-menu-widget-default'>
                											<li class='elgg-menu-item-collapse'>
                												<a title='hide'
                                                                   aspect='panel'
                                                                   panel = 'experience'
                                                                   rel='toggle'
                                                                   class='elgg-menu-content quebx-panel-collapse-button'>
                												</a>
                											</li>
                											<li class='elgg-menu-item-delete'>
                												<a title='close'
                                                                   aspect='module'
                                                                   panel='experience'
                												   class='elgg-menu-content quebx-module-close'>".
                                                                 quebx_view_icon('windowshade')
                												."</a>
                											</li>
                										</ul>"]);
$module_add_experience =   elgg_view('output/div',['class'=>'quebx-module-add',
                                     "options"=>['aspect'=>'module',
                                                 'panel' =>'experience',
                                                 'style' => 'display:none'],
                                     'content'=> elgg_view('output/div',['class'=>'elgg-head',
                                                                         'content'=>$controls]).
                                                 elgg_view('output/div', ['class'=>'elgg-body',
                                                                         'options'=>['aspect'=>'panel',
                                                                                     'panel' =>'experience',
                                                                                     'id'    =>'add_experience_panel'],
                                                                          'content'=>elgg_view($view, $body_vars)])]);
    $body_vars = array('element_type'   => 'item',
                       'aspect'         => 'receipt',
                       'presentation'   => 'full',
                       'entity'         => $entity,
                       'owner_guid'     => elgg_get_logged_in_user_guid(),
                       'container_guid' => elgg_get_logged_in_user_guid(),
                       'action'         => 'add',
                       'panel'          => 'receipts',
                       'title'          => 'Add a receipt',
                       'do'             => 'add_receipt');
    $view      = "forms/jot/elements";
$module_add_receipt = elgg_view('output/div',['class'=>'elgg-head',
                                   'options'=>['aspect'=>'panel',
                                               'panel' =>'receipt',
                                               'id'    =>'add_receipt_panel',
                                               'style' =>'display: none'],
                                   'content'=>elgg_view($view, $body_vars)]);

$header .= $module_add_thing.$module_add_experience.$module_add_receipt;

if (!empty($collection) || !empty($selected_owner) || !empty($selected_queb)){
    $filter   = 'Filters: ';
}
if (!empty($collection)){
    $filter2 .= elgg_view('input/checkbox', array('id' => 'filter_collection2', 'checked'=>'checked'));
    $filter  .= "$collection_label = ";
    $filter2 .= "$collection_label: ";
    $filter  .= get_entity($collection)->title.' ';
    $filter  .= elgg_view('input/checkbox', array('id' => 'filter_collection', 'checked'=>'checked'));
    $filter2 .= get_entity($collection)->title.'<br>';
}
if (!empty($selected_queb)){
    $filter2 .= elgg_view('input/checkbox', array('id' => 'filter_queb2', 'checked'=>'checked'));
    $filter  .= 'Queb = ';
    $filter2 .= 'Queb: ';
    $filter  .= $selected_queb.' ';
    $filter  .= elgg_view('input/checkbox', array('id' => 'filter_queb', 'checked'=>'checked'));
    $filter2 .= $selected_queb.'<br>';
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

$dbprefix = elgg_get_config('dbprefix');

$options = array(
	'types'            => 'object',
	'limit'            => 10,           // 0 = Unlimited
//	'full_view'        => true,         // default = false
	'pagination'       => true,         // default = false
	'pagination_type'  => 'infinite',
	'lazy_load'        => 10,           // Number of pages to lazy load
	'reversed'         => false,
    'position'         => 'after',
	'list_type_toggle' => true,         // default = false
	'view_type'        => $list_type,   // custom option
	'selected_state'   => 'closed',
    'wheres'           => array("NOT EXISTS (SELECT *
	                                  FROM {$dbprefix}metadata md
            	                      JOIN {$dbprefix}metastrings ms1 ON ms1.id = md.name_id
            	                      JOIN {$dbprefix}metastrings ms2 ON ms2.id = md.value_id
            	                      WHERE ms1.string = 'visibility_choices'
            	                        AND ms2.string = 'hide_in_catalog'
            	                        AND e.guid = md.entity_guid)"),
);



elgg_push_breadcrumb(elgg_echo('market:title'), "queb".$list_type_filter_1);
if (empty($collection)) {
////    $items = elgg_get_entities($options);
}

if (!empty($selected_queb)){
    $options['joins'][]  =  "JOIN {$dbprefix}metadata    t1 on t1.entity_guid = e.guid";
    $options['joins'][]  =  "JOIN {$dbprefix}metastrings t2 on t1.value_id    = t2.id";
    $options['joins'][]  =  "JOIN {$dbprefix}metastrings t3 on t1.name_id     = t3.id";
    $options['wheres'][] =  "t3.string in ('tags')";
    $options['wheres'][] =  "t2.string = '{$selected_queb}'";
}

$sidebar .= elgg_view('object/shelf', ['perspective'=>'sidebar']);
Switch($dimension){
    case 'market':
	    //$options['subtypes'] = 'market';
        $options['wheres'][] =  "e.subtype in (11, 72)";
        if (!empty($collection)) {
        	elgg_push_breadcrumb(elgg_echo('market:title'), $url."x=$selected_queb&z=$selected_owner".$list_type_filter_2);
            $category_set = hypeJunction\Categories\get_hierarchy($collection, true, true);
            foreach ($category_set as $category_guid){ 
                $this_category = get_entity($category_guid);
                elgg_push_breadcrumb($this_category->title, $url."x=$selected_queb&y=$category_guid&z=$selected_owner".$list_type_filter_2);
            }
        	$items = hypeJunction\Categories\get_filed_items($collection, $options);
        
        	if (!empty($items)){
            	foreach ($items as $item){$items_guids[] = $item->guid;}
            	$options['wheres'][] = "e.guid in (".implode(",", $items_guids).")";
        	}
        	else {
        	    $options['wheres'][] = "e.guid is NULL";
        	} 
        } else {
        	$items = elgg_get_entities($options);
        }
        $sidebar .= elgg_view('page/components/labelcloud_block', array(
				'subtypes' => array('market', 'item'),
				'owner_guid' => $selected_owner ?: elgg_get_page_owner_guid(),
			));
        break;
    case 'gallery':
	    $options['subtypes'] = 'hjalbumimage';
        if (!empty($collection)){
            $items = elgg_get_entities(array('container_guids'=>array($collection), 'limit'=>0));
            if (!empty($items)){
                	foreach ($items as $item){$items_guids[] = $item->guid;}
                	$options['wheres'][] = "e.guid in (".implode(',', $items_guids).")";
            	}
            	else {
            	    $options['wheres'][] = "e.guid is NULL";
            	}
        }
        $sidebar .= elgg_view('page/components/labelcloud_block', array(
				'subtypes' => ['hjalbumimage','image'],
				'owner_guid' => $selected_owner ?: elgg_get_page_owner_guid(),
			));
        break;
}
                                                                  //$display .= print_r($options, false);
                                                                  
if (!empty($selected_owner)){
    elgg_push_breadcrumb(get_entity($selected_owner)->name." Things", $url."z=$selected_owner".$list_type_filter_2);
    $options['wheres'][] = "e.owner_guid = {$selected_owner}";
    $options['wheres'][] =  "e.subtype not in (51, 72)";  // Only show things when owner is selected.  Do not show experiences.
}
if (!empty($items)){
    foreach ($items as $item) {
        $list_navigation[] = $item->guid;
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
}	

// display a sortable list.  See https://github.com/hypeJunction/hypeLists for configuration values
$items_list =elgg_view('lists/objects',['options'          => $options,
										 'show_search'      => true,
										 'show_filter'      => true,
		                                 'show_sort'        => true,
]);
//$items_list = elgg_list_entities($options);
$content .= $items_list;

if (empty($items_list)) {
	$content .= "Nothing found for:<br>$filter2";
}
if (elgg_is_logged_in()){
		$view_menu[] = ElggMenuItem::factory(['name'            => '01receipt',
				                              'class'           => 'jot-q',
				                              //'data-qid'        => "q".rand(0, 200),
				                              'data-element'    => 'qbox',
				                              'data-context'    => 'quebx',
    		                                  'data-space'      => 'transfer',
        									  'data-aspect'     => 'receipt',
				                              'data-perspective'=> 'add',
											  'text'            => 'Add new receipt ...'
		]);
/*		$view_menu[] = ElggMenuItem::factory(array('name'           => '01receipt',
		                                           'id'             => 'add_receipt_tab',
        											'aspect'         => 'module',
    		                                        'panel'          => 'receipt',
												'text'               => 'Add new receipt ...', 
//								                'href'               => "jot/edit/0/receipt",
                                                'href'              => '#',
		));*/
		elgg_register_menu_item('page', $view_menu[0]);
		
		$view_menu[0] = ElggMenuItem::factory(array('name'           => '001a_item',
												'text'               => 'Add something ...', 
											    'link_class'              => 'elgg-lightbox',
											    'data-colorbox-opts' => '{"width":500, "height":525}',
								                'href'               => "jot/box/0/item/add"));
//		elgg_register_menu_item('page', $view_menu[0]);

		$items_on_shelf = shelf_count_items();
    	$view_menu[12] = ElggMenuItem::factory(array('name'               => '02shelf',
    	                                             'text'               => "Shelf ($items_on_shelf)", 
//            								         'class'              => 'elgg-lightbox',
//            								         'data-colorbox-opts' => '{"width":500, "height":525}',
            					                     'href'               => "shelf",
    	));
    	elgg_register_menu_item('page', $view_menu[12]);

// @TODO Expand the add something tab to include experiences    	
		$view_menu = ElggMenuItem::factory(array('name'           => '001b_item',
		                                         'id'             => 'add_something_tab',
		                                         'link_class'          => 'quebx-module-open',
    											'aspect'         => 'module',
		                                        'panel'          => 'thing',
												'text'               => 'Add something ...',
								                'href'               => "#"));
		elgg_register_menu_item('page', $view_menu);
/* @EDIT - 2018-05-28 - SAJ - Removing global add experience tab in favor of individual experiences at the item level
 
		$view_menu = ElggMenuItem::factory(array('name'           => '001c_item',
		                                         'id'             => 'add_experience_tab',
		                                         'link_class'          => 'quebx-module-open',
    											 'aspect'         => 'module',
		                                         'panel'          => 'experience',
												 'text'           => 'Say something ...',
								                 'href'           => "#"));
		elgg_register_menu_item('page', $view_menu);
		*/
}

$display .= 'context: '.elgg_get_context().'<br>';
$params = array(
		'filter'      => $tabs,
        'header'      => $header,
		'footer'      => $footer,
        'navigation'  => $list_navigation,
		'content'     => $content,//$display
		'title'       => $title,
		'sidebar'     => $sidebar,
		'sidebar_alt' => $menu_sidebar,
		);
$body   = elgg_view_layout('content', $params);
//$body   = elgg_view_layout('two_sidebar', $params);

echo elgg_view_page($title, $body);

/*********************************/
eof:

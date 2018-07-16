<?php
/**
 * View a single jot
 *
 */
// Get input data
if (isset($page[1])){
    $jot_guid = (int) $page[1]; 
    if ($jot_guid > 0){
        $solo = true;
    }
    else {                              //assume command
        $command = $input_1;
    }
}

$section = isset($page[2]) ? $page[2] : 'Summary';

if ($solo){ 
    // $section       = get_input('section');
    $jot           = get_entity($jot_guid);
    $subtype       = $jot->getSubtype();
    $aspect        = $jot->aspect;
    $item          = get_entity($jot->asset ?: ($jot->item_guid ?: $jot->guid));
    $item_guid     = $item->guid;
    $owner_guid    = $item->owner_guid;
    $owner_item    = get_entity($owner_guid);
//    elgg_set_context('view_jot');
    $plugins_path  = elgg_get_plugins_path ();
    
    if ($subtype == 'experience'){
        if ($aspect == 'instruction'){
            $selected = 'Instructions';
        }
        else {
            $selected = ucfirst($aspect);
        }
    }
     
    $view = elgg_view_exists("object/$subtype") ? "object/$subtype" : "object/jot"; 
    
    if (!$jot) {
    	register_error(elgg_echo("jot not found: {$jot_guid}"));
    	REFERRER;
    }
    	$category = $item->category ?: $item->marketcategory;                                        $display .= '$category: '.$category.'<br>';
	    $category_name = get_entity($category)->title ?: elgg_echo("market:category:{$category}");                                      
	    $category_set = hypeJunction\Categories\get_hierarchy($category, true, true);
    
    $tabs = elgg_view('jot/menu', array('guid' =>$jot_guid, 'this_section' => $section, 'aspect' => $subtype)); // path: mod\jot\views\default\jot\menu.php
    
//    group_gatekeeper();
    
    $options = array(
    	'type'      => 'object',
    	'subtype'   => $subtype,
    	'full_view' => true,
    );
    
    $container = $jot->getOwnerEntity();
    if (!$container) {
    }
    
    $title = $jot->title;
    
    	if ($aspect == 'receipt'){
    /* Raising the form in a lightbox prevents the date picker from coming up.  Must resolve before using popup window for returns.
     * 		$view_menu[0] = ElggMenuItem::factory(array('name'           => '00return',
    												'text'               => 'Return items ...', 
    											    'class'              => 'elgg-lightbox',
    											    'data-colorbox-opts' => '{"width":500, "height":525}',
    								                'href'               => "jot/box/$jot_guid/$aspect/return"));
    */		$view_menu[0] = new ElggMenuItem('00return', 'Return items ...', "jot/edit/$jot_guid/return");
    		elgg_register_menu_item('page', $view_menu[0]);
    	}

	if ($owner_item->getType() == 'user'){
		elgg_push_breadcrumb($owner_item->name, "queb?z=$owner_guid");
	}
	if ($owner_item->getType() == 'group' ){
		elgg_push_breadcrumb($owner_item->name, "queb?z=$owner_guid");
	}
	elgg_push_breadcrumb(elgg_echo('market:title'), "queb");

    if (!empty($category_set)){
        foreach ($category_set as $category_guid){
            $this_category = get_entity($category_guid);
            elgg_push_breadcrumb($this_category->title, $this_category->getURL());
        }
    }
    jot_prepare_parent_breadcrumbs($jot);
    elgg_push_breadcrumb($item->title);
	
    // Display it
    $content = elgg_view_list_item($jot, array('full_view' => true, 'this_section'=>$section, 'selected'=>$selected));
    $content .= elgg_view_comments($jot);
}
if ($command == 'shelf'){
    $title = 'Bulk Transfer';
    $content = 'transfer from shelf';
}
$testing = "<div id='fb-root'></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1390736364334124';
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>";
// @TODO change
$sidebar = elgg_view("$plugins_path/market/sidebar/navigation");

$params = array(
		'filter'      => $tabs,
        'header'      => $subtype,//.$testing,
		'content'     => $content,
		'title'       => $title,
		'sidebar'     => $sidebar,
		);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
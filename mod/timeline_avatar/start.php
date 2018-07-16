<?php

// set default icon sizes - can be overridden in settings.php or with plugin   timlineavatar

global $CONFIG;
/*
if (!isset($CONFIG->coverphoto_sizes)) {  //if (!in_array($size, array('babu', 'nyanya', 'dada', 'kaka', 'shangazi', 'mjomba')))

$coverphoto_sizes = array(
                'babu' => array('w' => 320, 'h' => 95, 'square' => FALSE, 'upscale' => FALSE),
                'nyanya' => array('w' => 900, 'h' =>200, 'square' => FALSE, 'upscale' => FALSE),
                'dada' => array('w' => 320, 'h' => 180, 'square' => FALSE, 'upscale' => FALSE),
                'kaka' => array('w' => 200, 'h' => 200, 'square' => FALSE, 'upscale' => FALSE),
                'shangazi' => array('w' => 851, 'h' => 315, 'square' => FALSE, 'upscale' => FALSE),
                'mjomba' => array('w' => 990, 'h' => 288, 'square' => FALSE, 'upscale' => FALSE),
            );
elgg_set_config('coverphoto_sizes', $coverphoto_sizes);


}
*/
elgg_register_event_handler('init', 'system', 'timeline_avatar_init');


/**
* Init timeline_avatar plugin.
*/
function timeline_avatar_init() {


	// Let us register our classes

	 $path = elgg_get_plugins_path() . 'timeline_avatar/';

	// Register a pagehandler
  	elgg_register_page_handler('coveravatar', 'cover_photo_page_handler');
  
  	elgg_register_page_handler('timelinecover', 'timeline_avatar_handler'); // timeline page handler
 
	// Register a pagehandler for coveravatar action remove user timeline_avatar

  	elgg_register_page_handler('actions', 'actions_photo_page_handler');

 	$owner = elgg_get_page_owner_entity();

	if ($owner) {
 	
 	
 	// Add a menu item to the main site menu
 	elgg_register_menu_item('site', ElggMenuItem::factory(array(
	 	'name' => 'coveravatar',
 		'href' => "/coveravatar/edit/$owner->username ",
		'text' => elgg_echo('timline:coverphotos'),
  		)));
  		 
  		    }

	elgg_register_library('elgg:libtimlineavatar', elgg_get_plugins_path() . 'timeline_avatar/lib/timlineavatar.php');

	// let us load our elgg libraty  timeline_avatar/lib/timlineavatar.php
 	elgg_load_library('elgg:libtimlineavatar');

	elgg_register_library('elgg:libtimlinefilestore', elgg_get_plugins_path() . 'timeline_avatar/lib/timelinefilestore.php');

	// let us load our elgg libraty  timeline_avatar/lib/timelinefilestore.php
 	elgg_load_library('elgg:libtimlinefilestore');


	elgg_register_library('elgg:libtimlineimagelib', elgg_get_plugins_path() . 'timeline_avatar/lib/php_image_magician.php');

	// let us load our elgg libraty  timeline_avatar/lib/timlineavatar.php
	
 	elgg_load_library('elgg:libtimlineimagelib');






	// add a site navigation item
	elgg_extend_view('core/avatar/upload', 'maincore/timlineavatar/upload',600);
	// add to the main css
	 elgg_extend_view('css/elgg', 'css/timlineavatar');



 	$smartphone_css_dir =  "/mod/timeline_avatar/vendors/css/coverphotocss.php";
        
	 elgg_register_css('coverphotocss', $smartphone_css_dir );
 	elgg_load_css('coverphotocss'); // commented so that we do not load the css on every page
      


	$timeline_css_dir =  "/mod/timeline_avatar/vendors/css/covertimeline.php";
       elgg_register_css('covertimeline', $timeline_css_dir );
   	//   elgg_load_css('covertimeline'); // commented so that we do not load the css on every page  

 	// Register JS

      	// register external JS libraries
		elgg_register_js("timeline_js", "mod/timeline_avatar/vendors/js/timeline.js");
		elgg_register_js("blocksit_js", "mod/timeline_avatar/vendors/js/blocksit.js");
		 elgg_register_js("jquery_js", "mod/timeline_avatar/vendors/js/jquery.js");
	
	
		 elgg_load_js('timeline_js');
		 elgg_load_js('blocksit_js');
		// elgg_load_js('jquery_js'); // TM: Has conflict with long button and Admin plugin drag and drop Elgg uses 1.10.1 jquery now

		// register external CSS
		elgg_register_css("timelinestyle_css", 	"mod/timeline_avatar/vendors/css/timelinestyle.css");
		
                 elgg_load_css('timelinestyle_css'); // commented so that we do not load the css on every page

	// register actions
	$action_romove_path = elgg_get_plugins_path() . 'timeline_avatar/actions';
	// register actions
	$action_path = elgg_get_plugins_path() . 'timeline_avatar/actions/timelineavatar';

	elgg_register_action('timlineavatar/upload', "$action_path/upload.php");
	//elgg_register_action("timelineavatar/remove", "$action_path/remove.php"); // __DIR__ . 
	elgg_register_action("timelineavatar/remove",  "$action_romove_path/remove.php");  
        elgg_register_action('timlineavatar/crop', "$action_path/crop.php");



	// Register the user type

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'elgg_users_setup_entity_menu', 501);

	// entity menu

	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'elgg_user_coverphoto_hover_menu');
	//elgg_register_plugin_hook_handler('register', 'menu:entity', 'blog_entity_menu_setup');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'blog_ecml_views_hook');

	elgg_register_event_handler('pagesetup', 'system', 'users_coverphoto_pagesetup', 101);


}

// Timeline page
function timeline_avatar_handler($page) {

  	if (isset($page[0])) {
	$username = $page[0];
	$user = get_user_by_username($username);
	elgg_set_page_owner_guid($user->guid);
	}
	// short circuit if invalid or banned username
	if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
	register_error(elgg_echo('profile:notfound'));
	forward();
}

require dirname(__FILE__) . '/pages/timelinecover.php';

	$action = NULL;
	if (isset($page[1])) {
	$action = $page[1];
	}


	switch ($action) {
	case 'timelinecover':
	// use for the core profile edit page
	global $CONFIG;
	require dirname(__FILE__) . 'pages/timelinecover.php';
	break;

	default:
	if (elgg_is_logged_in()) {
		require dirname(__FILE__) . '/pages/timelinecover.php';
	} else {
		require dirname(__FILE__) . '/pages/timelinecover.php';
	}
	break;
	}

	//require_once("$pages/timelinecover.php");
	return true;
}

/**
 * Sets up user-related menu items
 *
 * @return void
 * @access private
 */
function users_coverphoto_pagesetup() {

	$owner = elgg_get_page_owner_entity();
//	$viewer = elgg_get_logged_in_user_entity();

	if ($owner) {
	
	
		elgg_register_menu_item('page', array(
			'name' => 'edit_coveravatar',
			'href' => "coveravatar/edit/{$owner->username}",
			'text' => elgg_echo('coveravatar:edit'),
			'contexts' => array('coverphoto_edit'),
			'contexts' => array('profile_edit'),  
			// 'contexts' => array('coverphoto'),  
		));

		elgg_register_menu_item('page', array(
			'name' => 'edit_coverphoto',
			'href' => "coverphoto/{$owner->username}/edit",
			'text' => elgg_echo('coverphoto:edit'),
			'contexts' => array('coverphoto_edit'),
			'contexts' => array('profile_edit'),
			// 'contexts' => array('coveravatar'),  
		));
	}

	
}

/**
 * Setup the default user hover menu
 * @access private
 */
function elgg_user_coverphoto_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	/* @var ElggUser $user */


	if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {

			$url = "coverphoto/$user->username/edit";
			$item = new ElggMenuItem('coverphoto:edit', elgg_echo('coverphoto:edit'), $url);
			$item->setSection('action');
			$return[] = $item;

			$url = "coveravatar/edit/$user->username";
			$item = new ElggMenuItem('coveravatar:edit', elgg_echo('coveravatar:edit'), $url);
			$item->setSection('action');
			$return[] = $item;
		}
	}
	

	// prevent admins from banning or deleting themselves
	if (elgg_get_logged_in_user_guid() == $user->guid) {
		return $return;
	}


	if (elgg_is_admin_logged_in()) {
	//	$actions = array();

		$url = "coverphoto/$user->username/edit";
		$item = new ElggMenuItem('coverphoto:edit', elgg_echo('coverphoto:edit'), $url);
		$item->setSection('admin');
		$return[] = $item;
	
	}

	return $return;
}


function cover_photo_page_handler($page) {

  
 	 global $CONFIG;
  
	$plugin_path = elgg_get_plugins_path();

	$pages = $plugin_path . 'timeline_avatar/pages/coveravatar';


	$user = get_user_by_username($page[1]);
	if ($user) {
	elgg_set_page_owner_guid($user->getGUID());
}

	if ($page[0] == 'edit') {
		require_once("$pages/edit.php");
		return true;
		} else {
		set_input('size', $page[2]);
		require_once("$pages/view.php");
		return true;
		}
		return false;
  
  
}


// This will make  shure that our timeline_avatar remove avatar works without page not found error

function actions_photo_page_handler($timelineaction) {
	if (!isset($timelineaction[0])) {
 	$timelineaction[0] = 'remove';
}
	$plugin_route = elgg_get_plugins_path();
	$pageofaction = $plugin_route . 'timeline_avatar/actions';
	switch ($timelineaction[0]) {
      		case 'remove':
   	 include "$pageofaction/remove.php";
	 break;
 default:
  return false;
 
 }
  return true;
}

elgg_register_event_handler('init', 'system', 'coverphoto_init', 2);

// Metadata on users needs to be independent
// outside of init so it happens earlier in boot. See #3316
register_metadata_as_independent('user');

/**
* coverphoto init function  timelineicon
*/
function coverphoto_init() {

//elgg_register_library('elgg:libtimlineavatar', elgg_get_plugins_path() . 'timeline_avatar/lib/timlineavatar');


// Register a URL handler for users - this means that coverphoto_url()
// will dictate the URL for all ElggUser objects
// elgg_register_entity_url_handler('user', 'all', 'coverphoto_url'); // this will make the coverphot be the default page for all users 
elgg_register_entity_url_handler('user', 'coverphoto', 'coverphoto_url');


elgg_register_plugin_hook_handler('entity:timelineicon:url', 'user', 'coverphoto_override_avatar_url');
elgg_unregister_plugin_hook_handler('entity:timelineicon:url', 'user', 'user_avatar_hook');


elgg_register_simplecache_view('timelineicon/user/default/babu');
elgg_register_simplecache_view('timelineicon/user/default/nyanya');
elgg_register_simplecache_view('timelineicon/user/default/dada');
elgg_register_simplecache_view('timelineicon/user/default/kaka');
elgg_register_simplecache_view('timelineicon/user/default/shangazi');
elgg_register_simplecache_view('timelineicon/user/default/mjomba');

elgg_register_page_handler('coverphoto', 'coverphoto_page_handler');

elgg_extend_view('page/elements/head', 'coverphoto/metatags');
elgg_extend_view('css/elgg', 'coverphoto/css');
elgg_extend_view('js/elgg', 'coverphoto/js');

// allow ECML in parts of the coverphoto
elgg_register_plugin_hook_handler('get_views', 'ecml', 'coverphoto_ecml_views_hook');

// allow admins to set default widgets for users on coverphotos
elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'coverphoto_default_widgets_hook');
}

/**
* coverphoto page handler
*
* @param array $page Array of URL segments passed by the page handling mechanism
* @return bool
*/
function coverphoto_page_handler($page) {


      // let us load our elgg libraty  timeline_avatar/lib/timlineavatar.php
	elgg_load_library('elgg:libtimlineavatar');

if (isset($page[0])) {
$username = $page[0];
$user = get_user_by_username($username);
elgg_set_page_owner_guid($user->guid);
} elseif (elgg_is_logged_in()) {
forward(elgg_get_logged_in_user_entity()->getURL());
}

// short circuit if invalid or banned username
if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
register_error(elgg_echo('coverphoto:notfound'));
forward();
}

$action = NULL;
if (isset($page[1])) {
$action = $page[1];
}

if ($action == 'edit') {
// use the core profile edit page
//$base_dir = elgg_get_root_path();
//require "{$base_dir}pages/profile/edit.php";
$plugin_path = elgg_get_plugins_path();
$pages = $plugin_path . 'timeline_avatar/pages/coverphoto';
 require "$pages/edit.php";



return true;
}


// main profile page
$params = array(
'content' => elgg_view('coverphoto/wrapper'),
'num_columns' => 3,
);

$content = elgg_view_layout('widgets', $params);

$body = elgg_view_layout('one_column', array('content' => $content));
echo elgg_view_page($user->name, $body);
return true;

}

/**
* Profile URL generator for $user->getUrl();
*
* @param ElggUser $user
* @return string User URL
*/
function coverphoto_url($user) {
return elgg_get_site_url() . "coverphoto/" . $user->username;
}

/**
* Use a URL for avatars that avoids loading Elgg engine for better performance
*
* @param string $hook
* @param string $entity_type
* @param string $return_value
* @param array $params
* @return string
*/
function coverphoto_override_avatar_url($hook, $entity_type, $return_value, $params) {

// if someone already set this, quit
if ($return_value) {
return null;
}

$user = $params['entity'];
$size = $params['size'];

if (!elgg_instanceof($user, 'user')) {
return null;
}

$user_guid = $user->getGUID();
$icon_time = $user->coverphototime;  //TM: icontime:coverphototime




if (isset($user->coverphototime)) {
return "coveravatar/view/$user->username/$size/$user->coverphototime";
} else {
return "_graphics/icons/user/default{$size}.gif";
}

if (!$icon_time) { 
return "mod/timeline_avatar/graphics/timelineicons/user/default{$size}.gif"; // mod/timeline_avatar/graphics/timelineicons/default
}

if ($user->isBanned()) {
return null;
}

$filehandler = new ElggFile();
$filehandler->owner_guid = $user_guid;
$filehandler->setFilename("coverphoto/{$user_guid}{$size}.jpg");

try {
if ($filehandler->exists()) {
$join_date = $user->getTimeCreated();
return "mod/timeline_avatar/timelineicondirect.php?lastcache=$icon_time&joindate=$join_date&guid=$user_guid&size=$size";
}
} catch (InvalidParameterException $e) {
elgg_log("Unable to get profile timelineicon for user with GUID $user_guid", 'ERROR');
return "mod/timeline_avatar/graphics/timelineicons/$size.png";
}

return null;
}

/**
* Parse ECML on parts of the coverphoto
*
* @param string $hook
* @param string $entity_type
* @param array $return_value
* @return array
*/
function coverphoto_ecml_views_hook($hook, $entity_type, $return_value) {
$return_value['profile/profile_content'] = elgg_echo('coverphoto');

return $return_value;
}

/**
* Register profile widgets with default widgets
*
* @param string $hook
* @param string $type
* @param array $return
* @return array
*/
function coverphoto_default_widgets_hook($hook, $type, $return) {
$return[] = array(
'name' => elgg_echo('coverphoto'),
'widget_context' => 'coverphoto',
'widget_columns' => 3,

'event' => 'create',
'entity_type' => 'user',
'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
);

return $return;
}

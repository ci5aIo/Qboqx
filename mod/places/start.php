<?php
/**
 * Elgg Places
 *
 * @package ElggPlaces
 */

elgg_register_event_handler('init', 'system', 'places_init');

/**
 * Initialize the Places plugin.
 *
 */
function places_init() {

	// register a library of helper functions
	elgg_register_library('elgg:places', elgg_get_plugins_path() . 'places/lib/places.php');

	$item = new ElggMenuItem('places', elgg_echo('places'), 'places/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('places', 'places_page_handler');

	// Register a url handler
	elgg_register_plugin_hook_handler('entity:url', 'object', 'places_set_url');
	elgg_register_plugin_hook_handler('entity:url', 'object', 'places_set_url');
	elgg_register_plugin_hook_handler('extender:url', 'annotation', 'places_set_revision_url');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'places/actions';
	$jot_base    = elgg_get_plugins_path() . 'jot/actions';
	elgg_register_action("places/edit"             , "$action_base/places/edit.php");
	elgg_register_action("places/set"              , "$action_base/places/set.php");
	elgg_register_action("places/delete"           , "$action_base/places/delete.php");
	elgg_register_action("jot/attach"              , "jot_base/jot/attach.php");
	elgg_register_action("jot/detach"              , "$jot_base/jot/detach.php");
	elgg_register_action("jot/add/element/"        , "$jot_base/add/element.php");
	elgg_register_action("annotations/place/delete", "$action_base/annotations/place/delete.php");

	// Extend the main css view
	elgg_extend_view('css/elgg', 'places/css');

	elgg_define_js('jquery.treeview', array(
		'src' => '/mod/places/vendors/jquery-treeview/jquery.treeview.min.js',
		'exports' => 'jQuery.fn.treeview',
		'deps' => array('jquery'),
	));
	$css_url = 'mod/places/vendors/jquery-treeview/jquery.treeview.css';
	elgg_register_css('jquery.treeview', $css_url);

	// Register entity type for search
	elgg_register_entity_type('object', 'place');
	elgg_register_entity_type('object', 'place_top');

	// Register for notifications
	elgg_register_notification_event('object', 'place');
	elgg_register_notification_event('object', 'place_top');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:place', 'places_prepare_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:place_top', 'places_prepare_notification');

	// add to groups
	add_group_tool_option('places', elgg_echo('groups:enableplaces'), true);
	elgg_extend_view('groups/tool_latest', 'places/group_module');

	//add a widget
	elgg_register_widget_type('places', elgg_echo('places'), elgg_echo('places:widget:description'));

	// Language short codes must be of the form "places:key"
	// where key is the array key below
	elgg_set_config('places', array(
		'title'           => 'text',
		'description'     => 'longtext',
		'address'         => 'text',
		'city'            => 'text',
		'state'           => 'text',
		'zip'             => 'text',
		'tags'            => 'tags',
		'parent_guid'     => 'parent',
		'access_id'       => 'access',
		'write_access_id' => 'write_access',
	));
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'places_owner_block_menu');
//@EDIT 2016-03-18 - SAJ
//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'places_owner_block_menu');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'places_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'places_container_permission_check');

	// icon url override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'places_icon_url_override');

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'places_entity_menu_setup');

	// register ecml views to parse
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'places_ecml_views_hook');
}

/**
 * Dispatcher for places.
 * URLs take the form of
 *  All places:        places/all
 *  User's places:     places/owner/<username>
 *  Friends' places:   places/friends/<username>
 *  View place:        places/view/<guid>/<title>
 *  New place:         places/add/<guid> (container: user, group, parent)
 *  Edit place:        places/edit/<guid>
 *  History of place:  places/history/<guid>
 *  Revision of place: places/revision/<id>
 *  Group places:      places/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $place
 * @return bool
 */
function places_page_handler($place) {

	elgg_load_library('elgg:places');

	elgg_push_breadcrumb(elgg_echo('places'), 'places/all');
	$base_dir = elgg_get_plugins_path() . 'places/pages/places';

	if (!isset($place[0])) {
		$place[0] = 'all';
	}
	$place_type = $place[0];
	switch ($place_type) {
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'friends':
			include "$base_dir/friends.php";
			break;
		case 'view':
			set_input('guid',    $place[1]);
			set_input('title',   $place[2]);
			set_input('section', $place[3]);
			include "$base_dir/view.php";
			break;
		case 'add':
			set_input('guid', $place[1]);
			include "$base_dir/new.php";
			break;
		case 'edit':
			set_input('guid', $place[1]);
			include "$base_dir/edit.php";
			break;
		case 'set':
			gatekeeper();
			set_input('guid', $place[1]);
			include "$base_dir/set.php";
			break;
		case 'show_more':
		    gatekeeper();
		    set_input('guid', $place[1]);
		    include "$base_dir/show_more.php";
		    break;
		case 'group':
			include "$base_dir/owner.php";
			break;
		case 'history':
			set_input('guid', $place[1]);
			include "$base_dir/history.php";
			break;
		case 'revision':
			set_input('id', $place[1]);
			include "$base_dir/revision.php";
			break;
		case 'all':
			include "$base_dir/world.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Override the page url
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function places_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (places_is_place($entity)) {
		$title = elgg_get_friendly_title($entity->title);
		return "places/view/$entity->guid/$title";
	}
}

/**
 * Override the page annotation url
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function places_set_revision_url($hook, $type, $url, $params) {
	$annotation = $params['extender'];
	if ($annotation->getSubtype() == 'place') {
		return "places/revision/$annotation->id";
	}
}

/**
 * Override the default entity icon for places
 *
 * @return string Relative URL
 */
function places_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	if (places_is_place($entity)) {
		switch ($params['size']) {
			case 'topbar':
			case 'tiny':
			case 'small':
				return 'mod/places/images/places.gif';
				break;
			default:
				return 'mod/places/images/places_lrg.gif';
				break;
		}
	}
}

/**
 * Add a menu item to the user ownerblock
 */
function places_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "places/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('places', elgg_echo('places'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->places_enable != "no") {
			$url = "places/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('places', elgg_echo('places:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add links/info to entity menu particular to places plugin
 */
function places_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'places') {
		return $return;
	}

	// remove delete if not owner or admin
	if (!elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $entity->getOwnerGuid()) {
		foreach ($return as $index => $item) {
			if ($item->getName() == 'delete') {
				unset($return[$index]);
			}
		}
	}

	$options = array(
		'name' => 'history',
		'text' => elgg_echo('places:history'),
		'href' => "places/history/$entity->guid",
		'priority' => 150,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Prepare a notification message about a new place
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification
 */
function places_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$descr = $entity->description;
	$title = $entity->title;

	$notification->subject = elgg_echo('places:notify:subject', array($title), $language);
	$notification->body = elgg_echo('places:notify:body', array(
		$owner->name,
		$title,
		$descr,
		$entity->getURL(),
	), $language);
	$notification->summary = elgg_echo('places:notify:summary', array($entity->title), $language);

	return $notification;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function places_write_permission_check($hook, $entity_type, $returnvalue, $params) {
	if (!places_is_place($params['entity'])) {
		return null;
	}
	$entity = $params['entity'];
	/* @var ElggObject $entity */

	$write_permission = $entity->write_access_id;
	$user = $params['user'];

	if ($write_permission && $user) {
		switch ($write_permission) {
			case ACCESS_PRIVATE:
				// Elgg's default decision is what we want
				return null;
				break;
			case ACCESS_FRIENDS:
				$owner = $entity->getOwnerEntity();
				if (($owner instanceof ElggUser) && $owner->isFriendsWith($user->guid)) {
					return true;
				}
				break;
			default:
				$list = get_access_array($user->guid);
				if (in_array($write_permission, $list)) {
					// user in the access collection
					return true;
				}
				break;
		}
	}
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function places_container_permission_check($hook, $entity_type, $returnvalue, $params) {
	if (elgg_get_context() != "places") {
		return null;
	}
	if (elgg_get_page_owner_guid()
			&& can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) {
		return true;
	}
	if ($place_guid = get_input('place_guid', 0)) {
		$entity = get_entity($place_guid);
	} elseif ($parent_guid = get_input('parent_guid', 0)) {
		$entity = get_entity($parent_guid);
	}
	if (isset($entity) && places_is_place($entity)) {
		if (can_write_to_container(elgg_get_logged_in_user_guid(), $entity->container_guid)
				|| in_array($entity->write_access_id, get_access_list())) {
			return true;
		}
	}
}

/**
 * Return views to parse for places.
 *
 * @param string $hook
 * @param string $entity_type
 * @param array  $return_value
 * @param array  $params
 *
 * @return array
 */
function places_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/place'] = elgg_echo('item:object:place');
	$return_value['object/place_top'] = elgg_echo('item:object:place_top');

	return $return_value;
}

/**
 * Is the given value a place object?
 *
 * @param mixed $value
 *
 * @return bool
 * @access private
 */
function places_is_place($value) {
	return ($value instanceof ElggObject) && in_array($value->getSubtype(), array('place', 'place_top'));
}

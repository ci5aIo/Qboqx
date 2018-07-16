<?php
/**
 * Adapted from Pages function library
 */

/**
 * Prepare the add/edit form variables
 * Experimental - Altered pages_prepare_form_vars() for QuebX
 *
 * @param ElggObject $page
 * @return array
 */
function asset_prepare_form_vars($item = null, $parent_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $item,
		'parent_guid' => $parent_guid,
	);

	if ($item) {
		foreach (array_keys($values) as $field) {
			if (isset($item->$field)) {
				$values[$field] = $item->$field;
			}
		}
	}

	if (elgg_is_sticky_form('asset')) {
		$sticky_values = elgg_get_sticky_values('asset');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('asset');

	return $values;
}

/**
 * Prepare the file upload/edit form variables
 *
 * @param JotPluginFile $file
 * @return array
 */
function jot_prepare_file_vars($file = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $file,
	);

	if ($file) {
		foreach (array_keys($values) as $field) {
			if (isset($file->$field)) {
				$values[$field] = $file->$field;
			}
		}
	}

	if (elgg_is_sticky_form('file')) {
		$sticky_values = elgg_get_sticky_values('file');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('file');

	return $values;
}

/**
 * Recurses the lineage tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $issue Issue entity
 */
function jot_prepare_parent_breadcrumbs($item) {
    $parents = array();
    $parent = get_entity($item->parent_guid ?: $item->container_guid);
	while ($parent) {
		array_push($parents, $parent);
		$parent = get_entity($parent->parent_guid ?: $parent->container_guid);
	}
	while ($parents) {
		$parent = array_pop($parents);
		$subtype = $parent->getSubtype();
		if ($parent->type == 'object' ){
			if ($subtype == 'market'){
			  $path = "market/view/$parent->guid";
			}
			if ($subtype == 'jot' 
			 || $subtype == 'issue'
			 || $subtype == 'cause'
			 || $subtype == 'observation'
			 || $subtype == 'insight'
			 || $subtype == 'effort'
			 || $subtype == 'experience'
			 ){
			 	$path = "jot/view/$parent->guid/Details";
			 }
		}

		elgg_push_breadcrumb($parent->title, $path);
	}
}
function jot_prepare_container_breadcrumbs($item) {
	if ($item && $item->container_guid) {
		$containers = array();
		$container = get_entity($item->container_guid);
		while ($container) {
			array_push($containers, $container);
			$container = get_entity($container->container_guid);
		}
		while ($containers) {
			$container = array_pop($containers);
			$subtype = $container->getSubtype();
			if ($container->type == 'object' ){
				switch ($subtype){
					case 'market':
						$path = "market/view/$container->guid";
						break;
					case 'jot':
						break;
					case 'issue':
						break;
					case 'cause':
						break;
					case 'observation':
						break;
					case 'insight':
						break;
					case 'effort':
						break;
					case 'transfer':
				 		$path = "jot/view/$container->guid";
						break;
					default:
						break;
				 }
			}
			elgg_push_breadcrumb($container->title, $path);
		}
		$subtype = $item->getSubtype();
		if ($item->type == 'object' ){
				switch ($subtype){
					case 'market':
						$path = "market/view/$item->guid";
						break;
					case 'jot':
						break;
					case 'issue':
						break;
					case 'cause':
						break;
					case 'observation':
						break;
					case 'insight':
						break;
					case 'effort':
						break;
					case 'transfer':
				 		$path = "jot/view/$item->guid";
						break;
					default:
						break;
				 }
			}
		elgg_push_breadcrumb($item->title, $path);
	}
}
function jot_prepare_brief_view_vars($subtype = null,
                                     $jot     = null,
                                     $section = null,
									 $aspect  = null) {
	$referrer = $vars['referrer'];
	$asset = get_entity($jot->asset);
	$container = get_entity($jot->container_guid);
	$asset_link = elgg_view('output/url', array(
	   'href' => "market/view/$asset->guid",
	   'text' => $asset->title,
	));
	$container_link = elgg_view('output/url', array(
	   'href' => "jot/view/$container->guid",
	   'text' => $container->title,
	));
	$assigned_to_link = elgg_view('output/url', array(
	   'href' => "groups/profile/$jot->assigned_to",
	   'text' => get_entity($jot->assigned_to)->name,
	));
	switch ($subtype){
		case 'observation':
			switch ($jot->state){
				case  1: $state = 'Discover'; break;
				case  2: $state = 'Resolve' ; break;
				case  3: $state = 'Assigned'; break;
				case  4: $state = 'Accept'  ; break;
				case  5: $state = 'Complete'; break;
				default: $state = 'Discover'; break;
			}
			switch ($section) {
				case 'Summary':
					$values = array(
			       'asset'         => null,
			       'description'   => null,
			       'state'         => null,
			       'assigned_to'   => null,
				);
				    break;
				case 'Details':
				    break;
				case 'Resolution':
				    break;
				case 'Ownership':
				    break;
				case 'Discussion':
				    break;
				case 'Gallery':
				    break;
				default:
					$values = array(
				       'Title'         => $jot->title,
				       'Description'   => $jot->description,
				       'Status'        => $jot->status,
				       'Observation #' => $jot->number,
				       'Asset'         => $asset_link,
				       'Tags'          => $jot->tags,
					);
					break;
			}
			break;
		case 'supply':
			$values = array(
		       'title'       => '',
		       'description' => '',
		       'on_hand'     => null,
		       'part_no'     => $jot->number,
			);
			break;
		case 'issue':
			$values = array(
		       'title'       => '',
		       'description' => '',
		       'status'      => '',
		       'issue_no'    => $jot->number,
		       'asset'       => $asset_link,
		       'tags'        => '',
			);
			break;
		case 'cause':
			break;
		case 'insight':
			switch ($section) {
				case 'Summary':
					$values = array(
			       'title'          => '',
			       'description'    => '',
			       'status'         => '',
			       'observation_no' => $jot->number,
				);
				    break;
				case 'Details':
				    break;
				case 'Resolution':
				    break;
				case 'Ownership':
				    break;
				case 'Discussion':
				    break;
				case 'Gallery':
				    break;
				default:
					$values = array(
			       'Title'         => $jot->title,
			       'Description'   => $jot->description,
			       'Status'        => $jot->status,
			       'Observation #' => $jot->number,
			       'Asset'         => $asset_link,
			       'Tags'          => $jot->tags,
				);
					break;
			}
			break;
		case 'effort':
			switch ($jot->state) {
				case '1':  $state = 'Discover';
				   break;
				case '2':  $state = 'Diagnose';
				   break;
				case '3':  $state = 'Accept';
				   break;
				case '4':  $state = 'Repair';
				   break;
				case '5':  $state = 'Fixed';
				   break;
			       }
			switch ($section) {
				case 'Summary':
					$values = array(
			       'Observation'   => $container_link,
			       'Effort'        => $jot->title,
			       'State'         => $state,
				);
				    break;
				case 'Details':
				    break;
				case 'Resolution':
				    break;
				case 'Ownership':
				    break;
				case 'Discussion':
				    break;
				case 'Gallery':
				    break;
				default:
					$values = array(
			       'Title'         => $jot->title,
			       'Description'   => $jot->description,
			       'Status'        => $jot->status,
			       'Observation #' => $jot->number,
			       'Asset'         => $asset_link,
			       'Tags'          => $jot->tags,
				);
					break;
			}
			break;
		case 'transfer':
			switch ($aspect) {
				case 'receipt':
					$values = array(
						'title'           => null,
						'merchant'        => null,
						'cashier'         => null,
						'moment'   => null,
						'total_cost'      => null,
						'shipping_cost'   => null,
						'payment_account' => null,
						'receipt_no'      => null,
						'document_no'     => null,
						'purchased_by'    => null,
						'aspect'          => null,
						'aspect_02'       => null,
						'access_id'       => ACCESS_DEFAULT,
						'write_access_id' => ACCESS_DEFAULT,
						'tags'            => null,
						'guid'            => null,
						'container_guid'  => elgg_get_page_owner_guid(),
						'asset'           => null,
					); 
					break;
				case 'ownership':
					$values = array(
						'title'           => null,
						'transfer_to'     => null,
						'value_received'  => null,
						'offer_date'      => null,
						'request_date'    => null,
						'acceptance_date' => null,
						'delivery_date'   => null,
						'received_date'   => null,
						'selling_cost'    => null,
						'deposit_account' => null,
						'number'          => null,
						'aspect'          => null,
						'aspect_02'       => null,
						'access_id'       => null,
						'write_access_id' => null,
						'tags'            => null,
						'guid'            => null,
						'container_guid'  => null,
						'asset'           => null,
					); 
					break;
			}
	        break;
		default:
		    switch ($section) {
				case 'Summary':
					$values = array(
			       'asset'         => null,
			       'description'   => null,
			       'state'         => null,
			       'assigned_to'   => null,
				);
				    break;
				case 'Details':
				    break;
				case 'Resolution':
				    break;
				case 'Ownership':
				    break;
				case 'Discussion':
				    break;
				case 'Gallery':
				    break;
				default:
					$values = array(
				       'Title'         => $jot->title,
				       'Description'   => $jot->description,
				       'Status'        => $jot->status,
				       'Jot #' => $jot->number,
				       'Asset'         => $asset_link,
				       'Tags'          => $jot->tags,
					);
					break;
		    }
		}
		if ($jot) {
			foreach (array_keys($values) as $field) {
	//			if (isset($jot->$field)) {
					switch ($field) {
						case 'assigned_to':
							$values[$field] = $assigned_to_link;
							break;	
						case 'asset':
							$values[$field] = $asset_link;
							break;
/*						case 'aspect':
							$values[$field] = $jot->getSubtype();
							break;
						case 'aspect_02':
							$values[$field] = $jot->aspect;
							break;
*/						case 'referrer':
							$values[$field] = $referrer;
							break;
						case 'container_guid':
							$values[$field] = elgg_get_page_owner_guid();
							break;
						case 'access_id':
							$values[$field] = ACCESS_DEFAULT;
							break;
						case 'write_access_id':
							$values[$field] = ACCESS_DEFAULT;
							break;
						case 'state':
							$values[$field] = $state;
							break;
						default:
							$values[$field] = $jot->$field;
							break;
					}
	//			}
			}
		}
	
		if (elgg_is_sticky_form('jot')) {
			$sticky_values = elgg_get_sticky_values('jot');
			foreach ($sticky_values as $key => $value) {
				$values[$key] = $value;
			}
		}
	
		elgg_clear_sticky_form('jot');

return $values;
}
function jot_prepare_form_vars($subtype     = null,
                               $jot         = null, 
	                           $item_guid   = 0, 
	                           $referrer    = null, 
	                           $description = null,
							   $section     = null) {
	$ts = strtotime("now");
//experimental
/*
	$values = array(
		'aspect'                  => $subtype,
        'referrer'                => $referrer,
		'access_id'               => ACCESS_DEFAULT,
		'write_access_id'         => ACCESS_DEFAULT,
		'container_guid'          => elgg_get_page_owner_guid(),
		'guid'                    => $jot->getGuid(),
		'item_guid'               => $item_guid,
		'asset'                   => $item_guid,
		
	);
*/
	
	switch ($subtype){
		case 'observation':
			switch ($section) {
				case 'Summary':
				    break;
				case 'Details':
				    break;
				case 'Resolution':
				    break;
				case 'Ownership':
				    break;
				case 'Discussion':
				    break;
				case 'Gallery':
				    break;
				default:
					$values = array(
							'title' => '',
					        'description' => $description,
							'observation_type' => '',
							'number' => '',
							'observer' => elgg_get_logged_in_user_guid(),
							'element_type'=> 'observation',
							'aspect' => 'observation',
					        'referrer' => $referrer,
							'access_id' => ACCESS_DEFAULT,
							'write_access_id' => ACCESS_DEFAULT,
							'tags' => '',
							'container_guid' => elgg_get_page_owner_guid(),
							'guid' => null,
							'entity' => $observation,
							'item_guid' => $item_guid,
							'asset' => $item_guid,
						);
					break;
				
			}
			break;
		case 'insight':
				switch ($section) {
					case 'Summary':
					    break;
					case 'Details':
					    break;
					case 'Escallation':
					    break;
					case 'Ownership':
					    break;
					case 'Discussion':
					    break;
					case 'Gallery':
					    break;
					default:
						$values = array(
								'title' => '',
						        'description' => $description,
								'insight_type' => '',
								'number' => '',
								'observer' => elgg_get_logged_in_user_guid(),
							    'element_type'=> 'insight',
								'aspect' => 'insight',
						        'referrer' => $referrer,
								'access_id' => ACCESS_DEFAULT,
								'write_access_id' => ACCESS_DEFAULT,
								'tags' => '',
								'container_guid' => elgg_get_page_owner_guid(),
								'guid' => null,
								'entity' => $insight,
								'item_guid' => $item_guid,
								'asset' => $item_guid,
							);
						break;
				} 
			break;
		case 'cause':
				switch ($section) {
					case 'Summary':
					    break;
					case 'Details':
					    break;
					case 'Escallation':
					    break;
					case 'Ownership':
					    break;
					case 'Discussion':
					    break;
					case 'Gallery':
					    break;
					case 'Reports':
					    break;
					default:
						$values = array(
							'title' => '',
					        'description' => $description,
							'number' => 'text',
							'observer' => '',
							'aspect' => 'cause',
							'element_type'=> 'cause',
					        'referrer' => $referrer,
							'access_id' => ACCESS_DEFAULT,
							'write_access_id' => ACCESS_DEFAULT,
							'tags' => '',
							'container_guid' => elgg_get_page_owner_guid(),
							'guid' => null,
							'entity' => $cause,
							'item_guid' => $item_guid,
							'asset' => $item_guid,
							'root_cause' => $cause->root_cause,
						);
						break;
				}
			break;
		case 'issue':
			$values = array(
				'element_type'=> 'issue',
				'aspect' => 'issue',
		        'referrer' => $referrer,
		        'description' => $description,
				'access_id' => ACCESS_DEFAULT,
				'write_access_id' => ACCESS_DEFAULT,
				'container_guid' => elgg_get_page_owner_guid(),
				'guid' => null,
				'entity' => $issue,
				'item_guid' => $item_guid,
				'asset'           => $item_guid,
			); 
			break;
		case 'transfer':
			switch ($section) {
				case 'receive':
				case 'receipt':
					$values = array(
						'aspect'          => $section,
				        'referrer'        => $referrer,
						'access_id'       => ACCESS_DEFAULT,
						'write_access_id' => ACCESS_DEFAULT,
						'container_guid'  => elgg_get_page_owner_guid(),
						'item_guid'       => $item_guid,
				        'asset'           => $item_guid,
					); 
					break;
				case 'return':
					$values = array(
						'return_date'            => 'date',
						'return_no'              => 'text',
						'return_expiration_date' => 'date',
						'return_type'            => 'text',
						'return_shipping'        => 'text',
						'return_sales_tax'       => 'text',
						'action'                 => $section,
						'aspect'                 => $section,
						'referrer'               => $referrer,
						'access_id'              => ACCESS_DEFAULT,
						'write_access_id'        => ACCESS_DEFAULT,
						'container_guid'         => elgg_get_page_owner_guid(),
						'item_guid'              => $item_guid,
				        'asset'                  => $item_guid,
					); 
					break;
				case 'ownership':
					$values = array(
						'title'           => 'text',
						'transfer_to'     => 'text',
						'value_received'  => 'text',
						'offer_date'      => 'date',
						'request_date'    => 'date',
						'acceptance_date' => 'date',
						'delivery_date'   => 'date',
						'received_date'   => 'date',
						'selling_cost'    => 'text',
						'deposit_account' => 'dropdown',
						'number'          => 'text',
						'aspect'          => $section,
				        'referrer'        => $referrer,
						'access_id'       => ACCESS_DEFAULT,
						'write_access_id' => ACCESS_DEFAULT,
						'tags'            => 'tags',
						'container_guid'  => elgg_get_page_owner_guid(),
						'item_guid'       => $item_guid,
				        'asset'           => $item_guid,
					); 
					break;
			}
			break;	
		case 'project':
			$values = array(
				'title'                   => 'text',
				'start_date'              => 'date',
				'end_date'                => 'date',
				'number'                  => 'text',
				'status'                  => 'text',
				'assigned_to'             => 'text',
				'percent_done'            => 'text',
				'work_remaining'          => 'text',
				'aspect'                  => 'text',
		        'referrer'                => $referrer,
		        'description'             => 'longtext',
				'access_id'               => ACCESS_DEFAULT,
				'write_access_id'         => ACCESS_DEFAULT,
				'tags'                    => 'tags',
				'container_guid'          => elgg_get_page_owner_guid(),
				'guid'                    => null,
				'item_guid'               => $item_guid,
				'asset'                   => $item_guid,
			); 
			break;
 		case 'schedule':
			$values = array(
				'subtype'                 => 'maintenance',
				'element_type'            => 'schedule',
				'aspect'                  => $subtype,
		        'referrer'                => $referrer,
				'access_id'               => ACCESS_DEFAULT,
				'write_access_id'         => ACCESS_DEFAULT,
				'container_guid'          => elgg_get_page_owner_guid(),
				'guid'                    => null,
				'item_guid'               => $item_guid,
				'asset'                   => $item_guid,
				
			);
			break;
 		case 'experience':
			$values = array(
				'subtype'                 => $subtype,
		        'referrer'                => $referrer,
				'access_id'               => ACCESS_DEFAULT,
				'write_access_id'         => ACCESS_DEFAULT,
				'container_guid'          => elgg_get_page_owner_guid(),
				'guid'                    => $jot->guid,
				'item_guid'               => $item_guid,
				'asset'                   => $item_guid,
				
			);
			break;
  	default:
			$values = array(
				'aspect'                  => $subtype,
				'element_type'            => $subtype,
		        'referrer'                => $referrer,
				'access_id'               => ACCESS_DEFAULT,
				'write_access_id'         => ACCESS_DEFAULT,
				'container_guid'          => elgg_get_page_owner_guid(),
		        'guid'                    => $jot->getGuid(),
				'item_guid'               => $item_guid,
				'asset'                   => $item_guid,
			);
			break;
	}


    if ($jot) {
		foreach (array_keys($values) as $field) {
			if (isset($jot->$field)) {
				$values[$field] = $jot->$field;
			}
		}
	}

	foreach (array_keys($values) as $field) {
			switch ($field) {
				case 'referrer':
					$values[$field] = $referrer;
					break;
				case 'container_guid':
					$values[$field] = elgg_get_page_owner_guid();
					break;
				case 'access_id':
					$values[$field] = ACCESS_DEFAULT;
					break;
				case 'write_access_id':
					$values[$field] = ACCESS_DEFAULT;
					break;
				default:
//					$values[$field] = $jot->$field;
					break;
			}
	}
	
/*	if (elgg_is_sticky_form('jot')) {
		$sticky_values = elgg_get_sticky_values('jot');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('jot');
*/
	return $values;
}

/**
 * Registers a user, returning false if the username already exists
 * Pulled from engine\lib\users.php line 640
 *
 * @param string $username              The username of the new user
 * @param string $password              The password
 * @param string $name                  The user's display name
 * @param string $email                 The user's email address
 * @param bool   $allow_multiple_emails Allow the same email address to be
 *                                      registered multiple times?
 *
 * @return int|false The new user's GUID; false on failure
 * @throws RegistrationException
 */
function jot_register_user($username, $password, $name, $email, $allow_multiple_emails = false) {

	// no need to trim password.
	$username = trim($username);
	$name = trim(strip_tags($name));
	$email = trim($email);
	$password = generate_random_cleartext_password();
	

	// A little sanity checking
	if (empty($username)
			|| empty($password)
			|| empty($name)
			|| empty($email)) {
		return false;
	}

	// Make sure a user with conflicting details hasn't registered and been disabled
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	if (!validate_email_address($email)) {
		throw new RegistrationException(elgg_echo('registration:emailnotvalid'));
	}

	if (!validate_password($password)) {
		throw new RegistrationException(elgg_echo('registration:passwordnotvalid'));
	}

	if (!validate_username($username)) {
		throw new RegistrationException(elgg_echo('registration:usernamenotvalid'));
	}

	if ($user = get_user_by_username($username)) {
		throw new RegistrationException(elgg_echo('registration:userexists'));
	}

	if ((!$allow_multiple_emails) && (get_user_by_email($email))) {
		throw new RegistrationException(elgg_echo('registration:dupeemail'));
	}

	access_show_hidden_entities($access_status);

	// Create user
	$user = new ElggUser();
	$user->username = $username;
	$user->email = $email;
	$user->name = $name;
	$user->access_id = ACCESS_PUBLIC;
	$user->salt = _elgg_generate_password_salt();
	$user->password = $password; // store password in plain text, no encryption
//	$user->password = generate_user_password($user, $password);
	$user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
	$user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
	$user->language = get_current_language();
	if ($user->save() === false) {
		return false;
	}

	// Turn on email notifications by default
	set_user_notification_setting($user->getGUID(), 'email', true);

	return $user->getGUID();
}
/**
 * Is the given value a jot object?
 *
 * @param mixed $value
 *
 * @return bool
 * @access private
 */
function jot_is_jot($value) {
	return ($value instanceof ElggObject) && in_array($value->getSubtype(), array('jot', 'transfer', 'observation', 'issue', 'insight', 'cause'));
}

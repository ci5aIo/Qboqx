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
/*
function quebx_prepare_form_vars($item = null, $parent_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_PUBLIC,
		'write_access_id' => ACCESS_PUBLIC,
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

	if (elgg_is_sticky_form('market')) {
		$sticky_values = elgg_get_sticky_values('market');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('market');

	return $values;
}
*/
function market_prepare_brief_view_vars($item = null) {

	// input names => defaults
	$location  = $item->location;
	$container = $item->getContainerEntity();
	$subtype   = $container->getSubtype();
	if (is_numeric($location)){ // assume guid
		$place    = get_entity($location);
		switch($place->getSubtype()){
			case 'market':
				$stem = 'market/view';
				break;
			case 'item':
				$stem = 'market/view';
				break;
			case 'place':
				$stem = 'places/view';
				break;
		}
		$location_link = elgg_view('output/url', array(
			'href' => "$stem/$location",
			'text' => $place->title,
			));
	}
	if (!is_numeric($location)){ // assume label
		$location_link = $location;
	}
	if (($subtype == 'market' || $subtype == 'item') && !$location){
		$location_link = elgg_view('output/url', array(
			'href' => "market/view/$container->guid",
			'text' => $container->title,
			));		
	}
	$category = $item->marketcategory;
	switch ($category) {
		case 'car':
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model_no,
               'Part'         => $item->part_no,
               'SKU'          => $item->sku,
               'Location'     => $location_link,
			);
			break;
		case 'appliances':
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model_no,
               'Part'         => $item->part_no,
               'SKU'          => $item->sku,
               'Location'     => $location_link,
			);
			break;
		case 'auto':
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model_no,
               'Part'         => $item->part_no,
               'SKU'          => $item->sku,
               'Location'     => $location_link,
			);
			break;	  
		default:
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model_no,
               'Part'         => $item->part_no,
               'SKU'          => $item->sku,
               'Location'     => $location_link,
			);
			break;
	}

	if ($item) {
		foreach (array_keys($fields) as $field) {
			if (isset($item->$field)) {
				$values[$field] = $item->$field;
			}
		}
	}

	if (elgg_is_sticky_form('market')) {
		$sticky_values = elgg_get_sticky_values('market');
		foreach ($sticky_values as $key => $value) {
			$fields[$key] = $value;
		}
	}

	elgg_clear_sticky_form('market');

	return $fields;
}


function market_prepare_detailed_view_vars($item = null) {

	// input names => defaults
	$location  = $item->location;
	$container = $item->getContainerEntity();
	$subtype   = $container->getSubtype();
	if (is_numeric($location)){ // assume guid
		$place    = get_entity($location);
		switch($place->getSubtype()){
			case 'market':
				$stem = 'market/view';
				break;
			case 'item':
				$stem = 'market/view';
				break;
			case 'place':
				$stem = 'places/view';
				break;
		}
		$location_link = elgg_view('output/url', array(
			'href' => "$stem/$location",
			'text' => $place->title,
			));
	}
	if (!is_numeric($location)){ // assume label
		$location_link = $location;
	}
	if (($subtype == 'market' || $subtype == 'item') && !$location){
		$location_link = elgg_view('output/url', array(
			'href' => "market/view/$container->guid",
			'text' => $container->title,
			));		
	}
	$category = $item->marketcategory;
	switch ($category) {
		case 'car':
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model,
               'Part'         => $item->part,
               'SKU'          => $item->sku,
               'Warranty'     => $item->warranty,
               'Location'     => $location_link,
			);
			break;
		case 'appliances':
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model,
               'Part'         => $item->part,
               'SKU'          => $item->sku,
               'Warranty'     => $item->warranty,
               'Location'     => $location_link,
               'purchase_cost'=>$item->purchase_cost,
			);
			break;
		case 'auto':
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model,
               'Part'         => $item->part,
               'SKU'          => $item->sku,
               'Warranty'     => $item->warranty,
               'Location'     => $location_link,
			);
			break;	  
		default:
			$fields = array(
               'Manufacturer' => $item->manufacturer,
               'Brand'        => $item->brand,
               'Model'        => $item->model,
               'Part'         => $item->part,
               'SKU'          => $item->sku,
               'Warranty'     => $item->warranty,
               'Location'     => $location_link,
               'purchase_cost'=>$item->purchase_cost,
			);
			break;
	}

	if ($item) {
		foreach (array_keys($fields) as $field) {
			if (isset($item->$field)) {
				$values[$field] = $item->$field;
			}
		}
	}

	if (elgg_is_sticky_form('market')) {
		$sticky_values = elgg_get_sticky_values('market');
		foreach ($sticky_values as $key => $value) {
			$fields[$key] = $value;
		}
	}

	elgg_clear_sticky_form('market');

	return $fields;
}
/**
 * Adapted from engine\lib\access.php\get_user_access_collections
 * Returns an array of groups associated to $item_guid.
 *
 * @param int $item_guid  The entity guid
 * @param int $site_guid  The GUID of the site (default: current site).
 *
 * @return array|false
 */
function get_item_group_collections($item_guid, $relationship, $site_guid = 0) {
	global $CONFIG;
	$item_guid = (int) $item_guid;
	$site_guid = (int) $site_guid;

	if (($site_guid == 0) && (isset($CONFIG->site_guid))) {
		$site_guid = $CONFIG->site_guid;
	}
/*
	$query = "SELECT *
	        FROM {$CONFIG->dbprefix}metastrings msv
			where exists (Select *
			              from {$CONFIG->dbprefix}entities e
			              join {$CONFIG->dbprefix}metadata md 
			              on md.entity_guid = e.guid
			              JOIN {$CONFIG->dbprefix}metastrings msn 
			              on md.name_id = msn.id
					      where md.owner_guid = {$owner_guid}
						    AND e.site_guid = {$site_guid}
							and msv.id = md.value_id
							and msn.string IN ('tags'))
			 order by string";

	$collections = get_data($query);
*/	
	$collections = elgg_get_entities_from_relationship(array(
		'type' => 'group',
		'relationship' => $relationship,
		'relationship_guid' => $item_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	));

	return $collections;
}

// Array sort function
function aasort (&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
    asort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[$ii]=$array[$ii];
	}
	$array=$ret;
}

function anatcasesort (&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
    natcasesort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[$ii]=$array[$ii];
	}
	$array=$ret;
}

function quebx_get_group_by_title($options){
    global $CONFIG;
//@TODO Implement metaphone/levenshtein comparison for fuzzy search    
    $query = "SELECT e.* from {$CONFIG->dbprefix}entities e ".
             "JOIN {$CONFIG->dbprefix}groups_entity g ON e.guid=g.guid ".
             "WHERE e.type=\"group\" ";
// 2017-01-09 - SAJ - Selection by subtype does not work yet
    if (!empty($options['subtype'])){
        $subtype_guid = get_subtype_id('group', $options['subtype']);
//        $query .= "AND e.subtype = $subtype_guid"; 
    }
    $query .= " AND soundex(g.name)=soundex(\"{$options['name']}\") ".
              "LIMIT 0, 1";
    $row = get_data_row($query);
    if ($row)
        return new ElggGroup($row);
    else
        return false;
}
function market_render_section($options){
    $render_action = $options['action'];
    $section       = $options['section'];
    $entity        = $options['entity'];
    $selected      = $options['selected'];
    $selected_state = $options['selected_state'];
    $owner_guid    = $options['owner_guid'];
    $view_type     = $options['view_type'];
    $item_guid     = $options['item_guid'] ?: $entity->container_guid;
    $headline      = $options['headline'] ?: 'Experiences';
    $presentation  = $options['presentation'] ?: 'full';
    $action        = 'jot/elements';
    $form_vars     = array('name'    => 'contents_add', 
                           'enctype' => 'multipart/form-data', 
                           'action'  => 'action/jot/add/elements',);

/****************************************
 * $action = 'add'                       *****************************************************************************
 ****************************************/    
    if ($render_action == 'add'){
        Switch($section){
            case 'experience':
                $body_vars = array('element_type'   => 'item',
                                   'aspect'         => $section,
                                   'selected'       => $selected,
                                   'entity'         => $entity,
                                   'owner_guid'     => $owner_guid,
                                   'container_guid' => $item_guid,
                                   'action'         => $render_action,
                                   'panel'          => 'experiences');
                $view       = "forms/jot/elements";
                $add_form   = elgg_view($view, $body_vars);
                $add_button = elgg_view('output/url', array('text'=>'+', 'id'=>'experiences_add', 'title'=>'add experiences', 'class'=>'elgg-button-submit-element', 'style'=>'cursor:pointer;height:14px;width:30px'));
                $render     = "
                    <div class='rTable' style='width:550px'>
            			<div class='rTableBody'>
            				<div class='rTableRow'>
            					<div class='rTableCell' style='width:580px; padding: 0px 0px'>$add_button<b>$headline</b></div>
            					<div class='rTableCell' style='width:20px; padding: 0px 0px'></div>
            				</div>
            			</div>
            		</div>";
                
                $render .= "
                	<div class='rTable' style='width:550px'>
                		<div class='rTableBody'>
                            <div class='rTableRow'>
                				<div class='rTableCell' style='width:100%;padding:0px'>
                    				<div id='experiences_panel'>$add_form</div>
                	            </div>
                	        </div>
                        </div>
                    </div>";
	
                $render .= "
                    <div class='rTable' style='width:550px'>
            			<div class='rTableBody'>";
	    
            	if (!empty($options['entities'])){
                	foreach ($options['entities'] as $i) {
                			$element_type = 'experience';
                			unset($edit_experience_form);
                			if ($i->canEdit()) {
                				$delete = elgg_view("output/url",array(
                			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid",
                			    	'text' => elgg_view_icon('delete-alt'),
                			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
                			    	'encode_text' => false,
                			    ));
                				$edit = elgg_view("output/url",array(
                				    'href'  => "jot/edit/{$i->getGuid()}",
                			    	'text'  => elgg_view_icon('docedit'),
                				    'title' => 'edit this experience',
                			    ));
                			}
                
                			$render .= "<div class='rTableRow'>
                			                 <div class='rTableCell' style='width:90%; padding: 0px 0px'>".elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/{$i->guid}"))."</div>
                			                 <div class='rTableCell' style='width:10%; padding: 0px 0px;text-align:right'>$edit $delete</div>
                			            </div>";
                	}
            	}
            	$render .= "
            	        </div>
            	    </div>";
            	$list_options = array(
            	        'subtypes'         =>array('experience'),
            	        'container_guids'  => $entity->getGUID()
//            	        'list_type'        => ''
            	);
//            	$render .= elgg_list_entities(array($list_options));
            break;
        }
    }

/****************************************
 * $action = 'edit'                      *****************************************************************************
 ****************************************/
    if ($render_action == 'edit'){ 
        Switch($section){
            case 'experience':
                $body_vars = array('element_type'   => 'item',
                                   'aspect'         => $section,
                                   'selected'       => $selected,
                                   'entity'         => $entity,
                                   'owner_guid'     => $owner_guid,
                                   'container_guid' => $item_guid,
                                   'action'         => $render_action,
                                   'presentation'   => $presentation,
                                   'panel'          => 'experiences');
                $view          = "forms/jot/elements";
                $edit_form     = elgg_view($view, $body_vars);
                
                $render = "
                	<div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                            <div class='rTableRow'>
                				<div class='rTableCell' style='width:100%;padding:0px'>$edit_form
                	            </div>
                	        </div>
                        </div>
                    </div>";
	        break;
        }
    }
/****************************************
 * $action = 'view'                      *****************************************************************************
 ****************************************/
    if ($render_action == 'view'){ 
        Switch($section){
            case 'experience':
                //elgg_set_context('list_experience');
                $body_vars = array('element_type'   => 'item',
                                   'aspect'         => $section,
                                   'selected'       => $selected,
                                   'selected_state' => $selected_state,
                                   'entity'         => $entity,
                                   'owner_guid'     => $owner_guid,
                                   'container_guid' => $item_guid,
                                   'action'         => 'view',
                                   'panel'          => 'experiences');
                //$view          = "jot/display/jot/elements";
                $view          = "forms/jot/elements";
                $display_view  = elgg_view($view, $body_vars);
                
                $render        = "
                	<div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                            <div class='rTableRow'>
                				<div class='rTableCell' style='width:100%;padding:0px'>$display_view
                	            </div>
                	        </div>
                        </div>
                    </div>";
	        break;
            case 'receipt':
                $body_vars = array('element_type'   => 'transfer',
                                   'aspect'         => $section,
                                   'presentation'   => 'compact',
                                   'selected'       => $selected,
                                   'guid'           => $item_guid,
                                   'entity'         => $entity,
                                   'owner_guid'     => $owner_guid,
                                   'action'         => 'view',
                                   'panel'          => 'receipts');
                //$view          = "jot/display/jot/elements";
                $view          = "forms/jot/elements";
                $display_view  = elgg_view($view, $body_vars);
                
                $render        = "
                	<div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                            <div class='rTableRow'>
                				<div class='rTableCell' style='width:100%;padding:0px'>$display_view
                	            </div>
                	        </div>
                        </div>
                    </div>";
	        break;
        }
    }
    return $render;
}
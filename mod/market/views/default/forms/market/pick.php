Form: market\views\default\forms\market\pick.php<br>
<?php
/**
 * QuebX pick item form
 * Used by:
 	*  jot\views\default\forms\transfers\edit.php
 	*  market\pages\market\pick.php 
 	*  market\views\default\forms\market\edit\car\family.php
 	*  
 **/

$access_id          = elgg_extract('access_id', $vars, ACCESS_PUBLIC);
$guid               = elgg_extract('guid', $vars);
$selected_item      = elgg_extract('item', $vars, false);
$element_type       = elgg_extract('element_type', $vars);
$elements           = elgg_extract('elements', $vars);
$container_guid     = elgg_extract('container_guid', $vars);                           $display .= '$container_guid: '.$container_guid.'<br>';
$container_type     = elgg_extract('container_type', $vars);
$content_options    = elgg_extract('content_options', $vars);
$origin             = elgg_extract('origin', $vars);
$submit_label       = elgg_echo('Set');
$owner              = elgg_get_page_owner_entity();
$owner_guid         = $owner->guid;
if ($selected_item) {
	$url = $selected_item->getURL();
    $display = '<br>$item_guid: '.$selected_item->getguid().'<br>';
}
if ($element_type == 'item'){$link_type = 'single';}
if ($element_type == 'family_characteristics'){$link_type = 'multiple';}
//if ($element_type == 'supplier'){$link_type = 'multiple';}
$display .= '$guid: '.$guid.'<br>';
$display .= '$url: '.$url.'<br>';
$display .= '$element_type: '.$element_type.'<br>';
$display .= '$container_guid: '.$container_guid.'<br>';
$display .= '$container_type: '.$container_type.'<br>';

if (!$owner_guid) {
	$owner_guid = elgg_get_logged_in_user_guid();
}
$this_container = get_entity($container_guid);
$display .= '$item_guid: '.$this_container->item_guid.'<br>';
$guid = $guid ?: $this_container->item_guid;
// $container_type = $container->getsubtype();
/*
elgg_register_library('elgg:containers', elgg_get_plugins_path() . 'market/lib/market.php');
elgg_load_library('elgg:containers');
echo elgg_dump($vars);
*/
elgg_load_css('jquery.treeview');

if (!empty($elements)){
	$containers = $elements;
}
else {
	$containers = elgg_get_entities(array('type'=>'object','subtype'=>'market', 'owner_guid' => $owner_guid, ));
}

// Experimental $images
$images = elgg_list_entities(array(
			'types' => array('object'),
			'subtypes' => array('hjAlbumImage'),
			'owner_guids' => array($owner_guid),
			'full_view' => false,
			'list_type' => get_input('list_type', 'gallery'),
			'list_type_toggle' => true,
	'gallery_class' => 'tidypics-gallery',
//			'gallery_class' => 'gallery-photostream',
		    'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset-albums', 0),
			'offset_key' => 'offset-albums'
		));
$images = elgg_list_entities(array(
	'types' => array('object'),
//	'type' => 'object',
	'subtype' => 'image',
	'owner_guid' => $owner_guid,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => true,
	'gallery_class' => 'tidypics-gallery',
));

$linked_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'receipt_item',
	'relationship_guid' => $container_guid,
	'inverse_relationship' => true,
	'limit' => false,
));
if (!empty($origin)){$linked_items[] = get_entity($origin);}

if (isset($linked_items)){
    foreach ($linked_items as $this_item){                              $display .= '$linked_item->guid: '.$linked_item->guid.'<br>';
        if (elgg_instanceof($this_item, 'object', 'market')){           //$display .= '$linked_item->getGUID(): '.$linked_item->getGUID().'<br>';
            $linked_item = $this_item;
            $hidden_fields['pick[item_guid]']     = $linked_item->guid;
            break;                          
        }
    }
}
else {
	$linked_item = !empty($origin) ?get_entity($origin) :$this_container;
}
$linked_item = $linked_item ?: get_entity($this_container->item_guid);            $display .= '$linked_item->guid: '.$linked_item->guid.'<br>';
$hidden_fields['pick[title]']         = $this_container->title;
$hidden_fields['pick[element_type]']  = $element_type;
$hidden_fields['pick[container_guid]']= $container_guid;
$hidden_fields['pick[container_type]']= $container_type;
$hidden_fields['pick[owner_guid]']    = $owner_guid;
$hidden_fields['pick[access_id]']     = $access_id;

//echo $images;
//echo '$container->show_on_timeline:'.$container->show_on_timeline.'<br>';
foreach ($elements as $string){
//	echo $string->characteristic_name.'<br>';
}

Switch ($element_type){
    case 'item':
        $title          = elgg_echo("Properties for receipt line");

    	$show_on_timeline_options = array(
    				'name'   => 'pick[show_on_timeline]',
    				'label'  => 'Pin to Timeline as',
    				'value'  => 1,
    		);
    	if ($container->show_on_timeline == 1){$show_on_timeline_options[checked]= 'checked';}
    	$show_on_timeline = elgg_view('input/checkbox', $show_on_timeline_options);
    
    	$timeline_label   = elgg_view('input/text', array(
    							'name' => 'pick[timeline_label]',
    							'value' => $container->timeline_label,	
    							));
    	
    	$add_cost_to_que_options = array(
    				'name'   => 'pick[add_cost_to_que]',
    				'label'  => 'Add cost to this que',
    				'value'  => 1,
    		);
    	if ($container->add_cost_to_que == 1){$add_cost_to_que_options[checked] = 'checked';}
    	$add_cost_to_que = elgg_view('input/checkbox', $add_cost_to_que_options);
    
    	$distribute_freight_options = array(
    				'name'   => 'pick[distribute_freight]',
    				'label'  => 'Distribute shipping cost to this line item',
    				'value'  => 1,
    		);
    	if ($container->distribute_freight == 1){$distribute_freight_options[checked] = 'checked';}
    	$distribute_freight = elgg_view('input/checkbox',$distribute_freight_options);
    
    	$unpack_options = array(
    			'name'   => 'pick[unpack]',
    			'label'  => 'Unpack these items when I save this receipt',
    			'value'  => 1,
    	);
    	if ($container->unpack == 1){
    			$unpack_options[checked] = 'checked';
    	}
    	$unpack = elgg_view('input/checkbox',$unpack_options);
    	
    	$params = array(
    			'name' => 'pick[que_contribution]',
    			'value' => $container->que_contribution,
    			'options_values' => array('purchase'    => 'Purchase',
    							          'maintenance' => 'Maintenance',
    							          'sales'       => 'Selling',
    					                  'none'        => 'No que',
     				                     ),
    	);
    	
    	$que_options = elgg_view('input/dropdown', $params);
    	$link_type = elgg_view('input/dropdown',array(
    			'name'    => 'pick[link_type]',
    			'value'   => $container->link_type,
    			'options' => array('pick link type ...', 'Part', 'Supply', 'Warranty'),
    	));
    	$label_toggle = elgg_view("input/radio", array(
    			"name" => 'pick[retain_line_label]',
    			"value"=> $container->retain_line_label,
    			"options" => array(
    					'Keep my label for this receipt line'                   => "yes",
    					'Create a new item from this receipt line'              => "create",
    					'Identify this receipt line as the linked item selected below'  => "no",
    					"Link this receipt line to the item selected below as {$link_type}" => "link",
    			),
    	));
    	$submit_button = elgg_view('input/submit', array('value' => $submit_label));
    	$content_options = "<div class='rTable' style='width:100%'>
    							<div class='rTableBody'>
    								<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 5px 5px'></div>
    									<div class='rTableCell' style='text-align:right'>$submit_button</div>
    								</div>
    								<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 5px 5px'>$show_on_timeline</div>
    									<div class='rTableCell' style='padding:0px 0px 5px 5px'>$timeline_label</div>
    								</div>
    						";
    	$content_options .= "		<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$add_cost_to_que</div>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$que_options</div>
    								</div>
    							</div>
    						</div>";
    	$content_options .= "<div class='rTable' style='width:100%'>
    							<div class='rTableBody'>
    								<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$distribute_freight</div>
    								</div>
    	                         	<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$unpack</div>
    								</div>";
    	$content_options .= "		<div class='rTableRow'>
    									<div class='rTableCell'><br><b>Line Label</b><br>{$label_toggle}</div>
    								</div>
    							</div>
    						</div>";
// Build the item tree
/*
    $top_containers = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'market',
		'owner_guid' => $owner_guid,
		'limit' => false,
	));
	$top_containers = array_merge($top_containers, elgg_get_entities(array(
														'type' => 'object',
														'subtype' => 'item',
														'owner_guid' => $owner_guid,
														'limit' => false,
													)));
*//*
	$top_containers = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'market',
		'owner_guid' => $owner_guid,
		'limit' => false,
	));
*/	
	//Extract top containers from Shelf
	$file        = new ElggFile;
    $file->owner_guid = elgg_get_logged_in_user_guid();
    $file->setFilename("shelf.json");
    if ($file->exists()) {
    	$file->open('read');
    	$json = $file->grabFile();
    	$file->close();
    }

    $data =  json_decode($json, true);
    foreach($data as $key=>$contents){
        unset($entity);
        foreach($contents as $position=>$value){
            while (list($position, $value) = each($contents)){
                if ($position == 'guid'){
                    $entity = get_entity($value);
                }
            }
        }
        $top_containers[] = $entity;
    }
    if (elgg_instanceof($linked_item, 'object', 'market') || elgg_instanceof($linked_item, 'object', 'item')){                  $display .= '$linked_item->guid: '.$linked_item->guid.'<br>';
        foreach($top_containers as $top_container){                          $display .= '$top_container[guid]: '.$top_container['guid'].'<br>';
            $on_shelf = false;
            if ((int) $top_container['guid'] == (int) $linked_item->guid){
                $on_shelf = true;                                            $display .= '$on_shelf: '.$on_shelf.'<br>';
                break;
            }
        }
        if (!$on_shelf){                                                     $display .= '$on_shelf: '.$on_shelf.'<br>';
            $top_containers[] = $linked_item;
        }
    }
    
	$containers = array();
	$depths = array();

	foreach ($top_containers as $container) {
		$containers[] = array(
			'guid' => $container->getGUID(),
			'title' => $container->title,
			'url' => $container->getURL(),
			'depth' => 0,
		);
		$depths[$container->guid] = 0;

		$stack = array();
		array_push($stack, $container);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = new ElggBatch('elgg_get_entities_from_metadata', array(
				'type' => 'object',
				'subtype' => 'market',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
				'limit' => false,
			));

			foreach ($children as $child) {
				$containers[] = array(
					'guid' => $child->getGUID(),
					'title' => $child->title,
					'url' => $child->getURL(),
					'parent_guid' => $parent->getGUID(),
					'depth' => $depths[$parent->guid] + 1,
				);
				$depths[$child->guid] = $depths[$parent->guid] + 1;
				array_push($stack, $child);
			}
		}
	}
    break; //($element_type == 'item')
} 

if ($containers && $element_type == 'item') {
    elgg_load_library('elgg:market');
	aasort($containers,"title");
	$sort_order = (int) '';

	foreach ($containers as $container) {
			$sort_order = $sort_order + 1;
			$link = elgg_view('output/url', array(
			      'text' => $container['title'],
			      'href' =>  'market/view/'.$container['guid']));
				$input = elgg_view('input/checkbox', array(
						   'id'=>$container['guid'], 
						   'value'=>$container['guid'], 
						   ));
				if ($container['guid'] == $linked_item['guid']){
//					$input_radio = "<input type='radio' checked='checked' name='item_guid' value={$container['guid']}>$link</input>";
					$input_radio = '<input type="radio" checked="checked" name="item_guid" value='. $container['guid'].'>'.$container['title'];
				}
				else {
//					$input_radio = "<input type='radio' name='item_guid' value={$container['guid']}>$link</input>";
					$input_radio = '<input type="radio" name="item_guid" value='. $container['guid'].'>'.$container['title'];				
				}
			elgg_register_menu_item('containers_checkboxes', array(
				'name' => $container['guid'],
				'text' => $input.$container['title'],
				'href' => false,
				'parent_name' => $container['parent_guid'],
			));
			
			elgg_register_menu_item('containers_radio', array(
				'name' => $container['guid'],
				'text' => $input_radio,
				'href' => false,
				'parent_name' => $container['parent_guid'],
				'sort_order' => $sort_order,
			));
		}
}

if ($containers && $element_type == 'family_characteristics') {
	$sort_order = (int) '';
	$submit_label = 'Pick';
	$characteristic_names = $this_container->characteristic_names;              
	if ($characteristic_names && !is_array($characteristic_names)){
		$characteristic_names = array($characteristic_names);
	}
		foreach($characteristic_names as $characteristic){                //echo '216 characteristic_name: '.$characteristic.'<br>';
		}
	
	foreach ($containers as $container) {
		$sort_order = $sort_order + 1;
		$this_characteristic = $container->characteristic_name; 
		if (in_array($this_characteristic, $characteristic_names) || $this_container->record_stage == 'newborn' ){
			$input = elgg_view('input/checkbox', array(
			   'id'   =>$sort_order,
			   'name' => 'pick[characteristic_names][]',
			   'value'=>$this_characteristic,
			   'checked' => 'checked',
			   'disabled' => true,
			   ));
		}
		else {
			$input = elgg_view('input/checkbox', array(
			   'id'   =>$sort_order,
			   'name' => 'pick[characteristic_names][]',
			   'value'=>$this_characteristic,
			   ));
		}
		elgg_register_menu_item('containers_checkboxes', array(
				'name' => $this_characteristic,
				'text' => $input.$this_characteristic,
				'href' => false,
//				'parent_name' => $container['parent_guid'],
			));
		
	}
	
}	
	$content_checkbox = elgg_view_menu('containers_checkboxes', array('class' => 'containers-nav', 'sort_by' => 'sort_order'));
	if (!$content_checkbox) {
		$content_checkbox = '<p>' . elgg_echo('containers:none') . '</p>';
	}
	
	//echo elgg_view_module('aside', $title, $content);
	
	//$content_location = elgg_view_form('market/add/element', array("action" => "action/market/pick?element_type=item&item_guid=$item_guid&container_guid=$container_guid"));
	
	$content_radio = elgg_view_menu('containers_radio', array('class' => 'containers-nav', 'sort_by' => 'sort_order'));
	if (!$content_radio) {
		$content_radio = '<p>' . elgg_echo('containers:none') . '</p>';
	}
	
	//$content  = $content_location;
	//$content .= "<div class=\"elgg-col elgg-col-1of3\">";
	$shelf_link = elgg_view('output/url', array('text'=>'shelf', 'href'=>"shelf"));
	$shelf_link = "<span title='Set on the shelf to appear in this list'>$shelf_link</span>";
	if ($link_type == 'single'){
		$content  = "Linked item on $shelf_link:<br>";
		$content .= $content_radio;
	}
	if ($link_type == 'multiple'){
		$content  = "Linked items on $shelf_link:<br>";
		$content .= $content_checkbox;
	}
	
	//echo elgg_view_module('aside', $title, $content);
	
	?>
	<script>
	require(['jquery', 'jquery.treeview'], function($) {
		$(function() {
			$(".containers-nav").treeview({
				persist: "location",
				collapsed: false,
				unique: true
			});
	
	<?php if ($selected_container) { ?>
			// if in a container, we need to manually select the correct menu item
			// code taken from the jquery.treeview library
			var current = $(".containers-nav a[href='<?php echo $url; ?>']");
			var items = current.addClass("selected").parents("ul, li").add( current.next() ).show();
			var CLASSES = $.treeview.classes;
			items.filter("li")
				.swapClass( CLASSES.collapsable, CLASSES.expandable )
				.swapClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
					.find(">.hitarea")
						.swapClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
						.swapClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea );
	<?php } ?>
		});
	});
	</script>
	<?php

//echo $display;
foreach($hidden_fields as $name => $value){
    echo elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}
echo $content_options;
echo '<div>';
echo elgg_view_module('aside', $title, $content);
echo elgg_view('input/submit', array('value' => $submit_label)).'</p>';
echo '</div>';
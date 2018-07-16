<!-- Form: market\views\default\forms\market\properties.php -->
<?php
/**
 * QuebX pick item form
 * Used by:
 	*  jot\views\default\forms\transfers\edit.php
 	*  market\pages\market\pick.php 
 	*  market\views\default\forms\market\edit\car\family.php
 	*  
 **/

$access_id          = elgg_extract('access_id',       $vars, ACCESS_PUBLIC);
$guid               = elgg_extract('guid',            $vars);
$qid                = elgg_extract('qid',             $vars);
$qid_n              = elgg_extract('qid_n',           $vars);					$display .= '15 $qid_n:'.$qid_n.'<br>';
$selected_item      = elgg_extract('item',            $vars, false);
$element_type       = elgg_extract('element_type',    $vars);
$elements           = elgg_extract('elements',        $vars);
$container_guid     = elgg_extract('container_guid',  $vars, false);           $display .= '19 $container_guid: '.$container_guid.'<br>';
$container_type     = elgg_extract('container_type',  $vars);
$content_options    = elgg_extract('content_options', $vars);
$origin             = elgg_extract('origin',          $vars);
$sort_order         = elgg_extract('sort_order',      $vars);
$line_item_behavior_list_class  = elgg_extract('line_item_behavior_list_class',     $vars);
$line_item_behavior_list_data   = elgg_extract('line_item_behavior_list_data',      $vars);
$line_item_behavior_radio_class = elgg_extract('line_item_behavior_radio_class',     $vars);
$submit_label       = elgg_echo('Set');
$owner              = elgg_get_page_owner_entity();
$owner_guid         = $owner->guid;
if ($selected_item) {
	$url = $selected_item->getURL();                                    $display .= '30 $item_guid: '.$selected_item->getguid().'<br>25 $sort_order: '.$sort_order.'<br>';
	$item_guid = $selected_item->getguid();
}
elseif($sort_order == 1 && $origin){
    $item_guid = $origin;
}
if ($element_type == 'receipt_item'){$link_type = 'single';}
if ($element_type == 'family_characteristics'){$link_type = 'multiple';}

if (!$owner_guid) {
	$owner_guid = elgg_get_logged_in_user_guid();
}
$this_container = get_entity($container_guid);                          $display .= '42 $item_guid: '.$this_container->item_guid.'<br>';
$guid = $guid ?: $this_container->item_guid;
$item = get_entity($guid);

if (isset($guid)){
	  $hidden_fields['line_item['. $guid.'][guid]']      = $guid;
	  $hidden_fields['line_item['. $guid.'][item_type]'] = $element_type;}
else {$hidden_fields['line_item['. $qid_n.'][qid]']      = $qid_n;
      $guid                                              = $qid_n;}
if (isset($line_item_behavior_list_data)){
	if (!is_array($line_item_behavior_list_data)){
		$line_item_behavior_list_data = array($line_item_behavior_list_data);
	}
}

Switch ($element_type){
    case 'receipt_item':
        $title          = elgg_echo("Properties for receipt line");
        
        if ($container_guid){
	        $linked_items = elgg_get_entities_from_relationship(array(
	        	'type' => 'object',
	        	'relationship' => 'receipt_item',
	        	'relationship_guid' => $container_guid,
	        	'inverse_relationship' => true,
	        	'limit' => false,
	        ));
        }
        if (isset($linked_items)){
            foreach ($linked_items as $this_item){                              $display .= '72 $linked_item->guid: '.$linked_item->guid.'<br>';
                if (elgg_instanceof($this_item, 'object', 'market')){
                    $linked_item = $this_item;
                    $hidden_fields['line_item['. $guid.'][item_guid]'] = $linked_item->guid;
                    break;                          
                }
            }
        }
        else {
        	$linked_item = !empty($origin) ?get_entity($origin) :$this_container;
        }
        $linked_item = $linked_item ?: get_entity($this_container->item_guid);
        $linked_item = $linked_item ?: get_entity($item_guid);                             $display .= '84 $linked_item->guid: '.$linked_item->guid.'<br>';
                
        if (!empty($origin)){$linked_items[] = get_entity($origin);}
                
    	$show_on_timeline_options = array(
    				'name'   => 'line_item['. $guid.'][show_on_timeline]',
    				'label'  => 'Pin to Timeline as',
    	            'default'=> false,
    		);
    	if ($item->show_on_timeline == 1){$show_on_timeline_options[checked]= 'checked';}
    	$show_on_timeline = elgg_view('input/checkbox', $show_on_timeline_options);
    
    	$timeline_label   = elgg_view('input/text', array(
    							'name' => 'line_item['. $guid.'][timeline_label]',
    							'value' => $item->timeline_label,	
    							));
    	$item_title        = elgg_view('input/text', array(
				    			'name'  => 'line_item['. $guid.'][title]',
    							'value' => $item->title,
				    			'placeholder'=> 'title',
	 			    	        ));
    	
    	$sku               = elgg_view('input/text', array(
    	                        'name'  => 'line_item['. $guid.'][sku]',
    	                        'value' => $item->sku,
    	                        'placeholder'=> 'sku',
    	                        ));
    	
    	$seller            = elgg_view('input/text', array(
    	                        'name'  => 'line_item['. $guid.'][seller]',
    	                        'value' => $item->seller,
    	                        'placeholder' => 'if different from Merchant',
    	                        ));
    	
    	$add_cost_to_que_options = array(
    				'name'   => 'line_item['. $guid.'][add_cost_to_que]',
    				'label'  => 'Add cost to this que',
    			    'checked'=> 'checked',
    	            'default'=> false,
    		);
    	if ($item->add_cost_to_que == 'on'){$add_cost_to_que_options[checked] = 'checked';}
    	$add_cost_to_que = elgg_view('input/checkbox', $add_cost_to_que_options);
    
    	$distribute_freight_options = array(
    				'name'   => 'line_item['. $guid.'][distribute_freight]',
    				'label'  => 'Distribute shipping cost to this line item',
    	            'default'=> false,
    		);
    	if ($item->distribute_freight == 'on'){$distribute_freight_options[checked] = 'checked';}
    	$distribute_freight = elgg_view('input/checkbox',$distribute_freight_options);
    
    	$unpack_options = array(
    			'name'   => 'line_item['. $guid.'][unpack]',
    			'label'  => 'Unpack these items when I save this receipt',
    	        'default'=> false,
    	);
    	if ($item->unpack == 1){
    			$unpack_options[checked] = 'checked';
    	}
    	$unpack = elgg_view('input/checkbox',$unpack_options);
    	
    	$params = array(
    			'name' => 'line_item['. $guid.'][que_contribution]',
    			'value' => $item->que_contribution,
    			'options_values' => array('purchase'    => 'Purchase',
    							          'maintenance' => 'Maintenance',
    							          'sales'       => 'Selling'
     				                     ),
    	);
    	
    	$que_options = elgg_view('input/dropdown', $params);
    	$pick_link_type = elgg_view('input/dropdown',array(
    			'name'    => 'line_item['. $guid.'][link_type]',
    			'value'   => $item->link_type,
    			'options' => array('select ...', 'Part', 'Supply', 'Warranty'),
    	));
    	$label_toggle = elgg_view("input/radio", array(
    			"name" => 'line_item['. $guid.'][retain_line_label]',
    			"value"=> $item->retain_line_label ?: 'yes',
    			'class'=> $line_item_behavior_list_class,
    			"radio_class"=>$line_item_behavior_radio_class,
    			"data-qid" => $line_item_behavior_list_data['qid'],
    			"options" => array(
    					'Leave as entered'                   => "yes",
    					'Create a new item'                  => "create",
    					'Tag as the item below'              => "no",
    					"Tag as a {$pick_link_type} for ..." => "link",
    			),
    	));
    	if ($item->retain_line_label == 'no'){
    		$content .= "<div style='padding:0px 0px 5px 25px'>$item_title</div>";}
    	$content .= "<div class='rTable' style='width:100%'>
    					<div class='rTableBody'>";
    	$content_xxx .= "			<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 5px 5px'>$show_on_timeline</div>
    									<div class='rTableCell' style='padding:0px 0px 5px 5px'>$timeline_label</div>
    									</div>";
    	$content .= "			<div class='rTableRow'>
    									<div class='rTableCell' style='padding:5px 0px 5px 25px'><label>Inventory number</label></div>
    									<div class='rTableCell' style='padding:5px 0px 5px 5px'>$sku</div>
    								</div>";
    	$content .= "			<div class='rTableRow'>
    									<div class='rTableCell' style='padding:5px 0px 5px 25px'><label>Seller</label></div>
    									<div class='rTableCell' style='padding:5px 0px 5px 5px'>$seller</div>
    								</div>";
    	$content .= "		<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$add_cost_to_que</div>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$que_options</div>
    								</div>
    							</div>
    						</div>";
    	$content .= "<div class='rTable' style='width:100%'>
    							<div class='rTableBody'>
    								<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$distribute_freight</div>
    								</div>
    	                         	<div class='rTableRow'>
    									<div class='rTableCell' style='padding:0px 0px 0px 5px'>$unpack</div>
    								</div>";
    	$content .= "		<div class='rTableRow'>
    									<div class='rTableCell'>
        									<b>Line item behavior</b><br>
        									   For this receipt line item ...<br>
        									   $label_toggle
    									</div>
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
    if ($data){
	    foreach($data as $key=>$contents){
	        unset($entity);
	        foreach($contents as $position=>$value){
	//            while (list($position, $value) = each($contents)){
	                if ($position == 'guid' && elgg_entity_exists($value)){      $display .= '208 $value='.$value.'<br>';
	                    $top_containers[] = get_entity($value);
	                }
	                else {continue;}
	  //          }
	        }                                                                    $display .= '212 $top_containers: '.print_r($top_containers, true).'<br>';
	    }
    }
//@EDIT - 20187-04-12 - SAJ - Moved after anatcasesort($containers, 'title') to alphabetize the list while placing the linked item on top;
// // Test to see if the linked item is on the Shelf
//     if (elgg_instanceof($linked_item, 'object', 'market') || elgg_instanceof($linked_item, 'object', 'item')){                  $display .= '$linked_item->guid: '.$linked_item->guid.'<br>';
//         foreach($top_containers as $top_container){                          $display .= '$top_container[guid]: '.$top_container['guid'].'<br>';
//             $on_shelf = false;
//             if ((int) $top_container['guid'] == (int) $linked_item->guid){
//                 $on_shelf = true;                                            $display .= '$on_shelf: '.$on_shelf.'<br>';
//                 break;
//             }
//         }
//         if (!$on_shelf){                                                     $display .= '$on_shelf: '.$on_shelf.'<br>';
//             array_unshift($top_containers, $linked_item);
//         }
//     }
    
//	$containers = array();
	$depths = array();                                                        //$display .= '229 $top_containers: '.print_r($top_containers, true).'<br>';

	if ($top_containers){
		foreach ($top_containers as $container) {                                 $display .= '277 $container: '.print_r($container, true).'<br>';
	//continue;
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
	}
    break; //($element_type == 'item')
}

if ($linked_item && !$containers){
	$containers = array();
}                                                                                   $display .= '319 $containers:'.print_r($containers, true);

if ($element_type == 'receipt_item') {
    elgg_load_library('elgg:market');
	$sort_order = (int) 0;

	// Test to see if the linked item is on the Shelf
	    if (elgg_instanceof($linked_item, 'object', 'market') || elgg_instanceof($linked_item, 'object', 'item')){                  $display .= '$linked_item->guid: '.$linked_item->guid.'<br>';
	    $on_shelf = false;
	    	if ($containers){
		        anatcasesort($containers,"title");
		        foreach($containers as $container){                                  $display .= '318 $container[guid]: '.$container['guid'].'<br>';
		            $on_shelf = false;
		            if ((int) $container['guid'] == (int) $linked_item->guid){
		                $on_shelf = true;                                            $display .= '321 $on_shelf: '.$on_shelf.'<br>';
		                break;
		            }
		        }
	    	}
	        if (!$on_shelf){                                                     $display .= '325 $on_shelf: '.$on_shelf.'<br>';
	            array_unshift($containers,
	            	['guid' => $linked_item->getGUID(),
					'title' => $linked_item->title,
					'url' => $linked_item->getURL(),]
	            	);
	        }
	    }                                                                        $display .= '332 $containers[0][guid] = '.$containers[0]['guid'].'<br>';
	if ($containers){
		if (!is_array($containers)){$containers = (array) $containers;}
		foreach ($containers as $key=>$container) {
		    unset($input_radio, $radio_options);
				$link = elgg_view('output/url', array(
				      'text' => $container['title'],
				      'href' =>  'market/view/'.$container['guid']));
					$input = elgg_view('input/checkbox', array(
							   'id'=>$container['guid'], 
							   'value'=>$container['guid'], 
							   ));
					if ($container['guid'] == $item_guid){
						$input_radio = '<input type="radio" name="line_item['. $guid.'][item_guid]" value='. $container['guid'].' checked="checked" class="elgg-input-radio" >'.$container['title'];
					}
					else {
						$input_radio = '<input type="radio" name="line_item['. $guid.'][item_guid]" value='. $container['guid'].' class="elgg-input-radio">'.$container['title'];				
					}
				elgg_register_menu_item('containers_checkboxes', array(
					'name' => $container['guid'],
					'text' => $input.$container['title'],
					'href' => false,
					'parent_name' => $container['parent_guid'],
					'sort_order' => $key + 1,
				));
	//@EDIT 2018-04-12 - SAJ - Removed to make sorting work
	// 			elgg_register_menu_item('containers_radio', array(
	// 				'name' => $container['guid'],
	// 				'text' => $input_radio,
	// 				'href' => false,
	// 				'parent_name' => $container['parent_guid'],
	// 				'sort_order' => $key + 1,
	// 			));
				$content_radio_2 .= $input_radio."<br>";
		}
	}
}

	$content_checkbox = elgg_view_menu('containers_checkboxes', array('class' => 'containers-nav', 'sort_by' => 'sort_order'));
	if (!$content_checkbox) {
		$content_checkbox = '<p>' . elgg_echo('containers:none') . '</p>';
	}
	
// 	$content_radio = elgg_view_menu('containers_radio', array('class' => 'containers-nav', 'sort_by' => 'sort_order'));
// 	if (!$content_radio) {
// 		$content_radio = '<p>' . elgg_echo('containers:none') . '</p>';
// 	}
	$shelf_link = elgg_view('output/url', array('text'=>'Shelf', 'href'=>"shelf"));
	$shelf_link = "<span title='Set on the shelf to appear in this list'>$shelf_link</span>";
	$content   .= "Items on the $shelf_link:<br>";
	if ($link_type == 'single'){
		$content .= $content_radio_2;
	}
	if ($link_type == 'multiple'){
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
	
	<?php if ($selected_item) { ?>
			// if in a container, we need to manually select the correct menu item
			// code taken from the jquery.treeview library
//			var current = $(".containers-nav a[href='<?php echo $url; ?>']");
			var current = $(".containers-nav input[value=<?php echo $item_guid; ?>]");
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

foreach($hidden_fields as $name => $value){
    echo elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}

echo $content;
eof:
//register_error($display);

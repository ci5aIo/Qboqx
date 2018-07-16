<?php
/**
 * Select an item
 *
 * @package ElggFile
 */
/*
 * Used by
 	* jot\views\default\forms\transfers\edit.php
 * 
 */


elgg_load_library('elgg:file');
$identifiers    = 'Page: market\pages\market\pick.php<br>Action: market\actions\pick.php';
$element_type   = get_input('element_type');   // $page[1]
$container_guid = get_input('container_guid'); // $page[2]
$container      = get_entity($container_guid);
$owner          = elgg_get_page_owner_entity();
$submit_label   = elgg_echo('Set');
if ($container){
	$container_type = $container->getsubtype();
}

elgg_gatekeeper();
elgg_group_gatekeeper();

// create form
//@TODO: Move this to the form itself
if ($element_type == 'item'){
	$title          = elgg_echo("Properties for receipt line");

	$show_on_timeline_options = array(
				'name'   => 'show_on_timeline',
				'label'  => 'Pin to Timeline as',
				'value'  => 1,
		);
	if ($container->show_on_timeline == 1){$show_on_timeline_options[checked]= 'checked';}
	$show_on_timeline = elgg_view('input/checkbox', $show_on_timeline_options);

	$timeline_label   = elgg_view('input/text', array(
							'name' => 'timeline_label',
							'value' => $container->timeline_label,	
							));
	
	$add_cost_to_que_options = array(
				'name'   => 'add_cost_to_que',
				'label'  => 'Add cost to this que',
				'value'  => 1,
		);
	if ($container->add_cost_to_que == 1){$add_cost_to_que_options[checked] = 'checked';}
	$add_cost_to_que = elgg_view('input/checkbox', $add_cost_to_que_options);

	$distribute_freight_options = array(
				'name'   => 'distribute_freight',
				'label'  => 'Distribute shipping cost to this line item',
				'value'  => 1,
		);
	if ($container->distribute_freight == 1){$distribute_freight_options[checked] = 'checked';}
	$distribute_freight = elgg_view('input/checkbox',$distribute_freight_options);

	$unpack_options = array(
			'name'   => 'unpack',
			'label'  => 'Unpack these items when I save this receipt',
			'value'  => 1,
	);
	if ($container->unpack == 1){
			$unpack_options[checked] = 'checked';
	}
	$unpack = elgg_view('input/checkbox',$unpack_options);
	
	$params = array(
			'name' => 'que_contribution',
			'value' => $container->que_contribution,
			'options_values' => array('purchase'    => 'Purchase',
							          'maintenance' => 'Maintenance',
							          'sales'       => 'Selling',
					                  'none'        => 'No que',
 				                     ),
	);
	
	$que_options = elgg_view('input/dropdown', $params);
	$link_type = elgg_view('input/dropdown',array(
			'name'    => 'link_type',
			'value'   => $container->link_type,
			'options' => array('pick link type ...', 'Part', 'Supply', 'Warranty'),
	));
	$label_toggle = elgg_view("input/radio", array(
			"name" => 'retain_line_label',
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
} //@END if ($element_type == 'item')

if ($element_type == 'supply'){
	/* Properties of 'supply'
	   *  Quantity on hand
	   *  Quantity on order
	   *  Suppliers
	 * Actions to pick  
	   *   
	 */
	
//	include(elgg_get_plugins_path() . "jot/views/default/forms/transfers/return.php");

	$title          = elgg_echo("Properties for supply item");
	$qty_on_hand    = elgg_echo("##");
	$add_qty        = elgg_echo("add...");
	$supply         = $container;
	$item_guid      = $supply->guid;

	$suppliers = elgg_get_entities_from_relationship(array(
		'type' => 'group',
		'relationship' => 'supplier_of',
		'relationship_guid' => $item_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	));
	$group_type = 'supplier';
	$action     = 'groups/add/element';
	$form_vars  = array('enctype'     => 'multipart/form-data', 
	                    'name'        => 'group_list',
				 	    'action'      => "action/groups/add?element_type=$group_type&item_guid=$item_guid");
	$body_vars  = array('item_guid'   => $item_guid,
				        'group_type'  => $group_type,
			            'form_type'   => 'div',
	);
	$hoverhelp  = elgg_echo('jot:hoverhelp:Suppliers');
	$supplier_display = "<div class='rTable' style='width:100%'>
							<div class='rTableBody'>
								<div class='rTableRow'>
									<div class='rTableCell' style='padding:0px 0px 5px 5px'><b>Suppliers</b><span class='hoverhelp'>[?]
																									        	<span style='width:500px;'><p>$hoverhelp</span>
																									        </span></div>
			                        <div class='rTableCell' style='padding:0px 0px 0px 5px'>".elgg_view_form($action, $form_vars, $body_vars)."</div>
								</div>";
	if ($suppliers) {
		$supplier_display .= "	</div>
							</div>
							<div class='rTable' style='width:100%'>
								<div class='rTableBody'>";
		$element_type = 'supplier';
		foreach ($suppliers as $i) {
		$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid/$i->name"));
		
		if ($i->canEdit()) {
			$detach = elgg_view("output/confirmlink",array(
		    	'href' => "action/jot/detach?element_type=$element_type&guid=$i->guid&container_guid=$item_guid",
		    	'text' => elgg_view_icon('unlink'),
		    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
		    	'encode_text' => false,
		    ));
				
		}
		$supplier_display .= "<div class='rTableRow highlight'>
								<div class='rTableCell' style='padding:0px 0px 5px 5px'>$link</div>
								<div class='rTableCell' style='padding:0px 0px 5px 5px'>$detach</div>
							</div>";
		}
		$supplier_display .= "	</div>
							</div>";
	}	
	else {
		$supplier_display .= "<div class='rTableRow'>
								<div class='rTableCell' style='padding:0px 0px 5px 5px'>No suppliers identified</div>
							</div>
						</div>
					</div>";
	     }	
		
	$link_options = array(
				'name'   => 'link_to_items',
				'label'  => 'Link this supply item to these items ... ',
				'value'  => 1,
		);
	$link_to_items = elgg_view('input/checkbox', $link_options);
	$submit_button = elgg_view('input/submit', array('value' => $submit_label));
	
	$content_options = "<div class='rTable' style='width:100%'>
							<div class='rTableBody'>
								<div class='rTableRow'>
									<div class='rTableCell' style='padding:0px 0px 5px 5px'></div>
									<div class='rTableCell' style='text-align:right'>$submit_button</div>
								</div>
								<div class='rTableRow'>
									<div class='rTableCell' style='padding:0px 0px 5px 5px'>Supply item</div>
									<div class='rTableCell' style='padding:0px 0px 5px 5px'>$supply->title</div>
								</div>
								<div class='rTableRow'>
									<div class='rTableCell' style='padding:0px 0px 5px 5px'>Quantity on hand $qty_on_hand</div>
									<div class='rTableCell' style='padding:0px 0px 5px 5px'>$add_qty</div>
								</div>
							</div>
						</div>";
	$content_options .= $supplier_display;
	$content_options .= "<div class='rTable' style='width:100%'>
							<div class='rTableBody'>
								<div class='rTableRow'>
									<div class='rTableCell'>$link_to_items</div>
								</div>
							</div>
						</div>";
} //@END if ($element_type == 'supply')
$pick_action    = 'market/pick';
$pick_form_vars = array('enctype' => 'multipart/form-data', 
	                    'name'    => 'item_list',
			            'action'  => 'action/pick',
);
$pick_body_vars  = array('element_type'   => $element_type,
       	                 'container_guid' => $container_guid,
		                 'container_type' => $container_type,
		                 'content_options'=> $content_options,
);
$pick_form       = elgg_view_form($pick_action, $pick_form_vars, $pick_body_vars);
$content         = $pick_form;

$body = elgg_view_layout('action', array(
	'content' => $identifiers.$content,
	'title' => $title,
	'filter' => '',
));

/**
 * elgg_view_module() options
 *
 * @uses $vars['type']         The type of module (main, info, popup, aside, etc.)
 *                              - defined as classes in views\default\css\elements\modules.php
 * @uses $vars['title']        Optional title text (do not pass header with this option)
 * @uses $vars['header']       Optional HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       Optional HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */
$module_options = array(
		'type'        => 'popup',
);

// display form 
echo elgg_view_module($module_options, '', $body);
//echo elgg_view_module($module_type, $title, $body);

jot\pages\jot\tag.php
<?php
/**
 * Select an item
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

$element_type   = get_input('element_type');
$container_guid = get_input('container_guid');
$container      = get_entity($container_guid);
$owner          = elgg_get_page_owner_entity();
$container_type = $container->getsubtype();

elgg_gatekeeper();
elgg_group_gatekeeper();

// create form
$title          = elgg_echo("Properties for receipt line item");

//if ($container_type == 'receipt_item'){
	if ($container->show_on_timeline == 1){
	$show_on_timeline = elgg_view('input/checkbox', array(
			'name'   => 'show_on_timeline',
			'label'  => 'Pin to Timeline as',
			'value'  => 1,
			'checked'=> 'checked',
//			'default'=> true,
	));
	}
	else {
		$show_on_timeline = elgg_view('input/checkbox', array(
				'name'   => 'show_on_timeline',
				'label'  => 'Pin to Timeline as',
				'value'  => 1,
//  			'default'=> true,
		));
		
	}
	$timeline_label   = elgg_view('input/text', array(
							'name' => 'timeline_label',
							'value' => $container->timeline_label,	
							));
	if ($container->add_cost_to_que == 1){
		$add_cost_to_que = elgg_view('input/checkbox', array(
				'name'   => 'add_cost_to_que',
				'label'  => 'Add cost to this que',
				'value'  => 1,
				'checked'=> 'checked',
				//			'default'=> true,
		));
	}
	else {
		$add_cost_to_que = elgg_view('input/checkbox', array(
				'name'   => 'add_cost_to_que',
				'label'  => 'Add cost to this que',
				'value'  => 1,
				//  			'default'=> true,
		));
	}
	if ($container->distribute_freight == 1){
		$distribute_freight = elgg_view('input/checkbox', array(
				'name'   => 'distribute_freight',
				'label'  => 'Distribute shipping cost to this item',
				'value'  => 1,
				'checked'=> 'checked',
		));
	}
	else {
		$distribute_freight = elgg_view('input/checkbox', array(
				'name'   => 'distribute_freight',
				'label'  => 'Distribute shipping cost to this item',
				'value'  => 1,
		));
	}
	
	
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
	$label_toggle = elgg_view("input/radio", array(
			"name" => 'retain_label',
			"value"=> $container->retain_line_label,
			"options" => array(
					'Retain my custom label for this receipt line item'    => "yes",
					'Replace my label with the linked item selected below' => "no",
			),
	));
	$content_options = "<div class='rTable' style='width:100%'>
							<div class='rTableBody'>
								<div class='rTableRow'>
									<div class='rTableCell'>$show_on_timeline</div>
									<div class='rTableCell'>$timeline_label</div>
								</div>
						";
	$content_options .= "		<div class='rTableRow'>
									<div class='rTableCell'>$add_cost_to_que</div>
									<div class='rTableCell'>$que_options</div>
								</div>
							</div>
						</div>";
	$content_options .= "<div class='rTable' style='width:100%'>
							<div class='rTableBody'>
								<div class='rTableRow'>
									<div class='rTableCell'>$distribute_freight</div>
								</div>";
	$content_options .= "		<div class='rTableRow'>
									<div class='rTableCell'>$label_toggle</div>
								</div>
							</div>
						</div>";
//}
$pick_form_vars = array('enctype' => 'multipart/form-data', 
	                    'name'    => 'item_list',
			            'action'  => 'action/pick',
);
$pick_body_vars  = array('element_type'   => $element_type,
       	                 'container_guid' => $container_guid,
		                 'content_options'=> $content_options,
);
$pick_form       = elgg_view_form('jot/tag', $pick_form_vars, $pick_body_vars);
$content         = $pick_form;

$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

$module_options = array(
		'title'       => 'lorem ipsum',
		'header'      => 'lorem ipsum',
		'show_inner'  => false,
		'module_type' => 'popup'
);
//$title = '';
//$header = '';
//$show_inner = false;

/**
 * Elgg module element
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
//$module_type = 'popup';

// display form 
echo elgg_view_module($module_options, '', $body);
//echo elgg_view_module($module_type, $title, $body);

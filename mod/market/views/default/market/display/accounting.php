<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
setlocale(LC_MONETARY, 'en_US');
$fields = market_prepare_detailed_view_vars($entity);

/**/

$components = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'component',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));
$accessories = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'accessory',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));
$receipt_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'receipt_item',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => false,
	'limit' => false,
));

$receipt_items = array_merge($receipt_items, elgg_get_entities_from_metadata(array(
                                        'type'                 => 'object',
                                        'subtype'              => 'receipt_item',
                                        'metadata_name_value_pairs' => array('name'=>'item_guid', 'value'=>$item_guid),
                             )));
foreach($receipt_items as $receipt_item){
    $receipt_item_guids[] = $receipt_item->guid;
}
$receipt_item_guids = array_unique($receipt_item_guids); 					$display .= '41 '.print_r($receipt_item_guids, true).'<br>';
unset($receipt_items);
foreach($receipt_item_guids as $key=>$guid){
    $receipt_items[] = get_entity($guid);
}
$related_item_guids = array();
foreach($receipt_items as $receipt_item){
	$linked_items = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $receipt_item->guid,
			'inverse_relationship' => true,
			'limit' => false,
	));
	foreach($linked_items as $linked_item){
		$related_item_guids['linked_item']  = $linked_item->guid;
		$related_item_guids['receipt_item'] = $receipt_item->guid;
//		$related_item_guids[] = array_push($related_item_guids, array('linked_item'=>$linked_item->guid, 'receipt_item'=>$receipt_item->guid));
		
		
	}

	//	$related_item_guids = array_push($related_item_guids, $linked_item->getguid());
	//	echo elgg_dump($linked_item);
}

// combine component receipts with item receipts
if (isset($components)){
	foreach($components as $component){
		$component_receipt_items = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'relationship' => 'transfer_receipt',
				'relationship_guid' => $component->guid,
				'inverse_relationship' => true,
				'limit' => false,
		));
		$receipt_items = array_merge($receipt_items, $component_receipt_items);
	}
}
// combine accessory receipts with item receipts
if (isset($accessories)){
	foreach ($accessories as $accessory){
	    unset($options, $accessory_receipt_items);
	    $options = array('type' => 'object',
                    	'subtype' => 'receipt_item',
                    	'metadata_name_value_pairs' => array(
                    		'name' => 'item_guid',
                    		'value' => $accessory->guid
                    	));
	    $accessory_receipt_items = elgg_get_entities_from_metadata($options);
		$receipt_items = array_merge($receipt_items, $accessory_receipt_items);
	}
    foreach($accessories as $accessory){                                 $display .= '$accessory->title: '.$accessory->title.'<br>';
		unset($accessory_receipt_items);
		$accessory_receipt_items = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'relationship' => 'transfer_receipt',
				'relationship_guid' => $accessory->guid,
				'inverse_relationship' => true,
				'limit' => false,
		));
		$receipt_items = array_merge($receipt_items, $accessory_receipt_items);
	}
}
unset($returns);
foreach ($receipt_items as $receipt_item){
    if ($receipt_item->status != 'returned'){
        continue;
    }                              
    $return_line_items = elgg_get_entities_from_metadata(array('metadata_name_value_pairs'=>array(
            'receipt_line_item_guid' => $receipt_item->getGUID(),
            'aspect'                 => 'return',
    )));
    foreach ($return_line_items as $return_line){                       $display .= '100 $return_line->title:'.$return_line->title.'<br>100 $return_line->guid: '.$return_line->getGUID().'<br>100 aspect: '.$return_line->aspect.'<br>';
        $returns[] = $return_line;
		$credit += $return_line->total + $return_line->shipping_cost + $return_line->sales_tax;
    }
}
foreach ($receipt_items as $receipt_item){                              $display .= '$receipt_item->title: '.$receipt_item->title.'<br>$receipt_item->guid: '.$receipt_item->guid.'<br>$receipt_item->que_contribution: '.$receipt_item->que_contribution.'<br>';
    if ($receipt_item->que_contribution == 'purchase'){
		$purchases[] = $receipt_item;
		$purchase_cost += $receipt_item->total + $receipt_item->shipping_cost + $receipt_item->sales_tax;
	}
	if ($receipt_item->que_contribution == 'maintenance'){
		$maintenance[] = $receipt_item;
		$maintenance_cost += $receipt_item->total + $receipt_item->shipping_cost + $receipt_item->sales_tax;
	}
	if ($receipt_item->que_contribution == 'selling'){
		$selling[] = $receipt_item;
		$selling_cost += $receipt_item->total + $receipt_item->shipping_cost + $receipt_item->sales_tax;
	}
	if ($receipt_item->que_contribution != 'purchase' &&
		$receipt_item->que_contribution != 'maintenance' &&
		$receipt_item->que_contribution != 'selling' &&
		!empty($receipt_item->total)){
		$other[] = $receipt_item;
		$other_cost += $receipt_item->total + $receipt_item->shipping_cost + $receipt_item->sales_tax;
	}
}

// foreach($purchases as $receipt){
// 	$purchase_cost += $receipt->total;
// }
// foreach($maintenance as $receipt){
// 	$maintenance_cost += $receipt->total;
// }
// foreach($selling as $receipt){
// 	$selling_cost += $receipt->total;
// }

echo "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:0'></div>
				<div class='rTableHead' style='width:10%'>Qty</div>
				<div class='rTableHead' style='width:75%'>Item</div>
				<div class='rTableHead' style='width:15%'>Cost</div>
			</div>";

if (!empty($purchases)){
echo "			<div class='rTableRow'>
				<div class='rTableCell' style='width:0'></div>
				<div class='rTableCell' style='width:10%'></div>
				<div class='rTableCell' style='width:75%'><b>Purchases</b></div>
				<div class='rTableCell' style='width:15%'></div>
			</div>
		";

// Populate existing receipt items
$n=0;
foreach($purchases as $item){
	$n = $n++;
	unset($tax_check, $item_total);
	$element_type = 'receipt item';
	$title = $item->title;

	$transfer_receipts = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $item->guid,
			'inverse_relationship' => false,
			'limit' => false,
	));
	$transfer_receipt = get_entity($item->getContainerGUID());
	
	$transfer_receipt_guid = $transfer_receipt->getguid();
//	echo elgg_dump($transfer_receipts);
	
	$line_item = 	elgg_view('output/url', array('text' => $item->title,'href'=>"jot/view/$transfer_receipt_guid", 'class'=>'rTableform90',));

	if ($item->taxable == 1){
		$tax_check = 'x';
	}
	if ($n == 0){
		$item_total = money_format('%.2n', $item->total + $item->shipping_cost + $item->sales_tax);
	}
	else {
		$item_total = number_format($item->total + $item->shipping_cost + $item->sales_tax, 2);
	}

	echo"		<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'>$item->qty</div>
					<div class='rTableCell' style='width:75%'>$line_item</div>
					<div class='rTableCell' style='width:15%;text-align:right'>$item_total</div>
				</div>";
}

	echo"	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%;text-align:right'>Total Purchases</div>
					<div class='rTableCell' style='width:15%;text-align:right'>".money_format('%.2n', $purchase_cost)."</div>
			</div>";
	
}
if (!empty($maintenance)){
	echo "	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%'><b>Maintenance</b></div>
					<div class='rTableCell' style='width:15%'></div>
			</div>";
$n=0;
foreach($maintenance as $item){
	$n = $n++;
	unset($tax_check, $item_total);
	$element_type = 'receipt item';
	$title = $item->title;

	$transfer_receipts = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $item->guid,
			'inverse_relationship' => false,
			'limit' => false,
	));

	$transfer_receipt_guid = $transfer_receipts[0]->getguid();
	//	echo elgg_dump($transfer_receipts);

	$line_item = 	elgg_view('output/url', array('text' => $item->title,'href'=>"jot/view/$transfer_receipt_guid", 'class'=>'rTableform90',));

	if ($item->taxable == 1){
		$tax_check = 'x';
	}
	if ($n == 0){
		$item_total = money_format('%#5n', $item->total);
	}
	else {
		$item_total = number_format($item->total, 2);
	}

	echo"		<div class='rTableRow'>
	<div class='rTableCell' style='width:0'></div>
	<div class='rTableCell' style='width:10%'>$item->qty</div>
	<div class='rTableCell' style='width:75%'>$line_item</div>
	<div class='rTableCell' style='width:15%;text-align:right'>$item_total</div>
	</div>";
}
echo"	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%;text-align:right'>Total Maintenance</div>
					<div class='rTableCell' style='width:15%;text-align:right'>".money_format('%#10n', $maintenance_cost)."</div>
			</div>";
}

if (!empty($selling)){
echo "	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%'><b>Selling</b></div>
					<div class='rTableCell' style='width:15%'></div>
			</div>";
$n=0;
foreach($selling as $item){
	$n = $n++;
	unset($tax_check, $item_total);
	$element_type = 'receipt item';
	$title = $item->title;

	$transfer_receipts = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $item->guid,
			'inverse_relationship' => false,
			'limit' => false,
	));

	$transfer_receipt_guid = $transfer_receipts[0]->getguid();
	//	echo elgg_dump($transfer_receipts);

	$line_item = 	elgg_view('output/url', array('text' => $item->title,'href'=>"jot/view/$transfer_receipt_guid", 'class'=>'rTableform90',));

	if ($item->taxable == 1){
		$tax_check = 'x';
	}
	if ($n == 0){
		$item_total = money_format('%#5n', $item->total);
	}
	else {
		$item_total = number_format($item->total, 2);
	}

	echo"		<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'>$item->qty</div>
					<div class='rTableCell' style='width:75%'>$line_item</div>
					<div class='rTableCell' style='width:15%;text-align:right'>$item_total</div>
				</div>";
}

if (empty($receipt_items)){
echo "			<div class='rTableRow'>
			<div class='rTableCell' style='width:0'></div>
			<div class='rTableCell' style='width:10%'></div>
			<div class='rTableCell' style='width:75%'>No receipts found</div>
			<div class='rTableCell' style='width:15%'></div>
		</div>
	";
}
else {
echo"	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%;text-align:right'>Total Selling</div>
					<div class='rTableCell' style='width:15%;text-align:right'>".money_format('%#10n', $selling_cost)."</div>
			</div>";
	
}
}
if (!empty($other)){
	echo "	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%'><b>Other</b></div>
					<div class='rTableCell' style='width:15%'></div>
			</div>";
	$n=0;
	foreach($other as $item){
		$n = $n++;
	    unset($tax_check, $item_total);
		$element_type = 'receipt item';
		$title = $item->title;

		$transfer_receipts = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'relationship' => 'receipt_item',
				'relationship_guid' => $item->guid,
				'inverse_relationship' => false,
				'limit' => false,
		));
		$transfer_receipt_guid = $transfer_receipts[0]->guid;
	    $line_item = 	elgg_view('output/url', array('text' => $item->title,'href'=>"jot/view/$transfer_receipt_guid", 'class'=>'rTableform90',));
		

	    if ($item->taxable == 1){
	    	$tax_check = 'x';
	    }
	    if ($n == 0){
	    	$item_total = money_format('%#5n', $item->total);
	    }
	    else {
	    	$item_total = number_format($item->total, 2);
	    }
	    
	    echo"		<div class='rTableRow'>
	    <div class='rTableCell' style='width:0'></div>
	    <div class='rTableCell' style='width:10%'>$item->qty</div>
	    <div class='rTableCell' style='width:75%'>$line_item</div>
	    <div class='rTableCell' style='width:15%;text-align:right'>$item_total</div>
	    </div>";
	     
	}
	echo"	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%;text-align:right'>Total Other</div>
					<div class='rTableCell' style='width:15%;text-align:right'>".money_format('%#10n', $other_cost)."</div>
			</div>";	
}
if (!empty($returns)){
	echo "	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%'><b>Returns</b></div>
					<div class='rTableCell' style='width:15%'></div>
			</div>";
	$n=0;
	foreach($returns as $item){
		$n = $n++;
   	    unset($tax_check, $item_total);
		$element_type = 'return item';
		$title = $item->title;

		$return_line_items = elgg_get_entities_from_metadata(array(
                    'receipt_line_item_guid' =>$item->getGUID(),
            ));
		$transfer_receipt_guid = $return_line_items[0]->container_guid;
	    $line_item = 	elgg_view('output/url', array('text' => $title,'href'=>"jot/view/$transfer_receipt_guid", 'class'=>'rTableform90',));

	    if ($item->taxable == 1){
	    	$tax_check = 'x';
	    }
	    if ($n == 0){
	    	$item_total = money_format('%#5n', $item->total);
	    }
	    else {
	    	$item_total = number_format($item->total, 2);
	    }
	    
	    echo"		<div class='rTableRow'>
	    <div class='rTableCell' style='width:0'></div>
	    <div class='rTableCell' style='width:10%'>$item->qty</div>
	    <div class='rTableCell' style='width:75%'>$line_item</div>
	    <div class='rTableCell' style='width:15%;text-align:right'>$item_total</div>
	    </div>";
	     
	}
	echo"	<div class='rTableRow'>
					<div class='rTableCell' style='width:0'></div>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:75%;text-align:right'>Total Returns</div>
					<div class='rTableCell' style='width:15%;text-align:right'>".money_format('%#10n', $credit)."</div>
			</div>";	
}

// Populate form footer
	echo"	<div class='rTableRow'>
				<div class='rTableCell' style='width:0'></div>
				<div class='rTableCell' style='width:10%'></div>
				<div class='rTableCell' style='width:75%;text-align:right'>Total Expenses</div>
				<div class='rTableCell' style='width:15%;text-align:right'>".money_format('%#10n', $purchase_cost + $maintenance_cost + $selling_cost + $other_cost - $credit)."</div>
			</div>
	</div>
</div>";
//echo $display;
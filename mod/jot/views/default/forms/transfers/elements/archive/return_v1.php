Form: jot\views\default\forms\transfers\return.php<br>
<?php
/**********
 * Version 01
 * 
 */
$transfer_guid  = (int) get_input('guid');
$jot       = elgg_extract('jot', $vars, get_entity($transfer_guid));
$entity    = get_entity($transfer_guid);
$title     = $entity->title;
$aspect    = 'return';
$access_id = $vars['access_id'];
$view      = elgg_extract('view', $vars, '');
$referrer = "jot/view/$transfer_guid";
if ($jot){$subtype = $jot->getSubtype();}

$receipt_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'receipt_item',
	'relationship_guid' => $jot->guid,
	'inverse_relationship' => true,
	'limit' => false,
	'order_by_metadata'    => array('name' => 'sort_order', 
			                        'direction' => ASC, 
			                        'as' => 'integer'),
));
$merchants = elgg_get_entities_from_relationship(array(
	'type'                 => 'group',
	'relationship'         => 'merchant_of',
	'relationship_guid'    => $jot->guid,
    'inverse_relationship' => true,
	'limit'                => false,
));
$related_returns = elgg_get_entities_from_relationship(array(
	'type'                 => 'object',
	'relationship'         => 'return',
	'relationship_guid'    => $jot->guid,
    'inverse_relationship' => false,
	'limit'                => false,
)); 
$stored_merchant = get_entity($jot->merchant_guid);
$merchant='';
if ($merchants) {
	foreach ($merchants as $i) {
	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid"));
	$merchant .= "$link<br>";
	}
}
else if ($stored_merchant){
	$link         = elgg_view('output/url', array('text' => $stored_merchant->name,'href' =>  "groups/profile/$stored_merchant->guid"));
	$merchant .= "$link<br>";
}
else {$merchant = $jot->merchant;}
/*
echo '$title: '.$title.'<br>
	  $subtype: '.$subtype.'<br>
	  $aspect: '.$aspect.'<br>
	  $action: '.$action.'<br>
	  $view: '.$view.'<br>';*/
//echo '$transfer_guid: '.$transfer_guid.'<br>';
//echo elgg_dump($jot);

$hidden_field['guid']           = $jot->guid;
$hidden_field['asset']          = $vars['asset'];
$hidden_field['container_guid'] = $vars['container_guid'];
$hidden_field['parent_guid']    = $vars['parent_guid'];
$hidden_field['aspect']         = $aspect;
$hidden_field['item_type']      = 'return_item';
$hidden_field['referrer']       = $referrer;

foreach($hidden_field as $name => $value){
    $hidden_fields .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}
$title_display      = elgg_view('input/hidden'  , array('name' => 'jot[title]', 'value' => $title,));

echo $hidden_fields;
echo "<div id='borders' style='width:375px; border-style:solid; border-width:1px; border-color:#E9EAED'>";

if (count($related_returns) > 0){
	echo "<div class='rTable' style='width:375px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:275px'></div>
					<div class='rTableCell' style='width:100px'>Related Returns</div>
			</div>";
	foreach ($related_returns as $return){
		echo "<div class='rTableRow'>
					<div class='rTableCell' style='width:275px'></div>
					<div class='rTableCell' style='width:100px;list-style-type: circle'>".elgg_view('output/url', array(
																				        		'text' =>  $return->credit_date,
																				        		'href' =>  "jot/view/$return->guid",
																				        ))."</div>
			</div>";
	}
	echo '</div></div>';
}

echo "<div class='rTable' style='width:375px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Title</div>
				<div class='rTableCell' style='width:285px'>$jot->title $title_display</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Merchant</div>
				<div class='rTableCell' style='width:285px'>$merchant</div>
			</div>			
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Purchase Date</div>
				<div class='rTableCell' style='width:285px'>$jot->purchase_date</div>
			</div>			
		</div>
	</div>";

echo "<div class='rTable' style='width:375px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:110px'>Return Date</div>
				<div class='rTableCell' style='width:80px'>".elgg_view('input/date', array(
																	'name' => 'jot[return_date]',
																	'value' => $jot->return_date,
																))."
				</div>
				<div class='rTableCell' style='width:85px'>Return #</div>
				<div class='rTableCell' style='width:100px'>".elgg_view('input/text', array(
																	'name' => 'jot[return_no]',
																	'value' => $jot->return_no,
																))."
				</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:110px'>Expiration Date</div>
				<div class='rTableCell' style='width:80px'>".elgg_view('input/date', array(
																	'name' => 'jot[return_expiration_date]',
																	'value' => $jot->return_expiration_date,
																))."
				</div>
				<div class='rTableCell' style='width:85px'>Return type</div>
				<div class='rTableCell' style='width:100px'>".elgg_view('input/select', array(
																	'name' => 'jot[return_type]',
																	'value' => $jot->return_type,
						                                            'options' => array('pick ...', 'Cash', 'Credit Card', 'Store Credit')
																))."
				</div>
			</div>
		</div>
</div>";

echo "<div class='rTable' style='width:375px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableHead' style='width:5%;text-align:right'>Qty</div>
				<div class='rTableHead' style='width:10%'>Ret</div>
				<div class='rTableHead' style='width:65%'>Item</div>
				<div class='rTableHead' style='width:10%;text-align:right'>Cost</div>
				<div class='rTableHead' style='width:10%;text-align:right'>Rec'd</div>
			</div>
			";
// Populate existing receipt items
$n=0;
foreach($receipt_items as $item){
	$n = $n++;
	$element_type = 'receipt item';
        $title = $item->title;
        $linked_item = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $item->guid,
			'inverse_relationship' => true,
			'limit' => false,
		));
        $line_item_guid = elgg_view('input/hidden', array(
					'name' => 'return_item[line_item_guid][]',
					'value' => $item->guid,
				));
        $linked_item_guid = elgg_view('input/hidden', array(
					'name' => 'return_item[linked_item_guid][]',
					'value' => $linked_item[0]->guid,
				));
        $returned_pieces = elgg_get_entities_from_relationship(array(
					'type' => 'object',
					'relationship' => 'return',
					'relationship_guid' => $item->guid,
					'inverse_relationship' => false,
					'limit' => false,
		        ));
        
        $qty = $item->qty - count($returned_pieces);
        $ret_qty = elgg_view('input/number', array(
			'name'  => 'return_item[qty][]',
        	'max'   => $qty,       	
		));
        if (!empty($linked_item[0]) && $item->retain_line_label == 'no'){
	        $line_item = elgg_view('output/url', array(
	        		'text' =>  $linked_item[0]->title,
	        		'href' =>  "market/view/".$linked_item[0]->guid."/".$linked_item[0]->title."/Inventory",
	        ));
        } else {
        	$line_item = $title;        	
        }
        if ($item->status == 'returned'){$status = '(ret)';} 
        
        $item_total = '';
        $item_total = number_format($item->total + $item->sales_tax, 2);
        $received_value = elgg_view('input/text', array(
					'name' => 'return_item[received_value][]',
					'value' => $item->qty*$item_total,
				)); 

echo"		<div class='rTableRow'>$line_item_guid $linked_item_guid
				<div class='rTableCell' style='width:5%;text-align:right'>$qty</div>
				<div class='rTableCell' style='width:10%'>$ret_qty</div>
				<div class='rTableCell' style='width:65%'>$status $line_item</div>
				<div class='rTableCell' style='width:10%;text-align:right'>$item_total</div>
				<div class='rTableCell' style='width:10%;text-align:right'>$received_value</div>
			</div>";
}
echo "			<div class='rTableRow'>
				<div class='rTableCell' style='width:5%'></div>
				<div class='rTableCell' style='width:10%'></div>
				<div class='rTableCell' style='width:65%;text-align:right'>Credit Amount</div>
				<div class='rTableCell' style='width:10%;text-align:right'></div>
				<div class='rTableCell' style='width:10%;text-align:right'>".elgg_view('input/text', array(
																				'name' => 'jot[credit]',
																				'value' => $jot->total_cost,
																			))."</div>
			</div>";
echo "</div>
	</div>";

echo
'<div class="elgg-foot">'.
   elgg_view('input/submit', array('value' => elgg_echo('jot:save:return'), 'name' => 'submit')).
'</div>';
echo "</div id='borders'>";
<!-- Form: jot\views\default\forms\transfers\elements\return.php -->
<?php
/**********
 * Return receipt
 * Version 01
 * 
 */

setlocale(LC_MONETARY, 'en_US');
$transfer_guid  = (int) get_input('guid');                             $display       .= '$transfer_guid='.$transfer_guid.'<br>';
$section        = get_input('section');                                $display       .= '$section='.$section.'<br>';
$aspect         = elgg_extract('aspect',$vars);                        $display       .= '<br>$aspect='.$aspect.'<br>';
//$aspect         = $section;
//$aspect         = $vars['aspect'];
$asset          = elgg_extract('asset', $vars);
$container_guid = elgg_extract('container_guid', $vars);
$owner_guid     = elgg_extract('owner_guid', $vars, elgg_get_logged_in_user_guid());
$referrer       = elgg_extract('referrer', $vars);                     
$subtype        = 'transfer';
$graphics_root  = elgg_get_site_url().'_graphics';
$presentation   = elgg_extract('presentation', $vars, 'full');         $display       .= '$presentation='.$presentation.'<br>';

$receipt_exists = true; //  Does a transfer receipt already exist?  Initialize to 'true'
$return_exists  = false;
$item_exists    = false;
$existing_item  = false;
if ($transfer_guid == 0){
	$receipt_exists = false;
	$return_exists  = false;
}
else {
	$transfer = get_entity($transfer_guid);
    if (elgg_instanceof($transfer, 'object', 'transfer') && $transfer->aspect == 'receipt'){
        $title                 = $transfer->title;
        $aspect                = $transfer->aspect;
        $container_guid        = $transfer_guid;
        $receipt_exists        = true;
    }
    elseif (elgg_instanceof($transfer, 'object', 'transfer') && $transfer->aspect == 'return'){
        $title                 = $transfer->title;
        $aspect                = $transfer->aspect;
        $container_guid        = $transfer->container_guid;
        $hidden_fields['guid'] = $transfer_guid;
        $hidden_fields['jot[guid]'] = $transfer_guid;
        $return_exists         = true;
        $receipt_items         = elgg_get_entities(array('container_guid'=>$transfer_guid));
        $receipt               = get_entity($transfer->container_guid);
    }
    else {
        $title                 = $transfer->title;
        $hidden_fields['guid'] = $transfer_guid; 
    }
}                                                                      $display .= '53 $return_exists: '.$return_exists.'<br>53 $existing_item: '.$existing_item.'<br>53 $receipt->title: '.$receipt->title.'<br>';
?>
<script type="text/javascript">
//any time the amount changes
$(document).ready(function() {
    var taxable = 0;
    var tax_rate = 0;
    $('.ItemRow').each(function() {
        var $row = $(this).parent();
        var qty  = $row.find('input[id=qty_available]').val();
        var tax  = $row.find('input[id=tax_check]').val();
        var cost = $row.find('input[id=c]').val();
        taxable += parseFloat(qty*tax*cost, 10);
     });
    var receipt_total = $('input[id=receipt_total]').val();
    var sales_tax = $('input[id=tax_total]').val();
    var shipping = $('input[id=ship_total]').val();
    var sales_tax_rate = $('input[id=tax_rate]').val();
    if (receipt_total > 0){tax_rate = sales_tax/(receipt_total-sales_tax-shipping);}
 //@TODO This is a bug.  User's cannot enter their own tax rate.
    $('input[id=tax_rate]').val(Math.round(tax_rate*10000)/100);
//     if (sales_tax_rate is NULL){$('input[id=tax_rate]').val(Math.round(tax_rate*10000)/100);}
//     else {$('input[id=tax_rate]').val(sales_tax_rate);}
    //**********************************************************
    $('input[id=return_me]').change(function(e) {
    	var total     = 0;
        var sub_total = 0;
        var sales_tax = 0;
        var $row          = $(this).parent().parent();
        var checked_value = $(this).prop("checked");
        var tax           = $row.find('input[id=tax_check]').val();
        var cost          = $row.find('input[id=c]').val();
        if (checked_value){
            var qty_available = $row.find('input[id=qty_available]').val();
            $row.find('input[id=q]').val(qty_available);
            owed              = parseFloat(qty_available * cost);
            $row.find('input[id=v]').val(owed);
        }
        if (!checked_value){
            $row.find('input[id=q]').val(0);
            $row.find('input[id=v]').val(0);}
        
        $('input[id=v]').each(function() {
            //Get the value
            var am= $(this).val();
            console.log(am);
            //if it's a number add it to the total
             if (IsNumeric(am)) {
             	sub_total += parseFloat(am, 10);
             	sales_tax += parseFloat(Math.round(am*tax_rate*100)/100, 10);
             }
         });
         $('.sub_total').text(sub_total);
         $('input[id=tax]').val(sales_tax);
         var sub_total = $('.sub_total').text();
         var shipping = $('input[id=ship]').val();
         var sales_tax = $('input[id=tax]').val();
         if (IsNumeric(sub_total)) {total += parseFloat(sub_total, 10);}
         if (IsNumeric(shipping)) {total += parseFloat(shipping, 10);}
         if (IsNumeric(sales_tax)){total += parseFloat(sales_tax, 10);}
         $('.total').text(total);
         $('input[id=total]').val(total);
    });
    
    //**********************************************************

    $('input[id=q],input[id=c]').change(function(e) {
    	var total     = 0;
        var sub_total = 0;
        var sales_tax = 0;
        var $row = $(this).parent().parent();
        var qty  = $row.find('input[id=q]').val();
        var tax  = $row.find('input[id=tax_check]').val();
        var cost = $row.find('input[id=c]').val();
        owed = parseFloat(qty * cost);
        //update the received amount
        $row.find('input[id=v]').val(owed);
        $('input[id=v]').each(function() {
            //Get the value
            var am= $(this).val();
            console.log(am);
            //if it's a number add it to the total
             if (IsNumeric(am)) {
             	sub_total += parseFloat(am, 10);
             	sales_tax += parseFloat(Math.round(am*tax_rate*100)/100, 10);
             }
         });
         $('.sub_total').text(sub_total);
         $('input[id=tax]').val(sales_tax);
        var sub_total = $('.sub_total').text();
        var shipping = $('input[id=ship]').val();
        var sales_tax = $('input[id=tax]').val();
        if (IsNumeric(sub_total)) {total += parseFloat(sub_total, 10);}
        if (IsNumeric(shipping)) {total += parseFloat(shipping, 10);}
        if (IsNumeric(sales_tax)){total += parseFloat(sales_tax, 10);}
        $('.total').text(total);
        $('input[id=total]').val(total);
    });
    //**********************************************************
    $('input[id=v]').change(function(e) {
        var sub_total = 0;
        $('input[id=v]').each(function() {
            //Get the value
            var am= $(this).val();
            console.log(am);
            //if it's a number add it to the total
            if (IsNumeric(am)) {
            	sub_total += parseFloat(am, 10);
            }
        });
        $('.sub_total').text(sub_total);
        sub_total = $('.sub_total').text();
        var total = 0;
        var shipping = $('input[id=ship]').val();
        var sales_tax = $('input[id=tax]').val();
        if (IsNumeric(sub_total)) {total += parseFloat(sub_total, 10);}
        if (IsNumeric(shipping)) {total += parseFloat(shipping, 10);}
        if (IsNumeric(sales_tax)){total += parseFloat(sales_tax, 10);}
        $('.total').text(total);
    });
    //**********************************************************
    $('input[id=tax],input[id=ship]').change(function(e) {
        var total = 0;
        var sub_total = $('.sub_total').text();
        var shipping = $('input[id=ship]').val();
        var sales_tax = $('input[id=tax]').val();
        if (IsNumeric(sub_total)) {total += parseFloat(sub_total, 10);}
        if (IsNumeric(shipping)) {total += parseFloat(shipping, 10);}
        if (IsNumeric(sales_tax)){total += parseFloat(sales_tax, 10);}
        $('.total').text(total);
    });
    //**********************************************************
    $('input[id=tax_check]').change(function(e) {
        var $row           = $(this).parent().parent();
        var $checked_value = $(this).val();
        $row.find('input[id=taxable]').val($checked_value);
        if ($row.find('input[id=q]').val() > 0){
            var sub_total = $('.sub_total').text();
            var shipping = $('input[id=ship]').val();
            $('input[id=v]').each(function() {
                //Get the value
                var am= $(this).val();
                console.log(am);
                //if it's a number add it to the total
                if (IsNumeric(am)) {
                 	sales_tax += parseFloat(Math.round(am*tax_rate*100)/100, 10);
                }
            });
        }
        $('.sub_total').text(sub_total);
    });
});
//isNumeric function Stolen from: 
//http://stackoverflow.com/questions/18082/validate-numbers-in-javascript-isnumeric

function IsNumeric(input) {
    return (input - 0) == input && input.length > 0;
}
</script>
<?php

$hidden_fields['action']         = elgg_extract('action', $vars);
$hidden_fields['section']        = $section;
$hidden_fields['aspect']         = 'return';
$hidden_fields['subtype']        = $subtype;
$hidden_fields['container_guid'] = $container_guid;
$hidden_fields['item_type']      = 'return_item';
$hidden_fields['presentation']   = $presentation;

$hidden_fields['jot[aspect]']         = 'return';
$hidden_fields['jot[owner_guid]']     = $owner_guid;
$hidden_fields['jot[container_guid]'] = $container_guid;

if ($receipt_exists){
	// switch $subtype if it exists
	$subtype       = $transfer->getSubtype();
	$receipt_items = $receipt_items ?: elgg_get_entities_from_relationship(array(
                                    		'type'                 => 'object',
                                    		'relationship'         => 'receipt_item',
                                    		'relationship_guid'    => $transfer_guid,
                                    		'inverse_relationship' => true,
                                    		'limit'                => false,
                                    		// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
                                    		'order_by_metadata'    => array('name' => 'sort_order', 
                                    				                        'direction' => ASC, 
                                    				                        'as' => 'integer'),
                                    	));
    $merchant = $transfer->merchant ?: $receipt->merchant;                                        $display .= '231 $merchant: '.$merchant.'<br>';
    if (elgg_entity_exists($merchant) 
     // Account for merchants whose name begins with a number
     // && is_int($merchant)
            ){
        if (!empty($merchant) && elgg_entity_exists($merchant)){
            $merchants = array(get_entity($merchant));}                      $display .= '238 isset($merchants): '.isset($merchants).'<br>238 $merchant: '.print_r($merchants[0], true).'<br>';
    	if (!empty($merchant) && empty($merchants)){
        	$merchants = elgg_get_entities_from_relationship(array(
        		'type'                 => 'group',
        		'relationship'         => 'merchant_of',
        		'relationship_guid'    => $transfer_guid,
        	    'inverse_relationship' => true,
        		'limit'                => false,
        	));
    	}                                                                     $display .= '243 isset($merchants): '.isset($merchants).'<br>';
    	if (!empty($merchant) && empty($merchants)){
    		// provided for backward compatibility
    		$merchants = elgg_get_entities_from_relationship(array(
    				'type'                 => 'group',
    				'relationship'         => 'supplier_of',
    				'relationship_guid'    => $transfer_guid,
    				'inverse_relationship' => true,
    				'limit'                => false,
    		));
    	}
    }                                                                        $display .= '254 isset($merchants): '.isset($merchants).'<br>';
} //@END 218 if ($receipt_exists)
else {
    unset($hidden_fields['guid']);
}                                                                                 //$display       .= '135 $merchants[0]->name = '.$merchants[0]->getGuid().'<br>';
                                                                                  $display       .= '136 $receipt_items[0][retain_line_label] = '.$receipt_items[0]['retain_line_label'].'<br>136 $receipt_items->retain_line_label = '.$receipt_items->retain_line_label.'<br>';
if (!empty($merchants)) {
    if (elgg_entity_exists($merchants[0]->guid)){
    	$merchant = elgg_view('output/url', array('text' => $merchants[0]->name,'href' =>  "groups/profile/".$merchants[0]->getguid()));
	}
}
elseif (elgg_instanceof(get_entity($merchant), 'group')){                                                       $display .= '242 $merchant: '.$merchant.'<br>';
    $merchant = elgg_view('output/url', array('text' => get_entity($merchant)->name,'href' =>  "groups/profile/".$transfer->merchant ?: $receipt->merchant));
}
else {$merchant = $merchant;}
                                                                                 $display .= '255 $merchant: '.$merchant.'<br>';
if ($presentation == 'full'){

foreach($hidden_fields as $name => $value){
    $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}
    $receipt_link   = elgg_view('output/url'  , array('text' => $receipt->title , 'href'=>"jot/view/$receipt->guid"))
	               ?: elgg_view('output/url'  , array('text' => $transfer->title, 'href'=>"jot/view/$transfer->guid"));
    $title_input    = elgg_view('input/text'  , array('name' => 'jot[title]'                  , 'value' => $title,));
    $title_input   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][title]'        , 'value' => $title,));
    $cashier        = elgg_view('input/text'  , array('name' => 'jot[cashier]'                , 'value' => $transfer->cashier,));
    $cashier       .= elgg_view('input/hidden', array('name' => 'jot[snapshot][cashier]'      , 'value' => $transfer->cashier,));
    $moment  = $receipt->moment
                   ?: $transfer->moment;
    $return_date    = elgg_view('input/date'  , array('name' => 'jot[return_date]'            , 'value' => $transfer->return_date,));
    $return_date   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][return_date]'  , 'value' => $transfer->return_date,));
    $expire_date    = elgg_view('input/date'  , array('name' => 'jot[expire_date]'            , 'value' => $transfer->expire_date,));
    $expire_date   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][expire_date]'  , 'value' => $transfer->expire_date,));
    $returned_by    = elgg_view('input/text'  , array('name' => 'jot[returned_by]'            , 'value' => $transfer->returned_by,));
    $returned_by   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][returned_by]'  , 'value' => $transfer->returned_by,));
    $return_no      = elgg_view('input/text'  , array('name' => 'jot[return_no]'              , 'value' => $transfer->return_no,));
    $return_no     .= elgg_view('input/hidden', array('name' => 'jot[snapshot][return_no]'    , 'value' => $transfer->return_no,));
    $invoice_no     = elgg_view('input/text'  , array('name' => 'jot[invoice_no]'             , 'value' => $transfer->invoice_no,));
    $invoice_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][invoice_no]'   , 'value' => $transfer->invoice_no,));
    $document_no    = elgg_view('input/text'  , array('name' => 'jot[document_no]'            , 'value' => $transfer->document_no,));
    $document_no   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][document_no]'  , 'value' => $transfer->document_no,));
    $receipt_no     = elgg_view('input/text'  , array('name' => 'jot[receipt_no]'             , 'value' => $transfer->receipt_no,));
    $receipt_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][receipt_no]'   , 'value' => $transfer->receipt_no,));
    $return_type    = elgg_view('input/select', array('name' => 'jot[return_type]'            , 'value' => $transfer->return_type, 'options' => array('pick ...', 'Cash', 'Credit Card', 'Store Credit')));
    $return_type   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][return_type]'  , 'value' => $transfer->return_type));

	$form .= "
        <div class='rTable' style='width:600px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:90px'>Merchant</div>
					<div class='rTableCell' style='width:510px'>$merchant</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:90px'>Purchase Date</div>
					<div class='rTableCell' style='width:510px'>
    					<div class='rTable'>
					        <div class='rTableBody'>
					            <div class='rTableRow'>
					                <div class='rTableCell' style='width:180px'>$moment</div>
					                <div class='rTableCell' style='width:100px'>Receipt</div>
					                <div class='rTableCell' style='width:230px'>$receipt_link</div>
					            </div>
					        </div>
    					</div>
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:90px'>Return Title</div>
					<div class='rTableCell' style='width:510px'>$title_input</div>
				</div>
			</div>
		</div>";
	
	$form .= "<div class='rTable' style='width:700px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'>Sales Assoc.</div>
					<div class='rTableCell' style='width:200px'>$cashier</div>
					<div class='rTableCell' style='width:100px'>Return Date</div>
					<div class='rTableCell' style='width:120px'>$return_date</div>
					<div class='rTableCell' style='width:100px'>Return #</div>
					<div class='rTableCell' style='width:120px'>$return_no</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'>Returned by</div>
					<div class='rTableCell' style='width:200px'>$returned_by</div>
					<div class='rTableCell' style='width:100px'>Expiration date</div>
					<div class='rTableCell' style='width:120px'>$expire_date</div>
					<div class='rTableCell' style='width:100px'>Return type</div>
					<div class='rTableCell' style='width:120px'>$return_type</div>
				</div>
			</div>
	</div>
	<div class='rTable' style='width:100%'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableHead' style='width:5%'>Avail</div>
					<div class='rTableHead' style='width:5%'>Ret'd</div>
					<div class='rTableHead' style='width:60%'>Item</div>
					<div class='rTableHead' style='width:5%'>Tax?</div>
					<div class='rTableHead' style='width:10%'>Cost</div>
					<div class='rTableHead' style='width:10%'>Received</div>
					<div class='rTableCell' style='width:10%'></div>
				</div>
				";
                                                                               $display       .= '340 $receipt_items[0][retain_line_label] = '.$receipt_items[0]['retain_line_label'].'<br>';
	// Populate existing receipt items
	$n=0;
	foreach($receipt_items as $item){
		$n = $n+1;
		$element_type = 'receipt item';                                        $display .= '347 $item[retain_line_label]: '. $item['retain_line_label'].'<br>';
		unset($hidden_line_item_fields);
		$can_edit = $existing_item ?: $item->canEdit(); 
		$linked_item = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $item->guid,
			'inverse_relationship' => true,
			'limit' => false,
		));
        $returned_pieces  = elgg_get_entities_from_relationship(array(
					'type' => 'object',
					'relationship' => 'return',
					'relationship_guid' => $item->guid,
					'inverse_relationship' => false,
					'limit' => false,
		        ));
        
        $qty     = $item->qty - count($returned_pieces);
        $ret_qty = elgg_view('input/number', array('id' => 'q', 'name' => 'return_item[qty][]', 'value'=>$item->qty, 'max' => $qty,));
        $qty    .= elgg_view('input/hidden', array('id'=>'qty_available', 'value'=>$item->qty));
        
		if ($can_edit) {                                                       $display .= '351 can edit<br>';
			$select = elgg_view('input/checkbox', array('id'=>'return_me', 'default'=>false));
	        if ($existing_item){
	            $linked_item[] = $originating_item;                            $display .= '370 is_array($linked_item): '.is_array($linked_item).'<br>';
	            $item['retain_line_label'] = 'no';                             $display .= '372 $item[item_guid]: '.$item['item_guid'].'<br>';
	        }
	        elseif (!empty($item['item_guid'])){
	            $linked_item[] = get_entity($item['item_guid']);
	        }
	        else {                                                             $display .= '377 $linked_item->guid: '.$linked_item->guid.'<br>';
	            $linked_item = elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship' => 'receipt_item',
    				'relationship_guid' => $item->guid,
    				'inverse_relationship' => true,
    				'limit' => false,
    			));
	        }                                                                  $display .= '384 $linked_item[0]->guid:'.$linked_item[0]->guid.'<br>';
		    //populate defaults
            $hidden_line_item_fields['return_item[linked_item_guid][]'] = $linked_item[0]->guid;
            $hidden_line_item_fields['return_item[line_item_guid][]']   = $item->guid;
            $hidden_line_item_fields['return_item[subtype][]']          = 'return_item';
            
	        if (is_array($linked_item) && ($item->retain_line_label == 'no' || $item['retain_line_label'] == 'no' || $bulk_transfer)){         $display .= '388 link'.$item->guid.'<br>';
	            $item_guid = $linked_item[0]->guid ?: $item->item_guid;
				if (!$existing_item){
		            $hidden_line_item_fields['return_item[title][]']             = $item->title;
		            $hidden_line_item_fields['return_item[line_item_subtype][]'] = $item->getSubtype();
		            $hidden_line_item_fields['return_item[timeline_label][]']    = $item->timeline_label;
		            $hidden_line_item_fields['return_item[que_contribution][]']  = $item->que_contribution;
		            $hidden_line_item_fields['return_item[item_guid][]']         = $item->item_guid;
				}
	        	$line_item .= elgg_view('output/url', array(
	        			'text' =>  $linked_item[0]->title ?: $item->title,
	        			'href' =>  "market/view/$item_guid/Inventory",
		        		'class'=> 'rTableform90',
	        	    ));
		        if ($existing_item){
		            $hidden_line_item_fields['return_item[title][]']             = $originating_item->title;
		            $hidden_line_item_fields['return_item[item_guid][]']         = $originating_item->guid;
		        }
	        } else {                                                                                     $display .= '421 input<br>';
	        	$line_item = $item->title;        	
	        }
            $cost = $existing_item ?$originating_item->acquire_cost :$item->cost;
//$display .= '$line_item: '.$line_item.'<br>';
	        if ($item->taxable == 1){
	        	$tax_options = array('id'      => 'tax_check', 
	        	                     'name'    => 'return_item[taxable][]',
							         'checked' => 'checked',
							         'value'   => 1,
	        			             'default' => false,
	        			            );
	            $tax_check = elgg_view('input/checkbox', $tax_options);
	        	//$tax_check  = 'x';
	        	$tax_check .= elgg_view('input/hidden', array('id' => 'taxable', 'value' => $item->taxable));
	        } else {
	        	$tax_options = array('id'      => 'tax_check', 
	        	                     'name'    => 'return_item[taxable][]',
							         'value'   => 1,
	        			             'default' => false,
								    );
	        	$tax_check  = elgg_view('input/hidden', array('id' => 'taxable', 'value' => 0));
	        }
	        unset($item_total);
	        if (!empty($item->total) && $item->sort_order == 1){
	        	$item_total = money_format('%#10n', $item->total);
	        }
	        else {
	        	$item_total = number_format($item->total, 2);
	        }
	        $cost = elgg_view('input/hidden', array('id' => 'c', 'name' => 'cost', 'value' => $item->total));
		}
		if ($ret_qty*$item_total/$qty > 0){$this_value = $ret_qty*$item_total/$qty;}
        $received_value = elgg_view('input/text', array(
					'id'   => 'v',
                    'name' => 'return_item[received_value][]',
					'value' => $this_value,
                    'placeholder'=> 0,
				)); 

	$form .= "	<div class='rTableRow ItemRow'>
					<div class='rTableCell' style='width:5%'>$select</div>
					<div class='rTableCell' style='width:5%'>$qty</div>
					<div class='rTableCell' style='width:5%'>$ret_qty</div>
					<div class='rTableCell' style='width:60%'>$line_item</div>
					<div class='rTableCell' style='width:5%'>$tax_check</div>
					<div class='rTableCell' style='width:10%;text-align:right'>$item_total{$cost}</div>
					<div class='rTableCell' style='width:10%'>$received_value</div>";
                	foreach($hidden_line_item_fields as $name => $value){
                        $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
                    }
	$form .="   </div>";
	}
	$shipping_cost  = elgg_view('input/text'  , array('id'=>'ship', 'name' => 'jot[shipping_cost]'));
	$shipping_cost .= elgg_view('input/hidden', array('id'=>'ship_total', 'name' => 'jot[snapshot][shipping_cost]','value' => $transfer->shipping_cost,));
	$sales_tax      = elgg_view('input/text'  , array('id'=>'tax', 'name' => 'jot[sales_tax]'));
	$sales_tax     .= elgg_view('input/hidden', array('id'=>'tax_total', 'value' => $transfer->sales_tax,));
	$sales_tax_rate = elgg_view('input/text'  , array('id'=>'tax_rate', 'placeholder'=>'%'));
	$receipt_total  = elgg_view('input/hidden', array('id'=>'receipt_total', 'value'=>$transfer->total_cost));
	$return_total   = elgg_view('input/hidden', array('id'=>'total', 'name'=>'jot[total]'));
//	$return_total  = money_format('%#10n', $transfer->total_cost);
	
	$form .= "  <div class='rTableRow'>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:10%'>Subtotal</div>
					<div class='rTableCell sub_total' style='width:10%;text-align:right'></div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:10%'>Shipping</div>
					<div class='rTableCell' style='width:10%'>$shipping_cost</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'>$sales_tax_rate</div>
					<div class='rTableCell' style='width:10%'>Sales Tax</div>
					<div class='rTableCell' style='width:10%'>$sales_tax</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'>$receipt_total</div>
					<div class='rTableCell' style='width:10%'>Total</div>
					<div class='rTableCell total' style='width:10%;text-align:right'></div>
					$return_total
				</div>
			</div>
	</div>";
	$cancel_button = elgg_view('input/button', array(
			'value' => elgg_echo('cancel'),
			'href' =>  $transfer ? $referrer : '#',
	));
	$delete_link = elgg_view("output/url",array(
		    	'href' => "action/jot/delete?guid=$transfer_guid&delete_contents=true",
		    	'text' => 'Delete',
		    	'confirm' => sprintf('Delete receipt?'),
		    	'encode_text' => false,
		    ));
	$delete_button = elgg_view('input/button', array(
			'value' => elgg_echo('cancel'),
			'href' =>  $delete_link,
	));
	
	$form .='<div class="elgg-foot">'.
	   elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'submit')).
//	   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply')).
	'<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>'.
	$delete_link.'
	</div>';
							
$form .= "
		</div>
	</div>";
}
if ($presentation == 'box'){
    $transfer_guid  = (int) get_input('guid');
    $jot       = elgg_extract('jot', $vars, get_entity($transfer_guid));
    $transfer  = get_entity($transfer_guid);
    $title     = $transfer->title;
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
    $title_hidden      = elgg_view('input/hidden'  , array('name' => 'jot[title]', 'value' => $title,));
    
    $form .= $hidden_fields;
    $form .= "<div id='borders' style='width:375px; border-style:solid; border-width:1px; border-color:#E9EAED'>";
    
    if (count($related_returns) > 0){
    	$form .= "<div class='rTable' style='width:375px'>
    			<div class='rTableBody'>
    				<div class='rTableRow'>
    					<div class='rTableCell' style='width:275px'></div>
    					<div class='rTableCell' style='width:100px'>Related Returns</div>
    			</div>";
    	foreach ($related_returns as $return){
    		$form .= "<div class='rTableRow'>
    					<div class='rTableCell' style='width:275px'></div>
    					<div class='rTableCell' style='width:100px;list-style-type: circle'>".elgg_view('output/url', array(
    																				        		'text' =>  $return->credit_date,
    																				        		'href' =>  "jot/view/$return->guid",
    																				        ))."</div>
    			</div>";
    	}
    	$form .= '</div></div>';
    }
    
    $form .= "<div class='rTable' style='width:375px'>
    		<div class='rTableBody'>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:90px'>Return Title</div>
    				<div class='rTableCell' style='width:285px'>$jot->title $title_hidden</div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:90px'>Merchant</div>
    				<div class='rTableCell' style='width:285px'>$merchant</div>
    			</div>			
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:90px'>Purchase Date</div>
    				<div class='rTableCell' style='width:285px'>$jot->moment</div>
    			</div>			
    		</div>
    	</div>";
    
    $form .= "<div class='rTable' style='width:375px'>
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
    
    $form .= "<div class='rTable' style='width:375px'>
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
    
    $form .= "	<div class='rTableRow'>$line_item_guid $linked_item_guid
    				<div class='rTableCell' style='width:5%;text-align:right'>$qty</div>
    				<div class='rTableCell' style='width:10%'>$ret_qty</div>
    				<div class='rTableCell' style='width:65%'>$status $line_item</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>$item_total</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>$received_value</div>
    			</div>";
    }
    $form .= "	<div class='rTableRow'>
    				<div class='rTableCell' style='width:5%'></div>
    				<div class='rTableCell' style='width:10%'></div>
    				<div class='rTableCell' style='width:65%;text-align:right'>Credit Amount</div>
    				<div class='rTableCell' style='width:10%;text-align:right'></div>
    				<div class='rTableCell' style='width:10%;text-align:right'>".elgg_view('input/text', array(
    																				'name' => 'jot[credit]',
    																				'value' => $jot->total_cost,
    																			))."</div>
    			</div>";
    $form .= "</div>
    	</div>";
    
    $form .=
    '<div class="elgg-foot">'.
       elgg_view('input/submit', array('value' => elgg_echo('jot:save:return'), 'name' => 'submit')).
    '</div>';
    $form .= "</div id='borders'>";
} //@END 648 if ($presentation == 'box')
echo $form;
eof:
echo $display;

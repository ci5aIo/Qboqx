<!--View: jot/views/default/jot/display/transfer/summary.php-->

<?php
/**
 * transfers display body
 *
 * @package ElggPages	
 */

setlocale(LC_MONETARY, 'en_US');
$transfer_guid  = (int) get_input('guid');
$transfer_guid  = elgg_extract('item_guid'     , $vars, $transfer_guid);
$entity         = get_entity($transfer_guid);            //foreach($entity as $key=>$value){$display .= '13 $entity->'.$key.'=>'.$value.'<br>';}
$aspect         = elgg_extract('aspect'        , $vars, $entity->aspect);                 $display .= '$aspect: '.$aspect.'<br>';
$aspect_02      = elgg_extract('aspect_02'     , $vars, $entity->aspect_02);
$asset          = elgg_extract('asset'         , $vars, $entity->asset);
$container_guid = elgg_extract('container_guid', $vars, $entity->container_guid);
$presentation   = elgg_extract('presentation'  , $vars, 'full');                           $display .= '$presentation: '.$presentation.'<br>';
$referrer       = elgg_extract('referrer'      , $vars);
$graphics_root  = elgg_get_site_url().'_graphics';
$variables      = elgg_get_config("{$aspect}_{$aspect_02}"); 
$receipt_items  = elgg_get_entities_from_relationship(array(
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
$receipt_items  = $receipt_items ?: elgg_get_entities_from_metadata(array(
                                        'type'                 => 'object',
                                        'subtype'              => 'receipt_item',
                                        'container_guid'       => $transfer_guid,
                                		// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
                                		'order_by_metadata'    => array('name' => 'sort_order', 
                                				                        'direction' => ASC, 
                                				                        'as' => 'integer'),
                 ));
$return_items = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'return_item',
		'relationship_guid' => $transfer_guid,
		'inverse_relationship' => false,
		'limit' => false,
		'order_by_metadata'    => array('name' => 'sort_order', 
				                        'direction' => ASC, 
				                        'as' => 'integer'),
	));
		$return_items = array_merge($return_items, 
		        elgg_get_entities_from_relationship(array(
        			'type' => 'object',
        			'relationship' => 'return_item',
        			'relationship_guid' => $transfer_guid,
        			'inverse_relationship' => true,
        			'limit' => false,
        			'order_by_metadata'    => array('name' => 'sort_order', 
        					                        'direction' => ASC, 
        					                        'as' => 'integer'),
		)));
		    $return_items = array_merge($return_items,
		            elgg_get_entities(array('container_guid'=>$transfer_guid,
		)));
$shipment_receipts = elgg_get_entities([
		                 'type'                => 'object',
		                 'subtype'             => 'transfer',
		                 'container_guid'      => $transfer_guid,
		                 'limit'               => false]);
Switch ($aspect){
    case 'receipt':
        $receipt_items = $receipt_items;
        break;
    case 'return':
        $receipt_items = $return_items;
        break;
}
/*		    $receipt_items = array_merge($receipt_items, 
		            elgg_get_entities(array(
		                    'subtypes'=>'return_item', 
		                    'container_guid'=>$transfer_guid,
		    )));
*/		    
if (isset($entity->merchant)){
    $merchants = get_entity($entity->merchant);
}
if (empty($merchants)){
    $merchants = elgg_get_entities_from_relationship(array(
    	'type' => 'group',
    	'relationship' => 'merchant_of',
    	'relationship_guid' => $transfer_guid,
        'inverse_relationship' => true,
    	'limit' => false,
    ));
	$merchants = array_merge($merchants, elgg_get_entities_from_relationship(array(
			'type' => 'group',
			'relationship' => 'return_receipt',
			'relationship_guid' => $transfer_guid,
		    'inverse_relationship' => false,
			'limit' => false,
		)));
    $merchants = array_merge($merchants, elgg_get_entities_from_relationship(array(
    			'type' => 'group',
    			'relationship' => 'supplier_of',
    			'relationship_guid' => $transfer_guid,
    			'inverse_relationship' => true,
    			'limit' => false,
    	)));
}
																							$display .= '$transfer_guid '.$transfer_guid.'<br>';
																							$display .= 'merchant guid: '.$merchants[0]->guid.'<br>';
$icon_class = elgg_extract('icon_class', $vars);
$tag_icon   = elgg_view_icon('tag', $icon_class);
$url        = "jot/jot/$transfer_guid/tag/Merchant";
$tag_merchant = "<span title='Describe merchant for this transaction'>".
                     elgg_view('output/url', array(
						"href" => $url,
						"text" => $tag_icon,
						"class" => "elgg-lightbox",
					    'data-colorbox-opts' => '{"width":500, "height":400}',
				))."</span>";
//$merchant='';
$variables = array();
// $tag       = array();
// $tag['merchant'] = $entity->merchant;
$variables['entity'] = $entity;
$variables['tag_type'] = 'Merchant';
if (isset($merchants)) {
    $element_type = 'merchant';
	$i = $merchants[0] ?: $merchants;
    $variables['entity']['merchant'] = $i;
    if (elgg_entity_exists($i->guid)){
        $merchant_guid = (int) $i->guid;
    	$merchant  = "<span class='hoverinfo'>
    						<span style='width:150px;'>".
    							elgg_view('jot/display/jot/hoverinfo', $variables)
    		
    							."
    						</span>".
    							elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$merchant_guid"))
    							."
    		              </span>";
    }
    else {
        $merchant  = "<span class='hoverinfo'>
    						<span style='width:150px;'>".
    							elgg_view('jot/display/jot/hoverinfo', $variables)
    		
    							."
    						</span>".
    							$entity->merchant
    							."
    		              </span>";
    }
}
else {
    if (elgg_entity_exists($entity->merchant)){
        $merchant_guid = $entity->merchant;
    }
    $merchant = "<span class='hoverinfo'>
						<span style='width:200px;'>".
							elgg_view('jot/display/jot/hoverinfo', $variables)
		
							."
						</span>".
							$entity->merchant
							."
		              </span>";
}

Switch ($aspect) {
	case 'receipt':
		$actor_label          = 'Buyer';
		$actor                = $entity->purchased_by;
		$order_no_label       = 'Order #';
//@TODO 2017-01-22 - SAJ - Set the merchant url in the vendor profile.  Hardcoding for the present
		Switch ($merchant_guid){
	        case 1148:  // Amazon.com
	            $order_no = "<span title='view this order on Amazon.com'>".
	                         elgg_view('output/url', array(
	                                      'href'=> 'https://www.amazon.com/gp/css/summary/edit.html?ie=UTF8&orderID='.$entity->order_no,
	                                      'text'=>$entity->order_no,)).
	                         "</span>";
	            break;
	        default: 
	            $order_no = $entity->order_no;
	            break;
		}
		$transaction_no_label = 'Receipt #';
		$transaction_no       = $entity->receipt_no;
		$invoice_no_label = 'Invoice #';
		$invoice_no       = $entity->invoice_no;
		$transaction_date_label = 'Purchase Date';
		$transaction_date     = $entity->moment;
		$associate_label      = 'Sales Assoc.';
		break;
	case 'return':
		$actor_label          = 'Returned by';
		$actor                = $entity->returned_by;
		$order_no_label       = 'Order #';
		$order_no             = $entity->order_no;
		$transaction_no_label = 'Credit #';
		$transaction_no       = $entity->credit_no;
		$transaction_date_label = 'Credit Date';
		$transaction_date     = $entity->credit_date;
		$associate_label      = 'Returns Assoc.';
		
		break;
	default:
		$actor_label          = 'Buyer';
		$actor                = $entity->purchased_by;
		$order_no_label       = 'Order #';
		$order_no             = $entity->order_no;
		$transaction_no_label = 'Receipt #';
		$transaction_no       = $entity->receipt_no;
		$transaction_date_label = 'Purchase Date';
		$transaction_date     = $entity->moment;
		$associate_label      = 'Sales Assoc.';
		
		break;
        }
    //echo $display;
Switch ($presentation){
    case 'compact dropdown':
    case 'compact':
    case 'qbox':
    case 'popup':
    		if ($shipment_receipts){
    			unset($doc_links);
    			foreach($shipment_receipts as $document){
    				$doc_links .='<li>'.elgg_view('output/url',['text'=>$document->title, 'data-guid'=>$document->getGUID()]).'</li>';
    			}
    			$document_links = "<ul>$doc_links</ul>";
    			$documents = "<div class='rTableRow'>
				    				<div class='rTableCell'>Documents</div>
				    				<div class='rTableCell'>$document_links</div>
				    			</div>";
    		}
    	    $content .= "<div id='header_1' class='rTable' >
				    		<div class='rTableBody'>
                                $documents	
				    			<div class='rTableRow'>
				    				<div class='rTableCell'>Merchant</div>
				    				<div class='rTableCell'>$merchant</div>
				    			</div>		
				    		</div>
				    	</div>
                        <div id='header_2' class='rTable'>
				    		<div class='rTableBody'>
				    			<div class='rTableRow'>
				    				<div class='rTableCell'>$associate_label</div>
				    				<div class='rTableCell'>".$entity->cashier."</div>
				    				<div class='rTableCell'>$transaction_date_label</div>
				    				<div class='rTableCell'>$transaction_date</div>
				    				<div class='rTableCell'></div>
				    				<div class='rTableCell'></div>
				    			</div>
				    			<div class='rTableRow'>
				    				<div class='rTableCell'>$actor_label</div>
				    				<div class='rTableCell'>$actor</div>
				    				<div class='rTableCell'>$order_no_label</div>
				    				<div class='rTableCell'>$order_no</div>
				    				<div class='rTableCell'>$invoice_no_label</div>
				    				<div class='rTableCell'>$invoice_no</div>
				    			</div>
				    			<div class='rTableRow'>
				    				<div class='rTableCell'></div>
				    				<div class='rTableCell'></div>
				    				<div class='rTableCell'>Document #</div>
				    				<div class='rTableCell'>".$entity->document_no."</div>
				    				<div class='rTableCell'>$transaction_no_label</div>
				    				<div class='rTableCell'>$transaction_no</div>
				    			</div>
				    		</div>
				    	</div>
					    <div class='rTable receipt-line-items'>
					    		<div class='rTableBody'>
					    			<div class='rTableRow'>
					    				<div class='rTableCell'></div>
					    				<div class='rTableHead'>Rec'd</div>
					    				<div class='rTableHead'>Qty</div>
					    				<div class='rTableHead'>Item</div>
					    				<div class='rTableHead'>tax?</div>
					    				<div class='rTableHead'>Cost</div>
					    				<div class='rTableHead'>Total</div>
					    			</div>";
					    // Populate existing receipt items
					    $n=0;
					    foreach($receipt_items as $item){
					    	$n = $n++;
					    	unset($title, $tax_check, $linked_item, $item_guid, $line_item, $item_total);
					    	$element_type = 'receipt item';
					            $title = $item->title;                                                            $display .= '$title: '.$title.'<br>';
					            $linked_item = elgg_get_entities_from_relationship(array(
					    			'type' => 'object',
					    			'relationship' => 'receipt_item',
					    			'relationship_guid' => $item->guid,
					    			'inverse_relationship' => true,
					    			'limit' => false,
					    		));
					            $item_guid = $linked_item[0]->guid ?: $item->item_guid;
					            if (isset($item_guid) && $item->retain_line_label == 'no' || $item['retain_line_label'] == 'no' ){
					            	$line_item = elgg_view('output/url', array(
					            			'text' =>  $linked_item[0]->title ?: $item->title,
					            			'href' =>  "market/view/$item_guid/Inventory",
					    	        		'class'=> 'rTableform90',
					            	    ));
					            } else {
					            	$line_item = $title;        	
					            }
					            if ($item->taxable == 1){
					            	$tax_check = 'x';
					            }
					            if (!empty($item->total) && $item->sort_order == 1){
					            	$item_total = money_format('%#5n', $item->total);
					            }
					            else {
					            	$item_total = number_format($item->total, 2);
					            }
					            if ($item->status == 'returned'){                              
					                $return_line_items = elgg_get_entities_from_metadata(array(
					                        'receipt_line_item_guid' =>$item->getGUID(),
					                ));                                                        $display .= 'return_line_item_guid:'.$return_line_items[0]->guid.'<br>';
					                $return_item_guid = $return_line_items[0]->container_guid;
					                $status = '('. elgg_view('output/url', array('text'=>'ret', 'href'=>"jot/view/$return_item_guid")). ')';
					            }
					    
					    $content .= "<div class='rTableRow'>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'>$item->recd_qty</div>
					    				<div class='rTableCell'>$item->qty</div>
					    				<div class='rTableCell'>$status $line_item</div>
					    				<div class='rTableCell'>$tax_check</div>
					    				<div class='rTableCell'>".number_format($item->cost, 2)."</div>
					    				<div class='rTableCell'>$item_total</div>
					    			 </div>";
					    }
					    
					    // Populate form footer
					    $content .= "	<div class='rTableRow'>
					    				<div class='rTableCell'></div>
					    			</div>
					    			<div class='rTableRow'>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'>Subtotal</div>
					    				<div class='rTableCell'>".money_format('%#10n', (double) $entity->subtotal)."</div>
					    			</div>
					    			<div class='rTableRow'>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'>Shipping</div>
					    				<div class='rTableCell'>".number_format((double) $entity->shipping_cost, 2)."</div>
					    			</div>
					    			<div class='rTableRow'>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'>Sales Tax</div>
					    				<div class='rTableCell'>".number_format((double) $entity->sales_tax, 2)."</div>
					    			</div>
					    			<div class='rTableRow'>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'></div>
					    				<div class='rTableCell'>Total</div>
					    				<div class='rTableCell'>".money_format('%#10n', (double) $entity->total)."</div>
					    			</div>";
					    $content .= "
					    		</div>
					    </div>";
		    
        	$vars['content']      = $content;
        	$vars['disable_save'] = elgg_extract('disable_save', $vars, false);
        	$vars['show_title']   = elgg_extract('show_title', $vars, false);
        	$vars['position']     = 'relative';
        	$vars['message']      = $entity->status;
        	$form                 = elgg_view_layout('qbox', $vars);
        break;
    default:

/*    $form .= "<div class='rTable' style='width:550px'>
    		<div class='rTableBody'>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:90px'>Title</div>
    				<div class='rTableCell' style='width:460px'>$entity->title</div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:90px'>Merchant $tag_merchant</div>
    				<div class='rTableCell' style='width:460px'>$merchant</div>
    			</div>			
    		</div>
    	</div>";*/
    $form .= "<div class='rTable' style='width:100%'>
    		<div class='rTableBody'>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:90px'>Merchant $tag_merchant</div>
    				<div class='rTableCell' style='width:460px'>$merchant</div>
    			</div>			
    		</div>
    	</div>";
    $form .= "<div class='rTable' style='width:100%'>
    		<div class='rTableBody'>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:100px'>$associate_label</div>
    				<div class='rTableCell' style='width:200px'>".$entity->cashier."</div>
    				<div class='rTableCell' style='width:100px'>$transaction_date_label</div>
    				<div class='rTableCell' style='width:120px'>$transaction_date</div>
    				<div class='rTableCell' style='width:100px'></div>
    				<div class='rTableCell' style='width:120px'></div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:100px'>$actor_label</div>
    				<div class='rTableCell' style='width:200px'>$actor</div>
    				<div class='rTableCell' style='width:100px'>$order_no_label</div>
    				<div class='rTableCell' style='width:120px'>$order_no</div>
    				<div class='rTableCell' style='width:100px'>$invoice_no_label</div>
    				<div class='rTableCell' style='width:120px'>$invoice_no</div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:100px'></div>
    				<div class='rTableCell' style='width:200px'></div>
    				<div class='rTableCell' style='width:100px'>Document #</div>
    				<div class='rTableCell' style='width:120px'>".$entity->document_no."</div>
    				<div class='rTableCell' style='width:100px'>$transaction_no_label</div>
    				<div class='rTableCell' style='width:120px'>$transaction_no</div>
    			</div>
    		</div>
    </div>
    <div class='rTable' style='width:100%'>
    		<div class='rTableBody'>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:0'></div>
    				<div class='rTableHead' style='width:10%'>Qty</div>
    				<div class='rTableHead' style='width:65%'>Item</div>
    				<div class='rTableHead' style='width:5%'>tax?</div>
    				<div class='rTableHead' style='width:10%'>Cost</div>
    				<div class='rTableHead' style='width:10%'>Total</div>
    			</div>";
    
    // Populate existing receipt items
    $n=0;
    foreach($receipt_items as $item){
    	$n = $n++;
    	unset($title, $tax_check, $linked_item, $item_guid, $line_item, $item_total);
    	$element_type = 'receipt item';
            $title = $item->title;                                                            $display .= '$title: '.$title.'<br>';
            $linked_item = elgg_get_entities_from_relationship(array(
    			'type' => 'object',
    			'relationship' => 'receipt_item',
    			'relationship_guid' => $item->guid,
    			'inverse_relationship' => true,
    			'limit' => false,
    		));
            $item_guid = $linked_item[0]->guid ?: $item->item_guid;
            if (isset($item_guid) && $item->retain_line_label == 'no' || $item['retain_line_label'] == 'no' ){
            	$line_item = elgg_view('output/url', array(
            			'text' =>  $linked_item[0]->title ?: $item->title,
            			'href' =>  "market/view/$item_guid/Inventory",
    	        		'class'=> 'rTableform90',
            	    ));
            } else {
            	$line_item = $title;        	
            }
            if ($item->taxable == 1){
            	$tax_check = 'x';
            }
            if (!empty($item->total) && $item->sort_order == 1){
            	$item_total = money_format('%#5n', $item->total);
            }
            else {
            	$item_total = number_format($item->total, 2);
            }
            if ($item->status == 'returned'){                              
                $return_line_items = elgg_get_entities_from_metadata(array(
                        'receipt_line_item_guid' =>$item->getGUID(),
                ));                                                        $display .= 'return_line_item_guid:'.$return_line_items[0]->guid.'<br>';
                $return_item_guid = $return_line_items[0]->container_guid;
                $status = '('. elgg_view('output/url', array('text'=>'ret', 'href'=>"jot/view/$return_item_guid")). ')';
            }
    
    $form .= "	<div class='rTableRow'>
    				<div class='rTableCell' style='width:0'></div>
    				<div class='rTableCell' style='width:10%'>$item->qty</div>
    				<div class='rTableCell' style='width:65%'>$status $line_item</div>
    				<div class='rTableCell' style='width:5%;text-align:center'>$tax_check</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>".number_format($item->cost, 2)."</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>$item_total</div>
    			</div>";
    }
    
    // Populate form footer
    $form .= "	<div class='rTableRow'>
    				<div class='rTableCell'></div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:0'></div>
    				<div class='rTableCell' style='width:10%'></div>
    				<div class='rTableCell' style='width:65%'></div>
    				<div class='rTableCell' style='width:5%'></div>
    				<div class='rTableCell' style='width:10%'>Subtotal</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $entity->subtotal)."</div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:0'></div>
    				<div class='rTableCell' style='width:10%'></div>
    				<div class='rTableCell' style='width:65%'></div>
    				<div class='rTableCell' style='width:5%'></div>
    				<div class='rTableCell' style='width:10%'>Shipping</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>".number_format($entity->shipping_cost, 2)."</div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:0'></div>
    				<div class='rTableCell' style='width:10%'></div>
    				<div class='rTableCell' style='width:65%'></div>
    				<div class='rTableCell' style='width:5%'></div>
    				<div class='rTableCell' style='width:10%'>Sales Tax</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>".number_format($entity->sales_tax, 2)."</div>
    			</div>
    			<div class='rTableRow'>
    				<div class='rTableCell' style='width:0'></div>
    				<div class='rTableCell' style='width:10%'></div>
    				<div class='rTableCell' style='width:65%'></div>
    				<div class='rTableCell' style='width:5%'></div>
    				<div class='rTableCell' style='width:10%'>Total</div>
    				<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $entity->total)."</div>
    			</div>";
    $form .= "
    		</div>
    </div>";
    break;
}
echo elgg_view('output/div',['content'=>$form,
		                     'class'=>'elgg-body quebx-item-body']);
//echo register_error($display);
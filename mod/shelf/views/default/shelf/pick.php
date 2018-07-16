<?php
$inventory   = $vars['inventory'];
$target      = $vars['target'];
$item        = $vars['entity'];
$item_guid   = $item->guid;
$target_guid = $target->guid;

        $title = $item->title;
        $content_item = elgg_view('output/url', array(
        		'text' =>  $title,
        		'href' =>  $item->getURL(),
        		'class'=> 'rTableform90',
        ));
        $item_options = array('name'    => 'item[selected][]',
					          'value'   => $item_guid,
    			              'default' => false,
    			             );
    	if ($item_guid == $target_guid){
    	    $item_options['disabled'] = 'disabled';
    	}
    	    $item_check = elgg_view('input/checkbox', $item_options);
    	
        
        $qty = $vars['quantity'];
        
echo"		<div class='rTableRow'>
				<div class='rTableCell' style='width:0'>$item_check</div>
				<div class='rTableCell' style='width:5%'>$qty</div>
				<div class='rTableCell' style='width:95%'>$content_item</div>
					".elgg_view('input/hidden', array('name' => 'item[guid][]',
													  'value' => $item_guid,
													)).
					  elgg_view('input/hidden', array('name' => 'item[qty][]',
													  'value' => $qty,
													))."
			</div>";
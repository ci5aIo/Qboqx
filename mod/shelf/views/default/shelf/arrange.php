<?php
$inventory = $vars['inventory'];
$item = $vars['entity'];
$perspective = elgg_extract('perspective', $vars, 'page');

	$element_type = 'receipt item';
    $title = $item->title;
    $content_item = elgg_view('output/url', [
        		'text' =>  $title,
        		'href' =>  $item->getURL(),
        		'class'=> 'rTableform90',]);
    $item_options = ['name'    => 'item[selected][]',
					    'value'   => $item->guid,
    			        'default' => false,];
    $item_check = elgg_view('input/checkbox', $item_options);
    $qty = elgg_view('input/number', [
				'name'  => 'item[quantity][]',
	            'value' => $vars['quantity'],
	        	'max'   => $inventory,]);
        
Switch ($perspective){
	case 'sidebar':
		if (!empty($item->icon)){$icon_guid = $item->icon;}              
		else {$icon_guid = $item->guid;}
		$icon = elgg_view('market/thumbnail', ['marketguid' => $icon_guid, 'size' => 'tiny', 'item_guid'=>$item->guid]);
		$image_vars=['item_guid'=>$item->guid];
		$this_item = elgg_view_image_block($icon, $item->title, $image_vars);//, $vars);
		$content = "<li class='quebx-shelf-item' data-perspective=$perspective id='quebx-shelf-item-$item->guid' data-container-guid=$item->container_guid >
						$this_item
					</li>";
		break;
	case 'page':
	default:
		$content = "<div class='rTableRow'>
						<div class='rTableCell' style='width:0'>$item_check</div>
						<div class='rTableCell' style='width:5%'>$qty</div>
						<div class='rTableCell' style='width:95%'>$content_item</div>
							".elgg_view('input/hidden', array('name' => 'item[guid][]',
															  'value' => $item->guid,
															))."
					</div>";
	break;
}
echo $content;
<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
$asset_guid = $item_guid;
setlocale(LC_MONETARY, 'en_US');
$fields = market_prepare_brief_view_vars($entity);
//$fields = market_prepare_brief_view_vars($entity, $section);

/**/
$supplies = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'supply',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$parts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'part',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
));
$pieces = elgg_get_entities(array(
				'type' => 'object',
				'subtypes' => array('market', 'item'),
				'wheres' => array(
					"e.container_guid = $item_guid",
				),
			));
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
$receipts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'transfer_receipt',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
));
$suppliers = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'supplier_of',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
// combine component receipts with item receipts
if (!empty($components)){
	foreach($components as $component){
		$component_receipts = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'transfer_receipt',
			'relationship_guid' => $component->guid,
			'inverse_relationship' => true,
			'limit' => false,
		));
	$receipts = array_merge($receipts, $component_receipts);
	}
}
// combine accessory receipts with item receipts
if (!empty($accessories)){
	foreach($accessories as $accessory){
		$accessory_receipts = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'transfer_receipt',
			'relationship_guid' => $accessory->guid,
			'inverse_relationship' => true,
			'limit' => false,
		));
	$receipts = array_merge($receipts, $accessory_receipts);
	}
}
//echo '$contents: '.count($contents); 
$pick_button = elgg_view('output/url', array(
		'text' => 'pick ...', 
	    'class' => 'elgg-button-submit-element elgg-lightbox',
	    'data-colorbox-opts' => '{"width":500, "height":525}',
        'href' => "market/groups/supplier/" . $item_guid));					

echo '<table>';
// supplies
$hoverhelp = elgg_echo('jot:hoverhelp:Supplies');
	echo "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2><b>Supplies</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
	        </td>
	      </tr>";
/*	echo  "  <tr>
	        <td colspan=2>".
    			elgg_view_form('jot/add/element', array("action" => "action/market/add/element?element_type=supply&guid=$item_guid&asset=$asset_guid")).'
	        </td>
	      </tr>';*/
if ($supplies) {
foreach ($supplies as $i) {
			$element_type = 'supply';
	        $delete = '';
			$link = elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid"));
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
				$pick = elgg_view('output/url', array(
	        		'text' => elgg_view_icon('settings-alt'),
	        		'class' => 'elgg-lightbox',
	        		'data-colorbox-opts' => '{"width":600, "height":525}',
	        		'href' => "market/pick/supply/" . $i->guid));
	            $pick_menu = "<span title='F*A*K*E: Set supply item properties'>$pick</span>";
				
			}
	echo "<tr class='highlight'>
	        <td>$pick_menu $link
	        </td>
	        <td nowrap>$delete</td>
	      ";
    }
}
else {
	echo '<tr>
	        <td>No supply items on hand</td>
	        <td>
	      </tr>';	
     }	
							
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
// pieces
$hoverhelp = elgg_echo('jot:hoverhelp:Pieces');
	echo "<tr>
	        <td colspan=2><b>Pieces</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
	        </td>
	      </tr>";
/*	echo  "  <tr>
	        <td colspan=2>".
    			elgg_view_form('jot/add/element', array("action" => "action/market/add/element?element_type=supply&guid=$item_guid&asset=$asset_guid")).'
	        </td>
	      </tr>';*/
if ($pieces) {
foreach ($pieces as $i) {
			$element_type = 'piece';
	        $delete = '';
			$link = elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid"));
	        $observation = get_entity($i->guid);
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/market/delete?guid=$i->guid",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.$link.'
	        </td>
	        <td nowrap>'.$delete.'</td>
	      ';
    }
}
else {
	echo '<tr>
	        <td>No pieces on hand</td>
	        <td>
	      </tr>';	
     }	
							
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	
// parts
$hoverhelp = elgg_echo('jot:hoverhelp:Parts');
		echo "<tr>
		         <td width=100%><b>Parts</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>";
/*	echo  "  <td colspan=2>".
			    elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=part&guid=$item_guid&asset=$asset_guid"))."
	        </td>
		    </tr>";*/
	echo '<tr><td colspan=2>';
	
if ($parts) {
foreach ($parts as $i) {
	echo '<tr>
	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>
	      </tr>';
	}
}	
else {
	echo '<tr>
	        <td>No parts on hand</td>
	        <td>
	      </tr>';	
     }	

	echo '<tr><td colspan=2>&nbsp;</td></tr>';
// receipts
$hoverhelp = elgg_echo('jot:hoverhelp:Receipts');
$action     = 'jot/add/element';
$form_vars  = array('enctype'      => 'multipart/form-data', 
                    'name'         => 'receipt_add',
//			 	    'action'       => "action/jot/add/element",
			 	    'action'       => "action/jot/edit");
$body_vars  = array('element_type' => 'transfer',
                    'aspect'       => 'receipt',
                    'container_guid' => $item_guid,
                    'asset'        => $asset_guid,
                    );
		echo "<tr>
		         <td width=100%><b>Receipts</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>
		      <tr>";
//		echo elgg_view_form($action, $form_vars, $body_vars);
		echo "<tr>".
			    elgg_view_form($action, $form_vars, $body_vars)."
		    </tr>";
	echo '<tr><td colspan=2>';
	
if ($receipts) {
	$element_type = 'transfer_receipt';
	$element_label = "receipt";
	foreach ($receipts as $i) {
	$link         = elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid"));
	
	if ($i->canEdit()) {
		$detach = elgg_view("output/url",array(
	    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$item_guid}",
	    	'text' => elgg_view_icon('unlink'),
	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_label),
	    	'encode_text' => false,
	    ));
		$edit = elgg_view("output/url",array(
	    	'href' => "jot/edit/$i->guid",
	    	'text' => elgg_view_icon('docedit'),
	    	'title' => sprintf(elgg_echo('jot:edit'), $element_label),
	    	'encode_text' => false,
	    ));
		
		/* 2016-01-31 - SAJ - Attempt to show only the amount of the receipt that is related to the item.  Not entirely sure this is what should be shown.  Shelving for the time being
		$dbprefix = elgg_get_config('dbprefix');
		$receipt_item = elgg_get_entities(array(
							'type' => 'object',
							'subtypes' => array('receipt_item'),
							'joins' => array(
									"JOIN {$dbprefix}objects_entity oe ON oe.guid = e.guid",
									"JOIN {$dbprefix}entity_relationships er ON er.guid_one = e.guid",
									"JOIN {$dbprefix}metadata md ON md.guid = er.guid_two = e.guid"
							),
							'wheres' => array(
									"oe.guid = $item_guid",
							),
							'order_by' => 'oe.title ASC'
		));
		// draft version of select statement
		
		SELECT *
		FROM `elgg_entities` e
		
		inner join `elgg_objects_entity` oe
		on oe.guid = e.guid
		inner join `elgg_entity_relationships` er1
		on oe.guid = er1.guid_two
		inner join `elgg_entity_relationships` er2
		on er2.guid_one = er1.guid_two
		inner join `elgg_metadata`
		where er1.`relationship` = 'transfer_receipt'
				and er2.`relationship` = 'receipt_item'
						and oe.guid = 870
				
		$price = $receipt_item->purchase_cost;
*/
		$price = money_format('%#10n', $i->total);
	}
echo "<tr class='highlight'>
		<td colspan = 2>
			<div class='rTable' style='width:100%'>
				<div class='rTableBody'>
						<div class='rTableRow'>
							<div class='rTableCell' style='width:65%'>$link
							</div>
							<div class='rTableCell' style='width:25%;text-align:right'>$price
							</div>
							<div class='rTableCell'>$edit$detach
							</div>
						</div>
				</div>
			</div>
		</td>
	</tr>";
	}
}		
else {
	echo '<tr>
	        <td colspan=2>No receipts on hand</td>
	        <td>
	      </tr>';	
     }	

	echo '<tr><td colspan=2>&nbsp;</td></tr>';

// Suppliers

$group_type = 'supplier';
$action     = 'groups/add/element';
$form_vars  = array('enctype'     => 'multipart/form-data', 
                    'name'        => 'group_list',
			 	    'action'      => "action/groups/add?element_type=$group_type&item_guid=$item_guid");
$body_vars  = array('item_guid'   => $item_guid,
			        'group_type'  => $group_type);
$hoverhelp  = elgg_echo('jot:hoverhelp:Suppliers');
		echo "<tr>
		         <td width=100%><b>Suppliers</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>
		        <td>$pick_button
		        </td>
		    </tr>".elgg_view_form($action, $form_vars, $body_vars);
	echo '<tr><td colspan=2>';
if ($suppliers) {
	$element_type = 'supplier';
	foreach ($suppliers as $i) {
	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid/$i->name"));
	
	if ($i->canEdit()) {
		$detach = elgg_view("output/url",array(
	    	'href' => "action/jot/detach?element_type=$element_type&guid=$i->guid&container_guid=$item_guid",
	    	'text' => elgg_view_icon('unlink'),
	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
	    	'encode_text' => false,
	    ));
			
	}
	echo '<tr class="highlight">
	        <td>'.$link.'
	        </td>
	        <td nowrap>'.$detach.'</td>
	      ';
	}
}	
else {
	echo '<tr>
	        <td colspan=2>No suppliers identified</td>
	        <td>
	      </tr>';	
     }	
	
echo '</table>';

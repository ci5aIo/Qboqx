<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 *
 * Modified by Kevin Jardine for arckinteractive.com
 */

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}
$guid         = $vars['guid'];
$entity       = $vars['entity'] ?: get_entity($guid);
//$title        = $vars['markettitle'];
$body         = $vars['marketbody'];
$tags         = $vars['markettags'];
//$category     = $vars['marketcategory'];
$access_id    = $vars['access_id'];
$model_no     = $entity->model_no;
$serial_no    = $entity->serial_no;
$entity_guid  = $entity->getGUID();
$asset_guid   = $entity->getGUID();
$entity_owner = get_entity($entity->owner_guid);


$display = '';/*
foreach ($entity as $value=>$field){
	$display .= $value.'=>'.$field.'<br>';
}*/

$family_values = item_prepare_form_vars(NULL,NULL,$entity);
$entity_values = item_prepare_form_vars(NULL,NULL,$entity);
$values        = array_merge($family_values, $entity_values);
foreach($values as $value=>$field){
	$display .= $value.'=>'.$field.'<br>';
}
//echo $display;

elgg_set_context('edit_item');
//echo elgg_dump($entity);

	$receipts = elgg_get_entities_from_relationship(array(
		'type'                 => 'object',
		'relationship'         => 'transfer_receipt',
		'relationship_guid'    => $entity_guid,
		'inverse_relationship' => true,
		'limit'                => false,
	));
	$receipt_items = elgg_get_entities_from_relationship(array(
		'type'                 => 'object',
		'relationship'         => 'receipt_item',
		'relationship_guid'    => $entity_guid,
		'inverse_relationship' => false,
		'limit'                => false,
		// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
		'order_by_metadata'    => array('name' => 'sort_order', 
				                        'direction' => ASC, 
				                        'as' => 'integer'),
	));
$contents = elgg_get_entities(array(
				'type' => 'object',
				'subtypes' => array('market'),
				'wheres' => array(
					"e.container_guid = $entity_guid",
				),
			));
$characteristics = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'characteristic',
	'relationship_guid' => $entity_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$add_characteristic = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=characteristic&guid=$entity_guid&asset=$asset"));

if ($vars['entity']) {
  echo elgg_view('input/hidden',array('name'=>'parent_guid','value'=>$vars['entity']->parent_guid));
}
//echo elgg_dump($vars);
//echo elgg_dump($vars['entity']);

echo "<b>Family</b>";
echo "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Title</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[title]', 'value' => $entity->title,))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:5px 5px 0px 5px'>Manufacturer</div>
				<div class='rTableCell' style='width:80%;padding:5px 5px 0px 5px'>".elgg_view('input/text', array('name' => 'item[manufacturer]', 'value' => $entity->manufacturer,))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell'>Model #</div>
				<div class='rTableCell'>
			       <div class='rTable' style='width:100%'>
						<div class='rTableBody'>
						   	<div class='rTableRow'>
								<div class='rTableCell' style='padding:0px 0px'>".elgg_view('input/text', array('name' => 'item[model_no]', 'value' => $entity->model_no,))."</div>
								<div class='rTableCell' style='padding:0px 5px'>Part #</div>
								<div class='rTableCell' style='padding:0px 0px'>".elgg_view('input/text', array('name' => 'item[part_no]', 'value' => $entity->part_no,))."</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px;vertical-align:top;'>Description</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>";

if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='marketbody' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	echo "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "marketbody", "value" => $body));
}

echo"			</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Category</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('market/marketcategories',$vars)."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Owner</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('output/url', array('text' => $entity_owner->name,'href' =>  'profile/'.$entity_owner->username))."</div>
			</div>";
echo "		<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px;vertical-align:top;'></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('market/thumbnail', array('marketguid' => $entity->guid, 'size' => 'large', 'tu' => $entity->time_updated))."</div>
			</div>";
echo "		</div>
	</div>";

echo "<b>Family Characteristics</b><br>";


// Characteristics clone
// Taken from mod\market\views\default\forms\market\edit\car\profile.php 
echo '<div class="characteristics">
		<div>'. 
	elgg_view('input/text', array(
		'name' => 'item[component_names][]',
		'style' => 'width: 25%;'
	)).
	elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'style' => 'width: 65%;'
	)).
    '<a href="#" class="remove-node">remove</a>
	</div>
</div>'; // end of Characteristics clone




echo "<b>This Item</b>";
	echo "<div class='rTable' style='width:100%'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'>
						<span title='New line item'>".
							elgg_view('output/url', array(
							    'text' => '+',
								'href' => '#',
								'class' => 'elgg-button-submit-element clone-line-item-action' // unique class for jquery
								))."
						</span>
					</div>
					<div class='rTableHead' style='width:5%'>Qty</div>
					<div class='rTableHead' style='width:60%'>Receipt</div>
					<div class='rTableHead' style='width:5%'>tax?</div>
					<div class='rTableHead' style='width:10%'>Cost</div>
					<div class='rTableHead' style='width:10%'>Total</div>
				</div>
				";
	// Populate existing receipt items
	$n=0;
	$display .= '';
	foreach($receipt_items as $item){
		$n = $n+1;
		$element_type = 'receipt item';
		if ($item->canEdit()) {
			$delete = elgg_view("output/url",array(
		    	'href' => "action/jot/delete?guid=$item->guid&container_guid=$transfer_guid",
		    	'text' => elgg_view_icon('arrow-left'),
		    	'confirm' => sprintf('Remove receipt item?'),
		    	'encode_text' => false,
		    ));
			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
														'value'   => $item->guid,
								        			    'default' => false,
								        			   ));
			$title = $item->title;
	        $linked_item = elgg_get_entities_from_relationship(array(
				'type'                 => 'object',
				'relationship'         => 'receipt_item',
				'relationship_guid'    => $item->getGUID(),
				'inverse_relationship' => true,
				'limit'                => 1,
//	        	'limit' => false,
			));
	        $linked_receipt = elgg_get_entities_from_relationship(array(
				'type'                 => 'object',
				'relationship'         => 'transfer_receipt',
				'relationship_guid'    => $item->getGUID(),
				'inverse_relationship' => false,
	        	'limit'                => 1,
			));
	
	        if (!empty($linked_item[0]) && ($item->retain_line_label == 'no')){
/*				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=receipt_item&&guid=".$linked_item[0]->getGUID()."&container_guid=$item->getGUID()",
			    	'text' => elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
			    	'encode_text' => false,
			    ));
*/
/*				$link         = "<span class='hoverinfo'>".
								      elgg_view('output/url', array('text' => $linked_receipt[0]->title,'href' =>  "jot/view/$linked_receipt[0]->guid"))."
					 	             <span style='width:150px;'>".
						 	             elgg_view('market/display/hoverinfo', array('i'=>$linked_receipt[0]))."
			 	                     </span>
					 	          </span>";
*/
//				$line_item = $link;
	        	$line_item .= elgg_view('output/url', array(
//	        			'text' =>  $linked_receipt[0]->title,
	        			'text' =>  $linked_item[0]->title,
//	        			'href' =>  "market/view/".$linked_receipt[0]->getGUID()."/".$linked_receipt[0]->title."/Inventory",
	        			'href' =>  "market/view/".$linked_item[0]->getGUID()."/".$linked_item[0]->title."/Inventory",
		        		'class'=> 'rTableform90',
		        ));
		        $line_item .= elgg_view('input/hidden', array(
		        		'name' => 'item[receipt_item][title][]',
		        		'value' => $title,
		        ));	         
	        } else {
	        	$line_item = elgg_view('input/text', array(
	        			'name' => 'item[receipt_item][title][]',
	        			'value' => $title,
	        			'class'=> 'rTableform90',
	        	));        	
	        }
	$display .= '$line_item: '.$line_item.'<br>';
	$display .= 'item_guid: '.$item->item_guid.'<br>';
/*	        if ($item->taxable == 1){
	        	$tax_options = array('name'    => 'item[receipt_item][taxable][]',
							         'checked' => 'checked',
							         'value'   => 1,
	        			             'default' => false,
	        			            );
	        } else {
	        	$tax_options = array('name'    => 'item[receipt_item][taxable][]',
							         'value'   => 1,
	        			             'default' => false,
								    );
	        }
	        $tax_check = elgg_view('input/checkbox', $tax_options);
*/	        
	        $pick = elgg_view('output/url', array(
	        		'text' => elgg_view_icon('settings-alt'),
	        		'class' => 'elgg-lightbox',
	        		'data-colorbox-opts' => '{"width":500, "height":525}',
	        		'href' => "market/pick/item/" . $item->getGUID()));
	        $pick_menu = "<span title='Set line item properties'>$pick</span>";
	        $item_total = '';
	        if (!empty($item->total) && $item->sort_order == 1){
	        	$item_total = money_format('%#10n', $item->total);
	        }
	        else {
	        	$item_total = number_format($item->total, 2);
	        }
		}
	echo"		<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'>{$delete}{$select}</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
																	'name' => 'item[receipt_item][qty][]',
																	'value' => $item->qty,
																))."</div>
					<div class='rTableCell' style='width:60%'>$pick_menu $line_item</div>
					<div class='rTableCell' style='width:5%'>$tax_check</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																	'name' => 'item[receipt_item][cost][]',
																	'value' => $item->cost,
																))."</div>
					<div class='rTableCell' style='width:10%;text-align:right'>$item_total</div>
					".elgg_view('input/hidden', array(
																	'name' => 'item[receipt_item][guid][]',
																	'value' => $item->guid,
																))."
				</div>";
	}
	// Populate blank lines
	for ($i = $n+1; $i <= 1; $i++) {
	
	        if ($exists){
		        $pick = elgg_view('output/url', array(
		        		'text' => elgg_view_icon('settings-alt'),
		        		'class' => 'elgg-lightbox',
		        		'data-colorbox-opts' => '{"width":500, "height":525}',
		        		'href' => "market/pick/item/" . $transfer_guid));
		        $pick_menu = "<span title='Set line item properties'>$pick</span>";
	        	    }
		    else {
		        $pick = elgg_view('output/url', array(
		        		'text' => elgg_view_icon('settings-alt')));
		        $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
		    }
				//'href' => "market/pick?element_type=item&container_guid=" . $transfer_guid));
	echo"		<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'><a href='#' class='remove-node'>[X]</a></div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
												'name' => 'item[receipt_item][qty][]',
											))."</div>
					<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
												'name' => 'item[receipt_item][title][]',
							                    'class'=> 'rTableform90',
											))."</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
												'name' => 'item[receipt_item][taxable][]',
												'value'=> 1,
	        			                        'default' => false,
											))."</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
												'name' => 'item[receipt_item][cost][]',
											))."</div>
					<div class='rTableCell' style='width:10%'></div>
				</div>";
	}

	echo"	<div class='new_line_item'></div>
		</div>
	</div>";
/*

$marketcategories = elgg_view('market/marketcategories',$vars);
if (!empty($marketcategories)) {
	echo "<p><label>Category: </label>$marketcategories</p>";
}

echo "<p><label>Model #:</label>".
     elgg_view('input/text', array('name' => 'model_no','value' => $model_no)).
     "</p>";
*/
echo "<p><label>Serial #:</label>".
     elgg_view('input/text', array('name' => 'serial_no','value' => $serial_no)).
     "</p>";
/*
echo "<p><label>" . elgg_echo("market:text") . "<br>";
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='marketbody' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	echo "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "marketbody", "value" => $body));
}
echo "</label></p>";
*/
echo "<p><label>" . 
      elgg_echo("market:tags")."
	  </label>". $tags."<br>";

	$url = elgg_get_site_url() . "labels/$asset_guid";
	$url = elgg_add_action_tokens_to_url($url);
echo elgg_view('output/url', array(
                  "href" => $url,
                   "text" => "add label",
                   "class" => "elgg-lightbox"
        ));

// characteristics
// Taken from mod\jot\views\default\jot\display\observation\details.php`
	echo '<table width = 100%><tr>
	        <td colspan=2><b>Characteristics</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p></span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.$add_characteristic.'
	        </td>
	      </tr>';

if ($characteristics) {
foreach ($characteristics as $i) {
			$element_type = 'characteristic';
			if ($i->canEdit() && $entity->state <= 3) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$entity_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.$i->title.'</td>
	        <td>'.$delete.'
	      </tr>';
    }
}
else {
	echo '<tr>
	        <td>Enter new characteristic and click [add!]</td>
	        <td>
	      </tr>';	
     }	
echo '</table><br>';

echo "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("market:access:help") . "</small></small><br />";
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
echo "</label></p>";
echo
'<div class="elgg-foot">'.
     elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $entity_guid,
	)).
   elgg_view('input/hidden', array(
		'name' => 'asset',
		'value' => $vars['asset'],
	)).
   elgg_view('input/hidden', array(
		'name' => 'container_guid',
		'value' => $entity->getContainerGUID(),
	)).
   elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	)).
   elgg_view('input/hidden', array(
	     'name' => 'aspect', 
	     'value' => $aspect
	)).
   elgg_view('input/hidden', array(
	     'name' => 'item_type', 
	     'value' => 'receipt_item',
	)).
	elgg_view('input/hidden', array(
	'name' => 'referrer',
	'value' => $referrer,
	)).
	
   elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'submit')).
   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply')).
'<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>
</div>';

echo
"<div id='line_store' style='visibility: hidden; display:inline-block;'>
	<div class='line_item'>
		<div class='rTableRow'>
				<div class='rTableCell' style='width:10%'><a href='#' class='remove-node'>[X]</a></div>
				<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
											'name' => 'item[receipt_item][qty][]',
										))."</div>
				<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
											'name' => 'item[receipt_item][title][]',
						                    'class'=> 'rTableform90',
										))."</div>
				<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
											'name' => 'item[receipt_item][taxable][]',
											'value'=> 1,
        			                        'default' => false,
										))."</div>
				<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
											'name' => 'item[receipt_item][cost][]',
										))."</div>
				<div class='rTableCell' style='width:10%'></div>
			</div>
	</div>
</div>";
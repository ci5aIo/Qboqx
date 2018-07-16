<?php
$title = elgg_extract('title', $vars, '');
$item_guid   = $vars['item_guid'];                       $display .= '$item_guid: '.$item_guid.'<br>';
$description = $vars['description'];
$subtype     = elgg_extract('subtype', $vars, '');
$aspect      = elgg_extract('aspect', $vars, '');
$asset       = elgg_extract('asset', $vars, '');
$element_type= elgg_extract('element_type', $vars, '');
$referrer    = elgg_extract('referrer', $vars,'');
$item        = get_entity($item_guid);
$jot         = elgg_extract('jot', $vars);
$presentation= elgg_extract('presentation', $vars);
//$jot         = elgg_extract('jot', $vars, $item);
$ts = time();
$title = elgg_echo("New jot");
$tags = "";
$variables = elgg_get_config("{$aspect}s");

?>

<style>
#Family_panel, #Individual_panel, #Acquisition_panel, #Gallery_panel,
	#Testing_panel {
	display: none;
}

#Family_tab, #Individual_tab, #Acquisition_tab, #Gallery_tab,
	#Testing_tab {
	padding: 5px;
	text-align: center;
	background-color: #e5eecc;
	border: solid 1px #c3c3c3;
}

span.hoverhelp {
	background: #F0F0EE;
}
</style>


<?php
$display .= '$aspect: ' . $aspect . '<br>';
// $display .= 'Description: '.$description.'<br>';
$display .= '$item_guid: ' . $item_guid . '<br>';
//$display .= 'Title: '.$title.'<br>';


$add_box .= elgg_view('input/hidden', array('name' => 'item_guid'     , 'value' => $item_guid));
$add_box .= elgg_view('input/hidden', array('name' => 'subtype'       , 'value' => $subtype));
$add_box .= elgg_view('input/hidden', array('name' => 'element_type'  , 'value' => $element_type));
$add_box .= elgg_view('input/hidden', array('name' => 'access_id'     , 'value' => get_default_access()));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[guid]'     , 'value' => $jot->guid));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[aspect]'   , 'value' => $aspect));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[asset]'    , 'value' => $asset));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[initiator]', 'value' => elgg_get_logged_in_user_guid() ));
$add_box .= elgg_view('input/hidden', array('name' => 'referrer'      , 'value' => $referrer));
$add_box .= elgg_view('input/hidden', array('name' => 'state'         , 'value' => '1'));
//$add_box .= $display;

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('jot_allowhtml', 'jot');
$numchars = elgg_get_plugin_setting('jot_numchars', 'jot');
if($numchars == ''){
	$numchars = '250';
}


if (defined('ACCESS_DEFAULT')) {
	$access_id = ACCESS_DEFAULT;
} else {
	$access_id = 0;
}

Switch ($aspect){
	case 'receipt':
//		if ($item_guid == 0){
		$body_vars = array('aspect'        => $aspect,
					       'asset'         => $asset,
					       'element_type'  => $element_type,
					       'container_guid'=> $item_guid,
					       'presentation'  => $presentation,
					       'owner_guid'    => elgg_get_logged_in_user_guid(),
					       'referrer'      => $referrer,
		                );
		
		$add_box .= elgg_view_form('transfers/edit',NULL, $body_vars);
//		}
		break;
	case 'schedule':
	    $pace_options       = array('hour', 'day' , 'week' , 'month' , 'year');
		$frequency_options  = array('hours', 'days', 'weeks', 'months', 'years');
		if (get_entity($asset)->getSubtype() == 'market'){
			array_push($frequency_options, 'miles');
			if (!empty($item->frequency_units) && empty($jot->frequency_units)){
				$default = $item->frequency_units;
			}
			else if(!empty($jot->frequency_units)) {
				$default = $jot->frequency_units;
			}
			else {
				$default = 'miles';
			}
		}
		$pace_period = elgg_view('input/select', array(
								'options' => $pace_options,
								'name' => 'jot[pace_period]',
								'value' => $jot->pace_period,
								));
		$que_units = elgg_view('input/select', array(
							'options' => $frequency_options,
							'name' => 'jot[frequency_units]',
							'value' => $default,
							));
		$frequency_units = elgg_view('input/select', array(
							'options' => $frequency_options += [6 =>'this date'],
							'name' => 'jot[frequency_units]',
							'value' => $default,
							));
		$pace_units = elgg_view('input/select', array(
							'options' => $frequency_options,
							'name' => 'jot[pace_units]',
							'value' => $default,
		                    ));
		// Give no options for frequency units or pace units if the item has a default frequency unit and the schedule had not been set.
		// After the schedule has been set, the user can select a different frequency unit, but it will default to the unit initially saved.
		// The user can select a different frequency unit for a set schedule to account for the possibility that the item default
		   // frequency unit may change. Any changes to the schedule must be intentional to avoid unit conflicts.
		   // For example, if the item default frequency unit changes from 'miles' to 'months', the schedule should remain at 'miles' until explicitly changed.
		if (!empty($item->frequency_units) && empty($jot->frequency_units)){
			$frequency_units = $item->frequency_units;
			$pace_units      = $item->frequency_units;
			$add_box .= elgg_view('input/hidden', array('name' => 'jot[frequency_units]', 'value' => $frequency_units));
			$add_box .= elgg_view('input/hidden', array('name' => 'jot[pace_units]'     , 'value' => $frequency_units));
		}
	
		$trigger = elgg_view('input/radio', array(
				'name'    => 'jot[trigger]',
				'value'   => $jot->trigger,
				'options' => array(
						"Que automatically when due" => 1,
						"Que only after I approve"   => 2),
				'align'  => 'vertical',
		 ));
		$add_box .=
			"<div class='rTable' style='width:375px'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Activity*</div>
						<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>".elgg_view("input/text", array(
																		"name" => "jot[title]",
								                                        "value"=>$jot->title,
																		))."</div>
				    </div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Que every*</div>
						<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>
							<div class='rTable' style='width:285px'>
								<div class='rTableBody'>
									<div class='rTableRow'>
										<div class='rTableCell' style='width:75px;padding:0px 0px 0px 0px;'>".elgg_view("input/text", array(
																						"name" => "jot[frequency]",
																						"value"=> $jot->frequency,
																						))."</div>
										<div class='rTableCell' style='width:100px;padding:0px 0px 0px 0px;'>$que_units</div>
								    </div>
								</div>
							</div>
						</div>
				    </div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:75px; padding:0px 0px 0px 0px;'></div>
						<div class='rTableCell' style='width:300px; padding:0px 0px 0px 0px;'>$trigger</div>
				    </div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'>Last done @</div>
						<div class='rTableCell' style='width:285px; padding:0px 0px 0px 0px;'>
							<div class='rTable' style='width:285px'>
								<div class='rTableBody'>
									<div class='rTableRow'>
										<div class='rTableCell' style='width:75px; padding:0px 0px 0px 0px;'>".elgg_view("input/text", array(
																						"name" => "jot[last_done]",
																						"value"=> $jot->last_done,
																						))."</div>
										<div class='rTableCell' style='width:100px; padding:0px 0px 0px 0px;'>$frequency_units</div>
								    </div>
								</div>
							</div>
						</div>
				    </div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'>Pace*</div>
						<div class='rTableCell' style='width:285px; padding:0px 0px 0px 0px;'>
							<div class='rTable' style='width:285px'>
								<div class='rTableBody'>
									<div class='rTableRow'>
										<div class='rTableCell' style='width:75px; padding:0px 0px 0px 0px;'>".elgg_view("input/text", array(
																						"name" => "jot[pace]",
																						"value"=> $jot->pace,
																						))."</div>
										<div class='rTableCell' style='width:30px; padding:0px 0px 0px 0px;'>$pace_units</div>
										<div class='rTableCell' style='width:20px; padding:0px 0px 0px 0px;'>each</div>
										<div class='rTableCell' style='width:50px; padding:0px 0px 0px 0px;'>$pace_period</div>
								    </div>
								</div>
							</div>
						</div>
				    </div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:75px; padding:0px 0px 0px 0px; vertical-align:top;'>Notes</div>
						<div class='rTableCell' style='width:300px; padding:0px 0px 0px 0px;'>".elgg_view("input/longtext", array(
								   														"name" => "jot[description]", 
								 														"value" => $jot->description,
																						))."</div>
				    </div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:75px; padding:0px 0px 0px 0px;'></div>
						<div class='rTableCell' style='width:300px; padding:0px 0px 0px 0px;'></div>
				    </div>
				</div>
			</div>";
		
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save')));
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:set:schedule')));
		break;
	case 'item':
		if ($item_guid == 0){
		$add_box .= "
			<div class='rTable' style='width:375px'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'><label>Title</label></div>
						<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>".elgg_view('input/text', array(
																		'name' => 'item[title]',
								                                        'value'=>$jot->title,
																		))."</div>
					</div>
				</div>
			</div>";
/*				    <div class='rTableRow'>
						<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'><label>Category</label></div>
						<div class='rTableCell' style='width:285px; padding:0px 0px 0px 0px;'>".elgg_view('input/category', array('value'=>array($jot->category), 'add_leaf' => false,))."</div>
				    </div>
				</div>
			</div>";*/
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save')));
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => 'Describe...'));
		}
		break;
	default:
		$add_box .=
		"<div class='rTable' style='width:375px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'><label>Title</label></div>
					<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>".elgg_view('input/text', array(
																	'name' => 'jot[title]',
							                                        'value'=>$jot->title,
																	))."</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'><label>Asset</label></div>
					<div class='rTableCell' style='width:285px; padding:0px 0px 0px 0px;'>".elgg_view('output/url', array(
		                                 'name' => 'asset',
		                                 'text' => $item->title,
		                                 'href' => $referrer
										))."</div>
			    </div>
			</div>
		</div>";
		$add_box .="<label>Describe this $aspect</label><br>";
		$add_box .= elgg_view("input/longtext", array("name" => "jot[description]", "value" => $jot->description, "style"=>'width:375px'));
		$add_box .="<p>";
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save')));
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save:define')));
		break;
}

echo $add_box;
//echo $display;

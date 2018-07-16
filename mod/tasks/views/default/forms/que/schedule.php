<?php
/**
 * Page edit form body
 *
 * @package ElggPages
 */

$name = 'tasks';
$variables = elgg_get_config($name);
//echo elgg_dump($variables);

$guid           = get_input('guid');
if (empty($guid)){$guid   = $vars['item_guid'];}
$que            = get_entity($guid);
//$container_guid = get_input('container_guid');
//$element_type   = get_input('element_type');
$referrer       = elgg_extract('referrer', $vars);
$owner          = $que->getOwnerEntity();
$subtype        = $que->getSubtype();
$aspect         = $que->aspect;
$asset          = $que->asset;

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}

echo 'Form: mod\tasks\views\default\forms\que\schedule.php<br>';
?>
<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
	// if too long...trim it!
	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	} else {
		// otherwise, update 'characters left' counter
		$("#"+cntfield).html(maxlimit - field.value.length);
	}
}
</script>
<?php
$until_cycles = elgg_view('input/number', array(
			'name'  => 'que[cycles]',
		    'class' => 'w50',
		));
$until_date = elgg_view("input/date", array(
			"name" => "que[end_date]",
			"value"=> $que->end_date,
		    "class" => 'w100',
		));
$until = elgg_view('input/radio', array(
		'name'    => 'que[until_option]',
		'value'   => $que->until_option,
		'options' => array(
				"until $until_date"                   => 1,
				"until $owner->name no longer owns this item"     => 2,
		        "until performed $until_cycles times" =>3),
 ));
	$pace_options = array('day', 'week', 'month', 'year');
	$frequency_options  = array('days', 'weeks', 'months', 'years');
	if (get_entity($asset)->getSubtype() == 'market'){
		array_push($frequency_options, 'miles');
		if (!empty($item->frequency_units) && empty($que->frequency_units)){
			$default = $item->frequency_units;
		}
		else if(!empty($que->frequency_units)) {
			$default = $que->frequency_units;
		}
		else {
			$default = 'miles';
		}
	}
	$pace_period = elgg_view('input/select', array(
							'options' => $pace_options,
							'name' => 'que[pace_period]',
							'value' => $que->pace_period,
							));
	$frequency_units = elgg_view('input/select', array(
						'options' => $frequency_options,
						'name' => 'que[frequency_units]',
						'value' => $default,
						));
	$pace_units = elgg_view('input/select', array(
						'options' => $frequency_options,
						'name' => 'que[pace_units]',
						'value' => $default,
	                    ));
	// Give no options for frequency units or pace units if the item has a default frequency unit and the schedule had not been set.
	// After the schedule has been set, the user can select a different frequency unit, but it will default to the unit initially saved.
	// The user can select a different frequency unit for a set schedule to account for the possibility that the item default
	   // frequency unit may change. Any changes to the schedule must be intentional to avoid unit conflicts.
	   // For example, if the item default frequency unit changes from 'miles' to 'months', the schedule should remain at 'miles' until explicitly changed.
	if (!empty($item->frequency_units) && empty($que->frequency_units)){
		$frequency_units = $item->frequency_units;
		$pace_units      = $item->frequency_units;
		echo elgg_view('input/hidden', array('name' => 'que[frequency_units]', 'value' => $frequency_units));
		echo elgg_view('input/hidden', array('name' => 'que[pace_units]'     , 'value' => $frequency_units));
	}

$trigger = elgg_view('input/radio', array(
		'name'    => 'que[trigger]',
		'value'   => $que->trigger,
		'options' => array(
				"Que automatically when due" => 1,
				"Que only after I approve"   => 2),
		'align'  => 'vertical',
 ));
	
	echo "<label>Scheduled Maintenance</label>
	<div class='rTable' style='width:500px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Activity*</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>".elgg_view("input/text", array(
																"name" => "que[title]",
						                                        "value"=>$que->title,
																))."</div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Que every*</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>
					<div class='rTable' style='width:285px'>
						<div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell' style='width:75px;padding:0px 0px 0px 0px;'>".elgg_view("input/text", array(
																				"name" => "que[frequency]",
																				"value"=> $que->frequency,
																				))."</div>
								<div class='rTableCell' style='width:100px;padding:0px 0px 0px 0px;'>$frequency_units</div>
						    </div>
						</div>
					</div>
				</div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'>Starting</div>
				<div class='rTableCell' style='width:425px; padding:0px 0px 0px 0px;'>
					<div class='rTable' style='width:425px'>
						<div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell' style='width:100px;padding:0px 0px 0px 0px;'>
									".elgg_view("input/date", array("name" => "que[start_date]",
																	"value"=> $que->start_date,
											                        "class"=>"w100"
																	))."</div>
								<div class='rTableCell' style='width:3255px;padding:0px 0px 0px 0px;'>$until</div>
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
																				"name" => "que[last_done]",
																				"value"=> $que->last_done,
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
																				"name" => "que[pace]",
																				"value"=> $que->pace,
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
				<div class='rTableCell' style='width:300px; padding:0px 0px 0px 0px;'>";

if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='que_description' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.marketForm.que_description,"que-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.que_description,"que-remLen1",{$numchars})'>{$que->description}</textarea><br />
HTML;
	echo "<div class='que_characters_remaining'><span id='que-remLen1' class='que_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "que[description]", "value" => $que->description));
}

echo"			</div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:75px; padding:0px 0px 0px 0px;'></div>
				<div class='rTableCell' style='width:300px; padding:0px 0px 0px 0px;'></div>
		    </div>
		</div>
	</div>";
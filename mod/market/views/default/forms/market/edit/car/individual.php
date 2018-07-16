View: market/views/default/forms/market/edit/car/individual.php
<?php
$guid = $vars['guid'];
$entity = get_entity($guid);

if (empty($entity->frequency_units)){
	$frequency_units_value = 'miles';
}
else {
	$frequency_units_value = $entity->frequency_units;
}

$individual_content .= "
	<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
					<div class='rTableCell' style='width:250px'><label>Vehicle Identification Number (VIN)</label>&nbsp;</div>
					<div class='rTableCell' style='width:400px'>".elgg_view('input/text', array('name' => 'item[vin]','value' => $entity->vin))."			    </div>
			</div>
		</div>
	</div>";

$individual_content .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
					<div class='rTableCell' style='width:250px'><label>Individual Characteristics</label>&nbsp;</div>
					<div class='rTableCell' style='width:460px'></div>
					<div class='rTableCell' style='width:200px'><span class='hoverhelp'>".
												elgg_view('output/url', array(
													'text' => '[add characteristic]',
													'href' => '#',
													'class' => 'clone-individual-characteristic-action1' // unique class for jquery
												))."
												<span style='width:200px;'>Add another characteristic.</span>
												</span>
			    </div>
			</div>
		</div>
	</div>";

$individual_content .="<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'>".
			      elgg_view('input/text', array(
					'name' => 'item[this_characteristic_names][]',
					'value' => $name,
				))."</div>
				<div class='rTableCell' style='width:460px'>".
				elgg_view('input/text', array(
					'name' => 'item[this_characteristic_values][]',
					'value' => $values[$key],
				))."</div>
				<div class='rTableCell' style='width:200px'>
	            </div>
			</div>";


$names = $entity->this_characteristic_names;
if ($names && !is_array($names)) {
	$names = array($names);
}

$values = $entity->this_characteristic_values;
if ($values && !is_array($values)) {
	$values = array($values);
}

// iterate throught the names/values to populate what we have
foreach ($names as $key => $name) {
	if ($name === ''){
		continue;
	}
$individual_content .="<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'>$name".
			      elgg_view('input/hidden', array(
					'name' => 'item[this_characteristic_names][]',
					'value' => $name,
				))."</div>
				<div class='rTableCell' style='width:460px'>".
				elgg_view('input/text', array(
					'name' => 'item[this_characteristic_values][]',
					'value' => $values[$key],
				))."</div>
				<div class='rTableCell' style='width:200px'>
                    <a href='#' class='remove-node'>remove</a>
		        </div>
			</div>";
}
$individual_content .= '<div class="new_individual_characteristic1"></div>';
$individual_content .= "</div>
		</div>";

$individual_content .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'><b>Individual Features (options)</b>&nbsp;
				</div>
				<div class='rTableCell' style='width:460px'></div>
				<div class='rTableCell' style='width:200px'><span class='hoverhelp'>".
											elgg_view('output/url', array(
												'text' => '[add feature]',
												'href' => '#',
												'class' => 'clone-individual-feature-action1' // unique class for jquery
											))."
											<span style='width:200px;'>Add another feature.</span>
											</span>
		         </div>
			</div>
		</div>
	</div>";
$individual_content .="<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[this_features][]',
						'value' => $feature,
					))."
		        </div>
				<div class='rTableCell'>
				</div>
			</div>";
$individual_content .= "<div class='new_individual_feature1'></div>";

$features = $entity->this_features;
if ($features && !is_array($features)) {
	$features = array($features);
}

foreach ($features as $feature) {
	if ($feature === '') {
		// don't show empty values
		continue;
	}
$individual_content .= "<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>$feature".
			      elgg_view('input/hidden', array(
						'name' => 'item[this_features][]',
						'value' => $feature,
					))."</div>
				<div class='rTableCell'>
                    <a href='#' class='remove-node'>remove</a>
				</div>
			</div>";
}

$individual_content .= "</div>
		</div>";

$guids = array();
foreach ($groups as $e) {
	$guids[] = $e->guid;
}
if (count($individuals)>$value) {
   $value = count($individuals);
};

$individual_content .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Measure useage in</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/select', array('options' => array('days', 'weeks', 'months', 'years', 'miles', 'cycles'),'value' => $frequency_units_value,'name' => 'item[frequency_units]'))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Access</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/access', array('name' => 'access_id','value' => $access_id))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Group Visibility
					<span class='hoverhelp'>[?]
						<span style='width:500px;'>Select one or more groups to feature this item.  Begin typing to show matching groups.</span>
					</span>
				</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/tokeninput', array('value' => $guids,
																												'name' => 'groups',
																												'callback' => 'group_picker_callback', // this is a php function that searches groups in given criteria - see start.php
																												'query' => array('guid' => $entity->guid), // some information we need to send to the callback function
																												'multiple' => true,
																											))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>".elgg_echo("market:tags")."&nbsp
					<span class='hoverhelp'>[?]
					    <span style='width:500px;'>Use labels to place items into quebs (tidy little collections).  Separate labels with commas.</span>
				    </span>
				</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view("input/tags", array("name" => "item[tags]",
																										  "value" => $tags,
																										))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Warranty</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[warranty]','value' => $entity->warranty))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Lifecycle Type</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>
					<div class='rTable' style='width:100%'>
						<div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell' style='width:50%;padding:0px 0px'>".elgg_view('input/radio', array('name' => 'item[lifecycle]',
																										   'align' => 'horizontal',
																											'id' => 'lifecycle',
																											'options' => array('Depleting' => '1', 'Depreciating' => '2'),
																											'default' => 2,
																											'value' => $entity->lifecycle
																											))."</div>
								<div class='rTableCell' style='width:20%;padding:0px 0px'>Qty on Hand
									<span class='hoverhelp'>[?]
										<span style='width:500px;'><p>How many identical ' . elgg_echo($entity->title) . ' items are available?</p><p>Click [add +] to clone this item or to increase the quantity on hand.</span>
									</span>
								</div>
								<div class='rTableCell' style='width:5%;padding:0px 0px'>$value</div>
								<div class='rTableCell' style='width:25%;padding:0px 0px'>
									<span class='hoverhelp'>".elgg_view('output/url', array(
											'text' => '[add +]',
								            'href' =>  'market/inventory/'.$guid.'/'.$category.$next_level
										))."
										<span style='width:500px;'>Clone this item or increase the quantity on hand.</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>";

$individual_content .= "</div>
</div>";
echo $individual_content;


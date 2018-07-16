View: market/views/default/forms/market/edit/car/family.php
<?php
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}

$guid = $vars['guid'];
$entity = get_entity($guid);
		
if (empty($entity->frequency_units)){
	$frequency_units_value = 'miles';
}
else {
	$frequency_units_value = $entity->frequency_units;
}
$family_content = "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
            <div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Manufacture</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>
					<div class='rTable' style='width:100%'>
						<div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell' style='width:5%;padding:0px 5px 0px 0px'><b>Year</b></div>
								<div class='rTableCell' style='width:5%;padding:0px 5px 0px 0px'><b>Make</b></div>
								<div class='rTableCell' style='width:5%;padding:0px 5px 0px 0px'><b>Model</b></div>
								<div class='rTableCell' style='width:5%;padding:0px 5px 0px 0px'><b>Style</b></div>
							</div>
							<div class='rTableRow'>
								<div class='rTableCell' style='width:15%;padding:0px 5px 5px 0px'>".elgg_view('input/text', array('name' => 'item[year]', 'value' => $entity->year,))."</div>
        		                <div class='rTableCell' style='width:20%;padding:0px 5px 5px 0px'>".elgg_view('input/text', array('name' => 'item[manufacturer]', 'value' => $entity->manufacturer,))."</div>
								<div class='rTableCell' style='width:20%;padding:0px 5px 5px 0px'>".elgg_view('input/text', array('name' => 'item[model]', 'value' => $entity->model))."</div>
								<div class='rTableCell' style='width:20%;padding:0px 5px 5px 0px'>".elgg_view('input/text', array('name' => 'item[style]', 'value' => $entity->style))."</div>
							</div>
						</div>
					</div>
				</div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Measure useage in</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/select', array('options' => array('days', 'weeks', 'months', 'years', 'miles', 'cycles'),'value' => $frequency_units_value,'name' => 'item[frequency_units]'))."</div>
			</div>
		</div>
	</div>";	

$family_content .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'><b>Family Characteristics</b>&nbsp;
				                           <span class='hoverhelp'>[?]
												<span style='width:200px;'><p>Family Characteristics are characteristics that are common to all members of this family of ". elgg_echo("market:category:{$category}").".  Examples include Height, Weight, Fuel Type, etc.</p><p>Click [add characteristic] to add another.</span>
											</span>
				</div>
				<div class='rTableCell' style='width:460px'></div>
				<div class='rTableCell' style='width:200px'><span class='hoverhelp'>".
											elgg_view('output/url', array(
												'text' => '[add characteristic]',
												'href' => '#',
												'class' => 'clone-characteristic-action1' // unique class for jquery
											))."
											<span style='width:200px;'>Add another characteristic.</span>
											</span>
		         </div>
			</div>
		</div>
	</div>";
$family_content .="<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'>".
			      elgg_view('input/text', array(
					'name' => 'item[characteristic_names][]',
				))."</div>
				<div class='rTableCell' style='width:460px'>".
				elgg_view('input/text', array(
					'name' => 'item[characteristic_values][]',
				))."</div>
				<div class='rTableCell' style='width:200px'>
	            </div>
			</div>";

$names = $entity->characteristic_names;
if ($names && !is_array($names)) {
	$names = array($names);
}

$values = $entity->characteristic_values;
if ($values && !is_array($values)) {
	$values = array($values);
}

foreach ($names as $key => $name) {
	if ($name === ''){
		continue;
	}
	$family_content .="<div class='rTableRow'>
					<div class='rTableCell' style='width:250px'>$name".
				      elgg_view('input/hidden', array(
						'name' => 'item[characteristic_names][]',
						'value' => $name,
					))."</div>
					<div class='rTableCell' style='width:460px'>".
					elgg_view('input/text', array(
						'name' => 'item[characteristic_values][]',
						'value' => $values[$key],
					))."</div>
					<div class='rTableCell' style='width:200px'>
                    <a href='#' class='remove-node'>remove</a>
		            </div>
				</div>";
}

$family_content .= '<div class="new_characteristic1"></div>';
$family_content .= "</div>
			</div>";

$family_content .= "
	<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'><b>Family Features</b>&nbsp;
				</div>
				<div class='rTableCell' style='width:460px'></div>
				<div class='rTableCell' style='width:200px'><span class='hoverhelp'>".
											elgg_view('output/url', array(
												'text' => '[add feature]',
												'href' => '#',
												'class' => 'clone-feature-action1' // unique class for jquery
											))."
											<span style='width:200px;'>Add another feature.</span>
											</span>
		         </div>
			</div>
		</div>
	</div>";
				
$family_content .="
	<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[features][]',
					))."
		        </div>
				<div class='rTableCell'>
				</div>
			</div>";

$features = $entity->features;
if ($features && !is_array($features)) {
	$features = array($features);
}

foreach ($features as $feature) {
	if ($feature === '') {
		// don't show empty values
		continue;
	}

$family_content .="
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>$feature".
			      elgg_view('input/hidden', array(
						'name' => 'item[features][]',
						'value' => $feature,
					))."</div>
				<div class='rTableCell'>
                    <a href='#' class='remove-node'>remove</a>
				</div>
			</div>";
}
   		
$family_content .= "<div class='new_feature1'></div>";
$family_content .= "</div>
			</div>";

<!--View: market/views/default/forms/market/edit/animals/family.php<br>  -->
<?php
$guid = $vars['guid'];
$entity = get_entity($guid);
$entity_owner = get_entity($entity->owner_guid);

$title = $entity['title'];
$body = $entity['description'];
$tags = $entity->tags;
$category = $entity->category;
$access_id = $entity['access_id']; 
	
$individuals = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'market',
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token
	)
));

$components = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'component',
	'container_guid' => $entity->guid
));

$accessories = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$groups = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'shared',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'limit' => false,
	'callback' => false // because we don't need the user entity, this makes the query a bit more efficient
));
       	$pick = elgg_view('output/url', array(
        		'text' => '[pick]',
//       			'text' => elgg_view_icon('settings-alt'),
        		'class' => 'elgg-lightbox',
        		'data-colorbox-opts' => '{"width":600, "height":525}',
//        		'href' => "market/pick/item/" . $item->guid));
       			'href' => "market/pick_test/family_characteristics/" . $entity->guid));
        $pick_menu = "<span title='Select family characteristics'>$pick</span>";

// Extract characteristic names from existing entities
$dbprefix = elgg_get_config('dbprefix');
$q = "SELECT t1.string as characteristic_name
	FROM {$dbprefix}metastrings t1
	WHERE t1.string != ''
	  AND EXISTS (Select *
	              FROM {$dbprefix}metastrings     s1
				  JOIN {$dbprefix}metadata        s2 ON s1.id          = s2.name_id
				  JOIN {$dbprefix}entities        s4 ON s4.guid        = s2.entity_guid
				  JOIN {$dbprefix}entity_subtypes s5 ON s5.id          = s4.subtype
				  JOIN {$dbprefix}metadata        s6 ON s6.entity_guid = s4.guid
				  JOIN {$dbprefix}metastrings     s7 ON s7.id          = s6.value_id
				  WHERE s1.string   = 'characteristic_names'
				    AND s4.type     = 'object'
				    AND s5.subtype  = 'market'
				    AND s7.string   = '{$category}'
			  	    AND s2.value_id = t1.id)
	ORDER BY t1.string";

$characteristics = get_data($q);

foreach ($characteristics as $characteristic){
	$characteristic_names[]=$characteristic->characteristic_name;
}

//CSS code for class hoverhelp:
echo '<style>
		span.hoverhelp {background: #F0F0EE;}
	  </style>';
		
$family_content = "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
            <div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Scientific Name</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[scientific_name]', 'value' => $entity->scientific_name, 'placeholder'=>'Scientific Name'))
				                                                           .elgg_view('input/hidden', array('name' => 'jot[scientific_name]', 'value' => $entity->scientific_name))."</div>
		    </div>
            <div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Common Name</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[common_name]', 'value' => $entity->common_name, 'placeholder'=>'Common Name',))
				                                                           .elgg_view('input/hidden', array('name' => 'jot[common_name]', 'value' => $entity->common_name))."</div>
		    </div>
            <div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Taxonimic Serial #</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[tsn]', 'value' => $entity->tsn, 'placeholder'=>'Taxonomic Serial Number (TSN)',))
																		   .elgg_view('input/hidden', array('name' => 'jot[tsn]', 'value' => $entity->tsn))."</div>
			</div>
		</div>
	</div>";	

$names = $entity->characteristic_names;
if ($names && !is_array($names)) {
	$names = array($names);
}
if ($entity->record_stage == 'newborn'){
	$names = $characteristic_names;
}

$values = $entity->characteristic_values;
if ($values && !is_array($values)) {
	$values = array($values);
}

if ($names){
/*	$add_link = "<span title='Add another characteristic'>".
					elgg_view('output/url', array(
						'text' => '[add]',
						'href' => '#',
						'class' => 'clone-characteristic-action' // unique class for jquery
					))."
				</span>";*/
}
$family_content .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'><b>Family Characteristics</b>&nbsp;
				                           <span class='hoverhelp'>[?]
												<span style='width:400px;'><p>Family Characteristics are characteristics that are common to all members of this family of ". elgg_echo("market:category:{$category}").".  Examples include Height, Weight, Fuel Type, etc.</p><p>Click [add] to add a single characteristic or [pick] to select from a list of standard characteristics.</span>
											</span>
				</div>
				<div class='rTableCell' style='width:460px'></div>
				<div class='rTableCell' style='width:200px'>$pick_menu $add_link
		         </div>
			</div>";
//		</div>
//	</div>";

//$family_content .="<div class='rTable' style='width:100%'>
//		<div class='rTableBody'>";

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
/*	$family_content .="<div class='rTableRow'>
					<div class='rTableCell' style='width:250px'>".
				      elgg_view('input/text', array(
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
*/
}
$family_content .="<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'>".
			      elgg_view('input/text', array(
					'name' => 'item[characteristic_names][]',
			      	 'placeholder'=>'Characteristic',
				))."</div>
				<div class='rTableCell' style='width:460px'>".
				elgg_view('input/text', array(
					'name' => 'item[characteristic_values][]',
					'class' => 'last_characteristic',
					'placeholder'=>'Value',
				))."</div>
				<div class='rTableCell' style='width:200px'>
	            </div>
			</div>";

$family_content .= '<div class="new_characteristic"></div>';
$family_content .= "</div>
			</div>";

$family_content .= "
	<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'><b>Family Features</b>&nbsp;
				</div>
				<div class='rTableCell' style='width:460px'></div>
				<div class='rTableCell' style='width:200px'></div>
			</div>
		</div>
	</div>";

/*
 * $family_content .= "
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
												'class' => 'clone-feature-action' // unique class for jquery
											))."
											<span style='width:200px;'>Add another feature.</span>
											</span>
		         </div>
			</div>
		</div>
	</div>";
 */
$family_content .="
	<div class='rTable' style='width:100%'>
		<div class='rTableBody'>";

$features = $entity->features;
if ($features && !is_array($features)) {
	$features = array($features);
}
if ($features){
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
}
$family_content .="
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[features][]',
			      		'class' => 'last_feature',
			      		 'placeholder'=>'Feature',
					))."
					<div></div>
		        </div>
				<div class='rTableCell'>
				</div>
			</div>";

$family_content .= "<div class='new_feature'></div>";
$family_content .= "</div>
			</div>";
echo "$family_content";
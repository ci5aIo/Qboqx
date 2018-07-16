View: market/views/default/forms/market/edit/car/profile.php
<?php
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}
$selected_marketcategory = $vars['entity']->marketcategory;

$guid = $vars['guid'];
$entity = get_entity($guid);
if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  echo elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  echo elgg_view('input/hidden', array('name'=>'level', 'value' => 'family'));
  echo elgg_view('input/hidden', array('name'=>'item[family]', 'value' => 'family'));
  $this_level = '/car';
  $next_level = '/family'; //probably not the right way to reference the next level.
}
$entity_owner = get_entity($entity->owner_guid);
if (elgg_instanceof($entity, 'object','market')) {
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
}
?>
<script>

$(document).ready(function(){
    $("#Family_tab").click(function(){
        $("#Family_panel").slideToggle("slow");
    });
    $("#Individual_tab").click(function(){
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").addClass("elgg-state-selected");
    });
    $("#Acquisition_tab").click(function(){
        $("#Acquisition_panel").slideToggle("slow");
        $("#Acquisition_tab").addClass("elgg-state-selected");
    });
    $("#Schedule_tab").click(function(){
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").addClass("elgg-state-selected");
        
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Inventory_tab").click(function(){
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").addClass("elgg-state-selected");
        
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Management_tab").click(function(){
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").addClass("elgg-state-selected");
        
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Accounting_tab").click(function(){
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").addClass("elgg-state-selected");
        
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Gallery_tab").click(function(){
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").addClass("elgg-state-selected");
        
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Reports_tab").click(function(){
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").addClass("elgg-state-selected");
        
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Timeline_tab").click(function(){
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").addClass("elgg-state-selected");
        
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Individual_panel").slideToggle("slow");
        $("#Individual_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
    });
});
</script>

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
$(document).ready(function() {

	// clone characteristics node
	$('.clone-characteristic-action1').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.characteristics1').html();
		$(html).insertBefore('.new_characteristic1');
	});
	
	// clone characteristics node
	$('.clone-characteristic-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.characteristics').html();
		$(html).insertBefore('.new_characteristic');
	});

	// clone features node
	$('.clone-feature-action1').on('click', function(e){
		e.preventDefault();
		// clone the node
		var html = $('.features1').html();
		$(html).insertBefore('.new_feature1');
	});

	
	// clone features node
	$('.clone-feature-action').on('click', function(e){
		e.preventDefault();
		// clone the node
		var html = $('.features').html();
		$(html).insertBefore('.new_feature');
//		$(html).insertBefore('.clone-feature-action');
	});
	// clone characteristics node
	$('.clone-individual-characteristic-action1').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.individual_characteristics1').html();
		$(html).insertBefore('.new_individual_characteristic1');
	});

	// clone characteristics node
	$('.clone-individual-characteristic-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.individual_characteristics').html();
		$(html).insertBefore('.new_individual_characteristic');
	});	

	// clone individual features node
	$('.clone-individual-feature-action1').on('click', function(e){
		e.preventDefault();
		// clone the node
		var html = $('.individual_features1').html();
		$(html).insertBefore('.new_individual_feature1');
	});

	// remove a node
	$('.remove-node').on('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
});
</script>

<style> 
#Family_panel, #Individual_panel, #Acquisition_panel, #Schedule_panel, #Inventory_panel, #Management_panel, #Accounting_panel, #Gallery_panel, #Reports_panel, #Timeline_panel {
	display: none;
}
#Family_tab, #Individual_tab, #Acquisition_tab {
    padding: 5px;
    text-align: center;
    background-color: #e5eecc;
    border: solid 1px #c3c3c3;
}
</style>
<?php

$title = $entity['title'];
$body = $entity['description'];
//$body = $vars['marketbody'];
$tags = $entity->tags;
//$tags = $entity->labels;
$category = $entity->marketcategory;
$access_id = $entity['access_id']; 
	
$selected_category = $category;
$num_items = 16;  // 0 = Unlimited
$options = array(
	'types' => 'object',
	'subtypes' => 'market',
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => true,
	'list_type' => 'list', // options are list, gallery.  Seems to not allow other list types. See views.php in core.
	'list_type_toggle' => true,
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token)
);

// $options['metadata_name'] = "marketcategory";
// $options['metadata_value'] = $selected_category;
$content = elgg_list_entities_from_metadata($options);
// get related items
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
//CSS code for class hoverhelp:
echo '<style>
		span.hoverhelp {background: #F0F0EE;}
	  </style>';

if (count($individuals)>1) {
	echo '<table width=100%>
	      <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
	      <td>Qty on Hand: ';
		if (count($individuals)>$value) {
		   $value = count($individuals);
		};
		echo $value.'</td>';
		echo '<td>'.elgg_view('output/url', array(
					'text' => 'add',
					'href' =>  'market/edit_more/'.$guid.'/'.$category.'/family'
				    )).
			'</td></tr>';
	echo '<tr><td>#</td><td><b>Family Members</b></td><td>Manufacturer Serial #</td><td>Owner Serial #</td></tr>';
			foreach ($individuals as $i) {
				echo '<tr><td>'.$i->guid.'</td>
				      <td>'.elgg_view('output/url', array(
								'text' => $i->title,
								'href' =>  'market/edit_more/'.$i->guid.$this_level.$next_level.'/individual'
					//			'href' =>  'market/edit_more/'.$i->guid.$vars['h'].$next_level
							)).
					'</td> <td>'.$i->serial01.'</td>
					<td>'.$i->serial02.'</td>
					<td>'.elgg_view('output/url', array('text' => 'Delete', 'href' => elgg_add_action_tokens_to_url('action/market/delete?guid=' . $guid))).'</td>
					</tr>';
			 }
	echo '</table>';
}

echo "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Title</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[title]', 'value' => $entity->title,))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Category</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_echo("market:category:{$category}")."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Owner</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('output/url', array('text' => $entity_owner->name,'href' =>  'profile/'.$entity_owner->username))."</div>
			</div>";
echo "		<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px;vertical-align:top;'></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('market/thumbnail', array('marketguid' => $entity->guid, 'size' => 'large', 'tu' => $entity->time_updated))."</div>
			</div>";
/*			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/dropzone', array('name' => 'upload_guids[]',
																											  'accept' => "image/*",
																											  'max' => 25,
																											  'multiple' => false,
																											  'container_guid' => $guid,
																											))."</div>
			</div>*/
echo "		</div>
	</div>";


echo '<br>';
		$widget = get_entity(1012);
		if (!elgg_instanceof($widget, 'object', 'widget')) {
			return true;
		}
		
		$handler = $widget->handler;
		
		$can_edit = false;
		
		$controls = elgg_view_menu('widget', array(
			'entity' => $widget,
			'show_edit' => $can_edit,
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
		$widget_id = "elgg-widget-1012";
		$widget_instance = "elgg-widget-instance-$handler";
		$widget_class  = "elgg-module elgg-module-widget";
		$widget_class .= " elgg-state-fixed $widget_instance";
		$family_header = "<div id='Summary_tab' class='elgg-widget-handle clearfix'><h3>Family</h3>
			$controls
			</div>";
		
		$widget_body_class = "elgg-widget-content";
		$widget_is_collapsed = false;
		$widget_is_open = true;
		
		if (elgg_is_logged_in()) {
			$widget_is_collapsed = widget_manager_check_collapsed_state($widget->guid, "widget_state_collapsed");
			$widget_is_open      = widget_manager_check_collapsed_state($widget->guid, "widget_state_open");
		}
		if (($widget->widget_manager_collapse_state === "closed" || $widget_is_collapsed) && !$widget_is_open) {
		
			$widget_body_class .= " hidden";
		}
		$widget_body = "<div class='" . $widget_body_class . "'";
		$widget_body .= " id='elgg-widget-content-" . $widget->guid . "'>";
		$widget_body .= $content;
		$widget_body .= "</div>";
/*
echo elgg_view_module('widget', '', $widget_body, array(
		'class'  => $widget_class,
		'id'     => $widget_id,
		'header' => $widget_header,
));*/
		
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
/*	
$family_content .="
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[features][]',
						'value' => $feature,
					))."
		        </div>
				<div class='rTableCell'>
                    <a href='#' class='remove-node'>remove</a>
				</div>
			</div>";
}*/
   		
$family_content .= "<div class='new_feature1'></div>";
$family_content .= "</div>
			</div>";

$individual_content = "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>VIN</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[vin]', 'value' => $entity->vin))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px;vertical-align:top;'><b>Description</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>
				";

if ($allowhtml != 'yes') {
	$individual_content .=  "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	$individual_content .=  <<<HTML
<textarea name='description' class='mceNoEditor' rows='6' cols='40'
  onKeyDown='textCounter(document.marketForm.description,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.description,"market-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	$individual_content .=  "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	$individual_content .=  elgg_view("input/longtext", array("name" => "item[description]", "value" => $body));
}

$individual_content .= "		</div>
		</div>
	</div>
</div>";	
$individual_content .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'><b>Individual Characteristics</b>&nbsp;
				</div>
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
	
/*$individual_content .="<div class='rTableRow'>
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
                    <a href='#' class='remove-node'>remove</a>
		        </div>
			</div>";
*/}
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
/*	
$individual_content .= "<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[this_features][]',
						'value' => $feature,
					))."
		        </div>
				<div class='rTableCell'>
                    <a href='#' class='remove-node'>remove</a>
				</div>
			</div>";*/
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


$acquisition_content = "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Condition</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".
					elgg_view('input/select', array('name' => 'item[acquire_condition]',
                                                    'value' => $entity->acquire_condition, 
													'options_values'=>array('new' => 'New',
																			'used' => 'Used',
																			'refurbished' => 'Refurbished')))."
				</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Previous Owner</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[last_owner]', 'value' => $entity->last_owner))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Date Acquired</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/date', array('name' => 'item[moment]', 'value' => $entity->moment))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Amount Paid</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[acquire_cost]', 'value' => $entity->acquire_cost))."</div>
			</div>";

$acquisition_content .= "</div>
</div>";


/* -- 2016-07-26 - DRAFT attempt to indicate an open/closed state for the tab.
echo "<div id='Family_tab' style='cursor:pointer' class='elgg-module elgg-module-widget elgg-module elgg-module-widget elgg-state-fixed elgg-widget-instance-messageboard'>";
	echo "<div class='elgg-head'>";
		echo "<div class='elgg-widget-handle clearfix'><h3>Family</h3>";
			echo "<ul class='elgg-menu elgg-menu-widget elgg-menu-hz elgg-menu-widget-default'>
					<li class='elgg-menu-item-collapse' style='position:relative'>
						<a href='#Family_tab' class='elgg-menu-content elgg-widget-collapse-button elgg-state-active' rel='toggle'>";
			echo "		</a>
					</li>
				</ul>";
		echo "</div>";
		echo "<div class='elgg-body'>";
			echo "<div id='Family_panel' class='elgg-widget-content'>$family_content</div>";
		echo "</div>";
	echo "</div>";	
echo "</div>";*/

echo "<div id='Family_tab' style='cursor:pointer'><h3>Family</h3></div>";
echo "<div id='Family_panel' class='elgg-head'>$family_content</div>";

echo "<div id='Individual_tab' style='cursor:pointer'><h3>Individual</h3></div>";
echo "<div id='Individual_panel' class='elgg-head'>$individual_content</div>";

echo "<div id='Acquisition_tab' style='cursor:pointer'><h3>Acquisition</h3></div>";
echo "<div id='Acquisition_panel' class='elgg-head'>$acquisition_content</div>";


// add a submit button
echo '<br><br>';
echo '<div class="elgg-foot">';
	echo elgg_view('input/submit', array('value' => 'Save')).
	     elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply'));
echo '</div>';

// now we add a new empty set we can clone
// wrap it in a div with a unique class we can identify

// Characteristics clone
echo "<div style='visibility:hidden'>";
echo "<div class='characteristics1'>
	    <div class='rTableRow'>
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
			</div>
		</div>"; // end of Characteristics clone

// Characteristics clone
echo '<div class="characteristics">';
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[characteristic_names][]',
		'style' => 'width: 25%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[characteristic_values][]',
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</div>';
echo '</div>'; // end of Characteristics clone

//Feature clone
echo "<div class='features1'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[features][]',
						'value' => $feature,
					))."
		        </div>
				<div class='rTableCell'>
					<a href='#' class='remove-node'>remove</a>
				</div>
			</div>
		</div>"; // end of Feature clone

echo "<div class='individual_features1'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[this_features][]',
						'value' => $feature,
					))."
		        </div>
				<div class='rTableCell'>
				<a href='#' class='remove-node'>remove</a>
				</div>
			</div>
		</div>"; // end of Feature clone

// Characteristics clone
echo "<div class='individual_characteristics1'>
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
					<a href='#' class='remove-node'>remove</a>
		            </div>
			</div>
		</div>"; // end of Characteristics clone


echo "<div id='Inventory_panel' class='elgg-head'><h4>Inventory</h4>";
include __DIR__."/que/inventory.php";
echo "</div>";

echo "<div id='Management_panel' class='elgg-head'><h4>Management</h4>";
include __DIR__."/que/management.php";
echo "</div>";

echo "<div id='Accounting_panel' class='elgg-head'><h4>Accounting</h4>";
include __DIR__."/que/accounting.php";
echo "</div>";

echo "<div id='Gallery_panel' class='elgg-head'><h4>Gallery</h4>";
include __DIR__."/que/gallery.php";
echo "</div>";

echo "<div id='Reports_panel' class='elgg-head'><h4>Reports</h4>";
include __DIR__."/que/reports.php";
echo "</div>";

echo "<div id='Timeline_panel' class='elgg-head'><h4>Timeline</h4>";
include __DIR__."/que/timeline.php";
echo "</div>";

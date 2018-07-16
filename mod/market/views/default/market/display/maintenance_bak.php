<!-- Parent:  -->
<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
$asset_guid = $item_guid;
$fields = market_prepare_brief_view_vars($entity);

/**/
$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$tasks_qued = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'scheduled_for',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$issues = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
));
$observations = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'observation',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));

$support_groups = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'support_group_of',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));

$pick_button = elgg_view('output/url', array(
		'text' => 'pick ...', 
	    'class' => 'elgg-button-submit-element elgg-lightbox',
	    'data-colorbox-opts' => '{"width":500, "height":525}',
        'href' => "market/groups/support/$item_guid/2"));					
//        'href' => "market/groups?element_type=groups&container_guid=" . $item_guid));					

//Draft
$layout .=
"<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:400px'><label>Support Groups</label>$pick_button</div>
				<div class='rTableCell' style='width:150px'></div>
			</div>";

//echo elgg_dump($support_groups);
$layout1 .= 
"<table>
   <tr>
       <td><label>Support Groups</label></td>
       <td>$pick_button</td>
   </tr>";

// echo '<table>';
// echo "<tr><td><label>Support Groups</label></td><td>$pick_button</td></tr>";

if ($support_groups) {
foreach ($support_groups as $i) {
	$element_type = 'support_group';
	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid/$i->name"));
	
	if ($i->canEdit()) {
		$detach = elgg_view("output/confirmlink",array(
	    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$item_guid}",
	    	'text' => elgg_view_icon('unlink'),
	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'support group'),
	    	'encode_text' => false,
	    ));
			
	}
	$layout .= "<div class='rTableRow highlight'>
				<div class='rTableCell' style='width:400px'>$link</div>
				<div class='rTableCell' style='width:150px'>$detach</div>
			  </div>";
	$layout1 .= '<tr class="highlight">
	        <td>'.$link.'
	        </td>
	        <td nowrap>'.$detach.'</td>
	      ';
// 	echo '<tr class="highlight">
// 	        <td>'.$link.'
// 	        </td>
// 	        <td nowrap>'.$detach.'</td>
// 	      ';
	}
}	

// discoveries
$hoverhelp = elgg_echo('jot:hoverhelp:Observation');
	$layout .= "<div class='rTableRow'>
				<div class='rTableCell' style='width:400px'><label>Observations</label>&nbsp;
    		        <span class='hoverhelp'>[?]
    		        	<span style='width:500px;'><p>$hoverhelp</span>
    		        </span></div>
				<div class='rTableCell' style='width:150px'></div>
			</div>";
	$layout1 .= "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2><b>Observations</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
	        </td>
	      </tr>";
	/***********************/
// 	echo "<tr><td colspan=2>&nbsp;</td></tr>
// 	      <tr>
// 	        <td colspan=2><b>Observations</b>&nbsp;
// 		        <span class='hoverhelp'>[?]
// 		        	<span style='width:500px;'><p>$hoverhelp</span>
// 		        </span>
// 	        </td>
// 	      </tr>";

/*	      <tr>
	        <td colspan=2>".
    elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=observation&guid=$item_guid&asset=$asset_guid")).'
	        </td>
	      </tr>';*/

if ($observations) {
foreach ($observations as $i) {
			$element_type = 'observation';
	        $delete = '';
			$link = elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid/Details"));
	        $observation = get_entity($i->guid);
			switch ($i->state){
				case  1: $state = 'Discover'; break;
				case  2: $state = 'Resolve' ; break;
				case  3: $state = 'Assign'  ; break;
				case  4: $state = 'Accept'  ; break;
				case  5: $state = 'Complete'; break;
				default: $state = 'Discover'; break;
			}
			$canDelete = FALSE;
			$observations_downstream = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'relationship' => 'observation',
				'relationship_guid' => $i->guid,
			    'inverse_relationship' => true,
				'limit' => false,
			));
			$causes_downstream = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'relationship' => 'cause',
				'relationship_guid' => $i->guid,
			    'inverse_relationship' => true,
				'limit' => false,
			));
			$efforts_downstream = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'relationship' => 'effort',
				'relationship_guid' => $i->guid,
			    'inverse_relationship' => true,
				'limit' => false,
			));
			if ($i->state == 1 && !$observations_downstream && !$causes_downstream && !$efforts_downstream) {
				$canDelete = TRUE;
			}
			if ($i->canEdit() && $canDelete == TRUE) {
				$delete = elgg_view("output/confirmlink",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	$layout .= "<div class='rTableRow highlight'>
				<div class='rTableCell' style='width:400px'>$link</div>
				<div class='rTableCell' style='width:150px'>$delete $state</div>
			  </div>";
	$layout1 .= '<tr class="highlight">
	        <td>'.$link.'
	        </td>
	        <td nowrap>'.$delete.$state.'</td>
	      ';
	/***********************/
// 	echo '<tr class="highlight">
// 	        <td>'.$link.'
// 	        </td>
// 	        <td nowrap>'.$delete.$state.'</td>
// 	      ';
    }
}							
if ($issues) {
$layout1 .= '<tr><td colspan=2>&nbsp;</td></tr>';
//echo '<tr><td colspan=2>&nbsp;</td></tr>';
	// issues
$hoverhelp = elgg_echo('jot:hoverhelp:Issues');
	$layout .= "<div class='rTableRow'>
				<div class='rTableCell' style='width:400px'><label>Issues</label>&nbsp;
    		        <span class='hoverhelp'>[?]
    		        	<span style='width:500px;'><p>$hoverhelp</span>
    		        </span></div>
				<div class='rTableHead' style='width:150px'></div>
			</div>";
	$layout1 .= "<tr>
		         <td width=100%><b>Issues</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>
			      <td nowrap>
				  </td>
		    </tr>";
	$layout1 .= '<tr><td colspan=2>';
	/***********************/
// 		echo "<tr>
// 		         <td width=100%><b>Issues</b>&nbsp;
// 		        <span class='hoverhelp'>[?]
// 		        	<span style='width:500px;'><p>$hoverhelp</span>
// 		        </span>
// 		        </td>
// 			      <td nowrap>
// 				  </td>
// 		    </tr>";
// 	echo '<tr><td colspan=2>';
foreach ($issues as $i) {
	$layout .= "<div class='rTableRow highlight'>
				<div class='rTableCell' style='width:400px'>".elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/issue/'.$i->guid))."</div>
				<div class='rTableCell' style='width:150px'></div>
			  </div>";
	$layout1 .= '<tr>
	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/issue/'.$i->guid)).'</td>
	      </tr>';
	/***********************/
// 	echo '<tr>
// 	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/issue/'.$i->guid)).'</td>
// 	      </tr>';
	}
}	
if ($tasks) {
	$layout .= "<div class='rTableRow'>
				<div class='rTableCell' style='width:400px'><label>Work</label></div>
				<div class='rTableHead' style='width:150px'></div>
			</div>";
	
    $layout1 .= '<tr><td colspan=2>&nbsp;</td></tr>';
    $layout1 .= '<tr><td><b>Work</b></td>
	      <td nowrap>
	      </td></tr>';
    $layout1 .= '<tr><td colspan=2>';
	/***********************/
//echo '<tr><td colspan=2>&nbsp;</td></tr>';
// Tasks
// 	echo '<tr><td><b>Work</b></td>
// 	      <td nowrap>
// 	      </td></tr>';
// 	echo '<tr><td colspan=2>';

foreach ($tasks as $i) {
	$layout .= "<div class='rTableRow highlight'>
				<div class='rTableCell' style='width:400px'>".elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid))."</div>
				<div class='rTableCell' style='width:150px'></div>
			  </div>";
	$layout1 .= '<tr>
	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid)).'</td>
	      </tr>';
	/***********************/
// 	echo '<tr>
// 	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid)).'</td>
// 	      </tr>';
	}
}
$layout .= "</div>
	              </div>";
$layout1 .= '<tr><td colspan=2>&nbsp;</td></tr>';
$layout1 .= '</table>';
	/***********************/
// echo '<tr><td colspan=2>&nbsp;</td></tr>';
// echo '</table>';

//echo $layout1;

if ($tasks_qued) {
$layout .= "<b>Scheduled Maintenance</b>";
$layout .= "
<div class='rTable' style='width:550px'>
	<div class='rTableBody'>
		<ul style = 'list-style:none;
					 max-height:100px;
					 margin:0;
					 overflow:auto;
					 padding:0;
					 text-indent:0px'>";

// repeat to simply illustrate the scrolling of the list
// $n=1;
// for ($i = $n+1; $i <= 10; $i++) {

foreach ($tasks_qued as $que) {
	foreach ($que as $name => $value){
		$display .= $name.'=>'.$value.'<br>';
	}
	$set = elgg_view('output/url', array(
        		'text' => elgg_view_icon('settings-alt'),
        		'href' => "que/set/$que->guid"));
    $set_menu = "<span title='Set maintenance schedule properties'>$set</span>";
        
	$layout .=  "<li>
			  <div class='rTableRow highlight'>
				<div class='rTableCell' style='width:400px'>$set_menu <span title='Edit maintenance schedule'>".elgg_view('output/url', array('text' => $que->title,'href'=>'jot/box/'.$que->guid.'/schedule/edit','class'=>'elgg-lightbox','data-colorbox-opts'=>'{"width":500, "height":325}'))."</span></div>
				<div class='rTableCell' style='width:150px'>every $que->frequency $que->frequency_units</div>
			  </div>
		</li>";
	}	
$layout .=  "</ul></div>
  </div>";	
}
//}

/*		$view_menu[2] = ElggMenuItem::factory(array('name'           => '02scheduled',
												'text'               => 'Add scheduled maintenance ...', 
												'class'              => 'elgg-lightbox',
											    'data-colorbox-opts' => '{"width":500, "height":325}',
												'href'               => "jot/box/{$item_guid}/scheduled"));
*/

echo $layout;
//echo $display;

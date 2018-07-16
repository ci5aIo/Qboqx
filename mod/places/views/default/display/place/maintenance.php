<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
$asset_guid = $item_guid;
//$fields = market_prepare_brief_view_vars($entity);

/**/
$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
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

echo '<table>';
// discoveries
$hoverhelp = elgg_echo('jot:hoverhelp:Observation');
	echo "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2><b>Observations</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>".
    elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=observation&guid=$item_guid&asset=$asset_guid")).'
	        </td>
	      </tr>';
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
	echo '<tr class="highlight">
	        <td>'.$link.'
	        </td>
	        <td nowrap>'.$delete.$state.'</td>
	      ';
    }
}							
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	// issues
$hoverhelp = elgg_echo('jot:hoverhelp:Issues');
		echo "<tr>
		         <td width=100%><b>Issues</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>
			      <td nowrap>
				  </td>
		    </tr>";
	echo '<tr><td colspan=2>';
if ($issues) {
foreach ($issues as $i) {
	echo '<tr>
	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/issue/'.$i->guid)).'</td>
	      </tr>';
	}
}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
// Tasks
	echo '<tr><td><b>Work</b></td>
	      <td nowrap>
	      </td></tr>';
	echo '<tr><td colspan=2>';

if ($tasks) {
foreach ($tasks as $i) {
	echo '<tr>
	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid)).'</td>
	      </tr>';
	}
}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '</table>';

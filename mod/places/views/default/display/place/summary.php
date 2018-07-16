<?php
$place        = $vars['entity'];
$section      = $vars['this_section'];
$subtype      = $place->getSubtype();
$fields       = place_prepare_brief_view_vars($subtype, $place, $section);
$limit        = 3;
switch ($place->state){
	case  1: $state = 'Plan'; break;
	case  2: $state = 'Build' ; break;
	case  3: $state = 'Maintain'  ; break;
	case  4: $state = 'Remodel'; break;
	case  5: $state = 'Remove'; break;
	default: $state = 'Maintain'; break;
}
//$fields       = place_prepare_brief_view_vars($place, $section);
echo "<!--Section = $section-->";

$num_spaces = count(elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'place',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $place->guid,
				'limit' => false,
			)));
/**/
$spaces = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'place',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $place->guid,
				'limit' => $limit,
			));

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $place->guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
	'relationship_guid' => $place->guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$issues = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $place->guid,
	'inverse_relationship' => true,
	'limit' => false,
));
	// section 1
	$section1 = '';
	foreach ($fields as $label => $value) {
		if ($fields['value'] === '') {
			// don't show empty values
			continue;
		}
	    $section1 .= "<tr><td nowrap><b>$label<b></td><td style='padding-left: 15px;'>$value</td></tr>";
	}
	
	if ($section1) {
		echo '<table>' . $section1 . '</table><p>';
	}
echo '<table width = 100%>';

	$count_label = '';
	if($num_spaces>0){$count_label="($num_spaces)";}

// spaces
	echo "<tr>
	        <td colspan=2><b>Managed Spaces</b>&nbsp;$count_label
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'></span>
		        </span>
	        </td>
	      </tr>";
if ($spaces) {
foreach ($spaces as $i) {
			$element_type = 'space';
	echo '<tr class="highlight">
	        <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "places/view/$i->guid/$i->title/Details")).'</td>
	      </tr>';
    }
	
		if($num_spaces > $limit){
		echo "<tr>
			      <td colspan=2>".elgg_view('output/url', array(
					'text' => 'more ...', 
				    'class' => 'elgg-lightbox elgg-button-submit-element',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
	                'href' => "places/show_more/$place->guid"))."</td>
		      </tr>";
	}	

}
else {
	echo '<tr>
	        <td colspan=2>No managed spaces defined</td>
	      </tr>';	
     }	
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
			
	// Documentation
	echo '<tr><td width=100%><b>Documentation</b></td>
		      <td nowrap>'.
		      elgg_view('output/url', array(
				'text' => '[add new document]', 
			    'class' => 'elgg-lightbox',
			    'data-colorbox-opts' => '{"width":500, "height":225}',
                'href' => "jot/attach?element_type=document&container_guid=" . $place->guid)).'</td>
          </tr>';
	$icon = elgg_view_icon('unlink');
		if ($documents) {
		foreach ($documents as $i) {
			echo '<tr><td class="highlight">'.$icon;
			echo  elgg_view('output/url', array(
					'text' =>  $i->title,
					'href' =>  'file/view/'.$i->guid)).'</td>';
			echo '</tr>';
		}
	}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>';

	// issues
	echo '<tr><td><b>Issues</b></td>
	      <td nowrap>'.
	      elgg_view('output/url', array(
			'text' => '[add new issue]', 
			'href' => "jot/add/$$place->guid/issue")).'</td>
	    </tr>';
		if ($issues) {
		foreach ($issues as $i) {
			echo '<tr>';
				echo '<td class="highlight">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/issue/'.$i->guid)).'</td>';
			echo '</tr>';
		}
	}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	echo '<tr><td><b>Tasks</b></td>
		      <td nowrap>'.
		      elgg_view('output/url', array(
				'text' => '[add new task]', 
				'href' => "tasks/add/".$entity->owner_guid."?element_type=task&container_guid=".$place->guid
		      )).'
		      </td>
		  </tr>';
		if ($tasks) {
		foreach ($tasks as $i) {
			echo '<tr>';
	//			echo '<td>'.$i->guid.'</td>';
	//			echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'quebx/task/'.$i->guid)).'</td>';
				echo '<td class="highlight">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid)).'</td>';
			echo '</tr>';
		}
	}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>
          </table>';
//echo elgg_dump($place);

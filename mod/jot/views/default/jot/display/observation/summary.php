<?php
$observation  = $vars['entity'];
$section      = $vars['this_section'];
$subtype      = $observation->getSubtype();
$fields       = jot_prepare_brief_view_vars($subtype, $observation, $section);
//$fields       = observation_prepare_brief_view_vars($observation, $section);
//echo elgg_dump($observation);
echo "<!--Section = $section-->";
/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $observation->guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
	'relationship_guid' => $observation->guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$issues = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $observation->guid,
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
/*		if ($label == 'asset'){
			$value = get_entity($value)->title;
		}
*/	    $section1 .= "<tr><td nowrap><b>".elgg_echo('observations:'.$label)."<b></td><td style='padding-left: 15px;'>$value</td></tr>";
	}
	
	if ($section1) {
		echo '<table>' . $section1 . '</table><p>';
	}
	
	// Documentation
	echo '<table><tr><td width=100%><b>Documentation</b></td>
		      <td nowrap>'.
		      elgg_view('output/url', array(
				'text' => '[add new document]', 
			    'class' => 'elgg-lightbox',
			    'data-colorbox-opts' => '{"width":500, "height":225}',
                'href' => "jot/attach?element_type=document&container_guid=" . $observation->guid)).'</td>';
	echo '</tr>
	      <tr><td colspan=2>';
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
			'href' => "jot/add/$observation_guid/issue")).'</td>';
	echo '</tr>
	      <tr><td colspan=2>';
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
			'href' => "tasks/add/".$entity->owner_guid."?element_type=task&container_guid=".$observation->guid
	      )).'
	      </td></tr>';
	echo '<tr><td colspan=2>';
	
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
//echo elgg_dump($observation);

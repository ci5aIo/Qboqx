<?php
$issue     = $vars['entity'];
$section   = $vars['this_section'];
$fields    = issue_prepare_brief_view_vars($issue);

/**/
$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
	'relationship_guid' => $issue->guid,
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
	    $section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value</td></tr>";
	}
	
	if ($section1) {
//		echo '<table>' . $section1 . '</table><p>';
	}
// Tasks
	echo '<table>
	      <tr><td colspan=2>&nbsp;</td>
	      <tr><td width=100%><b>Tasks</b></td>
	      <td nowrap>'.
	      elgg_view('output/url', array(
			'text' => '[add new task]', 
			'href' => "tasks/add/".$entity->owner_guid."?element_type=task&container_guid=".$issue->guid
	      )).'
	      </td></tr>';
	echo '<tr><td colspan=2>';
	
		if ($tasks) {
		foreach ($tasks as $i) {
			echo '<tr class="highlight">';
	//			echo '<td>'.$i->guid.'</td>';
	//			echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'quebx/task/'.$i->guid)).'</td>';
				echo '<td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid)).'</td>';
			echo '</tr>';
		}
	}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>
          </table>';
<?php
$jot          = $vars['entity'];
$subtype      = $jot->getSubtype();
$section      = $vars['this_section'];
$aspect       = $jot->aspect;
$fields       = jot_prepare_brief_view_vars($subtype, $jot, $section);
echo "<!--Section = $section-->";
/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $jot->guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
	'relationship_guid' => $jot->guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$issues = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $jot->guid,
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
		echo '<table>' . $section1 . '</table><p>';
	}
echo '$aspect: '.$aspect.'<br>';
echo '$subtype: '.$subtype.'<br>';
	// Documentation
	echo '<table><tr><td width=100%><b>Documentation!!!</b></td>
		      <td nowrap>'.
		      elgg_view('output/url', array(
				'text' => '[add new document]', 
			    'class' => 'elgg-lightbox',
			    'data-colorbox-opts' => '{"width":500, "height":225}',
                'href' => "jot/attach?element_type=document&container_guid=" . $jot->guid)).'</td>';
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
			'href' => "jot/add/$jot_guid/issue")).'</td>';
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
			'href' => "tasks/add/".$entity->owner_guid."?element_type=task&container_guid=".$jot->guid
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
//echo elgg_dump($jot);

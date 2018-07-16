<?php
$issue     = $vars['entity'];
$section   = $vars['this_section'];
$asset     = $issue->asset;
$issue_guid = $issue->guid;
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
	echo '<table width = 100%>
	      <tr><td colspan=2>&nbsp;</td>
	      <tr>
	        <td colspan=2><b>Tasks</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p>Discoveries are a series of observations made to understand an issue.  Each observation can have further discoveries.  All observations roll up to the containing observation.</p><p>Enter your observation and click [add] to add a new observation.</span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.
    elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=task&guid=$issue_guid&asset=$asset")).'
	        </td>
	      </tr>';
		if ($tasks) {
		foreach ($tasks as $i) {
		echo '<tr class="highlight">
		        <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid)).'</td>
		      </tr>';
		}
	}	
	echo '</table>';
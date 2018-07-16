<?php
$issue     = $vars['entity'];
$section   = $vars['this_section'];
	/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $issue->guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
	// Documentation
	echo '<table><tr><td width=100%><b>Documentation</b></td>
		      <td nowrap>'.
		      elgg_view('output/url', array(
				'text' => '[add new document]', 
			    'class' => 'elgg-lightbox',
			    'data-colorbox-opts' => '{"width":500, "height":225}',
                'href' => "jot/attach?element_type=document&container_guid=" . $issue->guid)).'</td>';
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
	echo '<tr><td colspan=2>&nbsp;</td></tr>
          </table>';
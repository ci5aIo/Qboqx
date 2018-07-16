<?php
/*
 * Cause Details
*/ 
$entity    = $vars['entity'];
$section   = $vars['this_section'];
$asset     = $entity->asset;
$category  = $entity->marketcategory;
$item_guid = $entity->guid;
$fields    = cause_prepare_brief_view_vars($entity);

/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$observations = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'observation',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));
$discoveries = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'observation',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$causes = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'cause',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
echo "asset : $asset";
  echo elgg_view('input/hidden', array('name' =>'asset', 'value' => $asset));
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
	
// discoveries
	echo '<table width = 100%>
	      </tr>
	      <tr><td colspan=2><b>Discoveries</b>&nbsp;
	      <span class="hoverhelp">[?]
	      <span style="width:500px;"><p>Discoveries are a series of observations made to understand an issue.  Each observation can have further discoveries.  All observations roll up to the containing observation.</p><p>Enter your observation and click [add] to add a new observation.</span>
	      </span>
	      </td>
	      <tr>';
	echo elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=observation&guid=$item_guid&asset=$asset_guid"));
//	echo elgg_view_form('market/add/element', array("action" => "action/market/add/element?element_type=observation&guid=$item_guid&asset=$asset_guid"));
	echo '<td colspan=2>';
	    if ($discoveries) {
		foreach ($discoveries as $i) {
			$element_type = 'discovery';
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid/Details")).'</td>
	        <td>'.$delete.'
	      </tr>';
		}
	}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>
	      <tr><td colspan=2 width=100%><b>Causes</b>&nbsp;
	      <span class="hoverhelp">[?]
	      <span style="width:500px;"><p>A Cause draws a conclusion from the discoveries.  You may have multiple causes.  Each cause can be confirmed or rejected, based on further discoveries so feel free to guess.  You can always refine your suspicions.</p><p>Enter your suspected cause and click [add] to add a new cause.</span>
	      </span>
	      </td></tr>';
	echo elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=cause&guid=$item_guid"));
//	echo elgg_view_form('market/add/element', array("action" => "action/market/add/element?element_type=cause&guid=$item_guid"));
	echo '<tr><td colspan=2>';
		if ($causes) {
		foreach ($causes as $i) {
			echo '<tr class="highlight">';
				echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/view/'.$i->guid)).'</td>';
			echo '</tr>';
		}
	}	
	echo '<tr><td>&nbsp;</td>';
	echo '</tr>';
	echo '<tr><td colspan=1><b>Documentation</b></td>';
		echo '<td nowrap>'.
		      elgg_view('output/url', array(
			'text' => 'add ...', 
		    'class' => 'elgg-lightbox elgg-button-submit-element',
			    'data-colorbox-opts' => '{"width":500, "height":525}',
//            'href' => elgg_add_action_tokens_to_url("action/jot/attach?element_type=document&guid=" . $item_guid))).'</td>';
                'href' => "jot/attach?element_type=document&container_guid=" . $item_guid)).'</td>';
//				'href' => "file/add?element_type=document&container_guid=" . $item_guid)).'</td>';
	echo '<tr>';
	$icon = elgg_view_icon('unlink');
		if ($documents) {
		foreach ($documents as $i) {
			$element_type = 'document';
			if ($i->canEdit()) {
				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=$element_type&guid=$i->guid&container_guid=$item_guid",
			    	'text' => elgg_view_icon('unlink'),
//			    	'text' => elgg_view_icon('unlink', 'float-alt'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
				$edit = elgg_view("output/url",array(
			    	'href' => "file/edit/$i->guid",
			    	'text' => elgg_view_icon('docedit'),
			    	'encode_text' => false,
			    ));
				$download = elgg_view("output/url",array(
			    	'href' => "file/download/$i->guid",
			    	'text' => elgg_view_icon('download'),
			    	'title' => sprintf(elgg_echo('jot:download'), $element_type),
			    	'encode_text' => false,
			    ));
			}
			echo '<tr class="highlight"><td>'.
			      elgg_view('output/url', array(
					'text' =>  $i->title,
					'href' =>  'file/view/'.$i->guid)).'</td>'.
				'<td nowrap>'.$download.$edit.$detach.'</td>';
			echo '</tr>';
		}
	}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>
	      </table>';
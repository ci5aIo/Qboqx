<?php
/*
 * Observation Details
*/ 
$entity     = $vars['entity'];
$section    = $vars['this_section'];
$asset      = $entity->asset;
$item_guid  = $entity->guid;
$subtype    = $entity->getSubtype();
$fields     = observation_prepare_brief_view_vars($entity, $section);
$title      = $entity->title;
$referrer   = "jot/$subtype/$item_guid/$section";

echo "<!--Section = $section-->";

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
    'inverse_relationship' => true,
	'limit' => false,
));
$insights = elgg_get_entities_from_relationship(array(
	'type'                 => 'object',
	'relationship'         => 'insight',
	'relationship_guid'    => $item_guid,
    'inverse_relationship' => true,
	'limit'                => false,
));
$causes = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'cause',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$steps_arrival = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'step',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$steps_departure = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'next_step',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));

// Input form
			$form_action = "action/observations/edit";
			$form_name   = $entity->getSubtype().'_'.$section;
			$form_class = 'elgg-module elgg-module-aside elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $form_action);
/*			$form_header  = '<table width=100%>
			                 <tr>
			                   <td width=100%><h3>';
			$form_header .= elgg_echo($section);
			$form_header .= '   </h3></td>
			                    <td>'.
							elgg_view('input/submit', array('value' => elgg_echo('save'))).
							'   </td>
							  </tr>
				            </table>';
*/			$form_body  = '<table width=100%>';
			$form_body .= '  <tr class="elgg-head">
			                   <td><h3>';
			$form_body .= elgg_echo($section);
			$form_body .= '   </h3></td>
			                    <td width=100%>&nbsp;</td>
			                    <td>'.
							elgg_view('input/submit', array('value' => elgg_echo('save'))).
							'   </td>
							  </tr>';
            $form_body .= '  <tr><td colspan=3>&nbsp;</td></tr>
                             <tr>
			                   <td>';
			$form_body .=       '<b>'.elgg_echo("title").
					            '</b>
					           </td>
							   <td colspan=2>'.
							   elgg_view("input/text", array(
								        "name" => "title",
										"value" => $title,
							  		   )).
							  '</td>
							</tr></table>';
			$form_body .= '<div class="elgg-foot">';
				if ($item_guid) {
			$form_body .= elgg_view('input/hidden', array(
								'name' => 'observation_guid',
								'value' => $item_guid,
							));
						}
			$form_body .= elgg_view('input/hidden', array(
							'name' => 'referrer',
							'value' => $referrer,
						));
			$form_body .= elgg_view('input/hidden', array(
							'name' => 'asset',
							'value' => $asset,
						));
			$form_body .= elgg_view('input/hidden', array(
							'name' => 'container_guid',
							'value' => $vars['container_guid'],
						));
				if ($vars['parent_guid']) {
			$form_body .= elgg_view('input/hidden', array(
								'name' => 'parent_guid',
								'value' => $vars['parent_guid'],
							));
						}
			$form_body .= '</div>';
		$form_vars = array('name'    => $form_name, 
		                   'enctype' => 'multipart/form-data',
		                   'action'  => $form_action,
		                   'body'    => $form_body,
				  		   'class'   => $form_class,
					      );
	$body    = elgg_view('input/form', $form_vars);

//echo elgg_view_module('aside', $form_header, $body);
echo $body;
//echo elgg_view_module('aside', elgg_echo($section), $body);

// section 1
	$section1 = '';
	foreach ($fields as $label => $value) {
		if ($fields['value'] === ''      ||
		    $label           === 'Title' ||
		    $label           === 'Asset'   ) {
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
	      <tr>
	        <td colspan=2><b>Discoveries</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p>Discoveries are a series of observations made to understand an issue.  Each observation can have further discoveries.  All observations roll up to the containing observation.</p><p>Enter your observation and click [add] to add a new observation.</span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.
    elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=observation&guid=$item_guid&asset=$asset")).'
	        </td>
	      </tr>';
if ($observations) {
foreach ($observations as $i) {
			$element_type = 'discovery';
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=Details",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/view/'.$i->guid)).'</td>
	        <td>'.$delete.'
	      </tr>';
    }
}	
// causes
	echo '<tr>
	        <td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2 width=100%><b>Insights</b>&nbsp;
			    <span class="hoverhelp">[?]
			    <span style="width:500px;"><p>A Cause draws a conclusion from the discoveries.  You may have multiple causes.  Each cause can be confirmed or rejected, based on further discoveries so feel free to guess.  You can always refine your suspicions.</p><p>Enter your suspected cause and click [add] to add a new cause.</span>
			    </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.
	elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=insight&guid=$item_guid")).'
	        <td>
	      </tr>';
if ($insights) {
foreach ($insights as $i) {
			$element_type = 'insight';
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=Details",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/view/'.$i->guid)).'</td>
	        <td>'.$delete.'
	      </tr>';
		}
	}	
	echo '<tr>
	        <td colspan=2>&nbsp;</td>
	      </tr>
	      <tr>
	        <td>&nbsp;</td>
	        <td>&nbsp;</td>
	      </tr>';
// causes
	echo '<tr>
	        <td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2 width=100%><b>Causes</b>&nbsp;
			    <span class="hoverhelp">[?]
			    <span style="width:500px;"><p>A Cause draws a conclusion from the discoveries.  You may have multiple causes.  Each cause can be confirmed or rejected, based on further discoveries so feel free to guess.  You can always refine your suspicions.</p><p>Enter your suspected cause and click [add] to add a new cause.</span>
			    </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.
	elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=cause&guid=$item_guid")).'
	        <td>
	      </tr>';
if ($causes) {
foreach ($causes as $i) {
			$element_type = 'cause';
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=Details",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/view/'.$i->guid)).'</td>
	        <td>'.$delete.'
	      </tr>';
		}
	}	
	echo '<tr>
	        <td colspan=2>&nbsp;</td>
	      </tr>
	      <tr>
	        <td>&nbsp;</td>
	        <td>&nbsp;</td>
	      </tr>';
//documentation
$icon = elgg_view_icon('unlink');
	echo '<tr>
	        <td><b>Documentation</b></td>
	        <td nowrap>'.
	elgg_view('output/url', array(
							'text' => 'add ...', 
						    'class' => 'elgg-lightbox elgg-button-submit-element',
						    'data-colorbox-opts' => '{"width":500, "height":225}',
				            'href' => "jot/attach?element_type=document&container_guid=" . $item_guid)).'
            </td>
          <tr>';
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
<?php
/*
 * Observation Details
*/ 
$entity       = $vars['entity'];
$section      = $vars['this_section'];
$asset        = $entity->asset;
$item_guid    = $entity->guid;
$subtype      = $entity->getSubtype();
$fields       = jot_prepare_brief_view_vars($subtype, $entity, $section);
$title        = $entity->title;
$description  = $entity->description;
$referrer     = "jot/$subtype/$item_guid/$section";
$state_params = array("name"    => "state",
                      'align'   => 'horizontal',
					  "value"   => $entity->state,
					  "options" => array("Discover" => 1, "Research" => 2, "Assign" => 3, "Accept" => 4, "Complete" => 5),
				      'default' => 1,
					 );
echo "<!--Section = $section-->";

if (!$asset){
	$asset = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'observation',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => false,
		'limit' => false,
		));
}

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
$efforts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'effort',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$projects = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'assigned_to',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$support_groups = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'support_group_of',
	'relationship_guid' => $asset,
	'inverse_relationship' => true,
	'limit' => false,
	));
$assignment_options = array();
if ($support_groups){
	foreach($support_groups as $group){
		$assignment_options[$group->guid] = $group->name;
	}
}
$assigned_group = elgg_view("output/url",array(
	'href' => "groups/profile/$entity->assigned_to",
	'text' => elgg_view_icon('users'),
));

// echo '$entity->state: '.$entity->state.'<br>';
// echo '$entity->assigned_to: '.$entity->assigned_to.'<br>';
/*if ($assignments){
	$assignment     = 
}
else {
	$assignment     = elgg_view("output/url", array(
//	    'href' => "tasks/add/$entity->owner_guid?element_type=task&container_guid=$item_guid",
		'href' => "jot/box/$item_guid/project/$asset",
		'text' => "New Project",
	    'class' => 'elgg-lightbox elgg-button-submit-element',
	    'data-colorbox-opts' => '{"width":500, "height":325}',
	));
}*/


//echo elgg_dump($projects);
// Input form
			$form_action = "action/observations/edit";
			$form_name   = $entity->getSubtype().'_'.$section;
			$form_class = 'elgg-module elgg-module-aside elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $form_action);
			$form_body  = '<table width=100%>';
			$form_body .= '  <tr class="elgg-head">
			                   <td><h3>';
			$form_body .= elgg_echo($section);
			$form_body .= '   </h3></td>
			                    <td width=100% colspan=2>&nbsp;</td>
			                    <td>'.
							elgg_view('input/submit',	 array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element')).
							'   </td>
							  </tr>';
            $form_body .= '  <tr><td colspan=4>&nbsp;</td></tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Observation").'</b>&nbsp;
					           </td>
							   <td colspan=3>';
		if ($entity->state <= 3){
            $form_body .=   elgg_view("input/text", array(
								        "name" => "title",
										"value" => $title,
							  		   ));
            }
		else {
            $form_body .=   $title;	
            $form_body .=   elgg_view("input/hidden", array(
								        "name" => "title",
										"value" => $title,
							  		   ));
            } 
	            $form_body .=   '  </td>
							 </tr>';
            $form_body .=   '<tr>
			                   <td nowrap>
			                     <b>'.elgg_echo("Made by").'</b>
					           </td>
							   <td colspan=3>';
		if ($entity->state <= 3){
            $form_body .=   elgg_view("input/text", array(
								        "name" => "observer",
										"value" => $entity->observer,
							  		   ));
            }
		else {
			$form_body .=  $entity->observer;
            $form_body .=   elgg_view("input/hidden", array(
								        "name" => "observer",
										"value" => $entity->observer,
							  		   ));
		    }
					
            $form_body .=   '  </td>
							 </tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Date").'</b>
					           </td>
							   <td nowrap colspan=3>';
		if ($entity->state <= 3){
            $form_body .=   elgg_view("input/date", array(
								        "name" => "observation_date",
										"value" => $entity->observation_date,
										'class' => 'tiny date',
							  		   ));
            }
		else {
		    $form_body .=   $entity->observation_date;
            $form_body .=   elgg_view("input/hidden", array(
								        "name" => "observation_date",
										"value" => $entity->observation_date,
										'class' => 'tiny date',
							  		   ));
		    }
            $form_body .=   '  </td>
							 </tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("description").'</b>
					           </td>
							   <td colspan=3>';
		if ($entity->state <= 3){
            $form_body .=   elgg_view("input/longtext", array(
								        "name" => "description",
										"value" => $description,
							  		   ));
            }
		else {
            $form_body .=   $description;
            $form_body .=   elgg_view("input/hidden", array(
								        "name" => "description",
										"value" => $description,
							  		   ));
            }
            $form_body .=   '  </td>
							 </tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("State").'</b>
					           </td>
							   <td colspan=3>'.
							   elgg_view('input/radio', $state_params)
								.
							  '</td>
							 </tr>';
if ($entity->state >= 2){
            $form_body .=   '<tr>
			                   <td nowrap>
			                     <b>'.elgg_echo("Assigned to").'</b>&nbsp;
					           </td>
							   <td colspan=3>';
		if ($entity->state <= 3){
/*            $form_body .=   elgg_view("input/text", array(
								        "name" => "assigned_to",
										"value" => $entity->assigned_to,
							  		   ));
*/			$form_body .= elgg_view('input/dropdown', array(
									    'name' => "assigned_to",
									    'options_values' => $assignment_options,
									    'value' => $entity->assigned_to,
									));
			If ($entity->assigned_to){
				$form_body .= $assigned_group;
				}
            }
		else {
            $form_body .=   $entity->assigned_to;
            $form_body .=   elgg_view("input/hidden", array(
								        "name" => "assigned_to",
										"value" => $entity->assigned_to,
							  		   ));
            }
            $form_body .=   '  </td>
                             </tr>
                             <tr>
			                   <td nowrap>
			                     <b>'.elgg_echo("Assignment").'</b>&nbsp;
					           </td>
							   <td colspan=3>';
			if($projects){
				foreach($projects as $project){
					$form_body .= elgg_view("output/url", array(
										'href' => "tasks/view/$project->guid",
										'text' => "$project->title",
								)).' ';
						}
			}
			else {
					$form_body .= elgg_view("output/url", array(
				//	    'href' => "tasks/add/$entity->owner_guid?element_type=task&container_guid=$item_guid",
						'href' => "jot/box/$item_guid/project/$asset",
						'text' => "New Project",
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":325}',
					));
			}
//			$form_body .= $assignment;
            $form_body .=   '  </td>
							 </tr>';
}            $form_body .= '</table>';
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
if($entity->state <= 3){
	$add_discovery = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=observation&guid=$item_guid&asset=$asset"));
	$add_cause     = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=cause&guid=$item_guid&asset=$asset"));
}	
if($entity->state == 1 || $entity->state == 2 || $entity->state == 3){
	$add_effort    = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=effort&guid=$item_guid&asset=$asset&assigned_to=$entity->assigned_to"));
}	
	
	echo '<table width = 100%>';
// efforts
if ($entity->state >= 2){
	echo '<tr>
	        <td colspan=2><b>Efforts to Resolve</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p></span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.$add_effort.'
	        </td>
	      </tr>';
	if ($efforts) {
	foreach ($efforts as $i) {
			$element_type = 'effort';
			if ($i->canEdit() && $entity->state <= 3) {
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
    else {
		echo '<tr>
		        <td>No efforts made</td>
		        <td>
		      </tr>';	
	     }	
    echo '<tr>
	        <td colspan=2>&nbsp;</td>
	      </tr>';
}
// discoveries
	echo '<tr>
	        <td colspan=2><b>Discoveries</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p>Discoveries are a series of observations made to understand an issue.  Each observation can have further discoveries.  All observations roll up to the containing observation.</p><p>Enter your observation and click [add] to add a new observation.</span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.$add_discovery.'
	        </td>
	      </tr>';
if ($observations) {
foreach ($observations as $i) {
			$element_type = 'discovery';
			if ($i->canEdit() && $entity->state <= 3) {
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
else {
	echo '<tr>
	        <td>No discoveries made</td>
	        <td>
	      </tr>';	
     }	
	
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
	        <td colspan=2>'.$add_cause.'
	        <td>
	      </tr>';
if ($causes) {
foreach ($causes as $i) {
			$element_type = 'cause';
			if ($i->canEdit() && $entity->state <= 3) {
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
else {
	echo '<tr>
	        <td>No causes identified</td>
	        <td>
	      </tr>';	
     }	

//documentation
$icon = elgg_view_icon('unlink');
	echo '<tr>
	        <td colspan=2>&nbsp;</td>
	      </tr>
	      <tr>
	        <td><b>Documentation</b></td>
	        <td nowrap>'.
	elgg_view('output/url', array(
							'text' => 'add ...', 
						    'class' => 'elgg-lightbox elgg-button-submit-element',
						    'data-colorbox-opts' => '{"width":500, "height":525}',
				            'href' => "jot/attach?element_type=document&container_guid=" . $item_guid)).'
            </td>
          <tr>';
if ($documents) {
foreach ($documents as $i) {
			$element_type = 'document';
			if ($i->canEdit() && $entity->state <= 3) {
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
	      

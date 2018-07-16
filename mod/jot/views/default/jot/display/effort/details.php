<?php
/*
 * Effort Details
*/ 
$entity       = $vars['entity'];
$section      = $vars['this_section'];
$asset        = $entity->asset;
$item_guid    = $entity->guid;
$subtype      = $entity->getSubtype();
$fields       = jot_prepare_brief_view_vars($subtype, $entity, $section);
$title        = $entity->title;
if ($entity->diagnosis){$diagnosis = $entity->diagnosis;} else {$diagnosis = 'No diagnosis';}
$description  = $entity->description;
$referrer     = "jot/view/$item_guid/$section";
$state_params = array("name"    => "state",
                      'align'   => 'horizontal',
					  "value"   => $entity->state,
					  "options" => array("Discover" => 1, "Diagnose" => 2, "Accept" => 3, "Repair" => 4, "Fixed" => 5),
				      'default' => 1,
					 );
$efforts_label = "Efforts to Resolve";
echo "<!--Section = $section-->";


/**/
$efforts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'effort',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
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
$parts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'part',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$recommendations = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'recommendation',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
// Input form
			$form_action = "action/efforts/edit";
			$form_name   = $subtype.'_'.$section;
			$form_class = 'elgg-module elgg-module-aside elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $form_action);
			$form_body  = '<table width=100%>';
			$form_body .= '  <tr class="elgg-head">
			                   <td><h3>';
			$form_body .= elgg_echo($section);
			$form_body .= '   </h3></td>
			                    <td width=100% colspan=2>&nbsp;</td>
			                    <td>'.
							elgg_view('input/submit', array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element')).
							'   </td>
							  </tr>';
            $form_body .= '  <tr><td colspan=4>&nbsp;</td></tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Effort").'</b>&nbsp;
					           </td>
							   <td colspan=3>'.
							   elgg_view("input/text", array(
								        "name" => "title",
										"value" => $title,
							  		   )).
							  '</td>
							 </tr>';
if($entity->state <> 4){
            $form_body .=   '<tr>
			                   <td nowrap>
			                     <b>'.elgg_echo("Made by").'</b>&nbsp;
					           </td>
							   <td colspan=3>'.
							   elgg_view("input/text", array(
								        "name" => "provider",
										"value" => $entity->provider,
							  		   )).
							  '</td>
							 </tr>';
}
if($entity->state == 4){
            $form_body .=   '<tr>
			                   <td nowrap>
			                     <b>'.elgg_echo("Assigned to").'</b>&nbsp;
					           </td>
							   <td colspan=3>'.
							   elgg_view("input/text", array(
								        "name" => "provider",
										"value" => $entity->provider,
							  		   )).
							  '</td>
							 </tr>';
}
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Date").'</b>
					           </td>
							   <td nowrap colspan=3>'.
							   elgg_view("input/date", array(
								        "name" => "open_date",
										"value" => $entity->open_date,
										'class' => 'tiny date',
							  		   )).
							  '</td>
							 </tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Cost").'</b>
					           </td>
							   <td nowrap colspan=3>'.
							   elgg_view("input/text", array(
								        "name" => "cost",
										"value" => $entity->cost,
							  		   )).
							  '</td>
							 </tr>';
if($entity->state == 2){
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Diagnosis").'</b>&nbsp;&nbsp;
					           </td>
							   <td colspan=3>'.
							   elgg_view("input/text", array(
								        "name" => "diagnosis",
										"value" => $entity->diagnosis,
							  		   )).
							  '</td>
							 </tr>';
}
if($entity->state > 2){
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Diagnosis").'</b>&nbsp;&nbsp;
					           </td>
							   <td colspan=3>'.$diagnosis
							   .
							  '</td>
							 </tr>';

}            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("State").'</b>
					           </td>
							   <td colspan=3>'.
							   elgg_view('input/radio', $state_params)
								.
							  '</td>
							 </tr>';
            $form_body .= '</table>';
			$form_body .= '<div class="elgg-foot">';
				if ($item_guid) {
			$form_body .= elgg_view('input/hidden', array(
								'name' => 'effort_guid',
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
							'value' => $entity->container_guid,
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

// Parts form
			$parts_form_action = "action/jot/add/element?element_type=part&guid=$item_guid&asset=$asset";
			$repair_form_name   = "repair_part";
			$repair_form_class  = 'elgg-module elgg-module-aside elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $parts_form_action);
			$parts_form_body    = '<tr>
			                          <td>'.
							   elgg_view("input/text", array(
								        "name" => "qty",
										"value" => $qty,
							  		   )).
							      '   </td>
			                          <td>'.
							   elgg_view("input/text", array(
								        "name" => "element_title",
										"value" => $element_title,
							  		   )).
							      '   </td>
			                          <td>'.
							   elgg_view("input/text", array(
								        "name" => "number",
										"value" => $number,
							  		   )).
							      '   </td>
					                  <td>'.
							      elgg_view('input/submit', array('value' => elgg_echo('add!'), "class" => 'elgg-button-submit-element')).
								 '    </td>
									</tr>';
		if ($item_guid) {
			$parts_form_body .= elgg_view('input/hidden', array(
								'name' => 'effort_guid',
								'value' => $item_guid,
							));
						}
			$parts_form_body .= elgg_view('input/hidden', array(
							'name' => 'referrer',
							'value' => $referrer,
						));
			$parts_form_body .= elgg_view('input/hidden', array(
							'name' => 'asset',
							'value' => $asset,
						));
			$parts_form_body .= elgg_view('input/hidden', array(
							'name' => 'container_guid',
							'value' => $entity->container_guid,
						));
				if ($vars['parent_guid']) {
			$parts_form_body .= elgg_view('input/hidden', array(
								'name' => 'parent_guid',
								'value' => $vars['parent_guid'],
							));
						}
		$parts_form_vars = array('name'    => $parts_form_name, 
				                  'enctype' => 'multipart/form-data',
				                  'action'  => $parts_form_action,
				                  'body'    => $parts_form_body,
						  	      'class'   => $parts_form_class,
					      );
	$parts_body    = elgg_view('input/form', $parts_form_vars);

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

if($entity->state <= 4){
	$add_effort    = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=effort&guid=$item_guid&asset=$asset"));
//	$add_effort    = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=effort&guid=$item_guid&asset=$asset", "element_type"=>"efort"));
    	$form_action = 'jot/add/discoveries';
        $form_vars   = array("action" => "action/jot/add/element");
        $body_vars   = array('element_type'=>'observation',
                             'container_guid'=>$item_guid,
                             'asset'=>$asset,
                             'assigned_to'=>$entity->assigned_to,
                             'owner_guid'=>$owner);
	$add_discovery  = elgg_view_form($form_action,$form_vars, $body_vars);
	//$add_discovery = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=observation&guid=$item_guid&asset=$asset"));
	$add_cause     = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=cause&guid=$item_guid&asset=$asset"));
	$add_part      = $parts_body;
//}	
//if($entity->state == 2){
	$add_recommendation = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=recommendation&guid=$item_guid&asset=$asset"));
}
if ($entity->state == 4) {
	$efforts_label = "Repair Tasks";
}

echo $add_discovery.'<p>';

	echo '<table width = 100%>';
// efforts
	echo '<tr>
	        <td colspan=2><b>'.$efforts_label.'</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p></span>
		        </span>
	        </td>
	      </tr>'.$add_effort;
	if ($efforts) {
//    echo '<table width = 100%>';
	foreach ($efforts as $i) {
			$element_type = 'effort';
			if ($i->canEdit() && $entity->state <= 4) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=Details",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.$i->cost.' '.elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid/Details")).'</td>
	        <td>'.$delete.'</td>
	      </tr>';
	    }
//    echo '</table>';
	}
	else {
	echo '<tr>
	        <td>No efforts made</td>
	        <td>
	      </tr>';	
     }
// Repair parts
if ($entity->state >= 4){
	echo '<tr>
	        <td colspan=2>&nbsp;</td>
	      </tr>
	      <tr>
	        <td colspan=2><b>Repair Parts</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p></span>
		        </span>
	        </td>
	      </tr>
	      <tr>
		      <td colspan = 2>
			      <table width = 100%>
		              <tr>
		                  <td>Qty</td>
		                  <td>Part</td>
		                  <td>Part Number</td>
		                  <td>&nbsp;</td>
				      </tr>';
					  if($entity->state == 4){
					  	echo $add_part;
					  	}
/*	 echo '   </td>
	      </tr>';
*/
				// Parts table
	if ($parts) {
				//    echo '<table width = 100%>';
					foreach ($parts as $i) {
							$element_type = 'part';
							if ($i->canEdit() && $entity->state <= 4) {
								$delete = elgg_view("output/url",array(
							    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=Details",
							    	'text' => elgg_view_icon('delete'),
							    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
							    	'encode_text' => false,
							    ));
							}
				echo '<tr class="highlight">
			              <td>'.$i->qty.'</td>
						  <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid/Details")).'</td>
			              <td>'.$i->number.'</td>
				          <td>'.$delete.'</td>
				      </tr>';
				    }
	echo '        </table>
	          </td>
	      </tr>';
	}
// Parts table
	else {
	echo '        </table>
	          </td>
	      </tr>
	      <tr>
	          <td colspan=2>No parts used</td>
	          <td>
	      </tr>';	
     }
  }

// discoveries
/*	echo '<tr>
	        <td colspan=2>&nbsp;</td>
	      </tr>
	      <tr>
	        <td colspan=2><b>Discoveries</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p>Discoveries are a series of observations made to understand an issue.  Each observation can have further discoveries.  All observations roll up to the containing observation.</p><p>While the effort is in a state of "Discover", enter your observation and click [add] to add a new observation.  You can add multiple observations at once; separate each observation with a comma (",").</span>
		        </span>
	        </td>
	      </tr>'.$add_discovery;
if ($discoveries) {
foreach ($discoveries as $i) {
			$element_type = 'discovery';
			if ($i->canEdit() && $entity->state <= 4) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid/Details")).'</td>
	        <td>'.$delete.'</td>
	      </tr>';
    }
}
else {
	echo '<tr>
	        <td>No discoveries made</td>
	        <td>
	      </tr>';	
     }	
*/	
//causes
	echo '<tr>
	        <td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2 width=100%><b>Causes</b>&nbsp;
			    <span class="hoverhelp">[?]
			    <span style="width:500px;"><p>A Cause draws a conclusion from the discoveries.  You may have multiple causes.  Each cause can be confirmed or rejected, based on further discoveries so feel free to guess.  You can always refine your suspicions.</p><p>Enter your suspected cause and click [add] to add a new cause.</span>
			    </span>
	        </td>
	      </tr>'.$add_cause;
if ($causes) {
foreach ($causes as $i) {
			$element_type = 'cause';
			if ($i->canEdit() && $entity->state <= 4) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid/Details")).'</td>
	        <td>'.$delete.'</td>
	      </tr>';
		}
	}
else {
	echo '<tr>
	        <td>No causes identified</td>
	        <td>
	      </tr>';	
     }	
//recommendations
if ($entity->state >= 2){
	echo '<tr>
	        <td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2 width=100%><b>Recommendations</b>&nbsp;
			    <span class="hoverhelp">[?]
			    <span style="width:500px;"><p>[Help Note]</span>
			    </span>
	        </td>
	      </tr>'.$add_recommendation;
if ($recommendations) {
foreach ($recommendations as $i) {
			$element_type = 'recommendation';
			if ($i->canEdit() && $entity->state <= 4) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid/Details")).'</td>
	        <td>'.$delete.'</td>
	      </tr>';
		}
	}
else {
	echo '<tr>
	        <td>No recommendations</td>
	        <td>
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
	        <td nowrap>';
	if ($entity->state <= 4) {
	elgg_view('output/url', array(
							'text' => 'add ...', 
						    'class' => 'elgg-lightbox elgg-button-submit-element',
						    'data-colorbox-opts' => '{"width":500, "height":225}',
				            'href' => "jot/attach?element_type=document&container_guid=" . $item_guid)).'
            </td>
          <tr>';}
	else {'</td>
          <tr>';}
if ($documents) {
foreach ($documents as $i) {
			$element_type = 'document';
			if ($i->canEdit() && $entity->state <= 4) {
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
					'href' =>  "file/view/$i->guid")).'</td>'.
				'<td nowrap>'.$download.$edit.$detach.'</td>';
			echo '</tr>';
		}
	}
else {
	echo '<tr>
	        <td>No documents attached</td>
	        <td>
	      </tr>';	
     }	

echo '<tr><td colspan=2>&nbsp;</td></tr>
      </table>';
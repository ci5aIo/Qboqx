<?php
/*
 * Observation Details
*/ 
$entity       = $vars['entity'];
$section      = $vars['this_section'];
$asset        = $entity->asset;
$item_guid    = $entity->guid;
$container_guid = $entity->container_guid; 
$subtype      = $entity->getSubtype();
$fields       = place_prepare_brief_view_vars($subtype, $entity, $section);
$title        = $entity->title;
$description  = $entity->description;
$referrer     = "places/$subtype/$item_guid/$section";
$state_params = array("name"    => "state",
                      'align'   => 'horizontal',
					  "value"   => $entity->state,
					  "options" => array("Discover" => 1, "Resolve" => 2, "Assign" => 3, "Accept" => 4, "Complete" => 5),
				      'default' => 1,
					 );
echo "<!--Section = $section-->";

/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
/*	$spaces = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'place',
	'metadata_name' => 'parent_guid',
	'metadata_value' => $item_guid,
	'limit' => false,
));*/
$spaces = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => 'place',
		'container_guid' => $item_guid,));

aasort($spaces, 'title');
$contents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'contents',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$contents = array_merge($contents, elgg_get_entities(array(
                                        'type' => 'object',
                        				'subtypes' => array('market', 'item', 'contents'),
                                        'joins'    => array('JOIN elgg_objects_entity e2 on e.guid = e2.guid'),
                        				'wheres' => array(
                        					"e.container_guid = $item_guid",
                                            "NOT EXISTS (SELECT *
                                                         from elgg_entity_relationships s1
                                                         WHERE s1.relationship = 'component'
                                                           AND s1.guid_two = e.container_guid)"
                        				),
                                        'order_by' => 'e2.title',
                                        'limit' => false,
                        			)));
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
							elgg_view('input/submit', array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element')).
							'   </td>
							  </tr>';
            $form_body .= '  <tr><td colspan=4>&nbsp;</td></tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("Place").'</b>&nbsp;
					           </td>
							   <td colspan=3>'.
							   elgg_view("input/text", array(
								        "name" => "title",
										"value" => $title,
							  		   )).
							  '</td>
							 </tr>';
            $form_body .=   '<tr>
			                   <td>
			                     <b>'.elgg_echo("description").'</b>
					           </td>
							   <td colspan=3>'.
							   elgg_view("input/longtext", array(
								        "name" => "description",
										"value" => $description,
							  		   )).
							  '</td>
							 </tr>';
            $form_body .= '</table>';
			$form_body .= '<div class="elgg-foot">';
				if ($item_guid) {
			$form_body .= elgg_view('input/hidden', array(
								'name' => 'place_guid',
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
			continue;
		}
	    $section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value</td></tr>";
	}
	
	if ($section1) {
		echo '<table>' . $section1 . '</table><p>';
	}
$form_action= "jot/add/element";
$form_vars  = array('name' => 'jotForm', 'enctype' => 'multipart/form-data', "action" => "action/jot/add/element");
	
$add_space   = elgg_view_form($form_action, $form_vars, array("element_type"=>"place",
                                                              "owner_guid"    =>elgg_get_page_owner_guid(),
		                                                      "guid"          => '',
                                                              "container_guid"=>$entity->guid,
															  'action'        => 'create',));
$add_content = elgg_view_form($form_action, $form_vars, array("element_type"  =>"market",
                                                              "owner_guid"    =>elgg_get_page_owner_guid(),
		                                                      "guid"          => '',
                                                              "location"      =>$entity->guid,
                                                              "container_guid"=>$entity->guid,
															  'action'        => 'create',));
	echo '<table width = 100%>';
// efforts

// spaces
	echo '<tr>
	        <td colspan=2><b>Managed Spaces</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p>Discoveries are a series of observations made to understand an issue.  Each observation can have further discoveries.  All observations roll up to the containing observation.</p><p>Enter your observation and click [add] to add a new observation.</span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.$add_space.'
	        </td>
	      </tr>';
if ($spaces) {
foreach ($spaces as $i) {
			$element_type = 'space';
			if ($i->canEdit()) {
				$delete = elgg_view("output/confirmlink",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "places/view/$i->guid/$i->title/Details")).'</td>
	        <td>'.$delete.'
	      </tr>';
    }
}
else {
	echo '<tr>
	        <td colspan=2>No managed spaces defined</td>
	      </tr>';	
     }	
	
	echo '<tr>
	        <td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2 width=100%><b>Contents</b>&nbsp;
			    <span class="hoverhelp">[?]
			    <span style="width:500px;"><p>[Contents help text]</span>
			    </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.$add_content.'
	        <td>
	      </tr>';
if ($contents) {
foreach ($contents as $i) {
			$element_type = 'item';
			if ($i->canEdit()) {
				$delete = elgg_view("output/confirmlink",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid/Details")).'</td>
	        <td>'.$delete.'
	      </tr>';
		}
	}
else {
	echo '<tr>
	        <td>No contents</td>
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
				            'href' => "places/attach?element_type=document&container_guid=" . $item_guid)).'
            </td>
          <tr>';
if ($documents) {
foreach ($documents as $i) {
			$element_type = 'document';
			if ($i->canEdit()) {
				$detach = elgg_view("output/confirmlink",array(
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
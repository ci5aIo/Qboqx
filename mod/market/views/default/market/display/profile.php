<?php
$entity    = $vars['entity'];
$section   = $vars['this_section'];
$category  = $entity->marketcategory;
$item_guid = $entity->guid;
$fields    = market_prepare_brief_view_vars($entity);
$limit     = 3;
//echo elgg_dump($fields);
/**/
$num_documents = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	)));
$num_tasks = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	)));
$num_contents = count(elgg_get_entities(array(
                'type' => 'object',
				'subtypes' => array('market', 'item', 'contents'),
				'wheres' => array(
					"e.container_guid = $item_guid",
				),
                'limit' => false,
			)));
$num_components = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
)));
$num_accessories = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
)));
$num_observations = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'observation',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
)));
$num_issues = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
)));
$num_insights = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'insight',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
)));
$num_supplies = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'supply',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
)));
$num_parts = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'part',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
)));
$num_receipts = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'transfer_receipt',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
)));
$num_suppliers = count(elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'supplier_of',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
)));

/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
	));
$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
	));
/*$contents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'contents',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => $limit,
));*/
$contents = elgg_get_entities(array(
                'type' => 'object',
				'subtypes' => array('market', 'item', 'contents'),
                'joins'    => array('JOIN elgg_objects_entity e2 on e.guid = e2.guid'),
				'wheres' => array(
					"e.container_guid = $item_guid",
				),
                'order_by' => 'e2.title',
                'limit' => $limit,
			));
$components = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => $limit,
));
$accessories = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => $limit,
));
$parent_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => $limit,
));
$containers = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => $limit,
));
$issues = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
));
$observations = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'observation',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
));
$insights = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'insight',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
));
$supplies = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'supply',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
	));
$parts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'part',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
));
$parts = array_merge($parts, elgg_get_entities_from_relationship(array(
								'type' => 'object',
								'relationship' => 'part',
								'relationship_guid' => $item_guid,
								'inverse_relationship' => false,
								'limit' => $limit,
							)));
$receipts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'transfer_receipt',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => $limit,
));
$suppliers = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'supplier_of',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => $limit,
));
$valid_tags = elgg_get_registered_tag_metadata_names();
$action    = 'shelf/load';
$form_vars = array(
        'name'    => 'shelf', 
        'enctype' => 'multipart/form-data', 
        'action'  => "action/$action",
);
$body_vars = array(
        'entity' => $entity,
        'access_id' => $entity->access_id,
        'guid' => $item_guid, 
);
/*Moved mechanism to entity menu
$add_to_shelf = elgg_view_form($action, $form_vars, $body_vars);

echo $add_to_shelf;
*/
//echo elgg_dump($entity->tags);
	// section 1
	$section1 = '';
	foreach ($fields as $label => $value) {
		if ($value == '') {
			// don't show empty values
			continue;
		}
		if ($label == 'Manufacturer'){
		    // Link to manufacturer card
		    
		}
	    $section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value</td></tr>";
	}
	
	if ($section1) {
		echo '<table>' . $section1 . '</table><p>';
	}
	/*original code for section1 above
	foreach ($fields as $field) {
	  echo "<b>{$field['label']}:</b> {$field['value']}<br>";
	}*/	
// contents
	echo "<table width = 100%>";
	if ($contents) {
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Contents'), elgg_echo($entity->title));
	if($num_contents>0){$count_label="($num_contents)";}
	echo "<tr><td colspan=2 width=100%><b>Contents</b>&nbsp;$count_label
		      <span class='hoverhelp'>[?]
			      <span style='width:500px;'><p>$hoverhelp
			      </span>
		      </span>
	          </td>
	      </tr>";
		foreach ($contents as $i) {
			echo '<tr class="highlight">
				      <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid/Details")).'</td>
			      </tr>';
		}
	}
	if($num_contents > $limit){
		echo "<tr>
			      <td colspan=2>".elgg_view('output/url', array(
					'text' => 'more ...', 
				    'class' => 'elgg-lightbox elgg-button-submit-element',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
	                'href' => "jot/show_more/contents/$item_guid"))."</td>
		      </tr>";
	}	
// components
/*	$components = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'component',
		'container_guid' => $item_guid,
		'limit' => false,
	));
	
	$options = array(
		'relationship' => 'component',
		'guid_two' => $item_guid,
	);
	
	$components = elgg_get_entities_from_relationship($options);
*/	
	if ($components) {
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Components'), elgg_echo($entity->title));
	$count_label = '';
	if($num_components>0){$count_label="($num_components)";}
		echo "<tr><td colspan=2>&nbsp;</td></tr>
	          <tr><td width=100%><b>Components</b>&nbsp;$count_label
		          <span class='hoverhelp'>[?]
			          <span style='width:500px;'><p>$hoverhelp
			          </span>
			      </span>
			      </td>";
	echo '<td nowrap>'.
		elgg_view('output/url', array(
				'text' => 'add !', 
		        "class" => 'elgg-button-submit-element',
				'href' => elgg_add_action_tokens_to_url("action/jot/add/element?element_type=component&guid=" . $item_guid))).'</td>';
	echo '</tr>';
	echo '<tr><td colspan=2>';
		foreach ($components as $i) {
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/market/delete?guid=$i->guid",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
			
			echo '<tr class="highlight">
					<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>
					<td>'.$delete.'
				</tr>';
		}
		if($num_components > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/components/$item_guid"))."</td>
			      </tr>";
		}	
	}	
	/*	
	//	echo '<label>Components</label><br><ul style="list-style:square inside">';
		echo '<ul style="list-style:square inside">';
		foreach ($components as $component) {
			echo '<li>'.elgg_view('output/url', array(
				'text' => $component->title,
				'href' => 'market/view/'.$component->guid
	//			'href' => 'market/edit/'.$component->guid
	//			'href' => 'market/edit_element/'.$component->guid
			));
			echo '</li>';
		}
		echo '</ul>';
	//}
	*/
	// accessories
	$accessories_button = 	elgg_view('output/url',array('name'               => '06accessory',
        												 'text'               => 'add ...', 
        												 'class'              => 'elgg-button-submit-element elgg-lightbox',
        											     'data-colorbox-opts' => '{"width":500, "height":325}',
        			                                     'href'               => "jot/box/$item_guid/accessory/add"));
/*	$accessories_button = elgg_view('output/url', array('text' => 'add !', 
                                        		        "class" => 'elgg-button-submit-element',
                                        			    'href' => elgg_add_action_tokens_to_url("action/jot/add/element?element_type=accessory&guid=" . $item_guid)));
*/	if ($accessories) {
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	echo '<tr><td width=100%><b>Accessories</b>&nbsp;';
		echo '<span class="hoverhelp">[?]';
		echo '<span style="width:500px;"><p>Accessories are distinct, manageable items that connect to this  ' . elgg_echo($entity->title) . ' item. Examples include trailer, luggage rack or fuzzy dice.  Any work done to an accessory rolls up to its parent.</p><p>Click [add new accessory] to add a new accessory item.</span>';
		echo '</span>';
	echo '</td>';
	echo '<td nowrap>'.$accessories_button.'</td>';
	//echo '</tr></table>';
	echo '</tr>';
	echo '<tr><td colspan=2>';
		foreach ($accessories as $i) {
			echo '<tr class="highlight">';
				echo '<td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>';
			echo '</tr>';
		}
		if($num_accessories > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/accessories/$item_guid"))."</td>
			      </tr>";
		}	
	}	
	
	// containers
	if ($containers) {
	    echo '<tr><td colspan=2>&nbsp;</td></tr>';
		echo '<tr>
		         <td width=100%><b>Component of</b>&nbsp;
		            <span class="hoverhelp">[?]
		               <span style="width:500px;"><p>' . elgg_echo($entity->title) . ' is a component of these items. 
		               </span>
		            </span>
		         </td>
		         <td>&nbsp;</td>
		      </tr>';
		foreach ($containers as $i) {
			echo '<tr class="highlight">
			         <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'
			         </td>
			      </tr>';
			}	
		echo '<tr><td colspan=2>&nbsp;</td></tr>';
	}
	if ($parent_items) {
		echo '<tr>
		         <td width=100%><b>Attached to</b>&nbsp;
		              <span class="hoverhelp">[?]
		 	             <span style="width:500px;"><p>'. elgg_echo($entity->title) .' attaches to these items. 
		 	             </span>
		 	          </span>
		 	     </td>
		 	     <td>&nbsp;</td>
		 	  </tr>';
		foreach ($parent_items as $i) {
			echo '<tr class="highlight">
			        <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>
			      </tr>';
			}	
		echo '<tr><td colspan=2>&nbsp;</td></tr>';
	}
	
	$icon = elgg_view_icon('unlink');
	if ($documents) {
		echo '<tr><td colspan=2>&nbsp;</td></tr>';
		echo '<tr>
		         <td><b>Documentation</b>
		         </td>
		         <td nowrap>'.
			elgg_view('output/url', array(
					'text' => 'add ...', 
				    'class' => 'elgg-lightbox elgg-button-submit-element',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
	                'href' => "jot/attach?element_type=document&container_guid=$item_guid")).'
	             </td>
	          </tr>';
		foreach ($documents as $i) {
			$element_type = 'document';
			if ($i->canEdit()) {
				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=document&&guid=$i->guid&container_guid=$item_guid}",
			    	'text' => elgg_view_icon('unlink'),
//			    	'text' => elgg_view_icon('unlink', 'float-alt'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
				$edit = elgg_view("output/url",array(
			    	'href' => "file/edit/$i->guid",
			    	'text' => elgg_view_icon('docedit'),
			    	'title' => sprintf(elgg_echo('jot:edit'), $element_type),
			    	'encode_text' => false,
			    ));
				$download = elgg_view("output/url",array(
			    	'href' => "file/download/$i->guid",
			    	'text' => elgg_view_icon('download'),
			    	'title' => sprintf(elgg_echo('jot:download'), $element_type),
			    	'encode_text' => false,
			    ));
			}
			echo '<tr class="highlight">
			        <td>
		              <span class="hoverinfo">'.
					      elgg_view('output/url', array(
								'text' =>  $i->title,
								'href' =>  'file/view/'.$i->guid)).'
		 	             <span style="width:150px;">'.
			 	             elgg_view('market/display/hoverinfo', array('i'=>$i)).'
 	                     </span>
		 	          </span>
				    </td>
				    <td nowrap>'.$download.$edit.$detach.	'</td>';
			echo '</tr>';
		}
		if($num_documents > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/documents/$item_guid"))."</td>
			      </tr>";
		}	
	}
	
	if ($observations) {
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	// observations
	echo '<tr>
	         <td><b>Observations</b>
	         </td>
	         <td nowrap>'.
		      elgg_view('output/url', array(
				'text' => 'add ...', 
				'class' => 'elgg-lightbox elgg-button-submit-element',
			    'data-colorbox-opts' => '{"width":500, "height":325}',
				'href' => "jot/box/{$item_guid}/observation")).'
			 </td>
		  </tr>';
	echo '<tr><td colspan=2>';
	foreach ($observations as $i) {
			$element_type = 'observation';
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}

			echo '<tr class="highlight">
			         <td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/$element_type/$i->guid")).'</td>
			         <td>'.$delete.'
			      </tr>';
		}
		if($num_observations > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/observations/$item_guid"))."</td>
			      </tr>";
		}	
	}
	if ($insights) {
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
// insights
	echo '<tr>
	         <td><b>Insights</b>
	         </td>
	         <td nowrap>'.
		      elgg_view('output/url', array(
				'text' => 'add ...', 
		        "class" => 'elgg-lightbox elgg-button-submit-element',
			    'data-colorbox-opts' => '{"width":500, "height":325}',
				'href' => "jot/box/{$item_guid}/insight")).'
			 </td>
		  </tr>';
	echo '<tr><td colspan=2>';
		foreach ($insights as $i) {
			$element_type = 'insight';
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}

			echo '<tr class="highlight">
			         <td>'.
//			         elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid")).
			         elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/$element_type/$i->guid")).
			         '</td>
			         <td>'.$delete.'
			      </tr>';
		}	
		if($num_insights > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/insights/$item_guid"))."</td>
			      </tr>";
		}	
	}
	if ($issues) {
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	// issues
	echo '<tr><td><b>Issues</b></td>';
		echo '<td nowrap>'.
		      elgg_view('output/url', array(
				'text' => 'add ...', 
		        "class" => 'elgg-button-submit-element',
				'href' => "jot/add/{$item_guid}/issue")).
			'</td>
		</tr>';
		foreach ($issues as $i) {
			echo '<tr class="highlight">
			        <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'jot/issue/'.$i->guid)).'</td>
			      </tr>';
		}
		if($num_issues > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/issues/$item_guid"))."</td>
			      </tr>";
		}	
	}
	if ($tasks) {
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	echo '<tr><td><b>Tasks</b></td>
	      <td nowrap>'.
	      elgg_view('output/url', array(
				'text' => 'add ...', 
		        "class" => 'elgg-button-submit-element',
			'href' => "tasks/add/".$entity->owner_guid."?element_type=task&container_guid=".$item_guid
	      )).'
	      </td></tr>';
	echo '<tr><td colspan=2>';
	
		foreach ($tasks as $i) {
			echo '<tr>';
				echo '<td class="highlight">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'tasks/view/'.$i->guid)).'</td>';
			echo '</tr>';
		}
		if($num_tasks > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/tasks/$item_guid"))."</td>
			      </tr>";
		}	
	}

	// supplies
if ($supplies) {
	$hoverhelp = elgg_echo('jot:hoverhelp:Supplies');
	echo "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr>
	        <td colspan=2><b>Supplies</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
	        </td>
	      </tr>
	      <tr>";
foreach ($supplies as $i) {
			$element_type = 'supply';
	        $delete = '';
			$link = elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid"));
	        $observation = get_entity($i->guid);
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.$link.'
	        </td>
	        <td nowrap>'.$delete.'</td>
	      ';
    }
		if($num_supplies > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/supplies/$item_guid"))."</td>
			      </tr>";
		}	
    echo '<tr><td colspan=2>&nbsp;</td></tr>';
}
							
	// parts
if ($parts) {
$hoverhelp = elgg_echo('jot:hoverhelp:Parts');
		echo "<tr>
		         <td width=100%><b>Parts</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>";
	echo '<tr><td colspan=2>';
	
foreach ($parts as $i) {
	echo '<tr>
	        <td class="hoverhelp">'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>
	      </tr>';
	}
		if($num_parts > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/parts/$item_guid"))."</td>
			      </tr>";
		}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
}
// receipts
if ($receipts) {
	$hoverhelp = elgg_echo('jot:hoverhelp:Receipts');
	$action     = 'jot/add/element';
	$form_vars  = array('enctype'      => 'multipart/form-data', 
	                    'name'         => 'group_list',
				 	    'action'       => "action/$action");
	$body_vars  = array('element_type' => 'transfer',
	                    'aspect'       => 'receipt',
	                    'guid'         => $item_guid,
                    'asset'        => $asset_guid,
                    );
		echo "<tr>
		         <td width=100%><b>Receipts</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>";
	echo '<tr><td colspan=2>';
	
	$element_type = 'transfer_receipt';
	$element_label = "receipt";
	foreach ($receipts as $i) {
		$link         = elgg_view('output/div', ['content'=>elgg_view('output/url', ['text'=>$i->title, 'class'=>'do', 'data-perspective'=>'view', 'data-guid'=>$i->getGUID(), 'data-element'=>'popup', 'data-space'=>$i->getSubtype(), 'data-aspect'=>$i->aspect, 'data-context'=>'market', 'data-jq-dropdown'=>'#q'.$i->getGUID().'_v','data-qid'=>'q'.$i->getGUID().'_v']),
				                                 'class'  =>'drop-down']);
/*		$link         = "<span class='hoverinfo'>".
					      elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid"))."
		 	             <span style='width:150px;'>".
			 	             elgg_view('market/display/hoverinfo', array('i'=>$i))."
 	                     </span>
		 	          </span>";*/ 
		  
//		$link         = elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid"));
		if ($i->canEdit()) {
			$detach = elgg_view("output/url",array(
		    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$item_guid}",
		    	'text' => elgg_view_icon('unlink'),
		    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_label),
		    	'encode_text' => false,
		    ));
			$edit = elgg_view("output/url",array(
		    	'href' => "jot/edit/$i->guid",
		    	'text' => elgg_view_icon('docedit'),
		    	'title' => sprintf(elgg_echo('jot:edit'), $element_label),
		    	'encode_text' => false,
		    ));
			$price = money_format('%#10n', $i->total);
		}
/*		              <span class="hoverinfo">'.
					      elgg_view('output/url', array(
								'text' =>  $i->title,
								'href' =>  'file/view/'.$i->guid)).'
		 	             <span style="width:150px;">'.
			 	             elgg_view('market/display/hoverinfo', array('i'=>$i)).'
 	                     </span>
		 	          </span>
*/		
	echo "<tr class='highlight'>
			<td colspan = 2>
				<div class='rTable' style='width:100%'>
					<div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell' style='width:65%'>$link
								</div>
								<div class='rTableCell' style='width:25%;text-align:right'>$price
								</div>
								<div class='rTableCell'>$edit$detach
								</div>
							</div>
					</div>
				</div>
			</td>
		</tr>";	
		}
		if($num_receipts > $limit){
			echo "<tr>
				      <td colspan=2>".elgg_view('output/url', array(
						'text' => 'more ...', 
					    'class' => 'elgg-lightbox elgg-button-submit-element',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
		                'href' => "jot/show_more/receipts/$item_guid"))."</td>
			      </tr>";
		}	
		
}

// Suppliers

if ($suppliers) {
	$group_type = 'supplier';
	$action     = 'groups/add/element';
	$form_vars  = array('enctype'     => 'multipart/form-data', 
	                    'name'        => 'group_list',
				 	    'action'      => "action/groups/add?element_type=$group_type&item_guid=$item_guid");
	$body_vars  = array('item_guid'   => $item_guid,
				        'group_type'  => $group_type);
	$hoverhelp  = elgg_echo('jot:hoverhelp:Suppliers');
		echo "<tr><td colspan=2>&nbsp;</tr>
		      <tr>
		         <td width=100%><b>Suppliers</b>&nbsp;
		        <span class='hoverhelp'>[?]
		        	<span style='width:500px;'><p>$hoverhelp</span>
		        </span>
		        </td>";
	echo '<tr><td colspan=2>';
	$element_type = 'supplier';
	foreach ($suppliers as $i) {
	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid/$i->name"));
	
	if ($i->canEdit()) {
		$detach = elgg_view("output/url",array(
	    	'href' => "action/jot/detach?element_type=$element_type&guid=$i->guid&container_guid=$item_guid",
	    	'text' => elgg_view_icon('unlink'),
	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
	    	'encode_text' => false,
	    ));
			
	}
	echo '<tr class="highlight">
	        <td>'.$link.'
	        </td>
	        <td nowrap>'.$detach.'</td>
	      ';
	}
	if($num_suppliers > $limit){
		echo "<tr>
			      <td colspan=2>".elgg_view('output/url', array(
					'text' => 'more ...', 
				    'class' => 'elgg-lightbox elgg-button-submit-element',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
	                'href' => "jot/show_more/suppliers/$item_guid"))."</td>
		      </tr>";
	}	
}	
	echo '</table>';
/*******************************************/
//$categories = hypeJunction\Categories\get_entity_categories($entity->guid);
//$categories = get_entity($entity->category);
//echo elgg_dump($categories);

//echo elgg_dump(get_entity_relationships($entity->guid, false));

// Create a card for manufacturer, if it doesn't exist
	  $options = array('subtype'=>'manufacturer', 'name'=>$entity->manufacturer);
	  $manufacturers = quebx_get_group_by_title($options);
//	  echo elgg_dump($manufacturers);
<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
setlocale(LC_MONETARY, 'en_US');
$fields = market_prepare_detailed_view_vars($entity);
$owner_guid = elgg_get_logged_in_user_guid();

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$contents = elgg_get_entities(array(
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
			));
$components = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$accessories = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$parent_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));
$containers = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));
$characteristic_names = $entity->characteristic_names;
if ($characteristic_names && !is_array($characteristic_names)) {
	$characteristic_names = array($characteristic_names);
}
$characteristic_values = $entity->characteristic_values;
if ($characteristic_values && !is_array($characteristic_values)) {
	$characteristic_values = array($characteristic_values);
}
if (!empty($characteristic_names)){
    foreach($characteristic_names as $key=>$name){
        $family_characteristics[$name] = $characteristic_values[$key];
    }
}
$family_features = $entity->features;
if ($family_features && !is_array($family_features)) {
	$family_features = array($family_features);
}
$this_characteristic_names = $entity->this_characteristic_names;
if ($this_characteristic_names && !is_array($this_characteristic_names)) {
	$this_characteristic_names = array($this_characteristic_names);
}
$this_characteristic_values = $entity->this_characteristic_values;
if ($this_characteristic_values && !is_array($this_characteristic_values)) {
	$this_characteristic_values = array($this_characteristic_values);
}
if (!empty($this_characteristic_names)){
    foreach($this_characteristic_names as $key=>$name){
        $individual_characteristics[$name] = $this_characteristic_values[$key];
    }
}
$individual_features = $entity->this_features;
if ($individual_features && !is_array($individual_features)) {
	$individual_features = array($individual_features);
}

$characteristics = array_merge($family_characteristics, $individual_characteristics) 
                 ?: $family_characteristics 
                 ?: $individual_characteristics;
$features        = array_merge($family_features, $individual_features) 
                 ?: $family_features 
                 ?: $individual_features;

/*
$location = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'place',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
 */
//$files = elgg_get_entities(array('type'=>'object','subtype'=>'file', 'owner_guid' => $entity['owner']->guid, ));

	// section 1
/*	$url = elgg_get_site_url() . "labels/$asset_guid";
	$url = elgg_add_action_tokens_to_url($url);
	$item = elgg_view('output/url', array(
	                  "href" => $url,
	                   "text" => "add label",
	                   "class" => "elgg-lightbox"
	        ));

    echo $item;
*/	
	$section1 = '';
	foreach ($fields as $label => $value) {
		if ($value == '' && $label != 'Location') {
			// don't show empty values
			continue;
		}
		if ($label == 'Location'){
			$set_button = elgg_view('output/url', array(
					'text' => 'Place ...',
			        'title'=> 'Select a location',
				    'class' => 'elgg-button-submit-element elgg-lightbox',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
		            'href' => "places/set?container_guid=" . $item_guid));					
			$pack_button = elgg_view('output/url', array(
					'text' => 'pack ...', 
				    'class' => 'elgg-button-submit-element elgg-lightbox',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
		            'href' => "market/pack?element_type=contents&container_guid=" . $item_guid));					
			$transfer_button = elgg_view('output/url', array(
					'text' => 'transfer ...', 
				    'class' => 'elgg-button-submit-element elgg-lightbox',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
		            'href' => "jot/add/$item_guid/transfer/box"));
			$pick = elgg_view('output/url', array(
        			'name' => 'pick',
        		    'text' => 'Pick',
				    'class' => 'elgg-button-submit-element',
        			'title' => elgg_echo('shelf:load'),
        			'href' => elgg_add_action_tokens_to_url('action/shelf/load?guid=' . $item_guid),
        		));					
	        $section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value $pick $set_button</td></tr>";
//			$section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value $set_button $pack_button $transfer_button</td></tr>";
			
			continue;
			
		}
		if ($label == 'purchase_cost'){
			$section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>".money_format('%#10n', $value)."</td></tr>";
			continue; 
		}
	    $section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value</td></tr>";
	}
	if ($section1) {
		echo '<table>' . $section1 . '</table>';
	}
if ($characteristics) {
    $characteristics_panel .= "<br><b>Characteristics</b><hr>";
    $characteristics_panel .= "
    <div class='rTable' style='width:100%'>
    	<div class='rTableBody'>";
/*    		<ul style = 'list-style:none;
    					 max-height:100px;
    					 margin:0;
    					 overflow:auto;
    					 padding:0;
    					 text-indent:0px'>";*/
    
    foreach ($characteristics as $name=>$value) {
        $characteristics_panel .=  "
    			  <div class='rTableRow highlight'>
    				<div class='rTableCell' style='width:60%;white-space:nowrap'>$name</div>
    				<div class='rTableCell' style='width:40%;white-space:nowrap'>$value</div>
    			  </div>";
    	}	
    	
//     	$characteristics_panel .=  "<li>
//     			  <div class='rTableRow highlight'>
//     				<div class='rTableCell' style='width:60%;white-space:nowrap'>$name</div>
//     				<div class='rTableCell' style='width:40%;white-space:nowrap'>$value</div>
//     			  </div>
//     		</li>";
//     	}	
//     $characteristics_panel .=  "</ul></div>
//       </div>";	
     $characteristics_panel .=  "</div>
       </div>";
}
if (!empty($features)){
    $features_panel .= "<br><b>Features</b><hr>";
    $features_panel .= "
    <div class='rTable' style='width:100%'>
    	<div class='rTableBody'>";
/*    		<ul style = 'list-style:none;
    					 max-height:100px;
    					 margin:0;
    					 overflow:auto;
    					 padding:0;
    					 text-indent:0px'>";*/
    
    foreach ($features as $key=>$feature) {
        $features_panel .=  "
    			  <div class='rTableRow highlight'>
    				<div class='rTableCell' style='width:100%'>$feature</div>
    			  </div>";
    	}	      
//     	$features_panel .=  "<li>
//     			  <div class='rTableRow highlight'>
//     				<div class='rTableCell' style='width:100%'>$feature</div>
//     			  </div>
//     		</li>";
//     	}	
//     $features_panel .=  "</ul></div>
//       </div>";	
    	$features_panel .= "</div>
    			</div>";
}

echo "$characteristics_panel
      $features_panel";
$action    = 'market/add/details';
$form_vars = array('name'    => 'contents_add', 
                   'enctype' => 'multipart/form-data', 
                   'action'  => 'action/jot/add/elements',
/*                   'onsubmit'=>"window.open('http://test.quebx.smarternetwork.com?s=<?php echo elgg_dump(".$categories.") ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true",*/    
);

$body_vars = array('element_type'   => 'item',
                   'aspect'         => 'contents',
                   'container_guid' => $item_guid, 
                   'panel'          => 'contents');
$contents_add .= elgg_view_form($action, $form_vars, $body_vars);
$contents_add_button = "<a id='contents_add' title='add contents' class='elgg-button-submit-element' style='cursor:pointer;height:14px;width:30px'>+</a>";
/*****************/
$form_vars = array('name'    => 'components_add', 
                   'enctype' => 'multipart/form-data', 
                   'action'  => 'action/jot/add/elements',
);
$body_vars['aspect'] = 'component';
$body_vars['panel']  = 'components';

$components_add .= elgg_view_form($action, $form_vars, $body_vars);
$components_add_button = "<a id='components_add' title='add components' class='elgg-button-submit-element' style='cursor:pointer;height:14px;width:30px'>+</a>";
//     				    <div id='contents_add' class='elgg-button-submit-element' style='cursor:pointer;height:14px;width:30px'>add...</div>

$form_vars['name']   = 'accessories_add';
$body_vars['aspect'] = 'accessory';
$body_vars['panel']  = 'accessories';

$accessories_add .= elgg_view_form($action, $form_vars, $body_vars);
$accessories_add_button = "<a id='accessories_add' title='add accessories' class='elgg-button-submit-element' style='cursor:pointer;height:14px;width:30px'>+</a>";

// contents
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Contents'), elgg_echo($entity->title));
	echo "<br>$contents_add_button <b>Contents</b>
	<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
            <div class='rTableRow'>
				<div class='rTableCell' style='width:100%;padding:0px'>
    				<div id='contents_panel'>$contents_add</div>
	            </div>
	        </div>";

	if ($contents) {
//@EDIT 2018-02-28 - SAJ - Displays contents hierarchically.  Removes the unpack/pack... buttons
		$contents   = elgg_get_entities(['type'=>'object', 'subtypes'=>ELGG_ENTITIES_ANY_VALUE, 'limit' => false,]);
		foreach ($contents as $content){
			$elements[] = ['guid'           => $content->guid,
			               'container_guid' => $content->container_guid,
					       'title'          => $content->title];
		}
		$parent_id      = $item_guid;
		foreach ($elements as $element) {
		    $id = $element['guid'];
		    $parent_id = $element['container_guid'];
		    $data[$id] = $element;
		    $index[$parent_id][] = $id;
		}
		$options = ['data'           => $data, 
				    'index'          => $index, 
				    'parent_id'      => $item_guid, 
				    'ul_class'       => 'hierarchy', 
				    'collapsible'    => true,
				    'collapse_level' => 1,
				    'level'          => 0,
				    'links'          => true];
		echo quebx_display_child_nodes($options);
				
		
/*	    echo "<div id='show_contents_panel' class='rTableRow'>
				<div class='rTableCell' style='width:100%;padding:0px 5px'>
    				<div class='rTable' style='width:100%'>
                		<div class='rTableBody'>";
	    
		foreach ($contents as $i) {
		    $subtype = $i->getSubtype(); 
		    $contents_list = '';
		    $qty           = NULL;
    		if ($i->qty > 1){
    	       $this_qty = '('. $i->qty .')';
    	        }
    	    else {
    	       $this_qty = NULL;
    	        }
	    
    	    $box_contains = elgg_get_entities(array(
                    'type' => 'object',
    				'subtypes' => array('market', 'item', 'contents'),
                    'joins'    => array('JOIN elgg_objects_entity e2 on e.guid = e2.guid'),
    				'wheres'   => array("e.container_guid = $i->guid"),
                    'order_by' => 'e2.title',
                    'limit'    => false,
    			));
    	    if (!empty($box_contains)){
    	        $first_item = array_shift($box_contains);
    	        if ($first_item->qty > 1){
    	            $qty = '('. $first_item->qty .')';
    	        }
    	        
    	        $contents_list .= elgg_view('output/url', array('text' => $first_item->title,'href' =>  $first_item->getURL()."/Details")).' '.$qty;
    	    }
    		foreach ($box_contains as $contained_item){
    		    if ($contained_item->qty > 1){
    	            $qty = '('. $contained_item->qty .')';
    	        }
    	        else {
    	            $qty = NULL;
    	        }
    		    
    		    $contents_list .= ', '.elgg_view('output/url', array('text' => $contained_item->title,'href' => $contained_item->getURL()."/Details")).' '.$qty;
    		}
		        $pack_button = elgg_view('output/url', array(
					'text' => 'pack ...',
		            'title'=> 'pack to a different container', 
				    'class' => 'elgg-button-submit-element elgg-lightbox',
				    'data-colorbox-opts' => '{"width":500, "height":525}',
		            'href' => "market/pack?element_type=contents&guid=$i->guid&container_guid=$item_guid"));
    		    $unpack_link = elgg_view('output/url', array(
    		        'text' => 'unpack',
		            'title'=> 'remove from this container',
		            'href' => elgg_add_action_tokens_to_url("action/market/unpack?guid=$i->guid"),
    		    ));
    		    
		        $delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
		            'title'=> 'delete',
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
		        $materialize = elgg_view("output/url",array(
			    	'href' => elgg_add_action_tokens_to_url("action/market/materialize?guid=$i->guid"),
			    	'text' => elgg_view_icon('checkmark'),
		            'title'=> 'materialze',
			    	'confirm' => 'Materialize item?',
			    	'encode_text' => false,
			    ));
				$edit = elgg_view("output/url",array(
			    	'href' => "market/edit/$i->guid",
			    	'text' => elgg_view_icon('docedit'),
			    	'title' => sprintf(elgg_echo('jot:edit'), $element_type),
			    	'encode_text' => false,
			    ));

    		if ($subtype == 'market' || $subtype == 'item'){
		        $options   = $unpack_link.' '.$pack_button;
		    }
		    else {
		        $options   = $delete.$materialize;
		    }
		    $line_item = elgg_view('output/url', array('text' => $i->title,'href' => $i->getURL()."/Details"));
		    if (!empty($contents_list)){$line_item .= "<div style='padding:0px 0px 0px 15px'>$contents_list</div>";}
		    
		    echo "
              <div class='rTableRow highlight'>
				<div id=contents_item_{$i->guid} class='rTableCell' style='width:450px'>$line_item $this_qty</div>
				<div class='rTableCell' style='width:100px'>$options</div>
			  </div>";
		}*/
	echo "</div>
	</div>";
	}
echo "
	</div>
</div>";	

// loop through every form field
while( list( $field, $value ) = each( $_POST )) {
   // display values
   if( is_array( $value )) {
      // if checkbox (or other multiple value fields)
      while( list( $arrayField, $arrayValue ) = each( $value )) {
         echo "<p>" . $arrayValue . "</p>\n";
      }
   } else {
      echo "<p>" . $value . "</p>\n";
   }
}	
/*    				<div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                            <div class='rTableRow'>
                				<div class='rTableCell' style='width:20%;padding:0px 5px'>11</div>
                				<div class='rTableCell' style='width:80%;padding:0px 5px'>12</div>
                	        </div>
                	    </div>
                	</div>
                	
*/
/*	echo "<table width = 100%>
	      <tr><td colspan=2 width=100%><b>Contents</b>&nbsp;
		      <span class='hoverhelp'>[?]
			      <span style='width:500px;'><p>$hoverhelp
			      </span>
		      </span>
	          </td>
	      </tr>";
	echo elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=market&guid=$item_guid"));
	if ($contents) {
		foreach ($contents as $i) {
			echo '<tr class="highlight">
				      <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "market/view/$i->guid/Details")).'</td>
			      </tr>';
		}
	}	
*/
	echo "<table width = 100%>";
	
// components
	$components_button = 	elgg_view('output/url',array('name'               => '05component',
    												 'text'               => '+',
	                                                 'title'              =>'add component',
    												 'class'              => 'elgg-button-submit-element elgg-lightbox',
    											     'data-colorbox-opts' => '{"width":500, "height":325}',
    			                                     'href'               => "jot/box/$item_guid/component/add"));
	
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Components'), elgg_echo($entity->title));
		echo "<br>$components_add_button<b>&nbsp;Components</b>
    	<div class='rTable' style='width:100%'>
    		<div class='rTableBody'>
                <div class='rTableRow'>
    				<div class='rTableCell' style='width:100%;padding:0px'>
        				<div id='components_panel'>$components_add</div>
    	            </div>
    	        </div>";
/*	echo "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr><td colspan=2 width=100%>$components_button <b>Components</b>&nbsp;
		      <span class='hoverhelp'>[?]
			      <span style='width:500px;'><p>$hoverhelp
			      </span>
		      </span>
	          </td>
	      </tr>";*/
//	echo elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=component&guid=$item_guid"));
	if ($components) {
	    echo "<div id='show_components_panel' class='rTableRow'>
		<div class='rTableCell' style='width:100%;padding:0px 5px'>
			<div class='rTable' style='width:100%'>
        		<div class='rTableBody'>";
	}	
	echo "</div>
	</div>";
	
echo "
	</div>
</div>";	

		foreach ($components as $i) {
			echo '<tr class="highlight">
				      <td colspan=2>'.elgg_view('output/url', array('text' => $i->title, 'href' =>  $i->getURL()."/Details")).'</td>
			      </tr>';
		}
// Accessories
/*
	$accessories_button = 	elgg_view('output/url',array('name'               => '06accessory',
    												 'text'               => '+',
	                                                 'title'              =>'add accessory',
    												 'class'              => 'elgg-button-submit-element elgg-lightbox',
    											     'data-colorbox-opts' => '{"width":500, "height":325}',
    			                                     'href'               => "jot/box/$item_guid/accessory/add"));
	
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Accessories'), elgg_echo($entity->title));
	echo "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr><td colspan=2 width=100%>$accessories_button <b>Accessories</b>&nbsp;
		      <span class='hoverhelp'>[?]
			      <span style='width:500px;'><p>$hoverhelp
			      </span>
		      </span>
	          </td>
	      </tr>";*/
	echo "<br>$accessories_add_button<b>&nbsp;Accessories</b>
    	<div class='rTable' style='width:100%'>
    		<div class='rTableBody'>
                <div class='rTableRow'>
    				<div class='rTableCell' style='width:100%;padding:0px'>
        				<div id='accessories_panel'>$accessories_add</div>
    	            </div>
    	        </div>";


	if ($accessories) {
	    echo "<div id='show_accessories_panel' class='rTableRow'>
		<div class='rTableCell' style='width:100%;padding:0px 5px'>
			<div class='rTable' style='width:100%'>
        		<div class='rTableBody'>";
	}	
    	echo "</div>
    	</div>";
    	
    echo "
    	</div>
    </div>";	
	
//	echo elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=accessory&guid=$item_guid"));
	if ($accessories) {
		foreach ($accessories as $i) {
			echo '<tr class="highlight">
			        <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  $i->getURL())).'</td>
			      </tr>';
		}
	}	
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	
	
	// containers
$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Component of'), elgg_echo($entity->title));
		if ($containers) {
		echo "<tr><td width=100%><b>Component of</b>&nbsp;
			      <span class='hoverhelp'>[?]
			      	<span style='width:500px;'><p>$hoverhelp</span>
			      </span>
		      </td>
		      <td></td>
		      </tr>";
		foreach ($containers as $i) {
			echo '<tr class="highlight">';
				echo '<td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' => $i->getURL())).'</td>';
			echo '</tr>';
			}	
		echo '<tr><td colspan=2>&nbsp;</td></tr>';
	}
	
	// parent items
$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Attached to'), elgg_echo($entity->title));
	if ($parent_items) {
		echo "<tr><td width=100%><b>Attached to</b>&nbsp;
			      <span class='hoverhelp'>[?]
			      	<span style='width:500px;'><p>$hoverhelp</span>
			      </span>
		      </td>
		      <td></td>
		      </tr>";
		foreach ($parent_items as $i) {
			$element_type = 'accessory';
			if ($i->canEdit()) {
				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$item_guid}",
			    	'text' => elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
			    	'encode_text' => false,
			    ));

			echo '<tr class="highlight">
					<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  $i->getURL())).'</td>
					<td>'.$detach.'
				  </tr>';
			}	
		echo '<tr><td colspan=2>&nbsp;</td></tr>';
	}
}
/*
	// Location (test)
	echo '<tr><td><b>Location</b></td>';
	echo '<td nowrap align=right>'.
          elgg_view('output/url', array(
			'text' => 'set ...', 
		    'class' => 'elgg-button-submit-element elgg-lightbox',
		    'data-colorbox-opts' => '{"width":500, "height":525}',
            'href' => "places/set?container_guid=" . $item_guid)).'</td></tr>';
if ($location){
	foreach ($location as $i) {
			$element_type = 'place';
			$detach = elgg_view("output/url",array(
		    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$item_guid",
		    	'text' => elgg_view_icon('unlink'),
//			    	'text' => elgg_view_icon('unlink', 'float-alt'),
		    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
		    	'encode_text' => false,
		    ));
			$edit = elgg_view("output/url",array(
		    	'href' => "places/edit/$i->guid",
		    	'text' => elgg_view_icon('docedit'),
		    	'title' => sprintf(elgg_echo('jot:edit'), $element_type),
		    	'encode_text' => false,
		    ));		    			
	echo '<tr class="highlight"><td>'.
	      elgg_view('output/url', array(
			'text' =>  $i->title,
			'href' =>  'places/view/'.$i->guid)).'</td>'.
		'<td nowrap>'.$edit.$detach.'</td>
         </tr>';
         }
}
else {echo '<tr><td colspan=2>&nbsp;</td></tr>';}
 */
	// Documentation
	$documentation_button = elgg_view('output/url', array(
                        			'text' => '+', 
                        		    'class' => 'elgg-button-submit-element elgg-lightbox',
                        		    'data-colorbox-opts' => '{"width":500, "height":525}',
                                    'href' => "jot/attach?element_type=document"));

	echo "<tr colspan=2><td>$documentation_button <b>Documentation</b></td>
		  </tr>";
		if ($documents) {
		foreach ($documents as $i) {
			$element_type = 'document';
			if ($i->canEdit()) {
				$detach = elgg_view("output/url",
			       ['href' => "action/jot/detach?element_type=$element_type&guid=$i->guid&container_guid=$item_guid",
			    	'text' => elgg_view_icon('link').elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $element_type),
			        'class'=>'hover-change',
			    	'encode_text' => false,
			       ]
				);
				$edit = elgg_view("output/url",array(
			    	'href' => "file/edit/$i->guid",
			    	'text' => elgg_view_icon('file-text-o'),
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
			echo '<tr class="highlight"><td>
					<span class="hoverinfo">'.
					      elgg_view('output/url', array(
							'text' =>  $i->title,
							'href' =>  'file/view/'.$i->guid)).'
		 	             <span>'.
			 	             elgg_view('hoverinfo', $i)
							 .'
		 	                <table><tr><td colspan=2><b>'.elgg_echo($i->title).'</b></td></tr>
		 	                       <tr><td>Owner:</td><td>'.elgg_echo(get_entity($i->owner_guid)->name).'</td></tr>
		 	                       <tr><td>Submission:</td><td>'.elgg_echo(strftime("%b %d %Y", ($i->time_created))).'</td></tr>
		 	                       <tr><td>Source:</td><td>'.'</td></tr>
		 	                       <tr><td>Labels:</td><td>'.'</td></tr>
		 	                 </table>
		 	             </span>
		 	          </span>
                 </td>
				 <td nowrap>'.$download.'&nbsp;'.$edit.'&nbsp;'.$detach.'</td>';
			echo '</tr>';
		}
	}	
	
	echo '<tr><td colspan=2>&nbsp;</td></tr>
	      </table>';
/*		  
if(!$files){echo 'no files found';}

if ($files) {
foreach ($files as $i) {
	echo elgg_view('output/url', array('text' => $i->title,'href' =>  'file/view/'.$i->guid)).'<br>';
    }
}	
*/
/****************************/

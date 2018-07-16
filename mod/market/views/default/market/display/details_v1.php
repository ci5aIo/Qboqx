<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
setlocale(LC_MONETARY, 'en_US');
$fields = market_prepare_detailed_view_vars($entity);

/**/

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$contents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'contents',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$contents = array_merge($contents, elgg_get_entities(array(
				'type' => 'object',
				'subtypes' => array('market', 'item'),
				'wheres' => array(
					"e.container_guid = $item_guid",
				),
			)));
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
					'text' => 'place ...', 
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
		            'href' => "jot/add/$item_guid/transfer"));					
	    $section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value $set_button $pack_button $transfer_button</td></tr>";
			
			continue;
			
		}
		if ($label == 'purchase_cost'){
			$section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>".money_format('%#10n', $value)."</td></tr>";
			continue; 
		}
	    $section1 .= "<tr><td><b>$label<b></td><td style='padding-left: 15px;'>$value</td></tr>";
	}
	if ($section1) {
		echo '<table>' . $section1 . '</table><p>';
	}

// contents
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Contents'), elgg_echo($entity->title));
	echo "<table width = 100%>
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
	
// components
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Components'), elgg_echo($entity->title));
	echo "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr><td colspan=2 width=100%><b>Components</b>&nbsp;
		      <span class='hoverhelp'>[?]
			      <span style='width:500px;'><p>$hoverhelp
			      </span>
		      </span>
	          </td>
	      </tr>";
	echo elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=component&guid=$item_guid"));
	if ($components) {
		foreach ($components as $i) {
			echo '<tr class="highlight">
				      <td colspan=2>'.elgg_view('output/url', array('text' => $i->title, 'href' =>  "market/view/$i->guid/Details")).'</td>
			      </tr>';
		}
	}	
	// Accessories
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Accessories'), elgg_echo($entity->title));
	echo "<tr><td colspan=2>&nbsp;</td></tr>
	      <tr><td colspan=2 width=100%><b>Accessories</b>&nbsp;
		      <span class='hoverhelp'>[?]
			      <span style='width:500px;'><p>$hoverhelp
			      </span>
		      </span>
	          </td>
	      </tr>";
	echo elgg_view_form('market/add/element', array("action" => "action/jot/add/element?element_type=accessory&guid=$item_guid"));
	if ($accessories) {
		foreach ($accessories as $i) {
			echo '<tr class="highlight">
			        <td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>
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
				echo '<td colspan=2>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>';
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
					<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/view/'.$i->guid)).'</td>
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

	echo '<tr><td><b>Documentation</b></td>';
	echo '<td nowrap align=right>'.
	      elgg_view('output/url', array(
			'text' => 'add ...', 
		    'class' => 'elgg-button-submit-element elgg-lightbox',
//		    'class' => 'elgg-lightbox',
		    'data-colorbox-opts' => '{"width":500, "height":525}',
//          'href' => elgg_add_action_tokens_to_url("action/jot/attach?element_type=document&guid=" . $item_guid))).'</td>';
            'href' => "jot/attach?element_type=document&container_guid=" . $item_guid)).'</td>';
//			'href' => "file/add?element_type=document&container_guid=" . $item_guid)).'</td>';
	echo '</tr>';
		if ($documents) {
		foreach ($documents as $i) {
			$element_type = 'document';
			if ($i->canEdit()) {
				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$item_guid",
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
			echo '<tr class="highlight"><td>
					<span class="hoverinfo">'.
					      elgg_view('output/url', array(
							'text' =>  $i->title,
							'href' =>  'file/view/'.$i->guid)).'
		 	             <span style="width:150px;">'.
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
				 <td nowrap>'.$download.$edit.$detach.'</td>';
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
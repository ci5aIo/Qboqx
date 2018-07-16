<?php
/*
 * purchase Details
*/ 
$entity       = $vars['entity'];
$section      = $vars['this_section'];
$asset        = $entity->asset;
$item_guid    = $entity->guid;
$subtype      = $entity->getSubtype();
$referrer     = "jot/$subtype/$item_guid/$section";
echo "<!--Section = $section-->";

/**/
$efforts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'effort',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
	
// efforts
	echo '<table width = 100%>
	      <tr>
	        <td colspan=2><b>Efforts to Resolve</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p></span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.
    elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=effort&guid=$item_guid&asset=$asset")).'
	        </td>
	      </tr>';
if ($efforts) {
foreach ($efforts as $i) {
			$element_type = 'effort';
			if ($i->canEdit()) {
				$delete = elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=Details",
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
	      </table>';
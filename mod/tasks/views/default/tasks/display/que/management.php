<?php
$entity     = $vars['entity'];
$section    = $vars['this_section'];
$category   = $entity->marketcategory;
$item_guid  = $entity->guid;
$owner      = $entity->getOwnerEntity();

$transfers = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'transfer',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$transfers = array_merge(
	$transfers, 
	elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'transfer_receipt',
		'relationship_guid' => $item_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	)));
$transfers = array_merge(
	$transfers, 
	elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'transfer_owner',
		'relationship_guid' => $item_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	)));

$owner_link = elgg_view('output/url', array(
	'href' => "profile/$owner->username",
	'text' => "$owner->name",
));
$owner_transfer = elgg_view('output/url', array(
	'text' => "transfer",
	'class' => 'elgg-lightbox',
	'data-colorbox-opts' => '{"width":500, "height":525}',
	'href' => "jot/add/$item_guid/transfer",
)); 

echo "Section: $section<br>";

echo "<b>Management Skeleton</b>
		<table width = 100%>
			<tr>
				<td>Item Owner</td>
				<td><span title = 'Item Owner'>$owner_link</span></td>
				<td>$owner_transfer</td>
				<td>history</td>
			</tr>
			<tr>
				<td>Process Owner</td>
				<td></td>
				<td>transfer</td>
				<td>history</td>
			</tr>
			<tr>
				<td>Service Owner</td>
				<td></td>
				<td>transfer</td>
				<td>history</td>
			</tr>
			<tr>
				<td>Posessor</td>
				<td></td>
				<td>transfer</td>
				<td>history</td>
			</tr>
		</table>
		<p><b>Visibility</b></p>
		<p><b>Work Performance by Provider</b></p>
        ";


// transfers
	$hoverhelp = sprintf(elgg_echo('jot:hoverhelp:Transfers'), elgg_echo($entity->title));
	echo "<table width = 100%>
	      <tr><td colspan=2>&nbsp;</td></tr>
	      <tr><td colspan=2 width=100%><b>Transfer history</b>&nbsp;
		      <span class='hoverhelp'>[?]
			      <span style='width:500px;'><p>$hoverhelp
			      </span>
		      </span>
	          </td>
	      </tr>";
			if ($transfers) {
		foreach ($transfers as $i) {
			$element_type = 'transfer';
			if ($i->canEdit()) {
				$delete = elgg_view("output/confirmlink",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}

			echo '<tr class="highlight">
			         <td>'.
			         elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/$i->guid")).
			         '</td>
			         <td>'.$delete.'
			      </tr>';
		}
	}	
	else {
		echo '<tr>
		        <td>No transfers made</td>
		        <td>
		      </tr>';	
	     }	

	echo '<tr><td colspan=2>&nbsp;</td></tr>
	      </table>';
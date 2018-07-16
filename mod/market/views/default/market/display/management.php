<?php
$entity        = $vars['entity'];
$section       = $vars['this_section'];

$item_guid     = $entity->guid;
$owner         = $entity->getOwnerEntity();
$item_owner_guid    = $entity->item_owner;
$process_owner_guid = $entity->process_owner;
$service_owner_guid = $entity->service_owner;
$steward_guid       = $entity->steward;

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
$item_owners = elgg_get_entities_from_relationship([
		'type' => 'object',
		'relationship' => 'item_owner',
		'relationship_guid' => $item_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	]);
if (!empty($item_owner)){
	$owner_link = elgg_view('output/url', [
		'href' => "profile/$item_owner->username",
		'text' => "$item_owner->name",
	]);
}
else {
	$owner_link = elgg_view('output/url', [
		'href' => "profile/$owner->username",
		'text' => "$owner->name",
	]);
}

$owner_transfer = elgg_view('output/url', array(
	'text' => "transfer",
	'class' => 'elgg-lightbox',
	'data-colorbox-opts' => '{"width":500, "height":525}',
	'href' => "jot/add/$item_guid/transfer",
)); 

if ($process_owner_guid){
    $process_owner = get_entity($process_owner_guid);
    $process_owner_link = elgg_view('output/url', array(
                        	'href' => "profile/$process_owner->username",
                        	'text' => "$process_owner->name",
                        )); 
}
if ($service_owner_guid){
    $service_owner = get_entity($service_owner_guid);
    $service_owner_link = elgg_view('output/url', array(
                        	'href' => "profile/$service_owner->username",
                        	'text' => "$service_owner->name",
                        )); 
}
if ($steward_guid){
    $steward = get_entity($steward_guid);
    $steward_link = elgg_view('output/url', array(
                        	'href' => "profile/$steward->username",
                        	'text' => "$steward->name",
                        )); 
}
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
				<td>$process_owner_link</td>
				<td>transfer</td>
				<td>history</td>
			</tr>
			<tr>
				<td>Service Owner</td>
				<td>$service_owner_link</td>
				<td>transfer</td>
				<td>history</td>
			</tr>
			<tr>
				<td>Steward</td>
				<td>$steward_link</td>
				<td>transfer</td>
				<td>history</td>
			</tr>
		</table>
		<br><b>Visibility</b></br>
		<br><b>Access</b></br>
		<br><b>Work Performance by Provider</b></br>
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
				$delete = elgg_view("output/url",array(
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
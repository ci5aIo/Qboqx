<?php
echo 'View: views\default\market\display\appliances.php';
$marketpost = $vars['entity'];
$entity = $vars['entity'];
$item_guid = $entity->guid;

$fields = market_prepare_detailed_view_vars($entity);

// single items
foreach ($items as $item) {
  echo "<p><b>{$item['label']}:</b> {$item['field']}</p>";
}

// components/values
$names = $entity->component_names;
$values = $entity->component_values;

$html = '';
foreach ($names as $key => $name) {
	if ($name === '' || $values[$key] == '') {
		// don't show empty values
		continue;
	}
	
	$html .= '<tr><td>' . $name . '</td><td style="padding-left: 15px;">' . $values[$key] . '</td></tr>';
}

if ($html) {
	echo "<p><b>Components:</b><br>";
	echo '<table>' . $html . '</table>';
}
// components
$components = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'component',
	'container_guid' => $item_guid,
	'limit' => false,
));

//if ($components) {
	echo '<table><tr><td width=100%><b>Components</b>&nbsp;';
	echo '<span class="hoverhelp">[?]';
	echo '<span style="width:500px;"><p>Components are distinct, manageable items that are part of this  ' . elgg_echo($entity->title) . ' item. Examples include Wheels, Engine or Body.  Components can have components themselves.  Any work done to a component rolls up to its parent.</p><p>Click [add new component] to add a new component item.</span>';
	echo '</span>';
echo '</td>';
echo '<td></td><td nowrap>'.
	elgg_view('output/url', array(
		'text' => '[add new component]', 
		'href' => elgg_add_action_tokens_to_url("action/market/add/element?element_type=component&guid=" . $item_guid))).'</td>';
echo '</tr></table>';
	
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

// accessories
if ($accessories) {
	echo '<label>Accessories</label><br><ul style="list-style:square inside">';
	foreach ($accessories as $accessory) {
		echo '<li>'.elgg_view('output/url', array(
			'text' => $accessory->title,
			'href' => 'market/view/'.$accessory->guid
		));
		echo '</li>';
	}
	echo '</ul>';
}

// containers
if ($containers) {
	echo '<label>Attached to</label><br><ul style="list-style:square inside">';
	foreach ($containers as $container) {
		echo '<li>'.elgg_view('output/url', array(
			'text' => $container->title,
			'href' => 'market/view/'.$container->guid
		));
		echo '</li>';
	}
	echo '</ul>';
}
echo '<br><br>';

echo '<p><table width = "100%">';
echo '<tr><td><b>Documentation</b></td>';
	echo '<td nowrap>'.
	      elgg_view('output/url', array(
			'text' => '[add new document]', 
			'class' => 'elgg-lightbox',
			'data-colorbox-opts' => '{"width":500, "height":225}',
//            'href' => elgg_add_action_tokens_to_url("action/jot/attach?element_type=document&guid=" . $item_guid))).'</td>';
            'href' => "jot/attach?element_type=document&container_guid=" . $item_guid)).'</td>';
			
echo '</tr>';
echo '<tr><td colspan=2>';
	if ($documents) {
	foreach ($documents as $i) {
		echo '<tr><ul style="list-style:square inside">';
//			echo '<td>'.$i->guid.'</td>';
			echo '<td><li>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'file/view/'.$i->guid)).'</li></td>';
		echo '<ul></tr>';
	}
}	
echo '<tr><td colspan=2>&nbsp;</td></tr>';
//echo '</table>';

// output our uploaded file
if ($marketpost->upload) {
	echo elgg_view('output/url', array(
		'text' => 'Uploaded Document',
		'href' => 'market/file/' . $marketpost->upload
	));
}
// output our uploaded file
/*if ($entity->upload) {
	echo '<br>';
	echo elgg_view('output/url', array(
		'text' => 'Uploaded Document',
		'href' => 'market/file/' . $entity->upload
	));
}*/

//echo '<table width = "100%">';
// issues
echo '<tr><td><b>Issues</b></td>
      <td nowrap>'.
      elgg_view('output/url', array(
		'text' => '[add new issue]', 
		'href' => "jot/add/{$item_guid}/issue"
//		'href' => "jot/issue/add/{$item_guid}"
      )).'
      </td></tr>';
//echo '<tr><td colspan=3>';
	if ($issues) {
	foreach ($issues as $i) {
		echo '<tr>';
			echo '<td>'.$i->guid.'</td>';
			echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/issue/{$i->guid}")).'</td>';
		echo '</tr>';
	}
}	
echo '<tr><td colspan=2>&nbsp;</td></tr>';
/*
if ($issues) {
	echo '<br>';
	echo '<label>Issues</label><br>';
	foreach ($issues as $issue) {
		echo elgg_view('output/url', array(
			'text' => $issue->title,
			'href' => $issue->getURL()
		));
		echo '<br>';
	}
}*/
echo '<tr><td><b>Tasks</b></td>
      <td nowrap>'.
      elgg_view('output/url', array(
		'text' => '[add new task]', 
//		'href' => "tasks/add/".$item_guid));
		'href' => "tasks/add/".$entity->owner_guid."?element_type=task&container_guid=".$item_guid
//		'href' => "tasks/add?element_type=task&container_guid=" . $item_guid.'&owner_guid='.$entity->owner_guid));
//		'href' => "tasks/add?element_type=task&guid=" . $item_guid)
      )).'
      </td></tr>';
//echo '<tr><td colspan=3>';
	if ($tasks) {
	foreach ($tasks as $i) {
		echo '<tr>';
			echo '<td>'.$i->guid.'</td>';
			echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  '	tasks/view/'.$i->guid)).'</td>';
		echo '</tr>';
	}
}	
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '</table>';
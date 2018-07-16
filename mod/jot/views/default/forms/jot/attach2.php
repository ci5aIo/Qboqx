<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

$access_id      = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$guid           = elgg_extract('guid', $vars, null);
$element_type   = get_input('element_type');
$container_guid = get_input('container_guid');
$submit_label   = elgg_echo('attach');
$owner          = elgg_get_page_owner_entity();
$owner_guid     = $owner->guid;
if (!$owner_guid) {
	$owner_guid = elgg_get_logged_in_user_guid();
}
$input_form = elgg_view('input/dropzone', array('name' => 'attach_guids',
//									'accept' => 'application/pdf',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));

$files = elgg_get_entities(array('type'=>'object','subtype'=>'file', 'owner_guid' => $owner_guid, ));

echo elgg_view('input/hidden', array('name' => 'element_type'  , 'value' => $element_type));
echo elgg_view('input/hidden', array('name' => 'access_id'     , 'value' => $access_id));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'owner_guid'    , 'value' => $owner_guid));

echo $input_form;
echo '<div>';
$file_selection = array();
$checkboxes = '';
if ($files) {
// version 01
foreach ($files as $i) {
	$label = elgg_view('output/url', array(
      'text' => $i->title,
      'href' =>  'file/view/'.$i->guid));
	$input = elgg_view('input/checkbox', array(
	   'id'=>$i->guid, 
	   'name' => "attach_guids[]", 
	   'value'=>$i->guid, 
	   )).'<br>';
	$checkboxes .= "<tr><td>$input</td><td>$label</td></tr>";
    }
}
echo "<table width=100%>$checkboxes</table>";

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => $submit_label)).'</p>';

echo '</div>';
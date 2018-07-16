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

$files = elgg_get_entities(array('type'=>'object','subtype'=>'file', 'owner_guid' => $owner_guid, ));

$file_options[] = array();
if ($files){
	foreach ($files as $key=>$value){
      $file_options['name'] = $files->title;
      $file_options['value'] = $files->guid;
}}

// 11/23/2013 - Added by scottj

echo elgg_view('input/hidden', array('name' => 'element_type'  , 'value' => $element_type));
echo elgg_view('input/hidden', array('name' => 'access_id'     , 'value' => $access_id));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'owner_guid'    , 'value' => $owner_guid));

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
	   'name' => "params[$i]", 
	   'value'=>$i->guid, 
	   )).'<br>';
	$checkboxes .= "<tr><td>$input</td><td>$label</td></tr>";
    }
}
echo "<table width=100%>$checkboxes</table>";
    //version 02
/*
foreach ($files as $i) {
	$file_row[] = array('name' => $i->title,'value' =>  $i->guid);
    }
        $form_body = '<div>';
    foreach ($file_row as $name => $value) {
    if (sizeof($value > 0)) {
        $form_body .= elgg_view('input/checkboxes', array(
            'name' => $name,
            'options' => $value
                ));
    }
}
        $form_body .= '</div>';
*/
//echo elgg_view('input/checkboxes', $file_row);
//echo $form_body;
//version 03
/*
if ($files) {
    foreach ($files as $i) {
        $options = array($i->title, $i->guid);
    }
    echo '<div>';
    echo elgg_view('input/checkboxes', array(
        'options' => $options,
    ));
    echo '</div>';
}

	if($file_row){echo '<br>$file_row worked<br>';}
}
*/
//$file_row = array();
if ($file_options) {
foreach ($file_options as $key => $value) {
	echo "Key: $key; Value: $value<br />\n";
	$file_options[$key] -> $guid;
    }
}

echo elgg_view('input/checkboxes', array(
            'options' => $file_options,   
//            'value' => unserialize($vars['entity']->ads_header),
            'name' => 'params[ads_header]',
            'align' => 'vertical',
        ));

$options = array(elgg_echo("Enable Header 728x90 ads") => '728x90',
                 elgg_echo("Enable Header 300x250 ads") => '300x250',
                 elgg_echo("Enable Header 600x80 ads") => '600x80');

echo elgg_view('input/checkboxes', array(
            'options' => $options,   
            'value' => unserialize($vars['entity']->ads_header),
            'name' => 'params[ads_header]',
            'align' => 'vertical',
        ));

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => $submit_label)).'</p>';

echo '</div>';
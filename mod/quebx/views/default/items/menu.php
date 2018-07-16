<?php

/**
 * Item sections pages menu
 *
 * @uses $vars['section']
 */

$selected  = $vars['this_section'];
$title     = $vars['title'];
$this_guid = $vars['guid'];
$task      = get_entity($this_guid);
$user_guid = elgg_get_logged_in_user_guid(); 
$link_class= elgg_extract('link_class', $vars);
$data      = elgg_extract('data', $vars, false);
$compartments = elgg_extract('compartments', $vars,false);
if ($data && !is_array($data)){$data = array($data);}

if (empty($selected)) {
	 $selected = 'Summary';
}

if (empty($title)) {
	 $title = $task->title;
}

if ($user_guid == $task->owner_guid){$manage = true;}

//set the url
if ($task){$url = $task->getURL();}

$sections = array();
if ($manage){
    $sections[] = 'Profile';
    $sections[] = 'Summary';
    $sections[] = 'Details';
    $sections[] = 'Maintenance';
    $sections[] = 'Inventory';
    $sections[] = 'Management';
    $sections[] = 'Accounting';
    $sections[] = 'Gallery';
//    $sections[] = 'Reports';
    $sections[] = 'Timeline';
}
else {
    //$sections[] = 'Profile';
}

if ($compartments){$sections = $compartments;}

foreach ($sections as $key=>$section) {
	$data['compartment'] = $section;
	$data['qid_n']   = $data['qid'].'_'.$key;
	$tabs[] = ['title'      => elgg_echo("$section"),
			   'url'        => "$url/$section",
			   'data'       => $data,
			   'link_class' => $link_class,
			   'selected'   => $section == $selected,];
	if ($data['presentation']=='inline'){unset($tabs[$key]['url']);}
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
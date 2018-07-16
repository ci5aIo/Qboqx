<?php
/**
 * Market Owners menu
 * OBSOLETE
 * @uses $vars['type']
 */
$selected_queb     = $vars['this_label'];
$selected_category = $vars['this_category'];
$selected_owner    = $vars['this_owner'];
$list_type         = $vars['list_type'];
    if (!empty($list_type)){
        $list_type = "&list_type=$list_type";
    }
$user = elgg_get_logged_in_user_entity();

if (empty($selected_owner)) {
	 $tab_selected = 'everyone';
}
elseif ($selected_owner == $user->guid) {
    $tab_selected = 'mine';
}

//set the url
$url = elgg_get_site_url(). "queb?";

$owners[] = 'everyone';
//$owners[] = 'shared';
$owners[] = 'mine';
//$owners[] = 'friends';

foreach ($owners as $owner){
    switch ($owner){
        case 'everyone':
            $filter = "x=$selected_queb&y=$selected_category";
            break;
        case 'mine':
            $filter = "x=$selected_queb&y=$selected_category&z=$user->guid";
            break;
        default:
            $filter = $owner."/$user->username";
            break;
    }
    $tabs[] = array(
            'title'=> elgg_echo("market:$owner"),
            'url'  => $url.$filter.$list_type,
            'selected' => $tab_selected == $owner,
        );
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
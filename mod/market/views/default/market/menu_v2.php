<?php
/**
 * Market Owners menu
 *
 * @uses $vars['type']
 */

$type = $vars['owner'];
$user = elgg_get_logged_in_user_entity();

if (empty($type)) {
	 $type = 'everyone';
}

//set the url
$url = elgg_get_site_url(). "market/";

$owners[] = 'everyone';
$owners[] = 'shared';
$owners[] = 'mine';
$owners[] = 'friends';

foreach ($owners as $owner){
    switch ($owner){
        case 'everyone':
            $filter = 'category';
            break;
        case 'mine':
            $filter = 'owned/'.$user->username;
            break;
        default:
            $filter = $owner."/$user->username";
            break;
    }
    $tabs[] = array(
            'title'=> elgg_echo("market:$owner"),
            'url'  => $url.$filter,
            'selected' => $owner == $type,
        );
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));




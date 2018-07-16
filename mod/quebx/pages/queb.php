<?php

if (!elgg_is_logged_in()){
    forward(_elgg_walled_garden_init());
}

$x_dimension =       stripslashes(get_input('x'));
$y_dimension = (int) stripslashes(get_input('y'));
$z_dimension = (int) stripslashes(get_input('z'));
if ($y_dimension){
    $y      = get_entity($y_dimension);
    $aspect = $y->getSubtype();
}
set_input('owner', $z_dimension);
set_input('label', $x_dimension);

Switch ($aspect){
    case 'place':
        set_input('place', $y_dimension);
        break;
    case 'que':
        set_input('task', $y_dimension);
        break;
    case 'hjalbum':
        set_input('album', $y_dimension);
        break;
    default:
        set_input('cat'  , $y_dimension);
        break;
}
$base_dir = elgg_get_plugins_path() . 'quebx/pages/';
$page     = 'default.php';
include($base_dir.$page);

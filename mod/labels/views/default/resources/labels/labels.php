<?php

/*namespace Quebx\Labels;*/

gatekeeper();

$vars['user'] = elgg_get_logged_in_user_entity();

echo elgg_view_form('labels/add', array('class' => 'labels'), $vars);

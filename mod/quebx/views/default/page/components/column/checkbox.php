<?php
$entity             = $vars['item'];
$options['name']    = $vars['name'];
$options['value']   = $entity->getGUID();
$options['default'] = $vars['default'];
echo elgg_view('input/checkbox', $options);
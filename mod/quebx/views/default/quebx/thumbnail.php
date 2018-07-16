<?php

$quebxguid = $vars['itemguid'];
$size =  $vars['size'];
$class = $vars['class'];
$tu = $vars['tu'];

echo "<img src='" . elgg_get_site_url() . "mod/quebx/thumbnail.php?itemguid={$quebxguid}&size={$size}&ut={$tu}' class='elgg-photo $class'>";


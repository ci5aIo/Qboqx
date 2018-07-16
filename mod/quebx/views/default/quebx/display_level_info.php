<?php
echo '<div class="item-level-info-wrapper">';
$levels = array('quebxcategory','family','parent','individual','element');
$item = $vars['entity'];
$view = array();
foreach($levels as $level) {
  if ($item->$level) {
    $view[] = $item->$level;
    $v = 'quebx/display/'.implode("/",$view);
    echo elgg_view($v,array('entity'=>$item));
  }
}
echo '</div>';

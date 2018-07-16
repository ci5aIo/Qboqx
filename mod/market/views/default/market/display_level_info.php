<?php
echo '<div class="market-level-info-wrapper">';
$levels = array('marketcategory','family','parent','individual','element');
$marketpost = $vars['entity'];
$view = array();
foreach($levels as $level) {
  if ($marketpost->$level) {
    $view[] = $marketpost->$level;
    $v = 'market/display/'.implode("/",$view);
    echo elgg_view($v,array('entity'=>$marketpost));
  }
}
echo '</div>';

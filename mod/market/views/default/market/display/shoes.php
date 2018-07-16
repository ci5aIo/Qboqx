<?php
echo 'views\default\market\display\shoes.php';
$marketpost = $vars['entity'];
$items = array();
$items[] = array(
    'label'=>elgg_echo('market:edit_more:shoes:family:label'),
    'field'=> elgg_echo('market:family:shoes:'.$marketpost->family)
);
$items[] = array(
    'label'=>elgg_echo('market:edit_more:shoes:color:label'),
    'field'=> $marketpost->color
);

$items[] = array(
    'label'=>elgg_echo('market:edit_more:shoes:size:label'),
    'field'=> $marketpost->size
);

$items[] = array(
    'label'=>elgg_echo('market:edit_more:shoes:gender:label'),
    'field'=> $marketpost->gender
);

foreach ($items as $item) {
  echo "<p><b>{$item['label']}:</b> {$item['field']}</p>";
}

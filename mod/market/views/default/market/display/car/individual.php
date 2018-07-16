<?php
// for test purporses only.  Copied from Shoes.
echo 'views/default/market/display/car/individual.php';
echo 'Individual Level';
$marketpost = $vars['entity'];
$items = array();

$items[] = array(
    'label'=>elgg_echo('market:edit_more:shoes:dress:brand:label'),
    'field'=> $marketpost->brand
);

$items[] = array(
    'label'=>elgg_echo('market:edit_more:shoes:dress:price:label'),
    'field'=> "$".$marketpost->price
);

foreach ($items as $item) {
  echo "<p><b>{$item['label']}:</b> {$item['field']}</p>";
}

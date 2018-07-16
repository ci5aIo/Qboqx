<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$marketguid = $vars['marketguid'];
$entity     = get_entity($marketguid);
if (elgg_instanceof($entity, 'object', 'market') && !empty($entity->icon)){
    $marketguid = $entity->icon;
}
$size =  $vars['size'];
$class = $vars['class'];
$tu = $vars['tu'];

echo "<img src='" . elgg_get_site_url() . "mod/market/thumbnail.php?marketguid=$marketguid&size=$size&tu=$tu' class='elgg-photo $class'>";


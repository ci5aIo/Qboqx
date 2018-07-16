<?php
/**
 * Market icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class added to link
 */
$entity = elgg_extract('entity', $vars);
$vars['marketguid'] = $entity->icon ?: $entity->getGUID();
//$vars['marketguid'] = $entity->getGUID();

echo elgg_view('market/thumbnail', $vars);
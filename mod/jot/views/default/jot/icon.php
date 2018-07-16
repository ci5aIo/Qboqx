<?php
/**
 * Jot icon
 *
 * Uses a separate icon view due to dependency on annotation
 *
 * @uses $vars['jot']
 */

$jot = $vars['jot'];

// Get size
if (!in_array($vars['size'], array('small', 'medium', 'large', 'tiny', 'master', 'topbar'))) {
	$vars['size'] = "medium";
}

?>

<a href="<?php echo $jot->getURL(); ?>">
	<img alt="<?php echo $jot->title; ?>" src="<?php echo $jot->getIconURL($vars['size']); ?>" />
</a>

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

$num_display = $vars['entity']->num_display;
if($num_display == ''){
	$num_display = '10';
} 
?>

<p>
		<?php echo elgg_echo("market:num_display"); ?>:
		<select name="params[num_display]">
			<option value="5" <?php if($num_display == 1) echo "SELECTED"; ?>>5</option>
			<option value="10" <?php if($num_display == 2) echo "SELECTED"; ?>>10</option>
			<option value="25" <?php if($num_display == 3) echo "SELECTED"; ?>>25</option>
			<option value="50" <?php if($num_display == 4) echo "SELECTED"; ?>>50</option>
		</select>
</p>


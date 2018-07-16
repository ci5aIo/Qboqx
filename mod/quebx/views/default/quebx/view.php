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

// If there are any items to view, view them
if (is_array($vars['items']) && sizeof($vars['items']) > 0) {
			
	foreach($vars['items'] as $item) {
				
		echo elgg_view_entity($item);
				
	}
			
}



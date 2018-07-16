<?php

namespace hypeJunction\Cart;

/**
 * Database Interface
 */
interface DbInterface {
	
	/**
	 * Alias for elgg_get_entity()
	 * 
	 * @param int $guid Entity guid
	 * @return \ElggEntity|false
	 */
	public function getEntity($guid = 0);
}

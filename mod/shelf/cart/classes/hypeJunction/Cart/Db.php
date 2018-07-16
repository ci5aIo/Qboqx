<?php

namespace hypeJunction\Cart;

class Db implements DbInterface {

	/**
	 * {@inheritdoc}
	 */
	public function getEntity($guid = 0) {
		return get_entity($guid);
	}

}

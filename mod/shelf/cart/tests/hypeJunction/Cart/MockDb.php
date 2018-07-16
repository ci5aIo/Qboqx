<?php

namespace hypeJunction\Cart;

class MockDb implements DbInterface {

	public function getEntity($guid = 0) {
		$product = new MockProduct();
		$product->id = $guid;
		return $product;
	}

}

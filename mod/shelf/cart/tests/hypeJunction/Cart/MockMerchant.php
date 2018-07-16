<?php

namespace hypeJunction\Cart;

use hypeJunction\Payments\MerchantInterface;
use hypeJunction\Payments\OrderInterface;

class MockMerchant implements MerchantInterface {

	public $guid;
	
	public function __construct($guid = null) {
		$this->id = $guid;
	}

	public function getId() {
		return $this->id;
	}
	
	public static function factory($export) {
		$product = new MockMerchant();
		$product->id = $export['id'];
		return $product;
	}

	public function getCharges(OrderInterface $order) {
		return array();
	}

	public function toArray() {
		return array('_id' => $this->id);
	}

}

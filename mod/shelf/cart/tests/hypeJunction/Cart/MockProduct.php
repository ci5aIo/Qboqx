<?php

namespace hypeJunction\Cart;

use hypeJunction\Payments\ProductInterface;

class MockProduct implements ProductInterface {

	public $guid;
	
	public function __construct($guid = null) {
		$this->id = $guid;
	}

	public function getId() {
		return $this->id;
	}
	
	public static function factory($export) {
		$product = new MockProduct();
		$product->id = $export['id'];
		return $product;
	}

	public function getCharges() {
		return array();
	}

	public function getMedia(array $options = array()) {
		return array();
	}

	public function getMerchant() {
		return new MockMerchant(3);
	}

	public function getPrice() {
		return '10.00 EUR';
	}

	public function getPriceAmount() {
		return 1000;
	}

	public function getPriceCurrency() {
		return 'EUR';
	}

	public function inStock($quantity = 1) {
		return true;
	}

	public function setPrice($amount, $currencyCode) {
		return true;
	}

	public function toArray() {
		return array('_id' => $this->id);
	}

}

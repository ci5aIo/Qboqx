<?php

namespace hypeJunction\Cart;

class MockCartStorage implements StorageInterface {

	private $storage;

	public function get($id) {
		if (isset($this->storage)) {
			return $this->storage;
		}
	}

	public function invalidate($id) {
		unset($this->storage);
	}

	public function put($id, $data) {
		$this->storage = $data;
	}

}

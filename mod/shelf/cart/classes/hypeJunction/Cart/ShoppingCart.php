<?php

namespace hypeJunction\Cart;

use hypeJunction\Payments\OrderItemInterface;
use hypeJunction\Payments\ProductInterface;
use hypeJunction\Payments\Transaction;
use SebastianBergmann\Money\Currency;

/**
 * Cart collection
 */
final class ShoppingCart implements StorableInterface {

	/**
	 * Storage
	 * @var CartStorage
	 */
	private $storage;

	/**
	 * Database
	 * @var DbInterface
	 */
	private $db;

	/**
	 * Session
	 * @var Session
	 */
	private $session;

	/**
	 * Merchant and currency specific carts
	 * @var Cart[]
	 */
	private $carts;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(StorageInterface $storage, DbInterface $db, Session $session) {
		$this->storage = $storage;
		$this->db = $db;
		$this->session = $session;
		$this->carts = [];
	}

	/**
	 * Returns split cart hashes
	 * @return string[]
	 */
	public function hashes() {
		return array_keys($this->carts);
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($key, $value) {
		$this->carts[$key] = $value;
	}

	/**
	 * Get a cart by hash
	 *
	 * @param string $hash Hash
	 * @return Cart|null
	 */
	public function get($hash) {
		if (isset($this->carts[$hash])) {
			return $this->carts[$hash];
		}
	}

	/**
	 * Create a transaction from a cart by hash
	 *
	 * @param string $hash Cart hash
	 * @return Transaction
	 */
	public function createTransaction($hash) {
		$cart = $this->get($hash);
		if (!$cart instanceof Cart) {
			return;
		}

		$user = cart()->session->getLoggedInUser();
		$merchant = $cart->getMerchant();

		$transaction = Transaction::factory($user, $merchant, $cart->total(), $cart->getCurrency(), $cart->toArray());
		if ($transaction) {
			$cart->set('transaction_id', $transaction->id);
			unset($this->carts[$hash]);
			$this->save();
		}

		return $transaction;
	}

	/**
	 * Calculate split cart hash
	 *
	 * @param ProductInterface $product Product
	 * @return string
	 */
	private function getCartHash(ProductInterface $product) {
		return sha1($product->getMerchant()->getId() . $product->getPriceCurrency());
	}

	/**
	 * {@inheritdoc}
	 */
	public function add(ProductInterface $product, $quantity = 1) {
		$hash = $this->getCartHash($product);
		if (!isset($this->carts[$hash])) {
			$this->carts[$hash] = new Cart($product->getMerchant(), new Currency($product->getPriceCurrency()));
			$this->carts[$hash]->set('hash', $hash);
		}
		return $this->carts[$hash]->add($product, $quantity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update(ProductInterface $product, $quantity = 1) {
		$this->remove($product);
		return $this->add($product, $quantity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function remove(ProductInterface $product) {
		foreach ($this->carts as $cart) {
			$result = $cart->remove($product);
			if ($result) {
				break;
			}
		}
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function all() {
		$items = [];
		foreach ($this->carts as $cart) {
			$items += $cart->all();
		}
		return $items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		$quantity = 0;
		foreach ($this->all() as $item) {
			$quantity += $item->getQuantity();
		}
		return $quantity;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countLines() {
		return count($this->all());
	}

	/**
	 * {@inheritdoc}
	 */
	public function find(ProductInterface $product) {
		$result = false;
		foreach ($this->carts as $cart) {
			$result = $cart->find($product);
			if ($result) {
				break;
			}
		}
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has(ProductInterface $product) {
		return ($this->find($product) instanceof OrderItemInterface);
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray() {
		$export = [];
		foreach ($this->carts as $hash => $cart) {
			$export[$hash] = $cart->toArray();
		}
		return $export;
	}

	/**
	 * {@inheritdoc}
	 */
	public function restore() {
		$guid = $this->session->getLoggedInUserGuid();
		$carts = $this->storage->get($guid);
		foreach ($carts as $cart) {
			foreach ((array) $cart['_items'] as $item) {
				$product = $this->db->getEntity($item['_product']['_id']);
				$quantity = (int) $item['_quantity'];
				if ($product) {
					$this->add($product, $quantity);
				}
			}

			$props = $cart['_props'];
			$hash = elgg_extract('hash', $props);
			unset($props['hash']);

			if (isset($this->carts[$hash])) {
				foreach ($props as $key => $value) {
					$this->carts[$hash]->set($key, $value);
				}
			}
		}
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		$guid = $this->session->getLoggedInUserGuid();
		$this->storage->put($guid, $this->toArray());
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$this->carts = [];
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function subtotal() {
		$total = 0;
		foreach ($this->carts as $cart) {
			$total += $cart->subtotal();
		}
		return $total;
	}

	/**
	 * {@inheritdoc}
	 */
	public function charges() {
		$total = 0;
		foreach ($this->carts as $cart) {
			$total += $cart->charges();
		}
		return $total;
	}

	/**
	 * {@inheritdoc}
	 */
	public function total() {
		return $this->subtotal() + $this->total();
	}

}

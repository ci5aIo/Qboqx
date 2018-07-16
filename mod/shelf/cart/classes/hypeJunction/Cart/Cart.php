<?php

namespace hypeJunction\Cart;

use hypeJunction\Payments\ChargeInterface;
use hypeJunction\Payments\MerchantInterface;
use hypeJunction\Payments\OrderInterface;
use hypeJunction\Payments\OrderItemInterface;
use hypeJunction\Payments\ProductInterface;
use SebastianBergmann\Money\Currency;

final class Cart implements OrderInterface {

	/**
	 * Merchant
	 * @var MerchantInterface
	 */
	protected $merchant;

	/**
	 * Currency
	 * @var Currency
	 */
	protected $currency;

	/**
	 * Items
	 * @var CartItem[]
	 */
	protected $items;

	/**
	 * Additional data
	 * @var array
	 */
	protected $props = [];

	/**
	 * Constructor
	 *
	 * @param MerchantInterface $merchant
	 * @param Currency          $currency
	 */
	public function __construct(MerchantInterface $merchant, Currency $currency) {
		$this->merchant = $merchant;
		$this->currency = $currency;
		$this->items = [];
	}

	/**
	 * Returns a hash
	 * @return string
	 */
	public function id() {
		return $this->get('hash');
	}

	/**
	 * Returns a property
	 *
	 * @param string $key Key
	 * @return mixed
	 */
	public function get($key) {
		if (isset($this->props[$key])) {
			return $this->props[$key];
		}
	}

	/**
	 * Set a property
	 *
	 * @param string $key   Key
	 * @param mixed  $value Value
	 * @return void
	 */
	public function set($key, $value) {
		$this->props[$key] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add(ProductInterface $product, $quantity = 1) {
		$item = $this->find($product);
		if ($item) {
			$this->remove($product);
			$quantity += $item->getQuantity();
		}
		if ($quantity >= 1) {
			$item = new CartItem($product, $quantity);
			$this->items[] = $item;
		}
		return $item;
	}

	/**
	 * {@inheritdoc}
	 */
	public function all() {
		return $this->items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function find(ProductInterface $product) {
		$result = false;
		foreach ($this->items as $item) {
			if ($item->getProduct()->getId() === $product->getId()) {
				$result = $item;
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
	public function remove(ProductInterface $product) {
		$result = false;
		foreach ($this->items as $key => $item) {
			if ($item->getProduct()->getId() == $product->getId()) {
				unset($this->items[$key]);
				$result = true;
			}
		}
		return $result;
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
	public function count() {
		$quantity = 0;
		foreach ($this->items as $item) {
			$quantity += $item->getQuantity();
		}
		return $quantity;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countLines() {
		return count($this->items);
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$this->items = [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray() {

		$subtotal = $this->subtotal();
		$total = $this->total();

		$items = [];
		foreach ($this->items as $item) {
			$items[] = $item->toArray();
		}

		$charges = [];
		foreach ($this->merchant->getCharges($this) as $charge) {
			$ch = $charge->toArray();
			$ch['_amount'] = $charge->calculate($subtotal);
			$charges[] = $ch;
		}

		$customer = null;
		$user = elgg_get_logged_in_user_entity();
		if ($user) {
			$customer = (array) $user->toObject();
			$customer['_id'] = $user->guid;
		}

		return [
			'_merchant' => $this->merchant->toArray(),
			'_customer' => $customer,
			'_currency' => $this->currency->getCurrencyCode(),
			'_items' => $items,
			'_subtotal' => $subtotal,
			'_total' => $total,
			'_charges' => $charges,
			'_props' => $this->props,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function charges() {
		$total = 0;
		$cart_charges = (array) $this->merchant->getCharges($this);
		foreach ($cart_charges as $charge) {
			if (!$charge instanceof ChargeInterface) {
				continue;
			}
			$total += $charge->calculate($this->subtotal());
		}
		return $total;
	}

	/**
	 * {@inheritdoc}
	 */
	public function subtotal() {
		$total = 0;
		foreach ($this->all() as $item) {
			$total += $item->getProduct()->getPriceAmount() * $item->getQuantity();
		}
		return $total;
	}

	/**
	 * {@inheritdoc}
	 */
	public function total() {
		return $this->subtotal() + $this->charges();
	}

	/**
	 * Returns merchant
	 * @return MerchantInterface
	 */
	public function getMerchant() {
		return $this->merchant;
	}

	/**
	 * Returns currency
	 * @return Currency
	 */
	public function getCurrency() {
		return $this->currency;
	}

}

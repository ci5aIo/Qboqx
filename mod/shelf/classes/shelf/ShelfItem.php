<?php

namespace Shelf;
/*
use hypeJunction\Payments\OrderItemInterface;
use hypeJunction\Payments\ProductInterface;
*/
class ShelfItem implements OrderItemInterface {

	/**
	 * Product
	 * @var ProductInterface
	 */
	protected $product;

	/**
	 * Quantity
	 * @var int
	 */
	protected $quantity;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(ProductInterface $product, $quantity = 1) {
		$this->product = $product;
		$this->quantity = $quantity;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray() {
		$export = [
			'_product' => $this->product->toArray(),
			'_quantity' => $this->quantity,
		];
		return $export;
	}

}

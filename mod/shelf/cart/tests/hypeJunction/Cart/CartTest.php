<?php

namespace hypeJunction\Cart;

use ElggSession;
use PHPUnit_Framework_TestCase;

class CartTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var MockCartStorage
	 */
	private $storage;

	/**
	 *
	 * @var ShoppingCart
	 */
	private $cart;

	protected function setUp() {
		_elgg_services()->setValue('session', ElggSession::getMock());
		$this->storage = new MockCartStorage();
		$this->db = new MockDb();
		$this->session = new Session();
	}

	public function testCartSave() {
		$product = new MockProduct();
		$product->id = 2;

		$this->cart = new ShoppingCart($this->storage, $this->db, $this->session);
		$this->cart->add($product, 2);
		$this->cart->save();

		$this->cart2 = new ShoppingCart($this->storage, $this->db, $this->session);
		$this->cart2->restore();

		$this->assertNotEmpty($this->cart2->all());
		foreach ($this->cart2->all() as $item) {
			$this->assertInstanceOf(\hypeJunction\Payments\OrderItemInterface::class, $item);
		}

		foreach ($this->cart->all() as $key => $item) {
			$this->assertEquals($item->toArray(), $this->cart2->all()[$key]->toArray());
		}
	}

	public function testCartClear() {

		$product = new MockProduct();
		$product->id = 2;

		$this->cart = new ShoppingCart($this->storage, $this->db, $this->session);
		$this->cart->add($product, 2);

		$this->cart->clear();

		$this->assertEmpty($this->cart->all());

	}

	public function testCartAdd() {

		$product = new MockProduct();
		$product->id = 2;

		$this->cart = new ShoppingCart($this->storage, $this->db, $this->session);
		$this->cart->add($product, 5);

		$this->assertEquals(5, $this->cart->find($product)->getQuantity());

		$this->cart->update($product, 10);
		$this->assertEquals(10, $this->cart->find($product)->getQuantity());

		$this->cart->add($product, 5);
		$this->assertEquals(15, $this->cart->find($product)->getQuantity());
	}

	public function testCartRemove() {
		$product = new MockProduct();
		$product->id = 2;

		$this->cart = new ShoppingCart($this->storage, $this->db, $this->session);
		$this->cart->add($product, 2);

		$this->cart->remove($product);

		$this->assertEmpty($this->cart->all());
	}

	public function testCartHas() {
		$product = new MockProduct();
		$product->id = 2;

		$this->cart = new ShoppingCart($this->storage, $this->db, $this->session);

		$this->assertFalse($this->cart->has($product));
		
		$this->cart->add($product, 2);

		$this->assertTrue($this->cart->has($product));

	}

	public function testCartCounts() {

		$product = new MockProduct();
		$product->id = 2;

		$product2 = new MockProduct();
		$product2->id = 3;

		$this->cart = new ShoppingCart($this->storage, $this->db, $this->session);

		$this->cart->add($product, 2);
		$this->cart->add($product, 3);

		$this->cart->add($product2, 1);
		$this->cart->update($product2, 5);
		
		$this->assertEquals(10, $this->cart->count());
		$this->assertEquals(2, $this->cart->countLines());

		$this->assertTrue($this->cart->has($product));

	}
}

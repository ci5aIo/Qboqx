<?php

namespace hypeJunction\Cart;

interface StorableInterface {
	
	/**
	 * Constructor
	 *
	 * @param StorageInterface $storage
	 * @param DbInterface      $db
	 * @param Session          $session
	 */
	public function __construct(StorageInterface $storage, DbInterface $entityTable, Session $session);

	/**
	 * Restore from storage
	 * @return StorableInterface
	 */
	public function restore();

	/**
	 * Save to storage
	 * @return StorableInterface
	 */
	public function save();

	/**
	 * Clear all items
	 * @return StorableInterface
	 */
	public function clear();

}

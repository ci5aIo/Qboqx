<?php

namespace hypeJunction\Cart;

use ElggFile;

class CartStorage implements StorageInterface {

	/**
	 * {@inheritdoc}
	 */
	public function get($guid) {

		if (!$guid) {
			$guid = _elgg_services()->session->getId();
		}

		$file = new ElggFile;
		$file->owner_guid = elgg_get_site_entity()->guid;
		$file->setFilename("/cart/$guid.json");
		if ($file->exists()) {
			$file->open('read');
			$json = $file->grabFile();
			$file->close();
		}

		$data = json_decode($json, true);
		return $data ? : [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function put($guid, $data) {

		if (!$guid) {
			$guid = _elgg_services()->session->getId();
		}

		$file = new ElggFile;
		$file->owner_guid = elgg_get_site_entity()->guid;
		$file->setFilename("/cart/$guid.json");
		$file->open('write');
		$file->write(json_encode($data));
		$file->close();
	}

	/**
	 * {@inheritdoc}
	 */
	public function invalidate($guid) {

		if (!$guid) {
			$guid = _elgg_services()->session->getId();
		}

		$file = new ElggFile;
		$file->owner_guid = elgg_get_site_entity()->guid;
		$file->setFilename("/cart/$guid.json");
		if ($file->exists()) {
			$file->delete();
		}
	}

}

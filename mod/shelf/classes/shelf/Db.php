<?php

namespace Shelf;

class Db implements DbInterface {

	/**
	 * {@inheritdoc}
	 */
	public function getEntity($guid = 0) {
		return get_entity($guid);
	}

}

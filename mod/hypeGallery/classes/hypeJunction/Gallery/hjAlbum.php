<?php

		if ($this->cover) {
			$cover_image = get_entity($this->cover);
		}
		if (!$cover_image) {
			$images = $this->getContainedFiles(array('limit' => 1));
			$cover_image = $images[0];
		}

		if (elgg_instanceof($cover_image)) {
			return $cover_image->getIconURL($size);
		} else {
			return parent::getIconURL($size);
		}
	}

}
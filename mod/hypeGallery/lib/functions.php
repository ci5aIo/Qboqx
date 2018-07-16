<?php
use ElggEntity;
use ElggFile;
use ElggObject;
					'text' => elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'link_class' => 'elgg-button elgg-button-delete elgg-button-delete-entity elgg-requires-confirmation',
					'data-guid' => $entity->guid,
					'priority' => 1000
				);
			}
			break;
	}

	if ($items) {
		foreach ($items as $name => $options) {
			$options['name'] = $name;
			elgg_register_menu_item('title', $options);
		}
	}

	return true;
}

/**
 * Register Dashboard title menu items
 *
 * @param string $dashboard Dashboard filter context
 * @return void
 */
function register_dashboard_title_buttons($dashboard = 'site') {

	switch ($dashboard) {

		case 'site' :
		case 'owner' :
			if (elgg_is_logged_in()) {
				$user = elgg_get_logged_in_user_entity();

				elgg_register_menu_item('title', array(
					'name' => 'create:album',
					'text' => elgg_echo('gallery:create:album'),
					'href' => "gallery/create/album/$user->guid",
					'link_class' => 'elgg-button elgg-button-action',
					'priority' => 100
				));
			}

			break;

		case 'group' :

			$group = elgg_get_page_owner_entity();

			if ($group->canWriteToContainer()) {

				elgg_register_menu_item('title', array(
					'name' => 'create:album',
					'text' => elgg_echo('gallery:create:album'),
					'href' => "gallery/create/album/$group->guid",
					'link_class' => 'elgg-button elgg-button-action',
					'priority' => 100
				));
			}
			break;
	}
}

/**
 * Process uploaded files
 *
 * @param string $name           Name of the HTML file input
 * @param string $subtype        Object subtype to be assigned to newly created objects
 * @param type   $guid           GUID of an existing object
 * @param type   $container_guid GUID of the container entity
 * @return array An associative array of original file names and guids (or false) of created object
 */
function process_file_upload($name, $subtype = hjAlbumImage::SUBTYPE, $guid = null, $container_guid = null) {

	// Normalize the $_FILES array
	if (is_array($_FILES[$name]['name'])) {
		$files = prepare_files_global($_FILES);
		$files = $files[$name];
	} else {
		$files = $_FILES[$name];
		$files = array($files);
	}

	foreach ($files as $file) {
		if (!is_array($file) || $file['error']) {
			continue;
		}

		$filehandler = new ElggFile($guid);
		$prefix = 'hjfile/';

		if ($guid) {
			$filename = $filehandler->getFilenameOnFilestore();
			if (file_exists($filename)) {
				unlink($filename);
			}
			$filestorename = $filehandler->getFilename();
			$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
		} else {
			$filehandler->subtype = $subtype;
			$filehandler->container_guid = $container_guid;
			$filestorename = elgg_strtolower(time() . $file['name']);
		}

		$filehandler->setFilename($prefix . $filestorename);
		$filehandler->title = $file['name'];

		$mime_type = ElggFile::detectMimeType($file['tmp_name'], $file['type']);

		// hack for Microsoft zipped formats
		$info = pathinfo($file['name']);
		$office_formats = array('docx', 'xlsx', 'pptx');
		if ($mime_type == "application/zip" && in_array($info['extension'], $office_formats)) {
			switch ($info['extension']) {
				case 'docx':
					$mime_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
					break;
				case 'xlsx':
					$mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
					break;
				case 'pptx':
					$mime_type = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
					break;
			}
		}

		// check for bad ppt detection
		if ($mime_type == "application/vnd.ms-office" && $info['extension'] == "ppt") {
			$mime_type = "application/vnd.ms-powerpoint";
		}

		$filehandler->setMimeType($mime_type);

		$filehandler->originalfilename = $file['name'];
		$filehandler->simpletype = get_simple_type($mime_type);
		$filehandler->filesize = $file['size'];

		$filehandler->open("write");
		$filehandler->close();

		move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

		if ($filehandler->save()) {

			// Generate icons for images
			if ($filehandler->simpletype == "image") {

				generate_entity_icons($filehandler);

				// the settings tell us not to keep the original image file, so downsizing to master
				if (elgg_get_plugin_setting('remove_original_files', 'hypeGallery')) {
					$icon_sizes = elgg_get_config('icon_sizes');
					$values = $icon_sizes['master'];
					$master = get_resized_image_from_existing_file(
							$filehandler->getFilenameOnFilestore(), $values['w'], $values['h'], $values['square'], 0, 0, 0, 0, $values['upscale']);
					$filehandler->open('write');
					$filehandler->write($master);
					$filehandler->close();
				}
			}

			$return[$file['name']] = $filehandler->getGUID();
		} else {
			$return[$file['name']] = false;
		}
	}

	return $return;
}

/**
 * Normalize files global
 *
 * @param array   $_files Global files array
 * @param boolean $top    Top level?
 * @return array Normalized files array
 */
function prepare_files_global(array $_files, $top = TRUE) {

	$files = array();
	foreach ($_files as $name => $file) {
		if ($top) {
			$sub_name = $file['name'];
		} else {
			$sub_name = $name;
		}
		if (is_array($sub_name)) {
			foreach (array_keys($sub_name) as $key) {
				$files[$name][$key] = array(
					'name' => $file['name'][$key],
					'type' => $file['type'][$key],
					'tmp_name' => $file['tmp_name'][$key],
					'error' => $file['error'][$key],
					'size' => $file['size'][$key],
				);
				$files[$name] = prepare_files_global($files[$name], FALSE);
			}
		} else {
			$files[$name] = $file;
		}
	}
	return $files;
}

/**
function generate_entity_icons($entity, $filehandler = null, $coords = null) {
 * @return array|false
 */
function get_image_tags($entity) {

	$tag_params = array(
		'type' => 'object',
		'subtype' => 'hjimagetag',
		'container_guid' => $entity->guid,
		'limit' => 0,
		'order_by' => 'e.time_created asc'
	);

	$tags = elgg_get_entities($tag_params);

	return $tags;
}

/**
}

/**
 * Helper function to convert exif GPS to proper coords
 * @link http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data
 *
 * @param array  $exifCoord Exif coords
 * @param string $hemi      Hemisphere
 * @return float
 */
function exif_getGps($exifCoord, $hemi) {

	$degrees = count($exifCoord) > 0 ? exif_gps2Num($exifCoord[0]) : 0;
	$minutes = count($exifCoord) > 1 ? exif_gps2Num($exifCoord[1]) : 0;
	$seconds = count($exifCoord) > 2 ? exif_gps2Num($exifCoord[2]) : 0;

	$flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

	return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
}

/**
 * Helper function to convert exif GPS to proper coords
 * @link http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data
 *
 * @param string $coordPart GPS coords
 * @return int
 */
function exif_gps2Num($coordPart) {

	$parts = explode('/', $coordPart);

	if (count($parts) <= 0) {
		return 0;
	}

	if (count($parts) == 1) {
		return $parts[0];
	}

	return floatval($parts[0]) / floatval($parts[1]);
}

/**
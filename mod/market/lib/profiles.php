<?php
function item_prepare_form_vars($category, $aspect, $item, $container_guid, $referrer) {
	$container_guid = elgg_get_page_owner_guid();
	$ts = strtotime("now");
	if ($item['category']){
		$category = $item['category'];
	}
	 
	switch ($category){
		case 'antiques':
			break;
		case 'appliances':
			switch ($aspect) {
				case 'kitchen':
					break;
				default:
					break;
			}
		case 'art':
			break;
		case 'auto':
			break;
		case 'baby':
			break;
		case 'luggage':
			break;
		case 'media':
			break;
		case 'clothes':
			break;
		case 'collectibles':
			break;
		case 'computers':
			break;
		case 'electronics':
			break;
		case 'furniture':
			break;
		case 'home':
			break;
		case 'jewelry':
			break;
		case 'instruments':
			break;
		case 'office':
			break;
		case 'sports':
			break;
		case 'tools':
			break;
		case 'video':
			break;
		default:
			$values = array(
				'entity'          => $item,
			    'guid'            => null,
				'container_guid'  => $container_guid,
				'owner_guid'      => elgg_get_logged_in_user_guid(),
				'title'           => null,
		        'description'     => null,
		        'manufacturer'    => null,
		        'model_no'        => null,
		        'brand'           => null,
		        'category'        => $category,
				'aspect'          => $aspect,
		        'collection'      => null,
				'item_number'     => null,
				'mfg_serial'      => null,
				'owner_serial'    => null,
		        'referrer'        => $referrer,
				'access_id'       => ACCESS_PUBLIC,
				'write_access_id' => ACCESS_PUBLIC,
				'tags'            => null,
			);
			break;
	}
	if ($item){
		foreach (array_keys($values) as $field) {
			if (isset($item->$field)) {
				$values[$field] = $item->$field;
			}
		}
	}

	return $values;
		
}
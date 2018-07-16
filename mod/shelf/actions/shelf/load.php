<?php

//use Shelf;

$params = new stdClass();

$input_keys = array_keys((array) elgg_get_config('input'));
$request_keys = array_keys((array) $_REQUEST);
$keys = array_unique(array_merge($input_keys, $request_keys));
foreach ($keys as $key) {
	if ($key) {
		$params->$key = get_input($key);
	}
}

$params->quantity = (int) $params->quantity;

if ($params->quantity < 0) {
	register_error(elgg_echo('cart:add_to_cart:error:negative_quantity'));
	forward(REFERRER);
}
if ($params->quantity == 0) { // Makes no sense to add zero items to the shelf.  Allows for quick load from entity menu.  
    $params->quantity = 1;
}

// from CartStorage.php>get()
		$guid = elgg_get_logged_in_user_guid();     $display .= '$guid: '.$guid.PHP_EOL;
//		$guid = _elgg_services()->session->getId();     $display .= '$guid: '.$guid.PHP_EOL;
		$file = new ElggFile;
		$file->owner_guid = $guid;
		$file->setFilename("shelf.json");
		if ($file->exists()) {
			$file->open('read');
			$json = $file->grabFile();
			$file->close();
		}

		$data = json_decode($json, true);                 //$display .= '$data: '.$data.PHP_EOL;
foreach($data as $key=>$value){                           $display .= '39 $data->$key: '.$key.'=>'.$value.PHP_EOL;
    foreach($value as $key1=>$value1){                    //$display .= '40 $value->$key1: '.$key1.'=>'.$value1.PHP_EOL;
        if ($key1 == 'guid'){
            $guids[] = $value1;
        }
    }
}
if (in_array($params->guid, $guids)){
    $skip = true;
}
if (!$skip){
// from CartStorage.php>put($guid, $data)
	$guid = elgg_get_logged_in_user_guid();
	$data[] = $params;
	$file = new ElggFile;
	$file->owner_guid = $guid;
	$file->setFilename("shelf.json");
	$file->open('write');
	$file->write(json_encode($data));
	$file->close();
	
	system_message(elgg_echo('shelf:add_to_shelf:success'));
}
else {
	system_message(elgg_echo('Item is already on the shelf.  Skipping.'));
}

eof:
//register_error($display);
<?php
$token               = get_input('__elgg_token');
$timestamp           = get_input('__elgg_ts');
$data                = get_input('jot');                                //$display .= print_r($jot, true).'<br>';
$subtype             = get_input('subtype', $data['aspect']);
$transaction         = $data[$subtype];
$object              = $data[$subtype]['asset'];
$aspect              = $data[$subtype]['aspect'];
$transaction_data    = $data[$subtype][$aspect];
if (empty($transaction['guid'])){
	$jot                 = new ElggObject();
	$jot->subtype        = $subtype;
	$jot->container_guid = elgg_get_logged_in_user_guid();
	$jot->owner_guid     = elgg_get_logged_in_user_guid();                  // owner of the transaction, not the item
}
else {
	$jot = get_entity($transaction['guid']);                       //
}
if (is_array($transaction_data)){
	foreach ($transaction_data as $key=>$value){
		if (is_array($value))   {$line_items[$key] = $value;}
		else                    {if (!empty($value)){$transaction_header[$key] = $value;}}
	}
}
// assume $transaction exists as an array
foreach ($transaction as $key=>$value){
	if (!is_array($value) && !empty($value)){
		$transaction_header[$key] = $value;
	}
}
if (!empty($jot->moment) &&
	$transaction_header['moment'] > $jot->moment &&                       //Translate the new moment before saving to $jot 
	$transaction_header['moment'] > $jot->latest_moment){
	$transaction_header['latest_moment'] = $transaction_header['moment'];// strtotime($transaction_header['moment']);
	unset($transaction_header['moment']);
}
//$transaction_header['moment'] =  strtotime($transaction_header['moment']);
	
foreach ($transaction_header as $key=>$value){
	$jot->$key = $value;                                             $display .= '40 $jot->'.$key.'=>'.$jot->$key.'<br>';
}
$jot->save();
                                                                   //$display .= print_r($transaction, true).'<br>';
                                                                   //$display .= print_r($line_item, true).'<br>';
                                                                   //$display .= print_r($jot, true).'<br>';                                                                   
//goto eof;
Switch ($subtype){
	case 'transfer':
		Switch ($aspect){
	    /****************************************
	     * $aspect = 'donate'                   *****************************************************************************
	     ****************************************/
			case 'donate':
				$title = 'Donation receipt';
				$jot->status = 'Donated';
				if (is_array($line_items)){
					foreach($line_items as $line=>$values){
						unset($line_item, $asset_guid, $asset, $from, $to);
						if (empty($values['guid'])){
							$line_item          = new ElggObject();
							$line_item->subtype = 'line_item';
						}
						else {
							$line_item         = get_entity($values['guid']);
						}
						foreach ($values as $key=>$value){                 
							if (!empty($value)) {$line_item->$key = $value;                                   $display .= '63 $line_item->'.$key.' =>'.$line_item->$key.'<br>';
							} 
						}
						$line_item->container_guid = $jot->getGUID();
						$line_item->sort_order     = $line + 1;
						$asset_guid                = $line_item->asset;
						$asset                     = get_entity($asset_guid);
						$line_item->title          = $asset->title;
						$owner_guid                = $asset->item_owner ?: $asset->owner_guid;
						//$asset->item_owner         = $jot->recipient;
						//$asset->owner_guid         = $jot->recipient;
						if ($jot->stay_connected == 'yes'){add_entity_relationship($owner_guid, 'following', $asset_guid);}
						if     (empty($from))             {$from = $owner_guid;}
						elseif ($from != $owner_guid)     {$from = 'various donors';}
						$line_item->save();						
					}	
				}
				break;
	    /****************************************
	     * $aspect = 'trash'                     *****************************************************************************
	     ****************************************/
			case 'trash':
				access_show_hidden_entities(true);
				$asset = get_entity($object);
				$from  = $asset->item_owner ?: $asset->owner_guid;
				switch ($jot->disposition){
					case 'remove':
						$message           = $asset->title. ' removed';
						$asset->last_owner = $asset->owner_guid;
						$asset->owner_guid = $asset->site_guid;
						$jot->recipient    = $asset->site_guid;
						break;
					case 'trash':
						$message           = $asset->title. ' moved to trash';
						$asset->disable($message, false);
						$jot->recipient    = 'trash heap';
						$jot->status       = 'Trash';
						break;
					case 'recover':
						$message           = $asset->title. ' recovered from trash';
						$asset->enable(true);
						$from              = $jot->recipient;
						$jot->recipient    = $asset->item_owner ?: $asset->owner_guid;
						break;
				}
				$title = $message;
				access_show_hidden_entities(false);
				break;
		}
		$asset->save();
		$jot->title = $title;
		$jot->from  = $from;
		break;
}
if ($jot->save()){                                                             //$display .= print_r($jot, true).'<br>';
	// Add to river
    // Something's wrong.  Causes "PHP Fatal error:  Call to a member function getObjectEntity() on null in /home/mupy4c83/public_html/quebx_test_elgg_2_3_4/mod/jot/views/default/river/object/jot/create.php on line 7"
    //elgg_create_river_item(['view'=>'river/object/jot/create','action_type'=>'create','subject_guid'=>elgg_get_logged_in_user_guid(),'object_guid'=>$jot->guid, 'message'=>$message]);
}                                                                              $display .= '114 $jot->status: '.$jot->status.'<br>';
eof:
register_error($display);
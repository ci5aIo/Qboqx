<?php
$jot      =       get_input('jot');                        
$items    =       get_input('item');                     $display .= '$items: '.$items.PHP_EOL;
$guid     = (int) get_input('guid');                     $display .= '$guid: '.$guid.'<br>';
$container_guid = get_input('container_guid');           $display .= '$container_guid: '.$container_guid.PHP_EOL;
$boqx_guid=       get_input('boqx_guid');
$origin   =       get_input('origin');
$aspect   =       get_input('aspect', $jot['aspect']);

$container = get_entity($container_guid);
$action    = $jot['do'];                                  //$display.='05 $action:'.$action.PHP_EOL;
														 $display .= '$aspect: '.$aspect.'<br>';
Switch ($aspect){
	case 'item':
		$relationship  = 'contents';
		break;
	case 'component':
		$relationship  = $aspect;
		break;
	case 'accessory':
		$relationship  = $aspect;
		break;
	case 'transfer':
	    $relationship  = $aspect;
	    break;
	default:
		$relationship  = $aspect;
		$subtype       = $aspect;
		break;
}           
if ($items){
    // Packing items off of the shelf
    //   Process line items
    //     Pivot Line Items
    foreach ($items as $key=>$values){                        $display .= '33 $key: '.$key.'<br>';
        foreach($values as $key_value=>$value){               //$display .= '34 $values: '.$key_value.'=>'.$value.'<br>';
        	$line_items[$key_value][$key] = $value;           //$display .= '35 $line_items[$key_value][$key]: '.$line_items[$key_value].'['.$line_items[$key_value][$key].']<br>';
          }
    }
    $selected = $items['selected'];
    foreach($selected as $key=>$value){                       $display .= '39 $selected: '.$key.'=>'.$value.'<br>';
    }
    $owner_guid = elgg_get_logged_in_user_guid();
    $file = new ElggFile;
    $file->owner_guid = $owner_guid;
    $file->setFilename("shelf.json");
    if ($file->exists()) {
    	$file->open('read');
    	$json = $file->grabFile();
    	$file->close();
    }
    
    $data = json_decode($json, true);                 //$display .= '$data: '.$data.PHP_EOL;
    
    if ($relationship == 'contents'){ 
        foreach($selected as $key=>$value){
            // Move selected to new container and new location
            $contents = get_entity($value);
            $contents['container_guid'] = $container_guid;
            $contents['location'] = $container_guid;
            $contents->save();
            foreach($contents as $key1=>$value1){                  $display .= '60 $values: '.$key1.'=>'.$value1.'<br>';
            }

            // Clear selected from shelf
            foreach($data as $key1=>$value1){                          //$display .= '30 $data: '.$key.'=>'.$values.'<br>';    
                foreach($value1 as $key2=>$value2){               //$display .= '31 $values: '.$key_value.'=>'.$value.'<br>';
                    if ($key2 == 'guid' && $value2 == $value){
                        unset($data[$key1]);
            	        continue;
            	    }
                }
            }
        }
    }
    if ($relationship == 'accessory' || $relationship == 'component'){    //$display.= '$relationship:'.$relationship.'<br>';
        foreach($selected as $key=>$value){
            // Associate selected
            $item = get_entity($value);
            // Create the relationship
            if (!check_entity_relationship($item->guid, $relationship, $container_guid)){
                add_entity_relationship($item->guid, $relationship, $container_guid);
            }
            // Clear selected from shelf
            foreach($data as $key1=>$value1){                          //$display .= '30 $data: '.$key.'=>'.$values.'<br>';    
                foreach($value1 as $key2=>$value2){               //$display .= '31 $values: '.$key_value.'=>'.$value.'<br>';
                    if ($key2 == 'guid' && $value2 == $value){
                        unset($data[$key1]);
            	        continue;
            	    }
                }
            }
        }
    }
    if ($relationship == 'transfer'){
        $responsibility       = array($jot['ownership_selections']);
        $terms                = $jot['exchange_selection'];     $display .= '$terms: '.$terms.'<br>';
        $delivery             = $jot['delivery_selection'];     $display .= '$delivery: '.$delivery.'<br>';
        if (!$responsibility || !$terms || !$delivery){
            register_error('Transfer failed:<br>Responsibility, Terms and Delivery selections are required.'); goto eof;
        }
        
        foreach($responsibility as $key1=>$value1){             $display .= ' $values: '.$key1.'=>'.$value1.'<br>';
            foreach($value1 as $key2=>$value2){                 $display .= '101 $values: '.$key2.'=>'.$value2.'<br>';
                switch ($value2){
                    case 'item':
                        $item_owner    = true;
                        break;
                    case 'process':
                        $process_owner = true;
                        break;
                    case 'service':
                        $service_owner = true;
                        break;
                    case 'steward':
                        $steward       = true;
                        break;
                }                    
            }
        }
        foreach($selected as $key=>$value){
            $item = get_entity($value);                         $display .= '$item->guid: '.$item->guid.'<br>';
            if ($item_owner){
/* The process for changing the entity owner needs to be considered carfully.
 * It impacts more than mere metadata
                $item->owner_guid = $container_guid;
 */
            }
            if ($process_owner){
                switch ($terms){
                    case 'agreement':                            $display .= 'Agreement terms require approval from receiver';
                        break;
                    case 'assignment':
                        $item->process_owner = $container_guid;
                        break;
                    case 'promise':
                        $item->process_owner = $container_guid;
                        break;
                    case 'donate':
                        $item->process_owner = $container_guid;
                        break;
                    case 'trade':
                        break;
                }
            }
            if ($service_owner){
                switch ($terms){
                    case 'agreement':                            $display .= 'Agreement terms require approval from receiver';
                        break;
                    case 'assignment':
                        $item->service_owner = $container_guid;
                        break;
                    case 'promise':
                        $item->service_owner = $container_guid;
                        break;
                    case 'donate':
                        $item->service_owner = $container_guid;
                        break;
                    case 'trade':
                        break;
                }
            }
            if ($steward){
                switch ($terms){
                    case 'agreement':                            $display .= 'Agreement terms require approval from receiver';
                        break;
                    case 'assignment':
                        $item->steward = $container_guid;
                        break;
                    case 'promise':
                        $item->steward = $container_guid;
                        break;
                    case 'donate':
                        $item->steward = $container_guid;
                        break;
                    case 'trade':
                        break;
                }
            }
            $item->save();    
        }
        // Clear selected from shelf
        foreach($data as $key1=>$value1){    
            foreach($value1 as $key2=>$value2){
                if ($key2 == 'guid' && $value2 == $value){
                    unset($data[$key1]);
        	        continue;
        	    }
            }
        }
    }
// Don't write shelf data
goto eof; 
    // Write shelf data
    	$file = new ElggFile;
    	$file->owner_guid = $owner_guid;
    	$file->setFilename("shelf.json");
    	$file->open('write');
    	$file->write(json_encode($data));
	$file->close();
}
if ($guid){                                           $display .= 'Packing a single item';
    // Packing a single item
    //if ($origin == 'shelf'){goto eof;}
    $contents = get_entity($guid);
    $subtype  = $contents->getSubtype();
    $boqx     = get_entity($boqx_guid);
    Switch ($aspect){
    	case 'content':
		    $contents->container_guid = $boqx_guid;
		    $contents->location = $boqx_guid;
		    if ($contents->save()){
		        system_message("$contents->title packed into $boqx->title");
		    } 
		    if (check_entity_relationship($guid, 'accessory', $boqx_guid)){
    			remove_entity_relationship($guid, 'accessory', $boqx_guid);
    		}
		    if (check_entity_relationship($guid, 'component', $boqx_guid)){
    			remove_entity_relationship($guid, 'component', $boqx_guid);
    		}
    		// Clean-up
    		remove_entity_relationship($guid, 'component', $guid);                 // can't be a component of itself
    		remove_entity_relationship($guid, 'accessory', $guid);                 // can't be an accessory of itself
			    	
		    break;
    	case 'accessory':
    		if ($contents->container_guid == $boqx_guid){ 
    			$contents->container_guid = $contents->owner_guid;
    			$contents->save();
    		}
		    if (check_entity_relationship($guid, 'component', $boqx_guid)){         // can't be both an accessory and a component
    			remove_entity_relationship($guid, 'component', $boqx_guid);
    		}
    		$relationship = 'accessory';
    		if (!check_entity_relationship($guid, $relationship, $boqx_guid)){
    			add_entity_relationship($guid, $relationship, $boqx_guid);
    		}
    		// Clean-up
    		remove_entity_relationship($guid, 'component', $guid);                 // can't be a component of itself
    		remove_entity_relationship($guid, 'accessory', $guid);                 // can't be an accessory of itself
/* Remove while in Development
	    	$options = array('action_type' => 'create', 
					         'subject_guid' => elgg_get_logged_in_user_guid(), 
					         'object_guid' => $guid,
					         'target_guid' => $boqx_guid,
					        );
			if     (elgg_view_exists("river/object/$subtype/create")){
				$options['view'] = "river/object/$subtype/create";
			}
			elseif (elgg_view_exists("river/object/$aspect/create")){
				$options['view'] = "river/object/$aspect/create";
			}
			else {
				$options['view'] = "river/object/jot/create";
			}
			elgg_create_river_item($options);*/
    	case 'component':
    		if ($contents->container_guid == $boqx_guid){ 
    			$contents->container_guid = $contents->owner_guid;
    			$contents->save();
    		} 
		    if (check_entity_relationship($guid, 'accessory', $boqx_guid)){        // can't be both a component and an accessory 
    			remove_entity_relationship($guid, 'accessory', $boqx_guid);
    		}
    		$relationship = 'component';
    		if (!check_entity_relationship($guid, $relationship, $boqx_guid)){
    			add_entity_relationship($guid, $relationship, $boqx_guid);
    		}
    		// Clean-up
    		remove_entity_relationship($guid, 'component', $guid);                 // can't be a component of itself
    		remove_entity_relationship($guid, 'accessory', $guid);                 // can't be an accessory of itself
/* Remove while in Development	    	
	    	$options = array('action_type' => 'create', 
					         'subject_guid' => elgg_get_logged_in_user_guid(), 
					         'object_guid' => $guid,
					         'target_guid' => $boqx_guid,
					        );
			if     (elgg_view_exists("river/object/$subtype/create")){
				$options['view'] = "river/object/$subtype/create";
			}
			elseif (elgg_view_exists("river/object/$aspect/create")){
				$options['view'] = "river/object/$aspect/create";
			}
			else {
				$options['view'] = "river/object/jot/create";
			}
			elgg_create_river_item($options);*/
    }
}
eof:
//register_error($display);
<?php
$jot      = get_input('jot');                        
$items    = get_input('item');                           //$display .= '$items:'.$items.PHP_EOL;
$action   = $jot['do'];                                  $display.='04 $action:'.$action.PHP_EOL;

// Process line items
//   Pivot Line Items
foreach ($items as $key=>$values){                        //$display .= '08 $key: '.$key.'<br>';
    foreach($values as $key_value=>$value){               //$display .= '09 $values: '.$key_value.'=>'.$value.'<br>';
    	$line_items[$key_value][$key] = $value;           //$display .= '10 $line_items['.$line_items[$key_value].']='.$line_items[$key_value][$key].'<br>';
      }
}

// Get current shelf data
	$owner_guid = elgg_get_logged_in_user_guid();        //$display .= '$guid: '.$guid.PHP_EOL;
	$file = new ElggFile;
	$file->owner_guid = $owner_guid;
	$file->setFilename("shelf.json");
	if ($file->exists()) {
		$file->open('read');
		$json = $file->grabFile();                       //$display .= '36 $json:'."$json<br>";
		$file->close();
	}

	$data = json_decode($json, true);

// Update shelf data	
	if ($action == 'update quantities'){	
		foreach ($line_items as $line_item){
		    foreach($line_item as $key=>$value){
		        if ($key == 'guid'){$guid = $value;}       
		        if ($key == 'quantity'){$qty = $value;}                //$display .= '32 $key: '.$key.'; $guid: '."$guid ".'; $qty:'."$qty<br>";
		        }
		   foreach($data as $data_key =>$line){                        //$display .= '$data_key: '."$data_key = $line<br>";
	            foreach($line as $key1=>$value1){                      //$display .= '$key1: '."$key1 = $value1<br>";
	                if ($key1 == 'guid' && $value1 == $guid){          //$display .= '36 $data_key: '."$data_key; ".'$guid: '.$guid.'; $qty: '.$qty.'<br>';
	                    $data[$data_key]['quantity'] = $qty;           //$display .= '$data['.$data_key.'][quantity]'."$data[$data_key]['quantity']<br>";
	                    if ($qty == 0){
	                        unset($data[$data_key]);
	                    }
    	            }
	            }
	        }                        
		}
	}
	if ($action == 'remove selected'){
    	foreach ($line_items as $line_item){
    	    foreach($line_item as $key=>$value){
    	        if ($key == 'selected'){
    	            $remove = $value;                                   $display .= '48 $key: '.$key.'; $remove:'."$remove<br>";
    	            continue;
    	        }
    	    }
		   foreach($data as $data_key =>$line){                        //$display .= '$data_key: '."$data_key = $line<br>";
	            foreach($line as $key1=>$value1){                      //$display .= '$key1: '."$key1 = $value1<br>";
	                if ($key1 == 'guid' && $value1 == $remove){        $display .= '54 $data_key: '."$data_key; ".'$guid: '.$guid.'; $qty: '.$qty.'<br>';
	                   unset($data[$data_key]);
	                }
	            }
	        }    
    	}     
	}
	if ($action == 'empty shelf'){
	    $data = NULL;
	}
	if ($action == 'transfer selected'){
    	foreach ($line_items as $key0=>$line_item){                  //$display .= '67 $key0: '.$key0.'<br>';
    	    foreach($line_item as $key=>$value){                     //$display .= '68 $key=>$value: '.$key.'=>'."$value<br>";
    	        if ($key == 'selected'){
    	            $guids[] = $value;                               //$display .= '70 $key=>$value: '.$key.'=>'."$value<br>";
    	        }
    	    }
    	}
    	foreach($guids as $key=>$guid){
    	    foreach($line_items as $key0=>$line_item){
    	        foreach($line_item as $key=>$value){
    	            if ($key == 'guid' && $value == $guid){
        	            $qtys[]  = $line_items[$key0]['quantity'];       //$display .= '76 $key0: '.$key0.'<br>';
        	        }
    	        }
    	    }
    	}
    	foreach($qtys as $key=>$qty){                             $display .= '80 $key=>$qty: '.$key.'=>'."$qty<br>";
    	}
    	foreach($guids as $key=>$guid){                              $display .= '82 $key=>$guid: '.$key.'=>'."$guid<br>";
		   foreach($data as $data_key =>$line){                      //$display .= '$data_key: '."$data_key = $line<br>";
	            foreach($line as $key1=>$value1){                    //$display .= '$key1: '."$key1 = $value1<br>";
	                if ($key1 == 'guid' && $value1 == $guid){        //$display .= '76 $data_key: '."$data_key; ".'$guid: '.$guid.'; $qty: '.$qty.'<br>';
	                   $transfer[] = $data[$data_key];
	                   continue;
	                }
	            }
	        }
    	 }
    	 foreach($transfer as $key0=>$line_item){
    	    foreach($line_item as $key1=>$value){                     //$display .= '97 $key1=>$value: '.$key1.'=>'."$value<br>";
    	        foreach($guids as $key2=>$guid){
    	            if ($key1 == 'guid' && $value == $guid){
    	                $transfer[$key0]['quantity'] = $qtys[$key2]; $display .= '100 $transfer[$key0][quantity]: '.$transfer[$key0]['quantity']."<br>";
    	            }
    	        }
    	    }
    	 }
//goto eof;
	    $transfer_file = new ElggFile;
    	$transfer_file->owner_guid = $owner_guid;
    	$transfer_file->setFilename("transfer_que.json");
    	$transfer_file->open('write');
    	$transfer_file->write(json_encode($transfer));
    	$transfer_file->close();
	
	    $transfer_form = 'jot/edit/0/ownership';
	    forward($transfer_form);
	}
// Write shelf data
	$file = new ElggFile;
	$file->owner_guid = $owner_guid;
	$file->setFilename("shelf.json");
	$file->open('write');
	$file->write(json_encode($data));
	$file->close();

eof:
//register_error($display);
<?php
$jots              =       get_input('jot');
$element_type      =       get_input('element_type');              //$display .= '$element_type: '.$element_type.'<br>';
$container_guid    = (int) get_input('container_guid');
$aspect            =       get_input('aspect', $element_type) ?: $jot['aspect'];       //$display .= '$aspect: '.$aspect.'<br>';
$owner_guid        = (int) get_input('owner_guid', elgg_get_logged_in_user_guid());

$container         =       get_entity($container_guid);                                //$display .= '$container_guid: '.$container_guid.'<br>';
$owner             =       get_entity($owner_guid);                                    //$display .= '$owner_guid: '.$owner_guid.'<br>';
$access_id         =       elgg_extract('access_id', $vars, get_default_access());

// Process jot items
//   Pivot line items                                                    
	  foreach ($jots as $key=>$values){                          $display .= '14 $key: '.$key.'<br>';
	    if (!is_array($values)){                                 $display .= '15 $values = '.$values.'<br>';
	        $values = array($values);}
	    foreach($values as $key_value=>$value){                  $display .= '17 $values: '.$key_value.'=>'.$value.'<br>';
	  		$line_items[$key_value][$key] = $value;              //$display .= '18 $line_items[$key_value][$key]: '.$line_items[$key_value].'['.$line_items[$key_value][$key].']<br>';
	  		if (is_array($value)){
	  		    foreach ($value as $this_key=>$this_value){      //$display .= '21 $value: '.$this_key.'=>'.$this_value.PHP_EOL;
	  		    }
	  		}
		  }
	  }
//   Remove blank line items
	  foreach ($line_items as $key=>$values){                     //$display .= '21 $line_item: '.$key.'<br>';
	  	foreach($values as $key_value=>$value){                  //$display .= '22 $values: '.$key_value.'=>'.$value.'<br>';
			if ($key_value == 'title' && $value == ''){
				unset($line_items[$key]);
			}													 //$display .= '30 $line_items[$key]: '.$key_value.'=>'.$line_items[$key][$key_value].'<br>';
		 }                                                         
	  }

	  foreach ($line_items as $key=>$values){                     //$display .= '29 $line_item: '.$key.'<br>';
	  	foreach($values as $key_value=>$value){                  //$display .= '30 $values: '.$key_value.'=>'.$value.'<br>';
																 $display .= '36 $line_items[$key]: '.$key_value.'=>'.$line_items[$key][$key_value].'<br>';
		 }                                                         
	  }
	  // Create the item
	  foreach ($line_items as $line_item){
	      $subtype      = $element_type;
          $relationship = $aspect;
	          
	      $element = new ElggObject();
	      $element->subtype        = $subtype;
	      $element->access_id      = $access_id;
	      $element->owner_guid     = $owner_guid;
	      $element->container_guid = $container_guid;
	      
    	  // Push $line_item[] to $element
    	  foreach ($line_item as $key => $value) {
    		  $element->$key = $value;
    	}
	      //for display only ...
	      foreach($element as $key=>$value){                     //$display .= '61 $element['.$key.'] = '.$value.'<br>';
	      }
	      
	  	if (!$element->save()) {
    		register_error('Action Failed: Error creating '.$element_type);
    	}
    	    
    	// Create the relationship
    	if ($aspect != 'contents'){ //Contents are determined by the container_guid of the entity.  All other elements use relationships
    	    add_entity_relationship($element->guid, $relationship, $container_guid);
    	}
		$options = array('action_type' => 'create', 
				         'subject_guid' => elgg_get_logged_in_user_guid(), 
				         'object_guid' => $element->guid,
				         'target_guid' => $container_guid,
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
		elgg_create_river_item($options);
	  } //END foreach ($line_items as $line_item)
	  
	  register_error($display);
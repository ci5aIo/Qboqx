<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$guid               = (int) get_input('guid');      
$jot                =       get_input('jot');                                      //$display .= '$jot: '.$jot.'<br>';
if (!$guid){$guid   = (int) get_input('marketpost');}                              //$display .= '$guid: '.$guid.'<br>';
$container_guid     = (int) get_input('container_guid');
$parent_guid        = (int) get_input('parent_guid');
$title              =       get_input('title');
if (!$title){$title =       get_input('markettitle');}                             
/*
$category    =       get_input('marketcategory');
$body        =       get_input('marketbody');
$tags        =       get_input('markettags');
*/
$access             =       get_input('access_id');
$item               =       get_input('item');
$apply              =       get_input('apply');
$upload_guids       =       get_input('upload_guids', array());                    $display .= '31 upload_guids[][] '.print_r($upload_guids, true).'<br>';
// guids of files uploaded using filedrop
$filedrop_guids     =       get_input('filedrop_guids', array());                  $display .= '32 filedrop_guids[] '.print_r($filedrop_guids, true).'<br>';
if (!is_array($filedrop_guids)) {
	$filedrop_guids = array();
}
if ($filedrop_guids & empty($upload_guids)){
	foreach($filedrop_guids as $image_guid){                                       $display .= '38 $filedrop_guids['.$image_guid.']<br>';
		$upload_guids[][] = $image_guid;
	}
}
$categories         =       get_input('categories');                                //$display .= 'category: '.$categories[0].'<br>';
$custom_categories  =       get_input('custom_categories');                         //$display .= 'custom category: '.$custom_categories.'<br>';
    if (strlen($custom_categories == 0)){unset($custom_categories);}
$album_guid         = (int) get_input('upload_to_album');                           //$display .= '$album_guid: '.$album_guid.'<br>'; 
$unlink             =       get_input('unlink');
$create_transfer    =       get_input('create_transfer');
$referrer           =       get_input('referrer') ?: elgg_get_site_url()."market/view/$guid";
if ($unlink       && !is_array($unlink)      ){$unlink = array();}
if (!$title)                                  {$title = $item['title'];}             //$display .= '$title: '.$title.'<br>';
$user_guid   =       elgg_get_logged_in_user_guid();
$album       =       get_entity($album_guid);
$albums      =       elgg_get_entities(array('subtype'=>array(hypeJunction\Gallery\hjAlbum::SUBTYPE)));
if (is_array($albums)){
    foreach($albums as $this_album){
        if (strtolower($this_album['title']) == 'default'){
            $default_album       = $this_album;
            continue;}}
}             
$album       = isset($album) ? $album : $default_album; 
        
//	    register_error(elgg_echo('$model_no: '.$model_no));
//	    register_error(elgg_echo('$serial_no: '.$serial_no));                       //$display = '$title: '.$title;
                                                                                    //$display = "item[title]: ".$title.'<br>';

elgg_make_sticky_form('market');

// Convert string of tags into a preformatted array
$tagarray = string_to_tag_array($tags);
$error = FALSE;

if (filter_var($title, FILTER_VALIDATE_URL)){                                         $display .= 'url entered<br>';
    // user entered a URL as a title
    $url = true;
    $source_array = hypeScraper()->resources->get ($title, null, true);
    $title        = $source_array['metatags']['og:title'];
    $title = !empty($title) ? $title : $source_array['title'];                 $display .= '$title: '.$title.'<br>';
    $description  = $source_array['metatags']['description'];
    $description  = !empty($description) ? $description : $source_array['description'];
    $tags = $source_array['metatags']['keywords'];
    $icon_url = $source_array['metatags']['og:image'];
    $icon_url = !empty($icon_url) ? $icon_url : $source_array['thumbnail_url'];
    if (empty($icon_url)){
        $thumbnails = $source_array['thumbnails'];
        if (!empty($thumbnails)){
            // scan thumbnails for a likely icon
            foreach($thumbnails as $key=>$image_url){
                if (filter_var($image_url, FILTER_VALIDATE_URL)){
                    if (empty($icon_url) && strpos($image_url, 'image')>0){
                        $icon_url = $thumbnails[$key];                         $display .= '$icon_url: '.$icon_url.'<br>';
                        continue;
                    }
                }
            }
        }
    }

    $item['title'] = $title;
    $item['description'] = $description;
    $change_category = true;
//    $item['tags'] = $tags; // Pulls in irrelevant labels and corrupts the Queb schema1
    
} 
// Make sure the title isn't blank
if (empty($title) && $url){
    register_error(elgg_echo("So sorry.<br>URL not recognized.<br>Please try a different URL."));
}
else if (empty($title)) {
	register_error(elgg_echo("Please enter a title for your item or paste a URL"));
	$error =  TRUE;
} 
else {
  if ($guid) {
    // editing an existing market item                                                //$display .= 'editing an existing item...<br>';
    $entity                  = get_entity($guid);
	$parent_item             = get_entity($parent_guid);
	if ($entity->category != $categories[0]){$change_category = true;}
  } 
  else {
    // creating a new market item                                                     //$display .= 'creating a new item...<br>';
    $entity                   = new ElggObject();
    $entity->subtype          = 'market';
    $entity->owner_guid       = $user_guid;
//    $change_category          = true;
//    $entity->container_guid   = $user_guid;
  }
//  if (!$error && elgg_instanceof($entity,'object','market') && $entity->canEdit()) {
  if (!$error && $entity->canEdit()) {
    // process custom actions first
	// allows for error checking, relationships etc.
	if (file_exists(dirname(__FILE__) . "/custom/{$entity->marketcategory}/{$level}.php")) {
	 include dirname(__FILE__) . "/custom/{$entity->marketcategory}/{$level}.php";
	}
  // 2015-03-07 - added by Scott to process 	tags
	// Extract the string of tags from the entity
	$tags = $tags ?: $entity->labels;
	
	// Kick the string of tags back to the entity as an array of individual tags
	$entity->labels = string_to_tag_array($tags);
// Process $item[]	
// TODO: Delete empty characteristic names or features saved from earlier 'apply'.  Currently, they are just not displayed.
	foreach ($item as $key => $value) {                                             //$display .= '48 $key: '.$key.'<br>$value: '.$value.'<br>$item[$key]: '.$item[$key].'<br>';
		if ($value == '0' || $value == ''){                                       //$display .= '50 $value: '.$value.'<br>';
			unset($item[$key]);
		}
	  //* 2015-03-07 - added by Scott to process 	tags
		if ($key == 'tags'){
			$item[$key] = string_to_tag_array($value);
		}
		
//      Remove blank characteristics
		if ($key == 'characteristic_names'){
			foreach ($item[$key] as $this_key=>$this_value){                      //$display .= '59 $this_key: '.$this_key.'<br>$this_value: '.$this_value.'<br>';
				if ($this_value == ''){                                           //$display .= '60 $this_value: '.$this_value.'<br>';
					unset($item['characteristic_names'][$this_key]);
					unset($item['characteristic_values'][$this_key]);
				}
			}													                  //$display .= '64 $item[$key]: '.$item[$key].'<br>';
			foreach ($item[$key] as $this_key=>$this_value){                      //$display .= '65 $this_key: '.$this_key.'<br>$this_value: '.$this_value.'<br>';			
			}
		}
		if ($key == 'this_characteristic_names'){
			foreach ($item[$key] as $this_key=>$this_value){
				if ($this_value == ''){                     
					unset($item['this_characteristic_names'][$this_key]);
					unset($item['this_characteristic_values'][$this_key]);
				}
			}												
		}
		if ($key == 'features' || $key == 'this_features'){
			foreach ($item[$key] as $this_key=>$this_value){
				if ($this_value == ''){                     
					unset($item[$key][$this_key]);
				}
			}												
		}
		if ($key == 'images'){
			foreach ($item[$key] as $this_key=>$this_value){
				if ($this_value == 0){                     
					unset($item[$key][$this_key]);
				}
			}												
		}
	}  //@END - foreach ($item as $key => $value)

	    // Now see if we have an icon
	if (!empty($icon_url)) {
    	$prefix = "icons/";
    	$filehandler                 = new ElggFile();
    	$filehandler->owner_guid     = elgg_get_logged_in_user_guid();
    	$filehandler->subtype        = hypeJunction\Gallery\hjAlbumImage::SUBTYPE;
    	$filehandler->container_guid = $album->guid;
    	$filehandler->save();
    	$filehandler->setFilename($prefix . $filehandler->guid. ".jpg");
    	$filehandler->open("write");
    	$filehandler->write(file_get_contents($icon_url));
    	$filehandler->close();                                                        $display .= 'filename: '.$filehandler->getFilenameOnFilestore().'<br>';
    	hypeJunction\Gallery\generate_entity_icons($filehandler);
    	$upload_guids[] = [$filehandler->guid];                                       $display .= '189 $upload_guids[0][0]: '.$upload_guids[0][0].'<br>';
  }
	if ($url){
	    $item_images  = $filehandler->guid;
	    $entity->icon = $filehandler->guid;
	}
	else {
	    $item_images = $item['images'];
	} 
	if (!is_array($item_images)){array($item_images);}
	$item_images = array_unique($item_images);
/*	foreach ($item_images as $this_key=>$guid){                                   //$display .= '$item_images['.$this_key.'] => '.$guid.'<br>';
	}
	foreach ($unlink as $this_key=>$guid){                                        //$display .= '$unlink['.$this_key.'] => '.$guid.'<br>';
	}
*/	if (isset($unlink)){
		  $remaining = array_diff ($item_images, $unlink);
	}
	else {$remaining = $item_images;}
	
	foreach ($remaining as $this_key=>$guid){                                     //$display .= '$remaining['.$this_key.'] => '.$guid.'<br>';
	}
	$item['images'] = $remaining;
	$images         = $remaining; 

// @TODO - Analyze how to create new album.  Permit 'New Album' option on input form.
	
	if ($upload_guids && !is_array($upload_guids)){$upload_guids = [$upload_guids];}
	if ($upload_guids) {                                                         $display .= '218 $upload_guids[0]: '.$upload_guids[0].'<br>';
		
		$metadata = elgg_get_metadata(array(
		'guid' => $album->guid,
		'limit' => 0
			));
	
		// for display only ...
		foreach($upload_guids as $key => $values){                             //$display .= '156 ['.$key.']=>'.$values.'<br>';
			foreach ($values as $this_key => $value){                          //$display .= '157 ['.$this_key.']=>'.$value.'<br>';
				$upload_file = get_entity($value);
				foreach ($upload_file as $key => $value){                      //$display .= '159 ['.$key.']=>'.$value.'<br>';
				}
			}
		}
		
		foreach($upload_guids as $key=>$value){                               $display .= '231 $key['.$key.'] = '.$value.'<br>';
			foreach($value as $this_key=>$upload_guid){	                      $display .= '232 $this_key=>$upload_guid: ['.$this_key.']=>'.$upload_guid.'<br>'; 
				$images[] = $upload_guid;                                     $display .= '233 $upload_guid:'.$upload_guid.'<br>';
				
				$image = get_entity($upload_guid);                            //$display .= '169 $image->guid:'.$image->guid.'<br>'; 
				if (!elgg_instanceof($image)) {                               //$display .= '170 !elgg_instanceof($image)<br>';
					continue;
				}
				$image->container_guid = $album->guid; // in case these were uploaded with filedrop
				
				if (!$image->title) {
					 $image->title = $image->originalfilename;
				}
				$image->access_id = $album->access_id;
	
				foreach ($metadata as $md) {
					$names[] = $md->name;
				}
		
				$names = array_unique($names);
		
				foreach ($names as $name) {
					$image->$name = $album->$name;
				}
		
				if ($image->save()) {                                                 $display .= '257 image saved: '.$upload_guid.'<br>';
				}
			}
		}			
	} //END - if ($upload_guids)

	if (!is_array($images)){$images = array($images);}
	$item['images'] = $images; 
	foreach ($item['images'] as $this_key=>$guid){                                   $display .= '265 $item_images['.$this_key.'] => '.$guid.'<br>';
	}                                                                                

// Find and remove empty fields that existed previously.
	if ($jot){
	    foreach ($jot as $key => $value){
	        if ($jot[$key] && !$item[$key]){
	            $item[$key] = NULL;                                           $display .= '$jot[$key] '.$jot[$key].'<br>$item[$key] '.$item[$key].'<br>';
	        }
	    }
	}
// Push processed $item[] to $entity
  	if (is_array($item)){                                                      $display .= '275 $item values received ...<br>';
	  	foreach($item as $key => $value){
	  	    $entity->$key = $value;                                            //$display .= '&nbsp;&nbsp;&nbsp;'.$key.'=>'.$value.'<br>';
			                                                                   //$display .= '$entity->'.$key.'=>'.$entity->$key.'<br>';
			// for display only ...
			if (is_array($value)){                                             //$display .= 'Array: '.$key.'<br>';
				foreach($value as $this_key=>$this_value){                     //$display .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;276 ['.$this_key.'] => '.$this_value.'<br>';
				}
				continue;
			}                                                                  //$display .= $key.'=>'.$value.'<br>';
	  	}
	  }

//@TODO Create a card for merchant, if it doesn't exist
	  elgg_load_library('elgg:market');
	  if (!elgg_entity_exists($entity->merchant) && !empty($entity->manufacturer)){
          $options      = array('subtype'=>'manufacturer', 'name'=>$entity->manufacturer);
    	  $manufacturer = quebx_get_group_by_title($options);
    	  
    	  if ($manufacturer){$display .= 'manufacturer exists<br>';}
    	  else {$display .= 'manufacturer does not exist<br>';}
	  }
	  
//	if (count($images) == 1){$entity['images'][0] = $images[0];}                //$display .= '222 '.$entity->images.'<br>'; 
	if ($entity->record_stage == 'newborn' || $entity->record_stage == ''){$entity->record_stage = 'defined';}
	if (isset($categories)){
	    $entity->category     = (int) $categories[0];                                   $display .= '302 $entity->category '.$entity->category.'<br>';
	    $parent_category_guid = (int) $categories[0];                                   $display .= '303 $parent_category_guid:'.$parent_category_guid.'<br>';
	}
	if (isset($custom_categories)){
	    // determine the parent category
    	if (empty($parent_category_guid)){                                              //goto eof;
    	        // do not create top-level custom category.  Throw error.
    	        register_error('Please select a parent for your new custom category'); 
    	        goto abort_custom_categories;
    	    }
    	$change_category = true;
    	if (is_string($custom_categories)) {
    		$ar = explode(":", $custom_categories);
    		foreach ($ar as $a){
    		    $ar2[] = trim($a);                                                      $display .= 'trim($a):'.trim($a).'<br>';
    		}
    	}
    	foreach ($ar2 as $key=>$a){                                                     $display .= '$key=>$a: '.$key.'=>'.$a.'<br>';
    		// find existing categories at this level
    	    $subcat_names = array();
    	    $subcategories = hypeJunction\Categories\get_subcategories($parent_category_guid);
    	    $hierarchy     = hypeJunction\Categories\get_hierarchy($parent_category_guid, true, true);
    	    foreach ($subcategories as $key2=>$subcategory){
    	        $subcat_names[$subcategory->guid]= strtolower($subcategory->title);
    	    }
    	    // test whether requested top-level custom category exists at this level
    	        // if requested top-level custom category exists at this level, use it
    	    if (in_array(strtolower($a), $subcat_names)){                               $display .= 'strtolower($a):"'.strtolower($a).'"<br>';
    	        $parent_category_guid = array_search(strtolower($a), $subcat_names);    $display .= '$parent_category_guid: '.$parent_category_guid.'<br>';
    	                                                                                $display .= '$key: '.$key.' count($ar2): '.(count($ar2)).'<br>';
    	        // if this is the last custom category, set the entity category to it   
    	        if ($key == (count($ar2)-1)){                                            
                                                                                        $display .= '335 Setting category to '.$parent_category_guid.'<br>';
    	            $entity->category = $parent_category_guid;
    	        }
    	    }
    	    // otherwise, create a new category
    	    else {                                                                      $display .= 'creating a new category...<br>';
    	        // forked from "hypeCategories\actions\save.php"
                $category_title = $a;
                $category_container_guid = (int) $parent_category_guid;
                $category_parent_guid = (int) $parent_category_guid;
                $level = (int) count($hierarchy)+1;
                $access_id = ACCESS_PRIVATE;
            
                $category = new ElggObject();
                $category->subtype = HYPECATEGORIES_SUBTYPE;
                $category->title = $category_title;
                $category->sort = $category_title;
                $category->level = $level;
                $category->owner_guid = elgg_get_logged_in_user_guid();
                $category->access_id = $access_id;
            
                // If access is limited to a group, make that group a container
                $access_collection = get_access_collection($access_id);
                $group_check = get_entity($access_collection->owner_guid);
                if ($group_check instanceof ElggGroup
                        && $category_parent_guid == get_item_categories($group_check->guid)) {
                    $category->container_guid = $group_check->guid;
                } else {
                    $category->container_guid = $category_container_guid;
                }
            
                $category->save();
                    // insert new category into the category priority hierarchy
                    $default_options = array(
                    	'types' => 'object',
                    	'subtypes' => HYPECATEGORIES_SUBTYPE,
                        'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer'),
                    	'limit' => 9999
                    );
                    $cats = elgg_get_entities_from_metadata($default_options);
                    
                        $tlc_options = $default_options;
                        unset($tlc_options['order_by_metadata']);
                        $tlc_options['wheres'] = array('container_guid = '.elgg_get_site_entity()->getGUID());
                        $tl_cats = aasort(elgg_get_entities_from_metadata($tlc_options), 'title');
                        
                    foreach ($cats as $cat){
                        $cat_guids[] = (int) $cat->guid;
                    }                                                                                  $display .= '$cat_guids: '.count($cat_guids).'<br>';
                    $parent = get_entity($parent_category_guid);
                    $this_cat    = $category['guid'];                                                   $display .= '$this_cat: '.$this_cat.'<br>';
                    $this_cat_entity = get_entity($this_cat);
                    if (strcmp(strtolower($parent->title), strtolower($this_cat_entity->title)) == 0 ){
                        register_error('Child cannot have the same name as its parent');
                        goto abort_custom_categories;
                    }
                    $parent_priority = array_search($parent_category_guid, $cat_guids);                $display .= '$parent_priority: '.$parent_priority.'<br>';
                    $offset = $parent_priority;
                    $child_options = $default_options;
                    $child_options['container_guid'] = $parent_category_guid;
                    $children = elgg_get_entities($child_options);
                    // place the new category after children, sorted alphabetically
                    if ($children){
                        foreach($children as $child){
                            if (strcmp(strtolower($child->title), strtolower($this_cat)) < 0 ){
                                $offset = $offset + 1;
                            }
                            if (strcmp(strtolower($child->title), strtolower($this_cat)) == 0 ){
                                $offset = 0;
                            }
                    //        echo $child->title.' : '. strcmp(strtolower($child->title), strtolower($this_cat)).'<br>';
                        }
                    }
                    if ($offset > 0){
                        $this_priority = $offset+1;                                                        $display .= '$this_priority: '.$this_priority.'<br>';
                        array_splice($cat_guids, $this_priority, 0, $this_cat);
                        $new_hierarchy = $cat_guids;
                        foreach ($new_hierarchy as $key1=>$c_guid){
                            $reprioritized_cat = get_entity($c_guid);
                            $reprioritized_cat->priority = $key1;
                            if ($reprioritized_cat->save()){                                                $display .= $reprioritized_cat->title.' saved <br>';
                            }
                            
                        }
                    }
//goto eof;
            
/*                if ($category_parent_guid) {
                    add_entity_relationship($category->guid, 'child', $category_parent_guid);
                }*/
    	        // if this is the last custom category, set the entity category to it   
    	        if ($key == (count($ar2)-1)){                                            
    	                                                                          $display .= '302 Setting category to '.$category->guid.'<br>'; 
    	            $entity->category = $category->guid;
    	        }
    	        continue;
    	    }
    	}                                                                   //$display .= 'action aborted';
    	
//    	goto eof;
	} //END if (!empty($custom_categories))
abort_custom_categories:
//goto eof;
	  
	if (empty($entity->icon)){$entity->icon = $images[0];}            //$display .= '$images[0]=>'.$images[0].'<br>';
	$entity->access_id      = $access;                                    $display .= '433 $entity->category '.$entity->category.'<br>';
	if (empty($entity->item_owner)){$entity->item_owner = $entity->owner_guid;}
  	if (!$entity->save()) {
  	    register_error(elgg_echo('market:save:failure'));
	    $error = TRUE;
  	}
  	else {$referrer = $entity->getURL();}
  } //@END - if (!$error && elgg_instanceof($entity,'object','market') && $entity->canEdit())

if (!$error){
	$entity_relationships   = get_entity_relationships($entity->guid);
    //set category hierarchy
    if ($change_category){                                                              $display .= 'category change<br>';
        foreach ($entity_relationships as $key=>$relationship){
            if ($relationship['relationship'] == HYPECATEGORIES_RELATIONSHIP){
                remove_entity_relationship($entity->guid, HYPECATEGORIES_RELATIONSHIP, $relationship_guid);
            }
        }
        $category_hierarchy     = hypeJunction\Categories\get_hierarchy($entity->category, true, true);
        foreach ($category_hierarchy as $category_guid){
            if (!check_entity_relationship($entity->guid, HYPECATEGORIES_RELATIONSHIP, $category_guid)){
                add_entity_relationship($entity->guid, HYPECATEGORIES_RELATIONSHIP, $category_guid);
            }
        }
    }

		
  $new_h = $h . '/' . $item[$level];
		
		// Success message
		//system_message(elgg_echo("market:posted"));
	
		if ($apply){
			// Stay on page
		  system_message("Changes applied");
		  goto eof;
		}
		elseif 	($create_transfer){
		    // Create a new receipt for this item
		    forward ("jot/edit/$entity->guid/receipt");
		}
/* Removing hierarchical profiling from the forwarding options
		elseif (elgg_view_exists("forms/market/edit/$category")) {
			if (elgg_view_exists("forms/market/edit".$new_h)) {
//		    forward("market/edit_more/{$entity->guid}{$new_h}");
		  }
//		  forward("market/edit_more/{$entity->guid}/$category");
		}*/
		 else {
		 	elgg_clear_sticky_form('market');
		  // Forward to the calling page
		  forward($referrer);
		}
	}
}
eof:
//register_error($display);
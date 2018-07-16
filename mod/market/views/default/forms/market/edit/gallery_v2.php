<!--View: market/views/default/forms/market/edit/gallery.php-->
<?php
$guid = $vars['guid'];                                                                $display .= '03 $guid: '.$guid.'<br>';
$entity = get_entity($guid);
$category = $entity->marketcategory;
$access_id = $entity['access_id']; 
$entity_owner = get_entity($entity->owner_guid) ?: elgg_get_logged_in_user_entity();

$edit_panel .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>";

$item_image_guids = $entity->images;  
if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
foreach ($item_image_guids as $key=>$image_guid){                               $display .= '83 ['.$key.'] => '.$image_guid.'<br>';
	if ($image_guid == ''){                     
		unset($item_image_guids[$key]);
	}
}

if (empty($item_image_guids)){$item_image_guids[]=$entity->guid;              $display .= '89 empty $item_image_guids<br>';
	}

   $item_images = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => array('image', 'hjalbumimage'),
		'guids'   => $item_image_guids,
   		'limit'   => 0,
	));

   $thumbnails = "<div class='edit_gallery' style='margin:0 auto;'>";
   $icon       = $entity->icon ?: $entity->guid;
   
				
	foreach ($item_image_guids as $key=>$image_guid){                      //$display .= 'Images<br>';
//	foreach ($item_images as $image){                      //$display .= 'Images<br>';
		
		$thumbnail = elgg_view('market/thumbnail', array('marketguid' => $image_guid,
														 'size'       => 'small',
													));
		if ($image_guid == $icon){
			$thumbnail   = "<span title='default image'>$thumbnail</span>";
			$input_checkbox = '';
			$input_radio = "<span title='default image'><input type='radio' checked='checked' name='item[icon]' value='$image_guid'></span>";
		}
		else {
			$input_checkbox = elgg_view('input/checkbox', array('id'   => $image_guid,
					                                            'name' => 'unlink[]', 
										 					    'value'=> $image_guid,
					                                            'default' => false,
															));
			$input_checkbox = "<span title='remove image from this gallery'>".$input_checkbox."</span>";
			$input_radio    = "<span title='set as default image'><input type='radio' name='item[icon]' value='$image_guid'></span>";
		}
		$input_images = elgg_view('input/hidden', array('name'=>'item[images][]', 'value' => $image_guid));
		//$thumbnail = "$input_checkbox.$input_radio.$thumbnail";
		$options = array(
			'text' => $thumbnail, 
			'class' => 'elgg-lightbox',
		    'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
            'href' => "market/viewimage/$image_guid");
		if ($image_guid == $icon){
	   		$options['style'] = 'background-color: #e5eecc';
	   		}		
		
		$thumbnail = elgg_view('output/url', $options);					
					
		$thumbnails .= $thumbnail.$input_images.$input_radio.$input_checkbox;
	}
$thumbnails = $thumbnails."</div>";

$edit_panel .=  "<div class='rTableRow'>
				<div class='rTableCell' style='padding:0px 5px'>$thumbnails</div>
			</div>";

$edit_panel .=  "		</div>
	</div>";
/*********************/
$add_panel .= "<div class='add_to_gallery' style='margin:0 auto;'>";
$add_panel .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
";
                                                                                             $display .= '130 $entity->owner_guid: '.$entity->owner_guid.'<br>';
$options = array('type'=>'object',
				 'subtype'=>'hjalbum',
				 'owner_guids'=> array($entity_owner->getGuid()),
			);
$albums = elgg_get_entities($options);

//	$upload_to[] = 'New Album';   //@TODO Allow upload to new album after analyze how to create new album
	foreach ($albums as $album){
		$upload_to[$album->guid] = $album->title;
		if (strtolower($album['title']) == 'default'){
	            $default_album       = $album;
	            continue;}
	}
	$upload_to_album = elgg_view('input/select', array('name'          =>'upload_to_album',
			                                           'options_values'=>$upload_to));      
$add_panel .=  "<div class='rTableRow'>
				<div class='rTableCell' style='padding:0px 5px'><b>Upload to album: </b>$upload_to_album</div>
			</div>";
$add_panel .=  "<div class='rTableRow'>
				<div class='rTableCell' style='padding:0px 5px'>".elgg_view('input/dropzone', array('name'            => 'upload_guids[]',
    																								'accept'          => "image/*",
    																								'max'             => 25,
    																								'multiple'        => true,
    																								'container_guid'  => $default_album->getGuid(),
    			                                                                                    'subtype'         => 'hjalbumimage',
		                                                                                            'action'          => 'action/gallery/upload/filedrop',
    	                                                                                            'default-message' => '<strong>Drop your images here</strong><br /><span>or click to select images from your computer</span>',
    																							))."</div>
			</div>";
$add_panel .=  "		</div>
	</div>";

if (empty($albums)){
	$add_panel .= 'No albums found';
}
else {$add_panel .= '<h4>Add from gallery ...</h4>';
	foreach ($albums as $album){
		$album_link = elgg_view('output/url', array('href' => "gallery/view/$album->guid",
				                                    'text' => $album->title,
		                                            'is_trusted' => true,
		                                        ));
		$add_panel .= "<h5>$album_link</h5><br>";
		$album_images = elgg_get_entities(array('type'=>'object',
					                            'subtypes' => array('image', 'hjalbumimage'),
						                        'container_guid'=>$album->guid,
				                                'limit'=>0,
		                                 ));
		foreach($album_images as $image){
			$input_checkbox = '';
			$thumbnail = elgg_view('market/thumbnail', 
							array('marketguid' => $image->guid,
							      'size'       => 'small',
							));
			
			$input_checkbox_options = array('id'      => $image->guid,
                                            'name'    => 'item[images][]', 
					 					    'value'   => $image->guid,
					                        'default' => false, 
										);
			$options = array(
				'text' => $thumbnail, 
				'class' => 'elgg-lightbox',
			    'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
	            'href' => "market/viewimage/$image->guid");
			
			if (in_array($image->guid, $item_image_guids)){
				$options['style'] = 'background-color: rgb(238, 204, 204);';
			}
			else {$input_checkbox   = elgg_view('input/checkbox', $input_checkbox_options);
			}			
						
		   	$thumbnail = "<span title = '$image->title'>".elgg_view('output/url', $options)."</span>";					

		   	$add_panel .= $thumbnail.$input_checkbox;
		}	
	}
}
$add_panel .= "</div>";

//echo $display;

echo "<div id='gallery_panel' class='elgg-head'>$add_panel</div>";

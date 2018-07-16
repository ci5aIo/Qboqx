<?php
$entity = $vars['entity'];
$body = elgg_view('gallery_field/images_list', array(
		'entity' => $entity,
	));	
//echo $body;
/*OPTION 2*******************************/
	$limit = get_input('limit', 20);
	$offset = get_input("offset-images-$entity->guid", 0);

	$options = array(
		'types' => 'object',
		'subtype' => 'image',
		'container_guids' => $entity->guid,
		'limit' => $limit,
		'offset' => $offset,
		'count' => true,
		'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer'),
		'list_type' => get_input('list_type', 'gallery'),
		'gallery_class' => 'gallery-photostream',
		'full_view' => false,
		'pagination' => true,
		'offset_key' => "offset-images-$entity->guid",
	);

	$body = elgg_list_entities_from_metadata($options);
	
	
//echo '<hr></hr>OPTION 2: Image IDs obtained from $entity->images.  This allows the image to be contained by the album in the gallery.';
$thumbnails ="<div class='scrollimage' style='max-width:600px;margin:0 auto;'>";

$item_image_guids = $entity->images;
if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}

	$filehandler = new ElggFile();
	$filehandler->setFilename("market/{$entity->guid}medium.jpg");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$item_image_guids[] = $entity->guid;
		} 
	}

foreach ($item_image_guids as $key=>$image_guid){                               $display .= '43 ['.$key.'] => '.$image_guid.'<br>';
	if ($image_guid == ''){                     
		unset($item_image_guids[$key]);
	}
}

foreach ($item_image_guids as $key=>$image_guid){                               $display .= '49 ['.$key.'] => '.$image_guid.'<br>';
	$thumbnail = elgg_view('market/thumbnail', array('marketguid' => $image_guid,
													 'size'       => 'medium',
													));
	$options = array(
		'text' => $thumbnail, 
		'class' => 'elgg-lightbox',
		'data-colorbox-opts' => '{"width":500, "height":525}',
	    'href' => "mod/market/viewimage.php?marketguid=$image_guid");
	
	if ($image_guid == $entity->icon || $image_guid == $entity->guid){
   		$options['style'] = 'background-color: #E9EAED';
   		}		
	
   	$thumbnail = elgg_view('output/url', $options);
	$thumbnails .= $thumbnail;
	}
$thumbnails = $thumbnails."</div>";

echo $thumbnails;	
//echo $display;



//	$comments = elgg_view_comments($entity);
/*
	echo '<hr>OPTION 2';
	echo '<div class="gallery-full">';
	echo "$summary$body$comments";
	echo '</div>';
*/
/*OPTION 3*******************************/
/*
div.scrollimage {
    background-color: #E9EAED;
    overflow: auto;
    white-space: nowrap;
}
*/
?>
<style>

div.scrollimage a {
    display: inline-block;
    color: white;
    padding: 14px;
    text-align: center;
    text-decoration: none;
}

div.scrollimage a:hover {
    background-color: #dedede;
}
</style>
<?php
   $images = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => array('hjAlbumImage', 'image'),
		'container_guid' => 1081,
//		'container_guid' => $entity->guid,
	));
   foreach ($images as $image){
   	  $image_ids[] = $image->guid;
   }
   
   if (empty($entity->icon)){$image_ids[] = $entity->guid;}
   $thumbnails ="<div class='scrollimage' style='max-width:600px;margin:0 auto;'>";
				
	foreach ($image_ids as $key=>$image_guid){                      //$display .= 'Images<br>';
		$thumbnail = elgg_view('market/thumbnail', array('marketguid' => $guid,
														 'size'       => 'medium',
														));

		$options = array(
			'text' => $thumbnail, 
			'class' => 'elgg-lightbox',
			'data-colorbox-opts' => '{"width":500, "height":525}',
		    'href' => "mod/market/viewimage.php?marketguid=$image_guid");
		
		if ($image_guid == $entity->icon || $guid == $entity->guid){
	   		$options['style'] = 'background-color: #E9EAED';
	   		}		
		
	   	$thumbnail = elgg_view('output/url', $options);					
/*		if ($image->guid == $entity->icon){
	   		$thumbnail = "<span style='background-color: #E9EAED'>$thumbnail</span>";
	   		}		
*/	   	
		$thumbnails .= $thumbnail;
	}
$thumbnails = $thumbnails."</div>";

//echo '<hr></hr>OPTION 3: Image IDs obtained from $image->container_guid where the container is the $entity';
//echo $thumbnails;
/*OPTION 4*******************************************//*
echo '<hr>OPTION 4';
   foreach ($images as $image){
//   	  $image->view = 'object/image/list'; 
	  $content = elgg_view_entity($image, array(
	  	  'full_view' => false,
		  'list_type' => 'gallery'
		));
	  echo $content;
   }
*/
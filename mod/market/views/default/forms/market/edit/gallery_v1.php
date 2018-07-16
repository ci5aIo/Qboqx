<!--View: market/views/default/forms/market/edit/gallery.php-->
<?php
$guid = $vars['guid'];
$entity = get_entity($guid);
$category = $entity->marketcategory;
$access_id = $entity['access_id']; 
$entity_owner = get_entity($entity->owner_guid);

?>
<style>
div.scrollimage {
    background-color: #E9EAED;
    overflow: auto;
    white-space: nowrap;
}
div.edit-panel {
	max-width:600px;
}
div.edit_gallery a {
    display: inline-block;
    text-align: center;
    padding: 4px;
    text-decoration: none;
}
div.add_to_gallery a {
    display: inline-block;
    text-align: center;
    padding: 4px;
    text-decoration: none;
}

span.hoverhelp {background: #F0F0EE;}
</style>
<script> 
$(document).ready(function(){
    $("#edit_tab").click(function(){
        $("#edit_panel").slideToggle("slow");
        $("#edit_tab").toggleClass("elgg-state-selected");
        
        $("#add_panel").slideUp("slow");
        $("#add_tab").removeClass("elgg-state-selected");
    });
    $("#add_tab").click(function(){
        $("#add_panel").slideToggle("slow");
        $("#add_tab").toggleClass("elgg-state-selected");
        
        $("#edit_panel").slideUp("slow");
        $("#edit_tab").removeClass("elgg-state-selected");
    });
});
</script>

<style> 
img.normal {
    height: auto;
}
img.big {
    height: 120px;
}
input.w100 {
    width: 100px;
}
input.w50 {
    width: 50px;
}
#add_panel{
	display: none;
}
#edit_tab, #add_tab {
    padding: 0px;
    text-align: left;
    background-color: #e5eecc;
    border: solid 1px #c3c3c3;
}

</style>
<?php
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
														 'size'       => 'medium',
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
			'data-colorbox-opts' => '{"width":500, "height":525}',
		    'href' => "mod/market/viewimage.php?marketguid=$image_guid");
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

$add_panel .= "<div class='add_to_gallery' style='margin:0 auto;'>";
$add_panel .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
";


$options = array('type'=>'object',
				 'subtype'=>'hjalbum',
				 'owner_guids'=> array($entity->owner_guid),
			);
$albums = elgg_get_entities($options);

//	$upload_to[] = 'New Album';   //Allow upload to new album after analyze how to create new album
	foreach ($albums as $album){
		$upload_to[$album->guid] = $album->title;
	}
	$upload_to_album = elgg_view('input/select', array('name'          =>'upload_to_album',
			                                           'options_values'=>$upload_to));

$add_panel .=  "<div class='rTableRow'>
				<div class='rTableCell' style='padding:0px 5px'><b>Upload to Album: </b>$upload_to_album</div>
			</div>";
$add_panel .=  "<div class='rTableRow'>
				<div class='rTableCell' style='padding:0px 5px'>".elgg_view('input/dropzone', array('name' => 'upload_guids[]',
																											  'accept' => "image/*",
																											  'max' => 25,
																											  'multiple' => true,
																											  'container_guid' => $guid,
						                                                                                      'subtype' => 'hjalbumimage',
																											))."</div>
			</div>";
$add_panel .=  "		</div>
	</div>";

if (empty($albums)){
	$add_panel .= 'No Albums Found';
}
else {$add_panel .= '<h4>Add from Gallery ...</h4>';
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
							      'size'       => 'medium',
							));
			
			$input_checkbox_options = array('id'      => $image->guid,
                                            'name'    => 'item[images][]', 
					 					    'value'   => $image->guid,
					                        'default' => false, 
										);
			$options = array(
				'text' => $thumbnail, 
				'class' => 'elgg-lightbox',
				'data-colorbox-opts' => '{"width":500, "height":525}',
			    'href' => "mod/market/viewimage.php?marketguid=$image->guid");
			
			if (in_array($image->guid, $item_image_guids)){
				$options['style'] = 'background-color: rgb(238, 204, 204);';
			}
			else {$input_checkbox   = elgg_view('input/checkbox', $input_checkbox_options);
			}			
						
		   	$thumbnail = "<span title = '$image->title'>".elgg_view('output/url', $options)."</span>";					

		   	$add_panel .= $thumbnail.$input_checkbox;
//			$add_panel .= '&nbsp;&nbsp'.$image->title.'<br>';
			
		}
	}
}
$add_panel .= "</div>";


echo "<div id='edit_tab' style='cursor:pointer'><h4>Edit Images in This Gallery</h4></div>";
echo "<div id='edit_panel' class='elgg-head edit-panel'>$edit_panel</div>";
echo "<div id='add_tab' style='cursor:pointer'><h4>Add Images to This Gallery</h4></div>";
echo "<div id='add_panel' class='elgg-head'>$add_panel</div>";


//echo $display;
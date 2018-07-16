<?php
$perspective = elgg_extract('perspective', $vars, 'page');

$file        = new ElggFile;
$file->owner_guid = elgg_get_logged_in_user_guid();
$file->setFilename("shelf.json");
if ($file->exists()) {
	$file->open('read');
	$json = $file->grabFile();
	$file->close();
}

$data = json_decode($json, true);

foreach($data as $key=>$contents){                        
    $qty = NULL; $entity = NULL;
    foreach($contents as $position=>$value){ 
        while (list($position, $value) = each($contents)){
            if ($position == 'guid'){                     
                $entity = get_entity($value);             
            }
            if ($position == 'quantity'){                 
                $qty = $value;                            
            }
        }
    }
    if (isset($subtype) && $entity->getSubtype() != $subtype){
    	continue;
    }
    $content .= elgg_view('shelf/arrange', ['quantity'=>$qty, 'entity'=>$entity, 'perspective'=>$perspective]);
}
Switch ($perspective){
	case 'page':
		$content = "<div id='shelf_list_items'>
		     <div class='rTable' style='width:100%'>
				<div class='rTableBody'>
				    <div class='rTableRow'>
						<div class='rTableCell' style='width:0'>
		<!-- stolen from 'file_tools\views\default\file_tools\js\site.php'
		                    <a id='shelf_select_all' class='float-alt' href='javascript:void(0);'>
		                    <span>" .  
		                        elgg_echo("file_tools:list:select_all") ."
		                    </span>
		                    <span class='hidden'>" . 
		                        elgg_echo("file_tools:list:deselect_all") . "
		                    </span></a>
		-->
		                </div>
						<div class='rTableCell' style='width:5%'>qty</div>
						<div class='rTableCell' style='width:95%'></div>
					</div>".
					$content.
		        "</div>
		    </div>
		</div>";
		break;
	case 'sidebar':
		$content = "<div id='shelf_list_items' class='shelf-list-items'>
		     <ul>
				$content
		    </ul>
		</div>";		
		break;
}
			
echo $content;
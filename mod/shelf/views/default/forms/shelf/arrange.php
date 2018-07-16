<?php
$data = $vars['data'];
$subtype = $vars['show'];

foreach($data as $key=>$contents){                        $display .= '$key=>$contents: '."$key -> $contents<br>";
    $qty = NULL; $entity = NULL;
    foreach($contents as $position=>$value){              //$display .= '$position->$value: '.$position.' -> '.$value.'<br>';
        while (list($position, $value) = each($contents)){//$display .= '$position->$value: '.$position.' -> '.$value.'<br>';
                                                          $display .= '$position: '.$position.'<br>';
            if ($position == 'guid'){                         //$display .= '$position->$value: '.$position.' -> '.$value.'<br>';
                $entity = get_entity($value);             $display .= 'title: '.$entity->title.'<br>';
            }
            if ($position == 'quantity'){                 $display .= '$position->$value: '.$position.' -> '.$value.'<br>';
                $qty = $value;                            $display .= '$qty: '.$qty.'<br>';
            }
        }
    }
    if (isset($subtype) && $entity->getSubtype() != $subtype){
    	continue;
    }
    $content .= elgg_view('shelf/arrange', array('quantity'=>$qty, 'entity'=>$entity));
}
$transfer_button = elgg_view('input/submit', array('name'=>'jot[do]', 'value' => 'transfer selected'));
$remove_button = elgg_view('input/submit', array('name'=>'jot[do]', 'value' => 'remove selected'));
$update_button = elgg_view('input/submit', array('name'=>'jot[do]', 'value' => 'update quantities'));
$clear_button = elgg_view('input/submit', array('name'=>'jot[do]', 'value' => elgg_echo('shelf:empty')));


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

echo $content;
echo $transfer_button.$remove_button.$update_button.$clear_button;
//echo $display;
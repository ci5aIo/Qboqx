<!-- View: shelf/views/default/forms/shelf/pick.php<br> -->
<?php
$item = $vars['item'];
$data = $vars['data'];
$subtype = $vars['show'];                                        $display .= '5 $subtype: '.$subtype.'<br>';
$aspect = $vars['aspect'];                                        
//goto eof;
Switch ($aspect){
    case 'media_input':
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
            $content .= elgg_view('shelf/pick', array('quantity'=>$qty, 'entity'=>$entity, 'target'=>$item));
        }
         echo $content;       
        break;
    default:
        if ($item->getSubtype() == 'market' || 
            $item->getSubtype() == 'item' || 
            $item->getSubtype() == 'place'){
            $view_page = 'pack';
        }
        if ($item->getType() == 'user'){
            $user = $item;
            $view_page = 'transfer';
        }
        ?>
        <script>
        $(document).ready(function(){
            $("#contents_add").click(function(){
                $("#contents_panel_1").slideToggle("slow");
                $("#contents_panel_2").slideToggle("slow");
                $("#user_name").addClass("elgg-state-selected");
                $("#contents_add").addClass("elgg-state-selected");
            });
            $("#components_add").click(function(){
                $("#components_panel").slideToggle("slow");
                $("#components_add").addClass("elgg-state-selected");
            });
            $("#accessories_add").click(function(){
                $("#accessories_panel").slideToggle("slow");
                $("#accessories_add").addClass("elgg-state-selected");
            });
        });
        </script>
        <style> 
        #contents_panel_1, #contents_panel_2, #components_panel, #accessories_panel{
        	display: none;
        }
        </style>
        <?php 
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
            $content .= elgg_view('shelf/pick', array('quantity'=>$qty, 'entity'=>$entity, 'target'=>$item));
        }
        if ($view_page == 'pack'){
            
            $action_value = 'pack selected';
            $hidden = elgg_view('input/hidden',array('name'=>'container_guid','value'=>$item->guid));
            $header = "<h2 class='elgg-heading-main'>Pack Items</h2><br><br>".
                      "Select items below to pack with this <b>$item->title</b> as...<p>"; 
            $aspect_options = array('Contents' => 'contents', 'Accessories'=>'accessory', 'Components'=>'component');
            $aspect_selector = elgg_view('input/radio', array('name'=>'jot[aspect]', 'value'=>'contents', 'options'=>$aspect_options, 'align'=>'horizontal'));;
        }
        
        if ($view_page == 'transfer'){
            
            $action_value = 'transfer selected';
            $hidden = elgg_view('input/hidden',array('name'=>'container_guid','value'=>$user->guid));
            $hidden .= elgg_view('input/hidden',array('name'=>'jot[aspect]','value'=>'transfer'));
            $ownership_options = array('name'=>'jot[ownership_selections]',
                                       'options'=>array('Item Owner'=>'item',
                                                        'Process Owner'=>'process',
                                                        'Service Owner'=>'service',
                                                        'Steward'=>'steward'),
                                       'align'=>'vertical');
            $ownership_selections = elgg_view('input/checkboxes', $ownership_options);
            $exchange_options = array('name'=>'jot[exchange_selection]',
                                       'options'=>array('Agreement'=>'agreement',
                                                        'Assignment'=>'assignment',
                                                        'Promise'=>'promise',
                                                        'Donate'=>'donate',
                                                        'Trade'=>'trade'),
                                       'align'=>'vertical');
            $exchange_selection = elgg_view('input/radio', $exchange_options);
            $delivery_options = array('name'=>'jot[delivery_selection]',
                                       'options'=>array('Assign'=>'assign',
                                                        'Ship'=>'ship',
                                                        'Pick Up'=>'pickup'),
                                       'align'=>'vertical');
            $delivery_selection = elgg_view('input/radio', $delivery_options);
            
            if ($entity->transfer_date){
                $transfer_date = $entity->transfer_date;
            }
            else {
                $transfer_date = date("Y-m-d", time());
            }
        
            $new_user_flag = elgg_view('input/checkbox', array('name'=>'new_user_flag'));
            $new_user_field = elgg_view('input/text', array('name'        => 'new_user',
                                                            'placeholder' => 'Not implemented yet',
                                                            'style'       => 'width:200px',));
            
            $header ="<h2 class='elgg-heading-main'>Transfer Responsibility</h2>
             <div class='rTable' style='width:400px'>
        		<div class='rTableBody'>
        		<div class='rTableRow'>
        				<div class='rTableCell' style='width:110px'>Transfer to</div>
        				<div class='rTableCell' style='width:290px'><a id='user_name' style='font-weight:normal'>$item->name</a></div>
        				<div id='contents_add' class='elgg-button-submit-element' style='cursor:pointer;height:14px;width:30px'>add...</div>
        		</div>	
        		<div class='rTableRow'>
        				<div class='rTableCell' style='width:110px'><div id='contents_panel_1'>New User</div></div>
        				<div class='rTableCell' style='width:290px'><div id='contents_panel_2'>$new_user_field</div></div>
        		</div>
        		<div class='rTableRow'>
        			<div class='rTableCell' style='width:110px'>Transfer Date</div>
        			<div class='rTableCell' style='width:290px'>".elgg_view('input/date', array(
        																'name' => 'jot[transfer_date]',
        																'value' => $transfer_date,
        			                                                    'style' =>'width:90px',
        															))."
        			</div>
        		</div>
        		<div class='rTableRow'>
        				<div class='rTableCell' style='width:110px'>Responsibility*</div>
        				<div class='rTableCell' style='width:290px'>$ownership_selections</div>
        			</div>
        			<div class='rTableRow'>
        				<div class='rTableCell' style='width:110px'>Terms*</div>
        				<div class='rTableCell' style='width:290px'>$exchange_selection</div>
        			</div>
        			<div class='rTableRow'>
        				<div class='rTableCell' style='width:110px'>Delivery*</div>
        				<div class='rTableCell' style='width:290px'>$delivery_selection</div>
        			</div>
        		</div>
        	</div>";
        }
        $action_button = elgg_view('input/submit', array('name'=>'jot[do]', 'value' => $action_value));
        $arrange_button = '<a href = "'.elgg_get_site_url(). 'shelf">'.
                    elgg_view("input/button", array("class" => "elgg-button-submit", "value" => 'arrange ...',)).
                    '</a>';
        
        $content = "Items to transfer<div class='rTable' style='width:100%'>
        		<div class='rTableBody'>".
        			$content.
                "</div>
            </div>";

        echo $hidden.
             $header.
             $aspect_selector.'<br>'.
             $content.
             $action_button.
             $arrange_button;
        break;
}
eof:
//echo $display;
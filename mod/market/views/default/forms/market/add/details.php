<?php
$panel = elgg_extract('panel', $vars);

/******************************/
Switch ($panel){
    case 'contents':
        //Contents Add
        //@EDIT - 2016-10-06 - SAJ - Cannot get the checkbox to work for multiple new line items.
            $create = elgg_view('input/checkbox', array('name'    => 'jot[retain_line_label][]',
                                                        'checked' => 'checked',
                							            'default' => false,
                				        			   ));
            $create .= 'create new';
            $add_sibling_button = elgg_view('output/url',['text'=>'+',
            		                                      'title'=>'add',
            		                                      'class'=>'elgg-button-submit-element contents_add_sibling',
            		                                      'style'=>'cursor:pointer;height:14px;width:30px']);
            $add_child_button = elgg_view('output/url',['text'=>'+',
            		                                      'title'=>'add inside',
            		                                      'class'=>'elgg-button-submit-element contents_add_child',
            		                                      'style'=>'cursor:pointer;height:14px;width:30px']);
            $panel_contents .= elgg_view('input/hidden', array('name'=>'element_type'  , 'value'=>$vars['element_type']));
            $panel_contents .= elgg_view('input/hidden', array('name'=>'aspect'        , 'value'=>$vars['aspect']));
            $panel_contents .= elgg_view('input/hidden', array('name'=>'container_guid', 'value'=>$vars['container_guid']));
            
            $panel_contents .="
        	     <div class='rTable' style='width:100%'>
            		<div class='rTableBody'>
        	            <div class='rTableRow'>";
            
            $panel_contents .="<div class='rTableCell' style='white-space:nowrap;'>$add_sibling_button$add_child_button</div>
            				<div class='rTableCell' style='width:100%'>".
            			      elgg_view('input/text', array(
            					'name' => 'jot[title][]',
            					'class' => 'last_content_item',
            			      	 'placeholder'=>'Item Name',
            				))."
        		            </div>";
            $panel_contents .="
                        </div>";
            
            $panel_contents .= '<div class="new_content_item"></div>';
            $panel_contents .= "</div>
            			</div>";
            
            $panel_contents .= elgg_view('input/submit', array('value'=>'add contents',
        	                                                 'class' => 'elgg-button-submit-element',
                                                             'style' => 'width:75px',));
            // Contents 
            $panel_contents .= "
            <div id ='contents_store' style='visibility:hidden'>";
            $panel_contents .= "
            	<div class='content_item'>
            	    <div class='rTableRow'>
        				<div class='rTableCell' style='width:250px'>".
        			      elgg_view('input/text', array(
        					'name'        => 'jot[title][]',
        					'class'       => 'last_content_item',
        		      	    'placeholder' => 'Item Name',
        				))."</div>";
            $panel_contents .= "
            		</div>
            	</div>";
            $panel_contents .= "
        	</div>";
            
            break;
    case 'components':
        /******************************/
        //Components Add
            $create = elgg_view('input/checkbox', array('name'    => 'jot[retain_line_label][]',
                                                        'checked' => 'checked',
                							            'default' => false,
                				        			   ));
            $create .= 'create new';
            $panel_contents .= elgg_view('input/hidden', array('name'=>'element_type'  , 'value'=>$vars['element_type']));
            $panel_contents .= elgg_view('input/hidden', array('name'=>'aspect'        , 'value'=>$vars['aspect']));
            $panel_contents .= elgg_view('input/hidden', array('name'=>'container_guid', 'value'=>$vars['container_guid']));
            
            $panel_contents .="
        	     <div class='rTable' style='width:100%'>
            		<div class='rTableBody'>
        	            <div class='rTableRow'>";
            
            $panel_contents .="
            				<div class='rTableCell' style='width:350px'>".
            			      elgg_view('input/text', array(
            					'name'        => 'jot[title][]',
            					'class'       => 'last_component_item',
            			      	'placeholder' => 'Component Name',
            				))."
        		            </div>";
            $panel_contents .="
                        </div>";
            
            $panel_contents .= '<div class="new_component_item"></div>';
            $panel_contents .= "</div>
            			</div>";
            
            $panel_contents .= elgg_view('input/submit', array('value'=>'add components',
        	                                                 'class' => 'elgg-button-submit-element',
                                                             'style' => 'width:90px',));
            // Contents 
            $panel_contents .= "
            <div id ='components_store' style='visibility:hidden'>";
            $panel_contents .= "
            	<div class='component_item'>
            	    <div class='rTableRow'>
        				<div class='rTableCell' style='width:250px'>".
        			      elgg_view('input/text', array(
        					'name'        => 'jot[title][]',
        					'class'       => 'last_component_item',
        		      	    'placeholder' => 'Component Name',
        				))."</div>";
            $panel_contents .= "
            		</div>
            	</div>";
            $panel_contents .= "
        	</div>";
            break;
            
    case 'accessories':
        
        /******************************/
        //Accessories Add
            $create = elgg_view('input/checkbox', array('name'    => 'jot[retain_line_label][]',
                                                        'checked' => 'checked',
                							            'default' => false,
                				        			   ));
            $create .= 'create new';
            $panel_contents .= elgg_view('input/hidden', array('name'=>'element_type'  , 'value'=>$vars['element_type']));
            $panel_contents .= elgg_view('input/hidden', array('name'=>'aspect'        , 'value'=>$vars['aspect']));
            $panel_contents .= elgg_view('input/hidden', array('name'=>'container_guid', 'value'=>$vars['container_guid']));
            
            $panel_contents .="
        	     <div class='rTable' style='width:100%'>
            		<div class='rTableBody'>
        	            <div class='rTableRow'>";
            
            $panel_contents .="
            				<div class='rTableCell' style='width:350px'>".
            			      elgg_view('input/text', array(
            					'name'        => 'jot[title][]',
            					'class'       => 'last_accessory_item',
            			      	'placeholder' => 'Accessory Name',
            				))."
        		            </div>";
            $panel_contents .="
                        </div>";
            
            $panel_contents .= '<div class="new_accessory_item"></div>';
            $panel_contents .= "</div>
            			</div>";
            
            $panel_contents .= elgg_view('input/submit', array('value'=>'add accessories',
        	                                                 'class' => 'elgg-button-submit-element',
                                                             'style' => 'width:90px',));
            // Contents 
            $panel_contents .= "
            <div id ='accessories_store' style='visibility:hidden'>";
            $panel_contents .= "
            	<div class='accessory_item'>
            	    <div class='rTableRow'>
        				<div class='rTableCell' style='width:250px'>".
        			      elgg_view('input/text', array(
        					'name'        => 'jot[title][]',
        					'class'       => 'last_accessory_item',
        		      	    'placeholder' => 'Accessory Name',
        				))."</div>";
            $panel_contents .= "
            		</div>
            	</div>";
            $panel_contents .= "
        	</div>";
            break;
}
/*****************************/

echo $panel_contents;
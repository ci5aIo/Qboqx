<?php
/**
 * experiences view body
 *
 * @package ElggPages	
 */
// Receive input
$guid        = elgg_extract('guid'             , $vars, get_input('guid'));
$owner_guid  = elgg_extract('owner_guid'       , $vars, get_input('owner_guid'));
$container_guid = elgg_extract('container_guid', $vars, get_input('container_guid'));
$section     = elgg_extract('section'          , $vars);
$action      = elgg_extract('action'           , $vars);
$tabs        = elgg_extract('tabs'             , $vars);
// Define variables
$jot         = get_entity($guid);
$aspect      = 'experience';
$stage_field = elgg_view('input/radio' , array(
                      "name"    => "jot[stage]",
                      "align"   => "horizontal",
					  "value"   => $jot->state ?: 5,
					  "options" => array("Planning" => 1, "In Process" => 2, "Cancelled" => 3, "Postponed" => 4, "Complete" => 5),
				      "default" => 5,
					 ));
Switch ($action){
/****************************************
 * $action = 'add'                       *****************************************************************************
 ****************************************/        
    case 'add':
        
        // Build sections        
        Switch ($section){
            /****************************************
             * $section = 'main'                     *****************************************************************
             ****************************************/
            case 'main':
                $title_field        = elgg_view("input/text"    , array("name" => "jot[title]"      , 'placeholder' => 'Name of this new experience', 'style' => 'width:495px'));
            	$start_date_field   = elgg_view("input/date"    , array("name" => "jot[start_date]" , 'class'       => 'tiny date',));
            	$end_date_field     = elgg_view("input/date"    , array("name" => "jot[end_date]"   , 'class'       => 'tiny date',));
            	$description_field  = elgg_view("input/longtext", array("name" => "jot[description]", 'placeholder' => 'What happened?',));
                $hidden            .= elgg_view('input/hidden'  , array('name' => 'jot[owner_guid]' , 'value' => $owner_guid));
            	$hidden            .= elgg_view('input/hidden'  , array('name' => 'jot[container_guid]' , 'value' => $container_guid));
            	$hidden            .= elgg_view('input/hidden'  , array('name' => 'jot[aspect]'     , 'value' => $aspect));
                $hidden            .= elgg_view('input/hidden'  , array('name' => 'jot[subtype]'    , 'value' => $aspect));
                
        		$form_body .= "
        	    <div class='rTable' style='width:550px'>
        			<div class='rTableBody'>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$title_field".elgg_view('input/submit',	 array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element'))."</div>
        				</div>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$description_field</div>
        				</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>$tabs</div>
            			</div>
            		</div>
        		</div>";
        		$form_body .= $hidden;
        		
        		break;
            /****************************************
             * $section = 'schedule'                 *****************************************************************
             ****************************************/
            case 'schedule':  
                $form_body = "
        		<div class='rTable' style='width:100%'>
        			<div class='rTableBody'>
        			    <div class='rTableRow'>
        					<div class='rTableCell' style='width:0; padding: 0px 5px'>Stage</div>
        					<div class='rTableCell' style='width:100%; padding: 0px 0px'>$stage_field</div>
        				</div>
        			</div>
        		</div>";
                /******************************/
                // Interval List
                $interval_add_button = "<a title='add interval' class='elgg-button-submit-element add-schedule-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $hidden['jot[schedule][asset]']          = $asset->guid;
                $hidden['jot[schedule][container_guid]'] = $task_guid;
                // Interval Add                
                foreach($hidden as $field=>$value){
                    $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                }
                    unset($start_date, $end_date, $activity);
                    $start_date  = elgg_view('input/date', array('name'=> 'jot[schedule][start_date][]', 'placeholder'=>'start date'));
                    $end_date    = elgg_view('input/date', array('name'=> 'jot[schedule][end_date][]', 'placeholder'=>'end date'));
                    $activity    = elgg_view('input/text', array('name'=> 'jot[schedule][title][]', 'class'=> 'last_schedule_item','placeholder' => 'Activity Name',));
                    $form_body  .= "$interval_add_button <span id='interval_button_label'>add interval</span>";
                    $form_body  .= "
                    <div class='rTable' style='width:100%'>
                		<div id='sortable_interval' class='rTableBody'>
                			<div class='rTableRow pin'>
                				<div class='rTableCell' style='width:15%'>Start</div>
                				<div class='rTableCell' style='width:15%'>End</div>
                				<div class='rTableCell' style='width:65%'>Activity</div>
                				<div class='rTableCell' style='width:5%'></div>
                			</div>
                    	    <div class='rTableRow' style='cursor:move'>
                				<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
                				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
                				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
                				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                    $form_body  .= "<div class='new_schedule_item'></div>";
                    $form_body  .= "
                    </div>
                        </div>";
                    $form_body  .= "
                    <div style='visibility:hidden'>
                    	<div class='schedule_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                				<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
                				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
                				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
                				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
            /****************************************
             * $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "Things used during this experience
                	          <span title='Things in Quebx'>";
                $form_body .= elgg_view('input/assetpicker', array('name'=>'jot[assets]', 'values'=>array($container_guid)));
                $form_body .= "</span>";
                $form_body .= "$item_add_button <span id='other_button_label'>add other things</span>
                <div class='rTable' style='width:100%'>
                    <div class='rTableBody'>";
                $form_body .= "<div class='new_other_item'></div>";
                $form_body .= "
            	    </div>
                </div>";
                $form_body .= "
                <div style='visibility:hidden'>
                	<div class='other_item'>
                	    <div class='rTableRow'>
                			<div class='rTableCell' style='width:100%'>".
            			      elgg_view('input/text', array(
            					'name'        => 'jot[ghost_assets][title][]',
            					'class'       => 'last_other_item',
            		      	    'placeholder' => 'New Item Name',
            				))."
            				</div>
            				<div class='rTableCell' style='text-align:right;padding:0px'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
            /****************************************
             * $section = 'steps'                    *****************************************************************
             ****************************************/
            case 'steps':
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                
                //Tasks Add
                $form_body .= elgg_view('input/hidden', array('name'=>'element_type'        , 'value'=>$vars['element_type']));
                $form_body .= elgg_view('input/hidden', array('name'=>'aspect'              , 'value'=>$vars['aspect']));
                $form_body .= elgg_view('input/hidden', array('name'=>'jot[guid]'          , 'value'=>$task_guid));
                $form_body .= elgg_view('input/hidden', array('name'=>'jot[container_guid]', 'value'=>$vars['container_guid']));
                $form_body .= elgg_view('input/hidden', array('name'=>'jot[parent_guid]'   , 'value'=>$vars['parent_guid']));
                $form_body .= 'Describe your path<br>';
                $form_body .= "$step_add_button <span id='step_button_label'>add step</span>
                <div class='rTable' style='width:100%'>
            		<div class='rTableBody'>
                    <ol style='list-style:decimal;padding-left:20px'>";
                $form_body .= "<div class='new_step_item'></div>";
                $form_body .= "
            	    </ol>
            	    </div>
                </div>";
                $form_body .= "
                <div id ='store' style='visibility:hidden'>
                	<div class='step_item'>
                	    <div class='rTableRow' style='cursor:move'>
                			<div class='rTableCell' style='width:100%;padding:0px'>
            	            <li>".
            			      elgg_view('input/text', array(
            					'name'        => 'jot[process_step][title][]',
            					'class'       => 'last_step_item',
            		      	    'placeholder' => 'Step Name',
            				))."
        				    </li>
            				</div>
            				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
            /****************************************
             * $section = 'documents'                *****************************************************************
             ****************************************/
            case 'documents':
                $form_body = elgg_view('input/dropzone', array('name' => 'jot[documents]',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));
                break;
            case 'gallery':
                $form_body = elgg_view('input/dropzone', array('name' => 'jot[images]',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'image',));
                break;
            default:
                break;
        }
        break;        
/****************************************
 * $action = 'edit'                      *****************************************************************************
 ****************************************/
    case 'edit':
        if ($jot->state <= 3){
            $title_field        = elgg_view("input/text"    , array("name" => "jot[title]"      ,"value" => $jot->title      , 'placeholder' => 'Name of this experience',));
        	$start_date_field   = elgg_view("input/date"    , array("name" => "jot[start_date]" ,"value" => $jot->start_date , 'class'       => 'tiny date',));
        	$end_date_field     = elgg_view("input/date"    , array("name" => "jot[end_date]"   ,"value" => $jot->end_date   , 'class'       => 'tiny date',));
        	$description_field  = elgg_view("input/longtext", array("name" => "jot[description]","value" => $jot->description, 'placeholder' => 'What happened?',));
        }
        else {
            $title_field        = $title;	
            $title_field       .= elgg_view("input/hidden", array("name" => "jot[title]"      ,"value" => $jot->title,));
            $start_date_field   = $jot->start_date;
        	$start_date_field  .= elgg_view("input/hidden", array("name" => "jot[start_date]" ,"value" => $jot->start_date,'class' => 'tiny date',));
        	$end_date_field     = $jot->end_date;
        	$end_date_field    .= elgg_view("input/hidden", array("name" => "jot[end_date]"   ,"value" => $jot->end_date  ,'class' => 'tiny date',));
        	$description_field  = $jot->description;
        	$description_field .= elgg_view("input/hidden", array("name" => "jot[description]","value" => $jot->description));
        }
        // Build sections        
        Switch ($section){
            /****************************************
             * $section = 'main'                     *****************************************************************
             ****************************************/
            case 'main':
               
        		$form_body .= "
        	    <div class='rTable' style='width:550px'>
        			<div class='rTableBody'>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$title_field</div>
        				</div>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$description_field</div>
        				</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>$tabs</div>
            			</div>
            		</div>
        		</div>";
        		
        		break;
            /****************************************
             * $section = 'schedule'                 *****************************************************************
             ****************************************/
            case 'schedule':
                
                $schedule = elgg_get_entities_from_metadata(array(
                	'type' => 'object',
                	'subtypes' => array('schedule_item'),
                	'limit' => false,
                	'metadata_name_value_pairs' => array(
                		'name' => 'parent_guid',
                		'value' => $guid
                	),
                	'order_by_metadata'    => array(
                	        'name' => 'sort_order',
                			'direction' => ASC,
                			'as' => 'integer'),
                ));
                $interval_add_button = "<a title='add interval' class='elgg-button-submit-element add-schedule-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $hidden['jot[schedule][asset]']         = $asset->guid;
                $hidden['jot[schedule][container_guid]'] = $task_guid;
                // Interval Add                
                foreach($hidden as $field=>$value){
                    $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                }
                $form_body  .= "$interval_add_button <span id='interval_button_label'>add interval</span>";
                $form_body  .= "
                <div class='rTable' style='width:100%'>
            		<div id='sortable_interval' class='rTableBody'>
            			<div class='rTableRow pin'>
            				<div class='rTableCell' style='width:15%'>Start</div>
            				<div class='rTableCell' style='width:15%'>End</div>
            				<div class='rTableCell' style='width:65%'>Activity</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>";
                if (!empty($schedule)){
                    foreach ($schedule as $schedule_item){
                        unset($start_date, $end_date, $activity, $hidden);
                        $start_date  = elgg_view('input/date', array('name'=> 'jot[schedule][start_date][]', 'value'=>$schedule_item->start_date, 'placeholder'=>'start date'));
                        $end_date    = elgg_view('input/date', array('name'=> 'jot[schedule][end_date][]'  , 'value'=>$schedule_item->end_date  , 'placeholder'=>'end date'));
                        $activity    = elgg_view('input/text', array('name'=> 'jot[schedule][title][]'     , 'value'=>$schedule_item->title     , 'placeholder' => 'Activity Name',));
                        $delete = elgg_view("output/url",array(
                			    	'href' => "action/jot/delete?guid=".$schedule_item->getGUID(),
                			    	'text' => elgg_view_icon('delete-alt'),
                			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
                			    	'encode_text' => false,
                			    ));
                        $hidden['jot[schedule][guid][]']         = $schedule_item->guid;
                        foreach($hidden as $field=>$value){
                            $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                        }
                        $form_body .= "<div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'>$delete</div>
                		</div>";
                    }
                }
                else {
                    unset($start_date, $end_date, $activity);
                    $start_date  = elgg_view('input/date', array('name'=> 'jot[schedule][start_date][]', 'placeholder'=>'start date'));
                    $end_date    = elgg_view('input/date', array('name'=> 'jot[schedule][end_date][]'  , 'placeholder'=>'end date'));
                    $activity    = elgg_view('input/text', array('name'=> 'jot[schedule][title][]'     , 'placeholder' => 'Activity Name', 'class'=> 'last_schedule_item',));
                    $form_body  .= "
                    	    <div class='rTableRow' style='cursor:move'>
                				<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
                				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
                				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
                				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                }
                $form_body  .= "<div class='new_schedule_item'></div>";
                $form_body  .= "
                </div>
                    </div>";
                
                unset($start_date, $end_date, $activity);
                $start_date  = elgg_view('input/date', array('name'=> 'jot[schedule][start_date][]', 'placeholder'=>'start date'));
                $end_date    = elgg_view('input/date', array('name'=> 'jot[schedule][end_date][]'  , 'placeholder'=>'end date'));
                $activity    = elgg_view('input/text', array('name'=> 'jot[schedule][title][]'     , 'placeholder' => 'Activity Name', 'class'=> 'last_schedule_item',));
             
                $form_body  .= "
                <div style='visibility:hidden'>
                	<div class='schedule_item'>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
            /****************************************
             * $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "Things used during this experience
                	          <span title='Things in Quebx'>";
                $form_body .= elgg_view('input/assetpicker', array('name'=>'jot[assets]', 'values'=>$jot->assets));
                $form_body .= "</span>";
                $form_body .= "$item_add_button <span id='other_button_label'>add other things</span>
                <div class='rTable' style='width:100%'>
                    <div class='rTableBody'>";
                $form_body .= "<div class='new_other_item'></div>";
                $form_body .= "
            	    </div>
                </div>";
                $form_body .= "
                <div style='visibility:hidden'>
                	<div class='other_item'>
                	    <div class='rTableRow'>
                			<div class='rTableCell' style='width:100%'>".
            			      elgg_view('input/text', array(
            					'name'        => 'jot[ghost_assets][title][]',
            					'class'       => 'last_other_item',
            		      	    'placeholder' => 'New Item Name',
            				))."
            				</div>
            				<div class='rTableCell' style='text-align:right;padding:0px'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
            /****************************************
             * $section = 'steps'                    *****************************************************************
             ****************************************/
            case 'steps':
                
                $task_steps = elgg_get_entities_from_metadata(array(
                	'type' => 'object',
                	'subtypes' => array('task', 'process_step'),
                	'limit' => false,
                	'metadata_name_value_pairs' => array(
                		'name' => 'parent_guid',
                		'value' => $guid
                	),
                	'order_by_metadata'    => array(
                	        'name' => 'sort_order',
                			'direction' => ASC,
                			'as' => 'integer'),
                ));
                if ($task_steps){
                    foreach ($task_steps as $task_step){
                        Switch ($task_step->getSubtype()){
                            case 'task': $element_type = 'task'; break;
                            case 'task_step': $element_type = 'step'; break;
                        };
                        $delete = elgg_view("output/url",array(
                			    	'href'    => "action/tasks/delete?guid=".$task_step->getGUID(),
                			    	'text'    => elgg_view_icon('delete-alt'),
                                    'title'   => 'remove step',
                			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
                			    	'encode_text' => false,
                			    ));
                        $task_link = elgg_view('input/text', array('name'=>'jot[process_step][title][]', 'value'=>$task_step->title, 'placeholder' => 'Step Name', ));
//                        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  "tasks/view/$task_step->guid"));
                        $steps .= "<div class='rTableRow' style='cursor:move'>
                    				<div class='rTableCell' style='width:100%;padding:0px'><li>$task_link</li></div>".
                    				elgg_view('input/hidden', array('name'=>'jot[process_step][guid][]', 'value'=>$task_step->getGUID()))."
                                    <div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'>$delete</div>
                    	        </div>";
                    }
                }
                else {$steps = false;}
                
                //Tasks Add
                $delete = elgg_view("output/url",array('href'=>"#",'text'=>elgg_view_icon('delete-alt'),'title'=>'remove step','class'=>'remove-node',));
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= 'Describe your path<br>';
                $form_body .= "$step_add_button <span id='step_button_label'>add step</span>
                <div class='rTable' style='width:100%'>
            		<div class='rTableBody'>
                    <ol style='list-style:decimal;padding-left:20px'>";
                $form_body .= $steps;
                $form_body .= "<div class='new_step_item'></div>";
                $form_body .= "
            	    </ol>
            	    </div>
                </div>";
                $form_body .= "
                <div id ='store' style='visibility:hidden'>
                	<div class='step_item'>
                	    <div class='rTableRow' style='cursor:move'>
                			<div class='rTableCell' style='width:100%;padding:0px'>
            	            <li>".
            			      elgg_view('input/text', array(
            					'name'        => 'jot[process_step][title][]',
            					'class'       => 'last_step_item',
            		      	    'placeholder' => 'Step Name',
            				))."
        				    </li>
            				</div>
            				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'>$delete</div>
                		</div>
                	</div>
            	</div>";
                break;
            /****************************************
             * $section = 'documents'                *****************************************************************
             ****************************************/
            case 'documents':
                $form_body = 'Documents section';
                break;
            /****************************************
             * $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery':
                $form_body = 'Gallery section';
                break;
            /****************************************
             * $section = 'legacy'                   *****************************************************************
             ****************************************/
            default:
                $variables = elgg_get_config("{$aspect}s");
                
                //$variables        = elgg_get_config('experiences');
                
                // 10/31/2014 - Added by scottj
                	$element_type = get_input('element_type');
                	echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
                //
                echo '<br>guid: '. $vars['guid'];
                echo '<br>element type: '.$element_type;
                echo '<br>aspect: '.$aspect;
                echo '<br>section:       '.$section;
                //echo elgg_dump($vars);
                
                foreach ($variables as $name => $type) {
                ?>
                <div>
                	<label><?php echo elgg_echo("experiences:$name") ?></label>
                	<?php
                		if ($type != 'longtext') {
                			echo '<br />';
                		}
                	?>
                	<?php echo elgg_view("input/$type", array(
                			'name' => $name,
                			'value' => $vars[$name],
                		));
                	?>
                </div>
                <?php
                }
                echo '<br>title: '.$vars['title'];
                
                if (elgg_view_exists('input/assetpicker', null, true)){echo 'YES';} else {echo 'NO';}
                echo 'asset: '. elgg_view('input/assetpicker', array(
                			"name" => "g1",
                			"id" => "g1",
                		));
                
                echo 'group:'. elgg_view('input/assetpicker', array(
                			"name" => "g1",
                			"id" => "g1",
                		));
                
                $cats = elgg_view('input/categories', $vars);
                if (!empty($cats)) {
                	echo $cats;
                }
                
                
                echo '<div class="elgg-foot">';
                if ($vars['guid']) {
                	echo elgg_view('input/hidden', array('name' => 'experience_guid','value' => $vars['guid'],));
                	echo elgg_view('input/hidden', array('name' => 'guid','value' => $vars['guid'],));
                }
                echo elgg_view('input/hidden', array('name' => 'container_guid','value' => $vars['container_guid'],));
                if ($vars['parent_guid']) {
                	echo elgg_view('input/hidden', array('name' => 'parent_guid','value' => $vars['parent_guid'],));
                }
                echo elgg_view('input/hidden', array('name' => 'aspect', 'value' => $aspect));
                                
                echo elgg_view('input/submit', array('value' => elgg_echo('save')));
                
                echo '</div>';
        }
        break;

/****************************************
 * $action = 'view'                      *****************************************************************************
 ****************************************/
    case 'view':
        // Build sections        
        Switch ($section){
            /****************************************
             * $section = 'main'                     *****************************************************************
             ****************************************/
            case 'main':
                $title_field        = $jot->title;	
            	$description_field  = $jot->description;
                
/*        		$form_body .= "<div id='fb-root'></div>
                                <script>(function(d, s, id) {
                                  var js, fjs = d.getElementsByTagName(s)[0];
                                  if (d.getElementById(id)) return;
                                  js = d.createElement(s); js.id = id;
                                  js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1390736364334124';
                                  fjs.parentNode.insertBefore(js, fjs);
                                }(document, 'script', 'facebook-jssdk'));</script>";
                
*/        		$form_body .= "<div class='fb-share-button' 
            			            data-href=''
            			            data-size='small'
    			                    data-layout='button'
            			            data-mobile-iframe='false'>
                			        <a class='fb-xfbml-parse-ignore' 
                			           target='_blank' 
                			           href='https://www.facebook.com/sharer/sharer.php?u=&amp;src=sdkpreparse'>
                			        Share
                			        </a>
            			        </div>";
        
        		$form_body .= "
        	    <div class='rTable' style='width:550px'>
        			<div class='rTableBody'>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$title_field</div>
        				</div>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$description_field</div>
        				</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>$tabs</div>
            			</div>
            		</div>
        		</div>";
        		break;
            /****************************************
             * $section = 'schedule'                 *****************************************************************
             ****************************************/
            case 'schedule':
                
                $schedule = elgg_get_entities_from_metadata(array(
                	'type' => 'object',
                	'subtypes' => array('schedule_item'),
                	'limit' => false,
                	'metadata_name_value_pairs' => array(
                		'name' => 'parent_guid',
                		'value' => $guid
                	),
                	'order_by_metadata'    => array(
                	        'name' => 'sort_order',
                			'direction' => ASC,
                			'as' => 'integer'),
                ));
                if (!empty($schedule)){
                $form_body  .= "
                <div class='rTable' style='width:100%'>
            		<div class='rTableBody'>
            			<div class='rTableRow pin'>
            				<div class='rTableCell' style='width:15%'>Start</div>
            				<div class='rTableCell' style='width:15%'>End</div>
            				<div class='rTableCell' style='width:65%'>Activity</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>";
                    foreach ($schedule as $schedule_item){
                        unset($start_date, $end_date, $activity);
                        $start_date  = $schedule_item->start_date;
                        $end_date    = $schedule_item->end_date;
                        $activity    = $schedule_item->title;
                        $form_body .= "
                    	<div class='rTableRow'>
            				<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'></div>
                		</div>";
                    }
                    $form_body .= '
                	</div>
                </div>';
                }
                else {
                    $form_body .= 'No intervals';
                }
                break;
            /****************************************
             * $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "Things used
                	          <span title='Things in Quebx'>";
                $assets = $jot->assets;
                if (!empty($assets)){
                    $form_body .= '<ul>';
                    if (!is_array($assets)){
                        $assets = array($assets);
                    }
                    foreach ($assets as $asset_guid){
                        $asset = get_entity($asset_guid);
                        $form_body .= "<li>".elgg_view('output/url', array('href' => $asset->getURL(), 'text'=>$asset->title)).'</li>';
                    }
                    $form_body .= "</ul>"; 
                }
                $form_body .= "</span>";
                break;
            /****************************************
             * $section = 'steps'                    *****************************************************************
             ****************************************/
            case 'steps':
                $steps = false;
                $task_steps = elgg_get_entities_from_metadata(array(
                	'type' => 'object',
                	'subtypes' => array('task', 'process_step'),
                	'limit' => false,
                	'metadata_name_value_pairs' => array(
                		'name' => 'parent_guid',
                		'value' => $guid
                	),
                	'order_by_metadata'    => array(
                	        'name' => 'sort_order',
                			'direction' => ASC,
                			'as' => 'integer'),
                ));
                if ($task_steps){
                    foreach ($task_steps as $task_step){
//                        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  $task_step->getURL()));
                        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  "tasks/view/{$task_step->getGUID()}"));
                        $steps .= "<div class='rTableRow'>
                    				<div class='rTableCell' style='width:100%;padding:0px'><li>$task_link</li></div>
                                    <div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    	        </div>";
                    }
                }
                
                //Tasks Add
                if ($steps){
                    $form_body .= "My path<br>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol style='list-style:decimal;padding-left:20px'>";
                    $form_body .= $steps;
                    $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                }
                else {
                    $form_body .= 'No steps';
                }
                break;
            /****************************************
             * $section = 'documents'                *****************************************************************
             ****************************************/
            case 'documents':
                $form_body = 'Documents section';
                break;
            /****************************************
             * $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery':
                $form_body = 'Gallery section';
                break;
            /****************************************
             * $section = 'legacy'                   *****************************************************************
             ****************************************/
            default:
                
                break;

        }
        break;
}

echo $form_body;

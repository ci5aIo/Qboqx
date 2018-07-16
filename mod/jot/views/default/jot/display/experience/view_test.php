<!-- Form: jot/views/default/forms/experiences/edit.php -->
<?php
/**
 * experiences edit form body
 *
 * @package ElggPages	
 */
//elgg_use_library('elgg:quebx');
// Receive input
$guid        = elgg_extract('guid'             , $vars, get_input('guid'));
$owner_guid  = elgg_extract('owner_guid'       , $vars, get_input('owner_guid'));
$container_guid = elgg_extract('container_guid', $vars, get_input('container_guid'));
$section     = elgg_extract('section'          , $vars);
$selected    = elgg_extract('selected'         , $vars, false);
$action      = elgg_extract('action'           , $vars);
$tabs        = elgg_extract('tabs'             , $vars);
$entity      = get_entity($guid);
if (elgg_entity_exists($guid)){
    $subtype = $entity->getSubtype();
}
$container = get_entity($container_guid);

if ($selected){
      $style = 'style="display: block;"';} 
else{ $style = 'style="display: none;"';}  

// Define variables
$jot         = get_entity($guid);
$aspect      = $jot->aspect ?: 'experience';
$stage_field = elgg_view('input/radio' , array(
                      "name"    => "jot[stage]",
                      "align"   => "horizontal",
					  "value"   => $jot->state ?: 1,
					  "options" => array("Planning" => 1, "In Process" => 2, "Cancelled" => 3, "Postponed" => 4, "Complete" => 5),
				      "default" => 1,
					 ));
Switch ($action){
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
                unset($form_body, $hidden);
                $title_field        = $jot->title;
            	$description_field  = $jot->description;
                
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
                unset($form_body, $hidden);
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
                        $start_date  = $schedule_item->start_date;
                        $end_date    = $schedule_item->end_date;
                        $activity    = $schedule_item->title;
                        $form_body .= "<div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'></div>
                		</div>";
                    }
                }
                break;
            /****************************************
             * $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
                unset($form_body, $hidden);
                $form_body .= "
                <div id='Things_panel' class='elgg-head' $style>";
                $form_body .= elgg_list_entities(array('guids'=>$jot->assets,'view_type'=>'list_experience'));
                $form_body .= "
            	</div>";
                break;
            /****************************************
             * $section = 'steps'                    *****************************************************************
             ****************************************/
            case 'steps':
                unset($form_body, $hidden);
                $task_steps = elgg_get_entities_from_metadata(array(
                	'type' => 'object',
                	'subtypes' => array('task', 'instruction'),
                    'container_guid' => $container_guid,
                	'limit' => false,
                	'metadata_name_value_pairs' => array(
                		'name' => 'aspect',
                		'value' => 'process_step'
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
                        $task_link = elgg_view('output/url', array('text' => $task_step->description, 'href' =>  "jot/view/$task_step->guid"));
                        $steps .= "<div class='rTableRow'>
                    				<div class='rTableCell' style='width:100%;padding:0px'><li>$task_link</li></div>
                                    <div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    	        </div>";
                    }
                }
                else {$steps = false;}
                
                //Tasks Add
                $form_body .= "<b>Instructions</b><br>
                <div class='rTable' style='width:100%'>
            		<div class='rTableBody'>
                    <ol style='list-style:decimal;padding-left:20px'>";
                $form_body .= $steps;
                $form_body .= "
            	    </ol>
            	    </div>
                </div>";
                break;
            /****************************************
             * $section = 'documents'                *****************************************************************
             ****************************************/
            case 'documents':
                unset($form_body, $hidden);
                if (is_array($jot->documents)){
                    foreach ($jot->documents as $document_guid){
                                $documents[] = (int) $document_guid;
                            } 
                }
                $form_body .= "
                <div id='Documents_panel' class='elgg-head' $style>";
                if (!empty($documents)){
                    $form_body .= elgg_list_entities(array('guids'=>$documents,));
                }
                else {
                    $form_body .= 'No documents';
                }
                $form_body .= "
                </div>";
                break;
            /****************************************
             * $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
                if (is_array($jot->images)){
                    foreach ($jot->images as $image_guid){
                                $images[] = (int) $image_guid;
                            } 
                }
                $form_body .= "
                <div id='Jot_experience_gallery_panel' class='elgg-head' $style>";
                if (!empty($images)){
                    $form_body .= elgg_list_entities(array('guids'=>$images,));
                }
                else {
                    $form_body .= 'No image or video files';
                }
                $form_body .= "
                </div>";
                break;
            /****************************************
             * $section = 'expand'                *****************************************************************
             ****************************************/
            case 'expand':
                unset($form_body, $hidden);
                $form_body .= "
                <div id='Expand_panel' class='elgg-head' $style>";
                $form_body .= elgg_view('forms/experiences/edit',
                                  array('action'         => $action,
                                        'selected'       => $selected == 'Expand',
                                        'container_guid' => $container_guid,
                                        'section'        => $aspect,));
                $form_body .= "
				</div>";
                break;
            /****************************************
             * $section = 'instruction'              *****************************************************************
             ****************************************/
            case 'instruction':
                unset($form_body, $hidden);
                $form_body .= elgg_view('forms/experiences/edit',
                                  array('action'         => 'view',
                                        'container_guid' => $container_guid,
                                        'section'        => 'steps',));                     
                break;

        }
        break;
}

echo $form_body;

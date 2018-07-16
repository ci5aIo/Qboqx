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
$selected_state = elgg_extract('selected_state', $vars, 'selected');
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
            	
/*        		$form_body .= "<div id='fb-root'></div>
                                <script>(function(d, s, id) {
                                  var js, fjs = d.getElementsByTagName(s)[0];
                                  if (d.getElementById(id)) return;
                                  js = d.createElement(s); js.id = id;
                                  js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1390736364334124';
                                  fjs.parentNode.insertBefore(js, fjs);
                                }(document, 'script', 'facebook-jssdk'));</script>";
                
        		$form_body .= "<div class='fb-share-button' 
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
*/        
        		$form_body .= "
        	    <div class='rTable' style='width:100%'>
        			<div class='rTableBody'>
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
                unset($form_body, $hidden);
                $form_body .= "
                <div panel='Things' aspect='attachments' guid=$guid id='Things_panel' class='elgg-head' $style>";
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
                    'container_guid' => $guid,
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
                <div panel='Documents' aspect='attachments' guid=$guid id='Documents_panel' class='elgg-head' $style>";
                if (!empty($documents)){
                    $form_body .= elgg_list_entities(array('guids'=>$documents, 'list_type'=>'list_experience'));
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
                <div panel='Gallery' aspect='attachments' guid=$guid id='Jot_experience_gallery_panel' class='elgg-head' $style>";
                if (!empty($images)){
                    $form_body .= elgg_list_entities(array('guids'=>$images, 'list_type'=>'list_experience', 'limit'=>0));
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
                Switch ($selected_state){
                    case 'closed':
                        $style = 'display: none;';
                        break;
                    case 'selected':
                        $style = 'display: block;';
                        break;
                    default:
                        $style = 'display: block;';
                }
                $form_body .= "
                <div panel='Expand' aspect='attachments' guid=$guid id='Expand_panel' class='elgg-head'  style='$style'>";
                $form_body .= elgg_view('jot/display/experience/view',
                                  array('action'         => $action,
                                        'selected'       => $selected == 'Expand...',
                                        'guid'           => $guid,
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
                $form_body .= elgg_view('jot/display/experience/view',
                                  array('action'         => 'view',
                                        'container_guid' => $container_guid,
                                        'section'        => 'steps',));
                break;
            
            /****************************************
*view********* $section = 'observation'              *****************************************************************
             ****************************************/
            case 'observation':
                unset($form_body, $hidden);
                $state                  = $jot->state ?: 1;
                $selected_panel[1]      = 'display:none';
                $selected_panel[2]      = 'display:none';
                $selected_panel[3]      = 'display:none';
                $selected_panel[4]      = 'display:none';
                $selected_panel[5]      = 'display:none';
                $selected_panel[$state] = 'display:block' ;
                
                $state_params = array("name"    => "jot[observation][state]",
                                      'align'   => 'horizontal',
                					  "value"   => $state,
                                      "guid"    => $guid,
                					  "options2" => array(
                					                   array("label" =>"Discovery",
                					                         "option" => 1,
                					                         "title" => "Initial observation"),
                					                   array("label" => "Resolve",
                					                         "option" => 2,
                					                         "title" => "Early attempts to resolve"),
                					                   array("label" => "Assign",
                					                         "option" => 3,
                					                         "title" => "Assign to a specialist"),
                					                   array("label" => "Accept",
                					                         "option" => 4,
                					                         "title" => "Accept the situation and move on"),
                					                   array("label" => "Complete",
                					                         "option" => 5,
                					                         "title" => "Assigned tasks are complete.")
                					                   ),
                				      'default' => 1,
                					 );
                $state_selector = elgg_view('input/quebx_radio', $state_params);
                switch ($jot->state){
                    case 1: $state_selector = 'Discovery';break;
                    case 2: $state_selector = 'Resolve'; break;
                    case 3: $state_selector = 'Assign'; break;
                    case 4: $state_selector = 'Accept'; break;
                    case 5: $state_selector = 'Complete'; break;
                };
                
                $input_tabs[] = array('title'=>'Discovery', 'selected' => $state == 1 , 'link_class' => '', 'link_id' => '', 'panel'=>'observation_discoveries', 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Resolve'  , 'selected' => $state == 2, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_efforts'    , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Assign'   , 'selected' => $state == 3, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_assign'     , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Accept'   , 'selected' => $state == 4, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_accept'     , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Complete' , 'selected' => $state == 5, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_complete'   , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'observation_input', 'tabs'=>$input_tabs));
                
                $discoveries_panel = elgg_view('jot/display/experience/view', array(
                                               'action'         => 'view',
                                               'section'        => 'discoveries',
                                               'guid'           => $guid,));
                $resolve_panel = elgg_view('jot/display/experience/view', array(
                                               'action'         => 'view',
                                               'section'        => 'resolve',
                                               'guid'           => $guid,));
                $assign_panel = elgg_view('jot/display/experience/view', array(
                                               'action'         => 'view',
                                               'section'        => 'assign',
                                               'guid'           => $guid,));
                $accept_panel = elgg_view('jot/display/experience/view', array(
                                               'action'         => 'view',
                                               'section'        => 'accept',
                                               'guid'           => $guid,));
                $complete_panel = elgg_view('jot/display/experience/view', array(
                                               'action'         => 'view',
                                               'section'        => 'complete',
                                               'guid'           => $guid,));
                $form_body .= "<h3>Observation</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:10%'>Stage
                                    </div>
                					<div class='rTableCell' style='width:90%'>$state_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:10%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:90%;padding:0px;'>
                                		<div panel='observation_discoveries' guid=$guid aspect='observation_input' id='observation_discoveries_panel' class='elgg-head' style='$selected_panel[1]'>
                                		    $discoveries_panel</div>
                                		<div panel='observation_efforts'     guid=$guid aspect='observation_input' id='observation_efforts_panel'     class='elgg-head' style='$selected_panel[2]'>
                                		    $resolve_panel</div>
                                        <div panel='observation_assign'      guid=$guid aspect='observation_input' id='observation_assign_panel'      class='elgg-head' style='$selected_panel[3]'>
                                            $assign_panel</div>
                                        <div panel='observation_accept'      guid=$guid aspect='observation_input' id='observation_accept_panel'      class='elgg-head' style='$selected_panel[4]'>
                                            $accept_panel</div>
                                        <div panel='observation_complete'    guid=$guid aspect='observation_input' id='observation_complete_panel'    class='elgg-head' style='$selected_panel[5]'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";            	
                break;
                
            /****************************************
*add********** $section = 'discoveries'              *****************************************************************
             ****************************************/
            case 'discoveries':
                unset($form_body, $hidden, $last_class);
                $options     = array('type'              => 'object',
                                     'subtype'           => 'observation',
                                     'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'discovery'),
                                     'container_guid'    => $guid,
                                     'order_by_metadata' => array('name' => 'sort_order', 'direction' => 'ASC', 'as' => 'integer'),
                               );
                $discoveries = elgg_get_entities_from_metadata($options);
                $form_body  .= "
            	     <b>Discoveries</b>
            		<div class='rTable' style='width:100%'>
            			<div id='sortable_discovery' class='rTableBody'>
            				<div class='rTableRow pin'>
            					<div class='rTableCell' style='width:0'></div>
            					<div class='rTableHead' style='width:15%; padding: 0px 0px'><span title='When this happened'>Date</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I did'>Action</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I observed'>Observation</span></div>
            					<div class='rTableHead' style='width:25%; padding: 0px 0px'><span title='What I learned'>Discovery</span></div>
            					<div class='rTableHead' style='width:0; padding: 0px 0px'></div>
            				</div>
            				";
            	foreach ($discoveries as $key=>$discovery) {
            		$form_body  .= 
            		" 		<div class='rTableRow'>
            					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center'></div>
            					<div class='rTableCell' style='width:15%; padding: 0px 0px;vertical-align:top' title='When this happened'>$discovery->date</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>$discovery->action</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>$discovery->observation</div>
            					<div class='rTableCell' style='width:25%; padding: 0px 0px;vertical-align:top' title='What I learned'>$discovery->discovery</div>
            					<div class='rTableCell' style='width:0; padding: 0px 0px'></div>
            				</div>";
            	}
            	$form_body  .="
                	</div>
                </div>";                        
                break;
            /****************************************
*view********** $section = 'resolve'                  *****************************************************************
             ****************************************/
            case 'resolve':
                unset($form_body, $hidden);
                $efforts_existing     = elgg_get_entities_from_metadata(array(
                                                                'type'              => 'object',
                                                                'subtype'           => 'observation',
                                                                'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'effort'),
                                                                'container_guid'    => $guid,
                                                                'limit'             => 0,
                        ));
                
                $form_body .= "<b>Efforts to Resolve</b>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>";
                if (!empty($efforts_existing)){
                    $form_body .= "
                        <ol style='list-style:decimal;padding-left:20px'>";
                    foreach ($efforts_existing as $effort){
                        $form_body .= "
                    	    <div class='rTableRow'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".elgg_view('output/url', array(
                                        'text' => $effort->title,
                                        'href'=> 'jot/view/'.$effort->guid,
                                        ))."</li>
                    			</div>
                		    </div>";
                    }
                    $form_body .= "
                	    </ol>";
                }
                else {
                        $form_body .= "
                    	    <div class='rTableRow'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                No efforts to resolve
                    			</div>
                		    </div>";   
                }
                $form_body .= "
                	    </div>
                    </div>";
                
                break;
            /****************************************
*add********** $section = 'assign'                    *****************************************************************
             ****************************************/
            case 'assign':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Assign to a specialist</b><br>
                    Early attempts to resolve were unsuccessful.  Time for the experts.<br>";
                break;
            /****************************************
*add********** $section = 'accept'                    *****************************************************************
             ****************************************/
            case 'accept':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Accept</b><br>
                    Accept the situation and move on.<br>";
                break;
            /****************************************
*add********** $section = 'complete'                 *****************************************************************
             ****************************************/
            case 'complete':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Task is complete and can be closed</b><br>";
                break;

        }
        break;
}

echo $form_body;

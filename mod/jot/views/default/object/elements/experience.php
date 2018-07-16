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
 * $action = 'add'                       *****************************************************************************
 ****************************************/        
    case 'add':
        
        // Build sections        
        Switch ($section){
            /****************************************
             * $section = 'main'                     *****************************************************************
             ****************************************/
            case 'main':
                $title_field        = elgg_view("input/text"    , array("name" => "jot[title]"          , 'placeholder' => 'Give your experience a name', 'style' => 'width:495px'));
            	$start_date_field   = elgg_view("input/date"    , array("name" => "jot[start_date]"     , 'class'       => 'tiny date',));
            	$end_date_field     = elgg_view("input/date"    , array("name" => "jot[end_date]"       , 'class'       => 'tiny date',));
            	$description_field  = elgg_view("input/longtext", array("name" => "jot[description]"    , 'placeholder' => 'Describe the experience ...',));
            	$hidden            .= elgg_view('input/hidden'  , array('name' => 'jot[owner_guid]'     , 'value' => $owner_guid));
            	$hidden            .= elgg_view('input/hidden'  , array('name' => 'jot[container_guid]' , 'value' => $container_guid));
            	$hidden            .= elgg_view('input/hidden'  , array('name' => 'jot[subtype]'        , 'value' => $aspect));
                
        		$form_body = "
        	    <div class='rTable' style='width:550px'>
        			<div class='rTableBody'>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$title_field".elgg_view('input/submit',	 array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element'))."</div>
        				</div>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$description_field</div>
        				</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>Add details or progress to a new level.</div>
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
                unset($form_body, $hidden);
                $form_body .= "
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
                $interval_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-schedule-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                unset($start_date, $end_date, $activity);
                $start_date  = elgg_view('input/date', array('name'=> 'jot[event][schedule][start_date][]', 'placeholder'=>'start date'));
                $end_date    = elgg_view('input/date', array('name'=> 'jot[event][schedule][end_date][]', 'placeholder'=>'end date'));
                $activity    = elgg_view('input/text', array('name'=> 'jot[event][schedule][title][]', 'class'=> 'last_schedule_item','placeholder' => 'Activity Name',));
//                    $form_body  .= "$interval_add_button <span id='interval_button_label'>add interval</span>";
                $form_body  .= "<p></p>
                <div class='rTable' style='width:100%'>
            		<div id='sortable_interval' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$interval_add_button</div>
            				<div class='rTableCell' style='width:15%'>Start</div>
            				<div class='rTableCell' style='width:15%'>End</div>
            				<div class='rTableCell' style='width:65%'>Activity</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x205c</div>
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
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x205c</div>
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
                unset($form_body, $hidden);
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "
                <div id='Things_panel' class='elgg-head' $style>";
                $form_body .= "Things used during this experience
                	          <span title='Things in Quebx'>";
                $form_body .= elgg_view('input/assetpicker', array('name'=>'jot[assets]', 'values'=>array($container_guid), 'placeholder'=>'Start typing the name of an item'));
                $form_body .= "</span>";
                $form_body .= "$item_add_button <span id='other_button_label'>include other things not found in Quebx</span>
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
                	</div>
                </div>";
                break;
            /****************************************
             * $section = 'steps'                    *****************************************************************
             ****************************************/
            case 'steps':
                unset($form_body, $hidden);
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "$step_add_button <span id='step_button_label'>add step</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol style='list-style:decimal;padding-left:20px'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                	            <li>".
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[instruction][step][description][]',
                					'class'       => 'last_step_item',
                    			    'style'       => 'height:17px',
                				))."
            				    </li>
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
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
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[instruction][step][description][]',
                					'class'       => 'last_step_item',
                    			    'style'       => 'height:17px',
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
                unset($form_body, $hidden);
                $form_body .= "
                <div id='Documents_panel' class='elgg-head' $style>";
                $form_body .= elgg_view('input/dropzone', array(
                                    'name' => 'jot[documents]',
                                    'default-message'=> '<strong>Drop document files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));
                $form_body .= "
				</div>";
                break;
            /****************************************
             * $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
                /*function quebx_get_object_by_title($subtype, $title)
                    {
                        global $CONFIG;
                        
                        $query ="SELECT e.* from {$CONFIG->dbprefix}entities e ".
                    "JOIN {$CONFIG->dbprefix}objects_entity o ON e.guid=o.guid ".
                    "WHERE e.subtype={$subtype} ".
                    "AND o.title=\"{$title}\" ".
                    "LIMIT 0, 1";
                        $row = get_data_row($query);
                        if ($row)
                            return new ElggObject($row);
                        else
                            return false;
                    }*/
                elgg_load_library('elgg:quebx');
                $album_subtype = get_subtype_id('object', 'hjalbum');
                $title   = 'Default';
                $album = quebx_get_object_by_title($album_subtype, $title); 
                                
                $form_body .= "
                <div id='Jot_experience_gallery_panel' class='elgg-head' $style>";
                $form_body .= elgg_view('input/dropzone', array(
                                    'name' => 'jot[images]',
                                    'default-message'=> '<strong>Drop image or video files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
                                    'accept'=> 'image/*, video/*',
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $album->getGUID(),
									'subtype' => 'hjalbumimage',));
                $form_body .= "
				</div>";
                break;
            /****************************************
             * $section = 'progress'                *****************************************************************
             ****************************************/
            case 'progress':
                unset($form_body, $hidden);
                
                $progress_script = '<script>
                    $(document).ready(function(){
                        $("input[name=\'jot[aspect]\']").change(function() {
                           if($(this).val() == "nothing") { 
                                $("#progress_nothing_panel").show();
                                $("#progress_instruction_panel").hide();
                                $("#progress_observation_panel").hide();
                                $("#progress_event_panel").hide();
                                $("#progress_project_panel").hide();
                              }
                           if($(this).val() == "instruction") { 
                                $("#progress_nothing_panel").hide(); 
                                $("#progress_instruction_panel").show();
                                $("#progress_observation_panel").hide();
                                $("#progress_event_panel").hide();
                                $("#progress_project_panel").hide();
                              }
                           if($(this).val() == "observation") { 
                                $("#progress_nothing_panel").hide(); 
                                $("#progress_instruction_panel").hide();
                                $("#progress_observation_panel").show();
                                $("#progress_event_panel").hide();
                                $("#progress_project_panel").hide();
                              }
                           if($(this).val() == "event") { 
                                $("#progress_nothing_panel").hide();
                                $("#progress_instruction_panel").hide();
                                $("#progress_observation_panel").hide();
                                $("#progress_event_panel").show();
                                $("#progress_project_panel").hide();
                              }
                           if($(this).val() == "project") { 
                                $("#progress_nothing_panel").hide();
                                $("#progress_instruction_panel").hide();
                                $("#progress_observation_panel").hide();
                                $("#progress_event_panel").hide();
                                $("#progress_project_panel").show();
                              }
                        });
                        $("input[name=\'jot[observation][state]\']").change(function() {
                               if($(this).val() == 1) { 
                                    $("#observation_discoveries_panel").show();
                                    $("#observation_efforts_panel").hide();
                                    $("#observation_assign_panel").hide();
                                    $("#observation_accept_panel").hide();
                                    $("#observation_complete_panel").hide();
                                  }
                               if($(this).val() == 2) {
                                    $("#observation_discoveries_panel").hide();
                                    $("#observation_efforts_panel").show();
                                    $("#observation_assign_panel").hide();
                                    $("#observation_accept_panel").hide();
                                    $("#observation_complete_panel").hide();
                                  }
                               if($(this).val() == 3) {
                                    $("#observation_discoveries_panel").hide();
                                    $("#observation_efforts_panel").hide();
                                    $("#observation_assign_panel").show();
                                    $("#observation_accept_panel").hide();
                                    $("#observation_complete_panel").hide();
                                  }
                               if($(this).val() == 4) {
                                    $("#observation_discoveries_panel").hide();
                                    $("#observation_efforts_panel").hide();
                                    $("#observation_assign_panel").hide();
                                    $("#observation_accept_panel").show();
                                    $("#observation_complete_panel").hide();
                                  }
                               if($(this).val() == 5) {
                                    $("#observation_discoveries_panel").hide();
                                    $("#observation_efforts_panel").hide();
                                    $("#observation_assign_panel").hide();
                                    $("#observation_accept_panel").hide();
                                    $("#observation_complete_panel").show();
                                  }
                            });
                   });
                        </script>';
                $options  = array(array('id'    =>'progress_nothing',
                                        'label' =>'Nothing',
                                        'option'=>'nothing',
                                        'title' =>'Nothing else'),
                                  array('id'    =>'progress_instruction',
                                        'label' =>'Instruction',
                                        'option'=>'instruction',
                                        'title' =>'Convert this experience into an instruction'),
                                  array('id'    =>'progress_observation',
                                        'label' =>'Observation',
                                        'option'=>'observation',
                                        'title' =>'Convert this experience into an observation'),
                                  array('id'    =>'progress_event',
                                        'label' =>'Event',
                                        'option'=>'event',
                                        'title' =>'Convert this experience into an event'),
                                  array('id'    =>'progress_project',
                                        'label' =>'Project',
                                        'option'=>'project',
                                        'title' =>'Convert this experience into a project'),
                                 );
                $selector = elgg_view('input/quebx_radio', array(
                        'id'      => 'progress',
                        'name'    => 'jot[aspect]',
                        'value'   => 'nothing',
                        'options2'=> $options,
                        'align'   => 'horizontal',
                ));
                $nothing_panel     = NULL;
                $instruction_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'instruction',));
                $observation_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'observation',));
                $event_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'event',));
                $project_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'project',));
                
                $form_body .= $progress_script;
                $form_body .= "
                <div id='Progress_panel' class='elgg-head' $style>";
                $form_body .= 'What happens next?';
                $form_body .= $selector."<hr>";
                $form_body .= "
                    <div id ='progress_nothing_panel' style='display: block;'>
                        $nothing_panel
                	</div>
                    <div id ='progress_instruction_panel' style='display: none;'>
                        $instruction_panel
                	</div>
                    <div id ='progress_observation_panel' style='display: none;'>
                        $observation_panel
                	</div>
                    <div id ='progress_event_panel' style='display: none;'>
                        $event_panel
                	</div>
                    <div id ='progress_project_panel' style='display: none;'>
                        $project_panel
                	</div>";
                $form_body .= "
				</div>";
                break;
            /****************************************
             * $section = 'instruction'              *****************************************************************
             ****************************************/
            case 'instruction':
                unset($form_body, $hidden);
                
                $form_body .= "
                <div id='instruction_steps_panel' class='elgg-head'  style='display: block;'>";
                
                $form_body .= elgg_view('forms/experiences/edit', array(
                                       'action'         => 'add',
                                       'section'        => 'steps',));
                $form_body .= "
                </div>";
                break;
            /****************************************
             * $section = 'event'                   *****************************************************************
             ****************************************/
            case 'event':
                unset($form_body, $hidden);
                
                $form_body .= "
                <div id='event_schedule_panel' class='elgg-head'  style='display: block;'>";
                
                $form_body .= elgg_view('forms/experiences/edit', array(
                                       'action'         => 'add',
                                       'section'        => 'schedule',));
                $form_body .= "
                </div>";
                break;
            /****************************************
             * $section = 'project'                   *****************************************************************
             ****************************************/
            case 'project':
                unset($form_body, $hidden);
                $stage_params = array("name"    => "jot[project][stage]",
                                      'align'   => 'horizontal',
                					  "value"   => 1,
                					  "options2" => array(
                					                   array("label" =>"Planning",
                					                         "option" => 1,
                					                         "title" => "Initial project setup"),
                					                   array("label" => "In Process",
                					                         "option" => 2,
                					                         "title" => "Project is active and under way"),
                					                   array("label" => "Blocked",
                					                         "option" => 3,
                					                         "title" => "Project is impeded"),
                					                   array("label" => "Cancelled",
                					                         "option" => 4,
                					                         "title" => "Project is incomplete and will not continue"),
                					                   array("label" => "Complete",
                					                         "option" => 5,
                					                         "title" => "Project is complete.")
                					                   ),
                				      'default' => 1,
                					 );
                $stage_selector = elgg_view('input/quebx_radio', $stage_params);
                $form_body .= "Convert this experience into a project.
                <div id='project_milestones_panel' class='elgg-head'  style='display: block;'>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:0'>Stage
                                    </div>
                					<div class='rTableCell' style='width:0'>$stage_selector
                                    </div>
                                </div>
                        </div>
                    </div>";
                $form_body .= elgg_view('forms/experiences/edit', array(
                                       'action'         => 'add',
                                       'section'        => 'milestones',));
                $form_body .= "
                </div>";
                break;
            /****************************************
             * $section = 'observation'              *****************************************************************
             ****************************************/
            case 'observation':
                unset($form_body, $hidden);
                
                $state_params = array("name"    => "jot[observation][state]",
                                      'align'   => 'horizontal',
                					  "value"   => 1,
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
                $discoveries_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'discoveries',));
                $resolve_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'resolve',));
                $assign_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'assign',));
                $accept_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'accept',));
                $complete_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'complete',));
                $form_body .= "<h3>Observation</h3>";
                $form_body .="Details of the experience that may help to resolve issues.
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:0'>Stage
                                    </div>
                					<div class='rTableCell' style='width:0'>$state_selector
                                    </div>
                                </div>
                        </div>
                    </div>";
                $form_body .= "<p></p>
                    <div id='observation_discoveries_panel' class='elgg-head' style='display: block;'>
                        $discoveries_panel</div>
                    <p></p>
                    <div id='observation_efforts_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $resolve_panel</div>
                    <div id='observation_assign_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $assign_panel</div>
                    <div id='observation_accept_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $accept_panel</div>
                    <div id='observation_complete_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $complete_panel</div>";
            	$form_body .= "
                    <div id='line_store' style='visibility: hidden; display:inline-block;'>
                    	<div class='discovery'>
                    		<div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x205c</div>
                    			<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".elgg_view('input/date', array(
                    											'name' => 'jot[observation][discovery][date][]',
                    									))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][action][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][observation][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][discovery][]',
                    							        		'class'=> 'rTableform last_discovery',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                    	
                    	<div class='resolve_effort'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => 'jot[observation][effort][title][]',
                    				'class'       => 'last_effort',
                    	      	    'placeholder' => 'Effort to Resolve',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                    </div>";
                break;
            /****************************************
             * $section = 'discoveries'              *****************************************************************
             ****************************************/
            case 'discoveries':
                unset($form_body, $hidden, $last_class);
                
                $form_body  .= "
            	     <b>Discoveries</b><br>
            	        Interesting things that I learned during this experience:
            		<div class='rTable' style='width:100%'>
            			<div id='sortable_discovery' class='rTableBody'>
            				<div class='rTableRow pin'>
            					<div class='rTableCell' style='width:0'>".
            								elgg_view('output/url', array(
            								    'text' => '+',
            									'href' => '#',
            									'class' => 'elgg-button-submit-element clone-discovery-action' // unique class for jquery
            									))."
            		            </div>
            					<div class='rTableHead' style='width:10%; padding: 0px 0px'><span title='When this happened'>Date</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I did'>Action</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I observed'>Observation</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I learned'>Discovery</span></div>
            					<div class='rTableHead' style='width:0; padding: 0px 0px'></div>
            				</div>
            				";
            	
            	// Populate blank lines
            	for ($i = $n+1; $i <= $n+3; $i++) {
            	    if ($i == 3){
            	        $last_class = ' last_discovery';
            	    }
            		$form_body  .= 
            		" 		<div class='rTableRow' style='cursor:move'>
            					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x205c</div>
            					<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".elgg_view('input/date', array(
            													'name' => 'jot[observation][discovery][date][]',
            											))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".elgg_view('input/longtext', array(
            									        		'name' => 'jot[observation][discovery][action][]',
            									        		'class'=> 'rTableform',
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".elgg_view('input/longtext', array(
            									        		'name' => 'jot[observation][discovery][observation][]',
            									        		'class'=> 'rTableform',
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".elgg_view('input/longtext', array(
            									        		'name' => 'jot[observation][discovery][discovery][]',
            									        		'class'=> 'rTableform'.$last_class,
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
            				</div>";
            	}
            	$form_body  .="
            	        <div class='new_discovery'></div>
                	</div>
                </div>";                            
                break;
            /****************************************
             * $section = 'resolve'                  *****************************************************************
             ****************************************/
            case 'resolve':
                unset($form_body, $hidden);
                
                $effort_add_button = "<a title='add effort' class='elgg-button-submit-element add-effort' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "<b>Efforts to Resolve</b><br>
                    Attempts that I made to resolve the issue.<br>
                    $effort_add_button <span id='effort_button_label'>add effort</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol style='list-style:decimal;padding-left:20px'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => 'jot[observation][effort][title][]',
                    				'class'       => 'last_effort',
                    	      	    'placeholder' => 'Effort to Resolve',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		
                		</div>";
                $form_body .= "<div class='new_effort'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                
                break;
            /****************************************
             * $section = 'assign'                    *****************************************************************
             ****************************************/
            case 'assign':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Assign to a specialist</b><br>
                    Early attempts to resolve were unsuccessful.  Time for the experts.<br>";
                break;
            /****************************************
             * $section = 'accept'                    *****************************************************************
             ****************************************/
            case 'accept':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Accept</b><br>
                    Accept the situation and move on.<br>";
                break;
            /****************************************
             * $section = 'complete'                 *****************************************************************
             ****************************************/
            case 'complete':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Task is complete and can be closed</b><br>";
                break;
            /****************************************
             * $section = 'milestones'               *****************************************************************
             ****************************************/
            case 'milestones':
                unset($form_body, $hidden);
                $target_date     = elgg_view('input/date', array('name'=> 'jot[project][milestone][target_date][]', 'placeholder'=>'target date'));
                $milestone_add_button = "<a title='add milestone' class='elgg-button-submit-element add-milestone-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= 'Project milestones<br>';
                $form_body .= "$milestone_add_button <span id='milestone_button_label'>add milestone</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol style='list-style:decimal;padding-left:20px'>
                        	<div class='milestone_item'>
                        	    <div class='rTableRow' style='cursor:move'>
                        			<div class='rTableCell' style='width:70%;padding:0px'>
                    	            <li>".
                    			      elgg_view('input/text', array(
                    					'name'        => 'jot[project][milestone][title][]',
                    					'class'       => 'last_milestone_item',
                        			    'style'       => 'height:17px',
                    				))."
                				    </li>
                    				</div>
                    				<div class='rTableCell' style='width:200px;text-align:right;padding:0px 10px'>$target_date</div>
                    				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                        		</div>
                    		</div>";
                $form_body .= "<div class='new_milestone_item'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='milestone_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:70%;padding:0px'>
                	            <li>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[project][milestone][title][]',
                					'class'       => 'last_milestone_item',
                    			    'style'       => 'height:17px',
                				))."
            				    </li>
                				</div>
                				<div class='rTableCell' style='width:200px;text-align:right;padding:0px 10px'>$target_date</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
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
                unset($form_body, $hidden);
                $form_body .= elgg_view('input/submit',	 array('name'=>'save', 'value' => elgg_echo('save'), "class" => 'elgg-button-submit-element')).
                              elgg_view('input/submit',	 array('name'=>'apply', 'value' => elgg_echo('apply'), "class" => 'elgg-button-submit-element'));
                $hidden['subtype']        = $subtype;
                $hidden['aspect']         = $aspect;
                $hidden['guid']           = $jot->getGUID();
                // Interval Add                
                foreach($hidden as $field=>$value){
                    $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                }
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
                $interval_add_button = "<a title='add interval' class='elgg-button-submit-element add-schedule-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $hidden['jot[schedule][asset]']         = $asset->guid;
                $hidden['jot[schedule][container_guid]'] = $task_guid;
                $schedule_item_count = count($schedule);
//                , 
                $form_body .= "
                <div id='Schedule_panel' class='elgg-head' $style>";
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
                        unset($last_item_class, $n);
                    foreach ($schedule as $schedule_item){
                        unset($start_date, $end_date, $activity, $hidden);
                        if (++$n == $schedule_item_count){
                            $last_item_class = 'last_schedule_item';}
                        $start_date  = elgg_view('input/date', array('name'=> 'jot[schedule][start_date][]', 'value'=>$schedule_item->start_date, 'placeholder'=>'start date'));
                        $end_date    = elgg_view('input/date', array('name'=> 'jot[schedule][end_date][]'  , 'value'=>$schedule_item->end_date  , 'placeholder'=>'end date'));
                        $activity    = elgg_view('input/text', array('name'=> 'jot[schedule][title][]'     , 'value'=>$schedule_item->title     , 'placeholder' => 'Activity Name', 'class'=>$last_item_class));
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
                	</div>
                </div>";
                break;
            /****************************************
             * $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
                unset($form_body, $hidden);
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $ghost_assets = $jot->ghost_assets;
                $quebx_assets = $jot->assets;
                
        		foreach ($jot->assets as $guid) {
        			$entity = get_entity($guid);
        			if ($entity) {
        				$current_assets .= elgg_view('input/assetpicker/item', array(
        					'entity' => $entity,
        					'input_name' => 'jot[assets]',
        				));
        			}
        		}
                
                $form_body .= "
                <div id='Things_panel' class='elgg-head' $style>";
                $form_body .= "Things used during this experience
                	          <span title='Things in Quebx'>";
                $form_body .= elgg_view('input/assetpicker', array('name'=>'jot[assets]', 'values'=>$jot->assets));
//                $form_body .= elgg_view('input/assetpicker', array('name'=>'jot[assets]'));
//                $form_body .= "<ul class='elgg-asset-picker-list'>$current_assets</ul>";
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
                	</div>
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
                $delete = elgg_view("output/url",array('href'=>"#",'text'=>elgg_view_icon('delete-alt'),'title'=>'remove step','class'=>'remove-node',));
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
                        $task_link = elgg_view('input/longtext', array('name'=>'jot[instruction][step][description][]', 'value'=>$task_step->description, 'style'=>'height:17px',));
//                        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  "tasks/view/$task_step->guid"));
                        $steps .= "<div class='rTableRow' style='cursor:move'>
                    				<div class='rTableCell' style='width:100%;padding:0px'><li>$task_link</li></div>".
                    				elgg_view('input/hidden', array('name'=>'jot[instruction][step][guid][]', 'value'=>$task_step->getGUID()))."
                                    <div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'>$delete</div>
                    	        </div>";
                    }
                }
                else {
                    $steps .= "
                        <div class='rTableRow' style='cursor:move'>
                			<div class='rTableCell' style='width:100%;padding:0px'>
            	            <li>".
            			      elgg_view('input/longtext', array(
            					'name'        => 'jot[instruction][step][description][]',
            					'class'       => 'last_step_item',
            			        'style'       => 'height:17px',
            				))."
        				    </li>
            				</div>
            				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'>$delete</div>
                		</div>";
                }
                
                //Steps Add
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
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
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[instruction][step][description][]',
                					'class'       => 'last_step_item',
                			        'style'       =>'height:17px',
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
                unset($form_body, $hidden);
                $form_body .= "
                <div id='Documents_panel' class='elgg-head' $style>";
                $form_body .= elgg_view('input/dropzone', array(
                                    'name' => 'jot[documents]',
//                                    'value'=> $jot->documents,
                                    'default-message'=> '<strong>Drop document files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));
                $form_body .= "
				</div>";
                break;
            /****************************************
             * $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
                /*function quebx_get_object_by_title($subtype, $title)
                    {
                        global $CONFIG;
                        
                        $query ="SELECT e.* from {$CONFIG->dbprefix}entities e ".
                                "JOIN {$CONFIG->dbprefix}objects_entity o ON e.guid=o.guid ".
                                "WHERE e.subtype={$subtype} ".
                                "AND o.title=\"{$title}\" ".
                                "LIMIT 0, 1";
                        $row = get_data_row($query);
                        if ($row)
                            return new ElggObject($row);
                        else
                            return false;
                    }*/
                    
                $album_subtype = get_subtype_id('object', 'hjalbum');
                $title   = 'Default';
                $album = quebx_get_object_by_title($album_subtype, $title);
                                
                $form_body .= "
                <div id='Jot_experience_gallery_panel' class='elgg-head' $style>";
                $entity = $jot;
                $item_image_guids = $entity->images;  
                if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
                foreach ($item_image_guids as $key=>$image_guid){                               $display .= '490 ['.$key.'] => '.$image_guid.'<br>';
                	if ($image_guid == ''){                     
                		unset($item_image_guids[$key]);
                		continue;
                	}
/*                	for ($n=1; $n<10; $n++){
                	    $item_image_guids[] = $image_guid;
                	}
*/                }
                
                if (!empty($item_image_guids)){
                   $item_images = elgg_get_entities(array(
                		'type' => 'object',
                		'subtypes' => array('image', 'hjalbumimage'),
                		'guids'   => $item_image_guids,
                   		'limit'   => 0,
                	));
                
                   $thumbnails = "<div class='edit_gallery' style='margin:0 auto;'>";
                   $icon       = $entity->icon ?: $entity->guid;
                   
                				
                	foreach ($item_image_guids as $key=>$image_guid){
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
                			$input_checkbox = "<span title='select image'>".$input_checkbox."</span>";
                			$input_radio    = "<span title='set as default image'><input type='radio' name='item[icon]' value='$image_guid'></span>";
                		}
                		$input_images = elgg_view('input/hidden', array('name'=>'jot[images][]', 'value' => $image_guid));
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
                    $some_buttons .= elgg_view('input/submit',	 array('name'=>'apply', 'value' => elgg_echo('Pick'), "class" => 'elgg-button-submit-element', 'action' => 'pick_images', 'this_section' =>'Gallery'));
                    $some_buttons .= elgg_view('input/submit',	 array('name'=>'apply', 'value' => elgg_echo('delete'), "class" => 'elgg-button-submit-element', 'action' => 'delete_images', 'this_section' =>'Gallery'));
                    $form_body .=  "
                     <div class='rTable' style='width:100%'>
                        <div class='rTableBody'>
                            <div class='rTableRow'>
                    			<div class='rTableCell' style='width:50;padding:0px 5px'>$some_buttons</div>
                    			<div class='rTableCell' style='padding:0px 5px'>$thumbnails</div>
                    		</div>
                		</div>
                	</div>";
                }
                $form_body .= elgg_view('input/dropzone', array(
                                    'name' => 'jot[images]',
//                                    'value'=>$jot->images,
                                    'default-message'=> '<strong>Drop image or video files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
                                    'accept'=> 'image/*, video/*',
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $album->getGUID(),
									'subtype' => 'hjalbumimage',));
                $form_body .= "
				</div>";

                break;
            /****************************************
             * $section = 'progress'                *****************************************************************
             ****************************************/
            case 'progress':
                unset($form_body, $hidden);
                $form_body .= "
                <div id='Progress_panel' class='elgg-head' $style>";
                $form_body .= elgg_view('forms/experiences/edit',
                                  array('action'         => $action,
                                        'selected'       => $selected == 'Progress',
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
                                  array('action'         => 'edit',
                                        'container_guid' => $container_guid,
                                        'section'        => 'steps',));                     
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
                unset($form_body, $hidden);
                $title_field        = $title;
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
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "Things used
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
                break;
            /****************************************
             * $section = 'steps'                    *****************************************************************
             ****************************************/
            case 'steps':
                unset($form_body, $hidden);
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
                        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  "tasks/view/$task_step->guid"));
                        $steps .= "<div class='rTableRow'>
                    				<div class='rTableCell' style='width:100%;padding:0px'><li>$task_link</li></div>
                                    <div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    	        </div>";
                    }
                }
                else {$steps = false;}
                
                //Tasks Add
                $form_body .= "Your path<br>
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
                $form_body = 'Documents section';
                break;
            /****************************************
             * $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
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

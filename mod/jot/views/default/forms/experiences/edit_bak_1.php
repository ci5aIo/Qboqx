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
$snippet     = elgg_extract('snippet'          , $vars);
$selected    = elgg_extract('selected'         , $vars, false);
$action      = elgg_extract('action'           , $vars);
$tabs        = elgg_extract('tabs'             , $vars);
$presentation = elgg_extract('presentation'    , $vars);
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
$aspect      = $jot->aspect ?: 'experience';                                    $display .= '30 $action: '.$action.'<br>30 $aspect: '.$aspect.'<br>30 $section: '.$section.'<br>';


elgg_load_library('elgg:quebx');
$album = quebx_get_object_by_title(get_subtype_id('object', 'hjalbum'), 'Default');
$drop_media   = elgg_view('input/dropzone', array(
		'name' => 'jot[images]',
		'default-message'=> '<strong>Drop image or video files here</strong><br /><span>or click to select them from your computer</span>',
		'max' => 25,
		'accept'=> 'image/*, video/*',
		'multiple' => true,
		'style' => 'padding:0;',
		'container_guid' => $album->getGUID(),
		'subtype' => 'hjalbumimage',));

Switch ($action){
    case 'add':
        Switch ($section){
            case 'main':
                unset($form_body, $hidden, $buttons);
                $title              = elgg_extract('title', $vars, 'Experiences');
                $title_field        = elgg_view("input/text"    , array("name" => "jot[title]"         , 'placeholder' => 'Give your experience a name', 'style' => 'width:495px', 'id'=>'title', 'data-parsley-required'=>'true'));
                $description_field  = elgg_view("input/longtext", array("name" => "jot[description]"   , 'placeholder' => 'Describe the experience ...', 'style' => 'overflow: hidden; word-wrap: break-word; resize: horizontal; height: 52px; margin-left: 0px; margin-right: 0px;', 'id' => 'autosize-example'));
            	
            	$hidden['jot[owner_guid]']     = $owner_guid;
            	$hidden['jot[container_guid]'] = $container_guid;
            	$hidden['jot[subtype]']        = $aspect;
            	$buttons = elgg_view('input/submit',	 array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element'));
            break;
            /****************************************
*add********** $section = 'things'                 *****************************************************************
             ****************************************/
            case 'things':
                unset($form_body, $hidden, $current_asset_guid);
                if (elgg_instanceof($container, 'object', 'market') || elgg_instanceof($container, 'object', 'item')){
                    $current_asset_guid = $container_guid; 
                }
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a> <span id='other_button_label'>add other things not stored in Quebx</span>";
                $quebx_assets = array($current_asset_guid);
            break;
            /****************************************
*add********** $section = 'documents'                 *****************************************************************
             ****************************************/
            case 'documents':
                unset($form_body, $hidden);
            break;
            /****************************************
*add********** $section = 'gallery'                 *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
            break;
            /****************************************
*add********** $section = 'expand'                  *****************************************************************
             ****************************************/
            case 'expand':
                unset($form_body, $hidden);
                
                $nothing_panel     = NULL;
                $instruction_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'instruction',
                                               'guid'           => $guid,));
                $observation_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'observation',
                                               'guid'           => $guid,));
                $event_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'event',
                                               'guid'           => $guid,));
                $project_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'project',
                                               'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'instruction'             *****************************************************************
             ****************************************/
                case 'instruction':
                    unset($input_tabs, $form_body, $hidden);
                    $material_panel .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => 'add',
                                                 'section'        => 'instruction_material',
                                                 'guid'           => $guid,));
                    $tools_panel    .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => 'add',
                                                 'section'        => 'instruction_tools',
                                                 'guid'           => $guid,));
                    $steps_panel    .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => 'add',
                                                 'section'        => 'instruction_steps',
                                                 'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'event'                 *****************************************************************
             ****************************************/
                case 'event':
                    unset($stage_selector, $stage_params, $form_body, $hidden, $input_tabs);
                    $stage_params = array("name"    => "jot[event][stage]",
                                          "align"   => "horizontal",
                    					  "value"   => $jot->state ?: 1,
                                          "guid"    => $guid,
                    					  "options2" => array(
                    					                   array("label" =>"Planning",
                    					                         "option" => 1,
                    					                         "title" => "Initial planning stage"),
                    					                   array("label" => "In Process",
                    					                         "option" => 2,
                    					                         "title" => "Event is active and under way"),
                    					                   array("label" => "Postponed",
                    					                         "option" => 3,
                    					                         "title" => "Event will be rescheduled"),
                    					                   array("label" => "Cancelled",
                    					                         "option" => 4,
                    					                         "title" => "Event is cancelled and will not continue"),
                    					                   array("label" => "Complete",
                    					                         "option" => 5,
                    					                         "title" => "Event is complete.")
                    					                   ),
                    				      "default" => 1,
                    					 );
                    $stage_selector = elgg_view('input/quebx_radio', $stage_params);
                    
                    $input_tabs[] = array('title'=>'Planning'  , 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'event_planning'  , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'In Process', 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_in_process', 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Postponed' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_postponed' , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Cancelled' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_cancelled' , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Complete'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_complete'  , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'event_input', 'tabs'=>$input_tabs));
                    
                    $planning_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => 'add',
                                           'section'        => 'event_planning',
                                           'guid'           => $guid,));
                    $in_process_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => 'add',
                                           'section'        => 'event_in_process',
                                           'guid'           => $guid,));
                    $cancelled_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => 'add',
                                           'section'        => 'event_cancelled',
                                           'guid'           => $guid,));
                    $postponed_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => 'add',
                                           'section'        => 'event_postponed',
                                           'guid'           => $guid,));
                    $complete_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => 'add',
                                           'section'        => 'event_complete',
                                           'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'project'                 *****************************************************************
             ****************************************/
                case 'project':
                    unset($stage_selector, $stage_params, $form_body, $hidden);
                    $stage_params = array("name"    => "jot[project][stage]",
                                          'align'   => 'horizontal',
                    					  "value"   => 1,
                                          "guid"    => $guid,
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
                    
                    $input_tabs[] = array('title'=>'Planning'  , 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'project_planning'  , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'In Process', 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_in_process', 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Blocked'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_blocked'   , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Cancelled' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_cancelled' , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Complete'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_complete'  , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'project_input', 'tabs'=>$input_tabs));
                    
                    $milestones_panel = elgg_view('forms/experiences/edit', array(
                                                  'action'         => 'add',
                                                  'section'        => 'project_milestones',
                                                  'guid'           => $guid,));
                    $in_process_panel = elgg_view('forms/experiences/edit', array(
                                                  'action'         => 'add',
                                                  'section'        => 'project_in_process',
                                                  'guid'           => $guid,));
                    $blocked_panel    = elgg_view('forms/experiences/edit', array(
                                                  'action'         => 'add',
                                                  'section'        => 'project_blocked',
                                                  'guid'           => $guid,));
                    $cancelled_panel  = elgg_view('forms/experiences/edit', array(
                                                  'action'         => 'add',
                                                  'section'        => 'project_cancelled',
                                                  'guid'           => $guid,));
                    $complete_panel   = elgg_view('forms/experiences/edit', array(
                                                  'action'         => 'add',
                                                  'section'        => 'project_complete',
                                                  'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'observation'                 *****************************************************************
             ****************************************/
                case 'observation':                                                                 $display .= '229 $action: '.$action.'<br>229 $aspect: '.$aspect.'<br>229 $section: '.$section.'<br>';
                    unset($state_params, $state_selector, $form_body, $hidden);
                    $discoveries_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'add',
                                                   'section'        => 'observation_discoveries',
                                                   'guid'           => $guid,));
                    $resolve_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'add',
                                                   'section'        => 'observation_resolve',
                                                   'guid'           => $guid,));
                    $assign_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'add',
                                                   'section'        => 'observation_request',
                                                   'guid'           => $guid,));
                    $accept_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'add',
                                                   'section'        => 'observation_accept',
                                                   'guid'           => $guid,));
                    $complete_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'add',
                                                   'section'        => 'observation_complete',
                                                   'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'observation_discoveries'                 *****************************************************************
             ****************************************/
                case 'observation_discoveries':
                    unset($form_body, $hidden, $last_class);
                break;
            /****************************************
*add********** $section = 'observation_resolve'                 *****************************************************************
             ****************************************/
                case 'observation_resolve':
                    unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'observation_request'                 *****************************************************************
             ****************************************/
                case 'observation_request':
                    unset($form_body, $hidden, $form_items);
                    $next_request_number = 'RITM#####';
                    $request_selector    = 'placeholder';
                    $form_items .= "
                            <div class='rTableRow'>
                        		<div class='rTableCell' style='width:30%;padding:0px'>Request Number   		</div>
                        		<div class='rTableCell' style='width:30%;padding:0px'>$next_request_number".
                        	      elgg_view('input/hidden', array(
                        			'name'        => 'jot[observation][request][number][]',
                        	        'value'       => $next_request_number,))."                               </div>
                        		<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    		</div>";
                    $form_items .= "
                    	   <div class='rTableRow'>
                        		<div class='rTableCell' style='width:30%;padding:0px'>Request Type           </div>
                        		<div class='rTableCell' style='width:30%;padding:0px'>$request_selector      </div>
                        		<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    	   </div>";
                break;
            /****************************************
*add********** $section = 'observation_accept'                    *****************************************************************
             ****************************************/
            case 'observation_accept':
                unset($form_body, $hidden, $form_items);
                $form_items .= "
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                    	    <div class='rTableRow'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                    		      elgg_view('input/longtext', array(
                    				'name'        => 'jot[observation][accept][description][]',
                    	      	    'placeholder' => 'Acceptance notes',
                    			))."
                    			</div>
                   			</div>
                		</div>
                    </div>";
                break;
            /****************************************
*add********** $section = 'observation_complete'                 *****************************************************************
             ****************************************/
            case 'observation_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'instruction_material'     *****************************************************************
             ****************************************/
            case 'instruction_material':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                break;
            /****************************************
*add********** $section = 'instruction_tools'     *****************************************************************
             ****************************************/
            case 'instruction_tools':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                $tool    = elgg_view('input/text', array('name'=> 'jot[instruction][tool][title][]', 'class'=> 'last_tool_item','placeholder' => 'Tool Name',));
                break;
            /****************************************
*add********** $section = 'instruction_steps'        *****************************************************************
             ****************************************/
            case 'instruction_steps':
                unset($form_body, $hidden);
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                break;
            /****************************************
*add********** $section = 'project_milestones'       *****************************************************************
             ****************************************/
            case 'project_milestones':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'project_in_process'              *****************************************************************
             ****************************************/
            case 'project_in_process':
                unset($form_body, $hidden);
                $set_properties_button = elgg_view('output/url', array(
                           'id'    => 'properties_set',
                           'text'  => elgg_view_icon('settings-alt'), 
                           'title' => 'set properties',
                           'style' => 'cursor:pointer;',
                ));
            	$delete_button = "<label class=remove-progress-marker>". 
                            	    elgg_view('output/url', array(
                            	        'title'=>'remove progress marker',
                            	        'class'=>'remove-progress-marker',
                            	        'text' => elgg_view_icon('delete-alt'),
                            	    )).
                            	 "</label>";
            	$delete = elgg_view("output/span", array("class"=>"remove-progress-marker", "content"=>$delete_button));
                $expander = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'expander undraggable',
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',
                          ));
                $collapser = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'collapser autosaves',
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',
                          ));
                break;
            /****************************************
*add********** $section = 'project_blocked'              *****************************************************************
             ****************************************/
            case 'project_blocked':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'project_cancelled'              *****************************************************************
             ****************************************/
            case 'project_cancelled':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'project_complete'              *****************************************************************
             ****************************************/
            case 'project_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'event_planning'                 *****************************************************************
             ****************************************/
            case 'event_planning':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                $interval_add_button = "<a title='add interval' class='elgg-button-submit-element add-schedule-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $interval_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-schedule-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                $start_date  = elgg_view('input/date', array('name'=> 'jot[event][schedule][start_date][]', 'placeholder'=>'start date'));
                $end_date    = elgg_view('input/date', array('name'=> 'jot[event][schedule][end_date][]', 'placeholder'=>'end date'));
                $activity    = elgg_view('input/text', array('name'=> 'jot[event][schedule][title][]', 'class'=> 'last_schedule_item','placeholder' => 'Activity Name',));
                break;
            /****************************************
*add********** $section = 'event_in_process'              *****************************************************************
             ****************************************/
            case 'event_in_process':
                unset($form_body, $hidden); 
                break;
            /****************************************
*add********** $section = 'event_postponed'         *****************************************************************
             ****************************************/
            case 'event_postponed':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'event_cancelled'              *****************************************************************
             ****************************************/
            case 'event_cancelled':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'event_complete'              *****************************************************************
             ****************************************/
            case 'event_complete':
                unset($form_body, $hidden);
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
*edit********* $section = 'main'                     *****************************************************************
             ****************************************/
            case 'main':
                unset($form_body, $hidden, $buttons);
                $hidden['subtype']        = $subtype;
                $hidden['aspect']         = $aspect;
                $hidden['guid']           = $jot->getGUID();
                $buttons = elgg_view('input/submit', array('name'=>'save', 'value' => elgg_echo('save'), "class" => 'elgg-button-submit-element')).
                           elgg_view('input/submit', array('name'=>'apply', 'value' => elgg_echo('apply'), "class" => 'elgg-button-submit-element')).
                           elgg_view('output/url'  , array('href'=>'jot/view/'.$jot->getGUID(), 'text' => elgg_echo('cancel'), "class" => 'elgg-button-submit-element'));
                break;
            /****************************************
*edit********* $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
                unset($form_body, $hidden);
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $ghost_assets = is_array($jot->ghost_assets) ? $jot->ghost_assets : array($jot->ghost_assets);
                $quebx_assets = is_array($jot->assets)       ? $jot->assets       : array($jot->assets);
        		$quebx_assets = array_merge($quebx_assets, $ghost_assets);
        		break;
            /****************************************
*edit********* $section = 'event_planning'           *****************************************************************
             ****************************************/
            case 'event_planning':
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
                break;
             /****************************************
*edit********* $section = 'documents'                *****************************************************************
             ****************************************/
            case 'documents':
                unset($form_body, $hidden);
                if (!empty($jot->documents)){
                    foreach ($jot->documents as $document_guid){
                                $documents[] = (int) $document_guid;
                            } 
                }
                break;			 
            /****************************************
*edit********* $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
                $entity = $jot;
                $item_image_guids = $entity->images;  
                if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
                foreach ($item_image_guids as $key=>$image_guid){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
                	if ($image_guid == ''){                     
                		unset($item_image_guids[$key]);
                		continue;
                	}
                }
                break;
            /****************************************
*edit********* $section = 'expand'                  *****************************************************************
             ****************************************/
            case 'expand':
                unset($form_body, $hidden);
                
                $nothing_panel     = NULL;
                $instruction_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'instruction',
                                               'guid'           => $guid,));
                $observation_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'observation',
                                               'guid'           => $guid,));
                $event_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'event',
                                               'guid'           => $guid,));
                $project_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'project',
                                               'guid'           => $guid,));
                $effort_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'observation_effort',
                                               'guid'           => $guid,));                  //$form_body .= $effort_panel;
                break;

            /****************************************
*edit********** $section = 'observation'                 *****************************************************************
             ****************************************/
                case 'observation':                                                            $display .= '524 $action: '.$action.'<br>524 $aspect: '.$aspect.'<br>524 $section: '.$section.'<br>';
                    unset($state_params, $state_selector, $form_body, $hidden);
                    $discoveries_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'edit',
                                                   'section'        => 'observation_discoveries',
                                                   'guid'           => $guid,));
                    $resolve_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'edit',
                                                   'section'        => 'observation_resolve',
                                                   'guid'           => $guid,));
                    $assign_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'edit',
                                                   'section'        => 'observation_request',
                                                   'guid'           => $guid,));
                    $accept_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'edit',
                                                   'section'        => 'observation_accept',
                                                   'guid'           => $guid,));
                    $complete_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => 'edit',
                                                   'section'        => 'observation_complete',
                                                   'guid'           => $guid,));
                break;
            /****************************************
*edit********* $section = 'observation_discoveries'              *****************************************************************
             ****************************************/
            case 'observation_discoveries':
                unset($form_body, $hidden, $last_class);
                break;
            /****************************************
*edit********* $section = 'observation_resolve'               *****************************************************************
             ****************************************/
             case 'observation_resolve':
                unset($form_body, $hidden, $last_class, $list_items);
                $efforts     = elgg_get_entities_from_metadata(array(
                                    'type'              => 'object',
                                    'subtype'           => 'observation',
                                    'metadata_name_value_pairs' => array(
                                            'name'      => 'aspect', 
                                            'value'     => 'effort'),
                                    'container_guid'    => $guid,
                                    'order_by_metadata' => array(
                                            'name'      => 'sort_order', 
                                            'direction' => 'ASC', 
                                            'as'        => 'integer'),
                                    'limit'             => 0,
                        ));
                if (!empty($efforts)){
                    foreach ($efforts as $effort){
                        $list_items .= "
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => 'jot[observation][effort][title][]',
                    		        'value'       => $effort->description,
                    	      	    'placeholder' => 'Effort to resolve',
                    		        'style'       => 'height:17px;list-style:decimal;padding-left:20px'
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>".
            					                          elgg_view('input/hidden', array(
            					                                'name' => 'jot[observation][effort][guid][]',
            					                                'value'=> $effort->guid,
            					                        ))."
            					</div>
                		</div>";
                    }
                }
            /****************************************
*edit********* $section = 'project_milestones'               *****************************************************************
             ****************************************/
            case 'project_milestones':
                $milestones     = elgg_get_entities_from_metadata(array(
                                    'type'              => 'object',
                                    'subtype'           => 'project',
                                    'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'milestone'),
                                    'container_guid'    => $guid,
                                    'order_by_metadata' => array('name' => 'sort_order', 'direction' => 'ASC', 'as' => 'integer'),
                                    'limit'             => 0,
                        ));
            break;
            /****************************************
*edit********** $section = 'instruction'             *****************************************************************
             ****************************************/
                case 'instruction':
                    unset($input_tabs, $form_body, $hidden);
                    $material_panel = elgg_view('forms/experiences/edit', array(
                                                'action'         => 'edit',
                                                'section'        => 'instruction_material',
                                                'guid'           => $guid,));
                    $tools_panel    = elgg_view('forms/experiences/edit', array(
                                                'action'         => 'edit',
                                                'section'        => 'instruction_tools',
                                                'guid'           => $guid,));
                    $steps_panel    = elgg_view('forms/experiences/edit', array(
                                                'action'         => 'edit',
                                                'section'        => 'instruction_steps',
                                                'guid'           => $guid,));
                break;
            /****************************************
*edit********** $section = 'instruction_material'     *****************************************************************
             ****************************************/
            case 'instruction_material':
                unset($form_body, $hidden, $item, $line_items);
                $material_items = elgg_get_entities_from_metadata(array(
                	'type'           => 'object',
                	'subtype'        => 'instruction',
                    'container_guid' => $guid,
                	'limit'          => false,
                	'metadata_name_value_pairs' => array(
                	    'name'       => 'aspect',
                		'value'      => 'material'),
                	'order_by_metadata' => array(
                	    'name'       => 'sort_order',
                		'direction'  => ASC,
                		'as'         => 'integer'),
                ));
                if ($material_items){
                    $delete = "<a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                    foreach ($material_items as $item){
                        $line_items .= "
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>".
                			    elgg_view('input/number', array(
                			            'name'=> 'jot[instruction][material][qty][]', 
                			            'value'=> $item->qty, 
                			            'max'  => 0,      
                			            'placeholder' => '#'))."</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>".
                			    elgg_view('input/text', array(
                			            'name'=> 'jot[instruction][material][units][]', 
                			            'value'=> $item->units, 
                			            'max'  => 0,      
                			            'placeholder' => 'units'))."</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>".
                			    elgg_view('input/text', array(
                			            'name'=> 'jot[instruction][material][title][]', 
                			            'value'=> $item->title, 
                			            'max'  => 0,      
                			            'placeholder' => 'Material Name'))."</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'>$delete</div>
                		</div>";
                    }
                }
                break;
            /****************************************
*edit********** $section = 'instruction_tools'     *****************************************************************
             ****************************************/
            case 'instruction_tools':
                unset($form_body, $hidden, $item, $line_items);
                $tool_items = elgg_get_entities_from_metadata(array(
                	'type'           => 'object',
                	'subtype'        => 'instruction',
                    'container_guid' => $guid,
                	'limit'          => false,
                	'metadata_name_value_pairs' => array(
                	    'name'       => 'aspect',
                		'value'      => 'tool'),
                	'order_by_metadata' => array(
                	    'name'       => 'sort_order',
                		'direction'  => ASC,
                		'as'         => 'integer'),
                ));
                if ($tool_items){
                    $delete = "<a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                    foreach ($tool_items as $item){
                        $line_items .= "
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:95%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[instruction][tool][title][]',
                			        'value'       => $item->title,
                    			    'style'       => 'height:17px',
                				))."</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'>$delete        </div>
                		</div>";
                    }
                }
                break;
            /****************************************
*edit********** $section = 'instruction_steps'        *****************************************************************
             ****************************************/
            case 'instruction_steps':
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
//                $delete = elgg_view("output/url",array('href'=>"#",'text'=>elgg_view_icon('delete-alt'),'title'=>'remove step','class'=>'remove-node',));
                $delete = "<a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                if ($task_steps){
                    foreach ($task_steps as $task_step){
                        Switch ($task_step->getSubtype()){
                            case 'task': $element_type = 'task'; break;
                            case 'task_step': $element_type = 'step'; break;
                        };
/*                        $delete = elgg_view("output/confirmlink",array(
                			    	'href'    => "action/tasks/delete?guid=".$task_step->getGUID(),
                			    	'text'    => elgg_view_icon('delete-alt'),
                                    'title'   => 'remove step',
                			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
                			    	'encode_text' => false,
                			    ));*/
                        $delete = "<a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                        $task_link = elgg_view('input/longtext', array('name'=>'jot[instruction][step][description][]', 'value'=>$task_step->description, 'style'=>'height:17px',));
//                        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  "tasks/view/$task_step->guid"));
                        $steps .= "<div class='rTableRow' style='cursor:move'>
                    				<div class='rTableCell' style='width:100%;padding:0px'><li>$task_link</li></div>".
                    				    elgg_view('input/hidden', array('name'=>'jot[instruction][step][guid][]', 'value'=>$task_step->getGUID()))."
                                    <div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'>$delete</div>
                    	        </div>";
                    }
                }
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                break;
            /****************************************
*edit********** $section = 'observation_effort'      *****************************************************************
             ****************************************/
            case 'observation_effort':
                unset($form_body, $hidden);
                $effort_header = '<br>';
                $material_panel .= elgg_view('forms/experiences/edit', array(
                                             'action'         => 'edit',
                                             'section'        => 'observation_effort_material',
                                             'guid'           => $guid,));
                $tools_panel    .= elgg_view('forms/experiences/edit', array(
                                             'action'         => 'edit',
                                             'section'        => 'observation_effort_tools',
                                             'guid'           => $guid,));
                $steps_panel    .= elgg_view('forms/experiences/edit', array(
                                             'action'         => 'edit',
                                             'section'        => 'observation_effort_steps',
                                             'guid'           => $guid,));
                break;
            /****************************************
*edit********** $section = 'observation_effort_material'     *****************************************************************
             ****************************************/
            case 'observation_effort_material':
                unset($form_body, $hidden, $item, $line_items);
                $material_items = elgg_get_entities_from_metadata(array(
                	'type'           => 'object',
                	'subtype'        => 'effort',
                    'container_guid' => $guid,
                	'limit'          => false,
                	'metadata_name_value_pairs' => array(
                	    'name'       => 'aspect',
                		'value'      => 'material'),
                	'order_by_metadata' => array(
                	    'name'       => 'sort_order',
                		'direction'  => ASC,
                		'as'         => 'integer'),
                ));
                if ($material_items){
                    $delete = "<a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                    foreach ($material_items as $item){
                        $line_items .= "
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>".
                			    elgg_view('input/number', array(
                			            'name'=> 'jot[effort][material][qty][]', 
                			            'value'=> $item->qty, 
                			            'max'  => 0,      
                			            'placeholder' => '#'))."</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>".
                			    elgg_view('input/text', array(
                			            'name'=> 'jot[effort][material][units][]', 
                			            'value'=> $item->units, 
                			            'max'  => 0,      
                			            'placeholder' => 'units'))."</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>".
                			    elgg_view('input/text', array(
                			            'name'=> 'jot[effort][material][title][]', 
                			            'value'=> $item->title, 
                			            'max'  => 0,      
                			            'placeholder' => 'Material Name'))."</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'>$delete</div>
                		</div>";
                    }
                }
                break;
            /****************************************
*edit********** $section = 'observation_effort_tools'     *****************************************************************
             ****************************************/
            case 'observation_effort_tools':
                unset($form_body, $hidden, $item, $line_items);
                $tool_items = elgg_get_entities_from_metadata(array(
                	'type'           => 'object',
                	'subtype'        => 'instruction',
                    'container_guid' => $guid,
                	'limit'          => false,
                	'metadata_name_value_pairs' => array(
                	    'name'       => 'aspect',
                		'value'      => 'tool'),
                	'order_by_metadata' => array(
                	    'name'       => 'sort_order',
                		'direction'  => ASC,
                		'as'         => 'integer'),
                ));
                if ($tool_items){
                    $delete = "<a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                    foreach ($tool_items as $item){
                        $line_items .= "
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:95%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[instruction][tool][title][]',
                			        'value'       => $item->title,
                    			    'style'       => 'height:17px',
                				))."</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'>$delete        </div>
                		</div>";
                    }
                }
                break;
            /****************************************
*edit********** $section = 'observation_effort_steps'        *****************************************************************
             ****************************************/
            case 'observation_effort_steps':
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
//                $delete = elgg_view("output/url",array('href'=>"#",'text'=>elgg_view_icon('delete-alt'),'title'=>'remove step','class'=>'remove-node',));
                $delete = "<a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                if ($task_steps){
                    foreach ($task_steps as $task_step){
                        Switch ($task_step->getSubtype()){
                            case 'task': $element_type = 'task'; break;
                            case 'task_step': $element_type = 'step'; break;
                        };
/*                        $delete = elgg_view("output/confirmlink",array(
                			    	'href'    => "action/tasks/delete?guid=".$task_step->getGUID(),
                			    	'text'    => elgg_view_icon('delete-alt'),
                                    'title'   => 'remove step',
                			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
                			    	'encode_text' => false,
                			    ));*/
                        $delete = "<a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                        $task_link = elgg_view('input/longtext', array('name'=>'jot[instruction][step][description][]', 'value'=>$task_step->description, 'style'=>'height:17px',));
//                        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  "tasks/view/$task_step->guid"));
                        $steps .= "<div class='rTableRow' style='cursor:move'>
                    				<div class='rTableCell' style='width:100%;padding:0px'><li>$task_link</li></div>".
                    				    elgg_view('input/hidden', array('name'=>'jot[instruction][step][guid][]', 'value'=>$task_step->getGUID()))."
                                    <div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'>$delete</div>
                    	        </div>";
                    }
                }
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                break;
        }        
    break;
    case 'view':
        
    break;
}
/*Skip to generalized section*/
goto generalize;

Switch ($action){
/****************************************
 * $action = 'add'                       *****************************************************************************
 ****************************************/        
    case 'add':
        // Build sections        
        Switch ($section){
            /****************************************
*add********** $section = 'main'                     *****************************************************************
             ****************************************/
            case 'main':
                $form_body = "<b>$title</b>
        	    <div class='rTable' style='width:550px'>
        			<div class='rTableBody'>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$title_field".elgg_view('input/submit',	 array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element'))."</div>
        				</div>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$description_field</div>
        				</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>Add details or expand this experience</div>
            			</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>$tabs</div>
            			</div>
            		</div>
        		</div>";
        		$form_body .= $hidden;
        		
        		break;
            /****************************************
*add********** $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
                $form_body .= "<div panel='Things' guid=$guid aspect='attachments' guid=$guid id='Things_panel' class='elgg-head' $style>";
                $form_body .= "Things used during this experience";
                $form_body .= elgg_view('input/assetpicker', array('name'=>'jot[assets]', 'values'=>array($current_asset_guid), 'placeholder'=>'Start typing the name of an item'));
                $form_body .= "$item_add_button
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
*add********** $section = 'documents'                *****************************************************************
             ****************************************/
            case 'documents':
                $form_body .= "<div panel='Documents' guid=$guid aspect='attachments' guid=$guid id='Documents_panel' class='elgg-head' $style>";
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
*add********** $section = 'gallery'                  *****************************************************************
             ****************************************/
            case 'gallery': 
                $form_body .= "<div panel='Gallery' guid=$guid aspect='attachments' guid=$guid id='Jot_experience_gallery_panel' class='elgg-head' $style>";
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
*add********** $section = 'expand'                *****************************************************************
             ****************************************/
            case 'expand':
                $form_body .= $expand_script;
                $form_body .= "
                <div panel='Expand' guid=$guid id='Expand_panel' aspect='attachments' class='elgg-head' $style>";
                $form_body .= 'Expand this experience ...';
                $form_body .= $selector;
                $form_body .= "
                    <div id ='expand_nothing_panel' style='display: block;'>
                        $nothing_panel
                	</div>
                    <div id ='expand_instruction_panel' style='display: none;'>
                        $instruction_panel
                	</div>
                    <div id ='expand_observation_panel' style='display: none;'>
                        $observation_panel
                	</div>
                    <div id ='expand_event_panel' style='display: none;'>
                        $event_panel
                	</div>
                    <div id ='expand_project_panel' style='display: none;'>
                        $project_panel
                	</div>";
                $form_body .= "
				</div>";
                break;
            /****************************************
*add********** $section = 'instruction'                   *****************************************************************
             ****************************************/
            case 'instruction':
                $form_body .= "
                <div id='event_schedule_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Instruction</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:0px;'>
                                		<div panel='instruction_material'   guid=$guid aspect='instruction_input' id='instruction_material_panel'   class='elgg-head' style='display:block'>
                                		    $material_panel</div>
                                		<div panel='instruction_steps' guid=$guid aspect='instruction_input' id='instruction_steps_panel' class='elgg-head' style='display:none'>
                                		    $steps_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                break;
            /****************************************
*add********** $section = 'event'                   *****************************************************************
             ****************************************/
            case 'event':
                $form_body .= "<div id='event_schedule_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Event</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%'>Stage
                                    </div>
                					<div class='rTableCell' style='width:85%'>$stage_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:0px;'>
                                		<div panel='event_planning'   guid=$guid aspect='event_input' id='event_planning_panel'   class='elgg-head' style='display:block'>
                                		    $planning_panel</div>
                                		<div panel='event_in_process' guid=$guid aspect='event_input' id='event_in_process_panel' class='elgg-head' style='display:none'>
                                		    $in_process_panel</div>
                                        <div panel='event_postponed'  guid=$guid aspect='event_input' id='event_postponed_panel'  class='elgg-head' style='display:none;'>
                                            $postponed_panel</div>
                                        <div panel='event_cancelled'  guid=$guid aspect='event_input' id='event_cancelled_panel'  class='elgg-head' style='display:none;'>
                                            $cancelled_panel</div>
                                        <div panel='event_complete'   guid=$guid aspect='event_input' id='event_complete_panel'   class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                break;
            /****************************************
*add********** $section = 'project'                   *****************************************************************
             ****************************************/
            case 'project':
                $form_body .= "Convert this experience into a project.
                <div id='project_milestones_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Project</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%'>Stage
                                    </div>
                					<div class='rTableCell' style='width:85%'>$stage_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:0px;'>
                                		<div panel='project_planning'   guid=$guid aspect='project_input' id='project_planning_panel'   class='elgg-head' style='display:block'>
                                		    $milestones_panel</div>
                                		<div panel='project_in_process' guid=$guid aspect='project_input' id='project_in_process_panel' class='elgg-head' style='display:none'>
                                		    $in_process_panel</div>
                                        <div panel='project_blocked'    guid=$guid aspect='project_input' id='project_blocked_panel'    class='elgg-head' style='display:none;'>
                                            $blocked_panel</div>
                                        <div panel='project_cancelled'  guid=$guid aspect='project_input' id='project_cancelled_panel'  class='elgg-head' style='display:none;'>
                                            $cancelled_panel</div>
                                        <div panel='project_complete'   guid=$guid aspect='project_input' id='project_complete_panel'   class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                $form_body .= "
                </div>
                <hr>";
                break;
            /****************************************
*add********** $section = 'observation'              *****************************************************************
             ****************************************/
            case 'observation':
                $form_body .= "<h3>Observation</h3>";
                $form_body .="Details of the experience that may help to resolve issues.
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
                                		<div panel='observation_discoveries' guid=$guid aspect='observation_input' id='observation_discoveries_panel' class='elgg-head' style='display:block'>
                                		    $discoveries_panel</div>
                                		<div panel='observation_efforts'     guid=$guid aspect='observation_input' id='observation_efforts_panel'     class='elgg-head' style='display:none'>
                                		    $resolve_panel</div>
                                        <div panel='observation_request'      guid=$guid aspect='observation_input' id='observation_request_panel'      class='elgg-head' style='display:none;'>
                                            $assign_panel</div>
                                        <div panel='observation_accept'      guid=$guid aspect='observation_input' id='observation_accept_panel'      class='elgg-head' style='display:none;'>
                                            $accept_panel</div>
                                        <div panel='observation_complete'    guid=$guid aspect='observation_input' id='observation_complete_panel'    class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
            	$form_body .= "
                    <div id='line_store' style='visibility: hidden; display:inline-block;'>
                    	<div class='discovery'>
                    		<div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c".quebx_view_icon('pencil')." - xxx</div>
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
                    </div>
                <hr>";
                break;
            /****************************************
*add********** $section = 'discoveries'              *****************************************************************
             ****************************************/
            case 'discoveries':
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
            					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
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
*add********** $section = 'resolve'                  *****************************************************************
             ****************************************/
            case 'resolve':
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
*add********** $section = 'assign'                    *****************************************************************
             ****************************************/
            case 'assign':
                $form_body .= "<b>Assign to a specialist</b><br>
                    Early attempts to resolve were unsuccessful.  Time for the experts.<br>";
                break;
            /****************************************
*add********** $section = 'accept'                    *****************************************************************
             ****************************************/
            case 'accept':
                $form_body .= "<b>Accept</b><br>
                    Accept the situation and move on.<br>";
                break;
            /****************************************
*add********** $section = 'complete'                 *****************************************************************
             ****************************************/
            case 'complete':
                $form_body .= "<b>Task is complete and can be closed</b><br>";
                break;
            /****************************************
*add********** $section = 'instruction_material'     *****************************************************************
             ****************************************/
            case 'instruction_material':
                $form_body  .= "
                <div class='rTable' style='width:100%'>
            		<div id='sortable_interval' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$material_add_button</div>
            				<div class='rTableCell' style='width:15%'>Qty</div>
            				<div class='rTableCell' style='width:15%'>Units</div>
            				<div class='rTableCell' style='width:65%'>Material</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>";
                $form_body  .= "<div class='new_material_item'></div>";
                $form_body  .= "
                </div>
                    </div>";
                $form_body  .= "
                <div style='visibility:hidden'>
                	<div class='material_item'>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
            /****************************************
*add********** $section = 'instruction_steps'        *****************************************************************
             ****************************************/
            case 'instruction_steps':
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
*add********** $section = 'project_milestones'       *****************************************************************
             ****************************************/
            case 'project_milestones':
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
            /****************************************
*add********** $section = 'project_in_process'              *****************************************************************
             ****************************************/
            case 'project_in_process':
            	$form_body .= 'Project In Process';
                break;
            /****************************************
*add********** $section = 'project_blocked'              *****************************************************************
             ****************************************/
            case 'project_blocked':
                $form_body .= 'Project Blocked';
                break;
            /****************************************
*add********** $section = 'project_cancelled'              *****************************************************************
             ****************************************/
            case 'project_cancelled':
                $form_body .= 'Project Cancelled';
                break;
            /****************************************
*add********** $section = 'project_complete'              *****************************************************************
             ****************************************/
            case 'project_complete':
                $form_body .= 'Project Complete';
                break;
            /****************************************
*add********** $section = 'event_planning'                 *****************************************************************
             ****************************************/
            case 'event_planning':
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
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
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
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
            /****************************************
*add********** $section = 'event_in_process'              *****************************************************************
             ****************************************/
            case 'event_in_process':
                $form_body .= 'Event in process'; 
                break;
            /****************************************
*add********** $section = 'event_postponed'         *****************************************************************
             ****************************************/
            case 'event_postponed':
                $form_body .= 'Event postponed';
                break;
            /****************************************
*add********** $section = 'event_cancelled'              *****************************************************************
             ****************************************/
            case 'event_cancelled':
                $form_body .= 'Event cancelled';
                break;
            /****************************************
*add********** $section = 'event_complete'              *****************************************************************
             ****************************************/
            case 'event_complete':
                $form_body .= 'Event complete';
                break;
        }
        break;        
/****************************************
 * $action = 'edit'                      *****************************************************************************
 ****************************************/
    case 'edit':
        // Build sections        
        Switch ($section){
            /****************************************
*edit********* $section = 'main'                     *****************************************************************
             ****************************************/
            case 'main':
                $form_body .= elgg_view('input/submit',	 array('name'=>'save', 'value' => elgg_echo('save'), "class" => 'elgg-button-submit-element')).
                              elgg_view('input/submit',	 array('name'=>'apply', 'value' => elgg_echo('apply'), "class" => 'elgg-button-submit-element')).
                              elgg_view('output/url',	 array('href'=>'jot/view/'.$jot->getGUID(), 'text' => elgg_echo('cancel'), "class" => 'elgg-button-submit-element'));                // Interval Add                
                foreach($hidden as $field=>$value){
                    $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                }
                $form_body .= "
        	    <div class='rTable' style='width:700px'>
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
*edit********* $section = 'schedule'                 *****************************************************************
             ****************************************/
            case 'schedule': 
                $form_body .= "
                <div id='Schedule_panel' class='elgg-head' $style>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                    }
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
                        $delete = elgg_view("output/confirmlink",array(
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
*edit********* $section = 'things'                   *****************************************************************
             ****************************************/
            case 'things':
//                 unset($form_body, $hidden);
//                 $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
//                 $ghost_assets = is_array($jot->ghost_assets) ? $jot->ghost_assets : array($jot->ghost_assets);
//                 $quebx_assets = is_array($jot->assets)       ? $jot->assets       : array($jot->assets);
//         		$quebx_assets = array_merge($quebx_assets, $ghost_assets);
                $form_body .= "
                <div panel='Things' aspect='attachments' guid=$guid id='Things_panel' class='elgg-head' $style>";
                $form_body .= "Things used during this experience";
                $form_body .= elgg_view('input/assetpicker', array('values'=>$quebx_assets, 'name'=>'jot[assets]', 'other_name' => 'jot[ghost_assets][title]', 'snapshot'=>true));
                $form_body .= "$item_add_button <span id='other_button_label'>add other things not stored in Quebx</span>
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
*edit********* $section = 'steps'                    *****************************************************************
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
                $delete = elgg_view("output/url",array('href'=>"#",'text'=>elgg_view_icon('delete-alt'),'title'=>'remove step','class'=>'remove-node',));
                if ($task_steps){
                    foreach ($task_steps as $task_step){
                        Switch ($task_step->getSubtype()){
                            case 'task': $element_type = 'task'; break;
                            case 'task_step': $element_type = 'step'; break;
                        };
                        $delete = elgg_view("output/confirmlink",array(
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
//                 $icon        = elgg_get_site_url() . "mod/market/graphics/noimagetiny.png";
//                 $icon_link   = "<img id='draggable_image' src=\"{$icon}\">";
//                 $form_body .= "<div>$icon_link</div>";
                $form_body .= "$step_add_button <span id='step_button_label'>add step</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_instruction_step' style='list-style:decimal;padding-left:20px'>";
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
*edit********* $section = 'documents'                *****************************************************************
             ****************************************/
            case 'documents':
                unset($form_body, $hidden);
                if (!empty($jot->documents)){
                    foreach ($jot->documents as $document_guid){
                                $documents[] = (int) $document_guid;
                            } 
                }
                $form_body .= "
                <div panel='Documents' aspect='attachments' guid=$guid id='Documents_panel' class='elgg-head' $style>";
                $form_body .= elgg_view('input/dropzone', array(
                                    'name'           => 'jot[documents]',
                                    //'values'         => $documents,
                                    'default-message'=> '<strong>Drop document files here</strong><br /><span>or click to select them from your computer</span>',
									'max'            => 25,
									'multiple'       => true,
		                            //'style'          => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype'        => 'file',));
                if (!empty($documents)){
                    $form_body .= elgg_list_entities(array(
                            'guids'            =>$documents,
                            'input_name'       => 'jot[documents][]',
                            'input_value_field'=> 'guid'
                    ));
                }
                $form_body .= "
				</div>";
                break;
            /****************************************
*edit********* $section = 'gallery'                  *****************************************************************
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
//                $input_shelf = elgg_view('input/shelf', array('entity'=>$entity, 'aspect'=>'media_input', 'style'=>'min-height:36px'));
//                $input_shelf = shelf_list_entities(array('list_type'=>'gallery', 'select_type'=>'checkbox', 'view_type'=>'list_experience'));
                $input_shelf = 'placeholder';
                $input_new = elgg_view('input/dropzone', array(
                                    'name'   => 'jot[images]',
                                    //'values' => $item_images,
                                    'default-message'=> '<strong>Drop new media files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
                                    'accept'=> 'image/*, video/*, audio/*',
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $album->getGUID(),
									'subtype' => 'hjalbumimage',));
                $input_tabs[] = array('title'=>'Shelf', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'shelf_media_input', 'guid'=>$guid, 'aspect'=>'media_input');
                $input_tabs[] = array('title'=>'New'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'new_media_input', 'guid'=>$guid, 'aspect'=>'media_input');
                $input_tabs_x = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'media_input', 'tabs'=>$input_tabs));
                $input        = "
                <div class='rTable' style='width:100%'>
                   <div class='rTableBody'>
                        <div class='rTableRow'>
                    		<div class='rTableCell' style='width:20%;padding:0px;vertical-align:top'>$input_tabs_x
                            </div>
                            <div class='rTableCell' style='width:80%;padding:0px;'>
                        		<div panel='shelf_media_input' guid=$guid aspect='media_input' style='display:block'>$input_shelf
                                </div>
                        		<div panel='new_media_input' guid=$guid aspect='media_input' style='display:none'>$input_new
                                </div>
                            </div>
                        </div>
                   </div>
                </div>";
                               
                $form_body .= "
                <div panel='Gallery' guid=$guid aspect='attachments' id='Jot_experience_gallery_panel' class='elgg-head' $style>";
                $entity = $jot;
                $item_image_guids = $entity->images;  
                if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
                foreach ($item_image_guids as $key=>$image_guid){                               $display .= '490 ['.$key.'] => '.$image_guid.'<br>';
                	if ($image_guid == ''){                     
                		unset($item_image_guids[$key]);
                		continue;
                	}
                }
                $form_body .= $input;
				if (!empty($item_image_guids)){
                $form_body .= elgg_list_entities(array(
                                    'guids'            =>$item_image_guids,
                                    'list_type'        => 'list',
                                    'list_type_toggle' => true,
                                    'input_name'       => 'jot[images][]',
                                    'input_value_field'=> 'guid',
                ));
                }
                $form_body .= "
				</div>";

                break;
            /****************************************
*edit********* $section = 'expand'                *****************************************************************
             ****************************************/
            case 'expand':
                unset($form_body, $hidden);
                $form_body .= "
                <div panel='Expand' aspect='attachments' guid=$guid id='Expand_panel' class='elgg-head' style='display: block;'>";
                $form_body .= elgg_view('forms/experiences/edit',
                                  array('action'         => $action,
                                        'selected'       => $selected == 'Expand...',
                                        'container_guid' => $container_guid,
                                        'section'        => $aspect,));
                $form_body .= "
				</div>";
                break;
            /****************************************
*edit********* $section = 'experience'               *****************************************************************
             ****************************************/
            case 'experience':
                $options  = array(array('id'    =>'expand_nothing',
                                        'label' =>'Nothing',
                                        'option'=>'nothing',
                                        'title' =>'Nothing else'),
                                  array('id'    =>'expand_instruction',
                                        'label' =>'Instruction',
                                        'option'=>'instruction',
                                        'title' =>'Convert this experience into an instruction'),
                                  array('id'    =>'expand_observation',
                                        'label' =>'Observation',
                                        'option'=>'observation',
                                        'title' =>'Convert this experience into an observation'),
                                  array('id'    =>'expand_event',
                                        'label' =>'Event',
                                        'option'=>'event',
                                        'title' =>'Convert this experience into an event'),
                                  array('id'    =>'expand_project',
                                        'label' =>'Project',
                                        'option'=>'project',
                                        'title' =>'Convert this experience into a project'),
                                  array('id'    =>'expand_issue',
                                        'label' =>'Issue',
                                        'option'=>'issue',
                                        'title' =>'Convert this experience into an issue'),
                                 );
                $selector = elgg_view('input/quebx_radio', array(
                        'id'      => 'expand',
                        'name'    => 'jot[aspect]',
                        'value'   => 'nothing',
                        'options2'=> $options,
                        'align'   => 'horizontal',
                ));
                $nothing_panel     = NULL;
                $instruction_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'instruction',
                                               'guid'           => $guid,));
                $observation_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'observation',
                                               'guid'           => $guid,));
                $event_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'event',
                                               'guid'           => $guid,));
                $project_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'add',
                                               'section'        => 'project',
                                               'guid'           => $guid,));
                
                $form_body .= $expand_script;
                $form_body .= "
                <div panel='Expand' guid=$guid id='Expand_panel' aspect='attachments' class='elgg-head' $style>";
                $form_body .= 'What happens next?';
                $form_body .= $selector;
                $form_body .= "
                    <div id ='expand_nothing_panel' style='display: block;'>
                        $nothing_panel
                	</div>
                    <div id ='expand_instruction_panel' style='display: none;'>
                        <hr>
                        $instruction_panel
                	</div>
                    <div id ='expand_observation_panel' style='display: none;'>
                        <hr>
                        $observation_panel
                	</div>
                    <div id ='expand_event_panel' style='display: none;'>
                        <hr>
                        $event_panel
                	</div>
                    <div id ='expand_project_panel' style='display: none;'>
                        <hr>
                        $project_panel
                	</div>";
                $form_body .= "
				</div>";
                break;
            /****************************************
*edit********* $section = 'instruction'              *****************************************************************
             ****************************************/
            case 'instruction':
                unset($form_body, $hidden);
                $form_body .= elgg_view('forms/experiences/edit',
                                  array('action'         => 'edit',
                                        'container_guid' => $container_guid,
                                        'section'        => 'steps',));                     
                break;
            /****************************************
*edit********* $section = 'observation'              *****************************************************************
             ****************************************/
            case 'observation':
                unset($form_body, $hidden);
                
                $state_params = array("name"    => "jot[observation][state]",
                                      'align'   => 'horizontal',
                					  "value"   => $jot->state,
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
                
                $input_tabs[] = array('title'=>'Discovery', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'observation_discoveries', 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Resolve'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_efforts'    , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Assign'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_request'     , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Accept'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_accept'     , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Complete' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_complete'   , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'observation_input', 'tabs'=>$input_tabs));
                
                $discoveries_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'discoveries',
                                               'guid'           => $guid,));
                $resolve_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'resolve',
                                               'guid'           => $guid,));
                $assign_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'assign',
                                               'guid'           => $guid,));
                $accept_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'accept',
                                               'guid'           => $guid,));
                $complete_panel = elgg_view('forms/experiences/edit', array(
                                               'action'         => 'edit',
                                               'section'        => 'complete',
                                               'guid'           => $guid,));

                $form_body .= "<h3>Observation</h3>";
                $form_body .="Details of the experience that may help to resolve issues.
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
                                		<div panel='observation_discoveries' guid=$guid aspect='observation_input' id='observation_discoveries_panel' class='elgg-head' style='display:block'>
                                		    $discoveries_panel</div>
                                		<div panel='observation_efforts'     guid=$guid aspect='observation_input' id='observation_efforts_panel'     class='elgg-head' style='display:none'>
                                		    $resolve_panel</div>
                                        <div panel='observation_request'      guid=$guid aspect='observation_input' id='observation_request_panel'      class='elgg-head' style='display:none;'>
                                            $assign_panel</div>
                                        <div panel='observation_accept'      guid=$guid aspect='observation_input' id='observation_accept_panel'      class='elgg-head' style='display:none;'>
                                            $accept_panel</div>
                                        <div panel='observation_complete'    guid=$guid aspect='observation_input' id='observation_complete_panel'    class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                $form_body_xxx .= "<p></p>
                    <div aspect='observation_input' panel='observation_discoveries' guid=$guid id='observation_discoveries_panel' class='elgg-head' style='display: block;'>
                        $discoveries_panel</div>
                    <p></p>
                    <div aspect='observation_input' panel='observation_efforts' guid=$guid id='observation_efforts_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $resolve_panel</div>
                    <div aspect='observation_input' panel='observation_request' guid=$guid id='observation_request_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $assign_panel</div>
                    <div aspect='observation_input' panel='observation_accept' guid=$guid id='observation_accept_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $accept_panel</div>
                    <div aspect='observation_input' panel='observation_complete' guid=$guid id='observation_complete_panel' class='elgg-head' style='display: none;'>
                    <p></p>
                        $complete_panel</div>";
            	$form_body .= "
                    <div id='line_store' style='visibility: hidden; display:inline-block;'>
                    	<div class='discovery'>
                    		<div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
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
*edit********* $section = 'discoveries'              *****************************************************************
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
            	     <b>Discoveries</b><br>
            	        Interesting things learned during this experience:
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
            	foreach ($discoveries as $line=>$discovery) {
            		$form_body  .= 
            		" 		<div class='rTableRow' style='cursor:move'>
            					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
            					<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".
            					                           elgg_view('input/date', array(
            													'name'  => 'jot[observation][discovery][date][]',
            					                                'value' => $discovery->date,
            											))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".
            					                           elgg_view('input/longtext', array(
            									        		'name' => 'jot[observation][discovery][action][]',
            					                                'value'=> $discovery->action,
            									        		'class'=> 'rTableform',
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".
            					                          elgg_view('input/longtext', array(
            									        		'name' => 'jot[observation][discovery][observation][]',
            					                                'value'=> $discovery->observation,
            									        		'class'=> 'rTableform',
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".
            					                          elgg_view('input/longtext', array(
            									        		'name' => 'jot[observation][discovery][discovery][]',
            					                                'value'=> $discovery->discovery,
            									        		'class'=> 'rTableform'.$last_class,
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>".
            					                          elgg_view('input/hidden', array(
            					                                'name' => 'jot[observation][discovery][guid][]',
            					                                'value'=> $discovery->guid,
            					                        ))."</div>
            				</div>";
            	}// Populate blank lines
            	for ($i = $n+1; $i <= $n+3; $i++) {
            		$form_body  .= 
            		" 		<div class='rTableRow' style='cursor:move'>
            					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
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
            									        		'class'=> 'rTableform',
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
*edit********* $section = 'resolve'                  *****************************************************************
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
                
                $effort_add_button = "<a title='add effort' class='elgg-button-submit-element add-effort' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $form_body .= "<b>Efforts to Resolve</b><br>
                    Attempts made to resolve the issue.<br>
                    $effort_add_button <span id='effort_button_label'>add effort</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol style='list-style:decimal;padding-left:20px'>";
                if (!empty($efforts_existing)){
                    foreach ($efforts_existing as $effort){
                        $form_body .= "
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => 'jot[observation][effort][description][]',
                    		        'value'       => $effort->title,
                    	      	    'placeholder' => 'Effort to Resolve',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>".
            					                          elgg_view('input/hidden', array(
            					                                'name' => 'jot[observation][effort][guid][]',
            					                                'value'=> $effort->guid,
            					                        ))."
            					</div>
                		</div>";
                    }
                }
                // Populate blank lines
            	for ($i = $n+1; $i <= $n+0; $i++) {
            		$form_body .= "
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => 'jot[observation][effort][description][]',
                    				'class'       => 'last_effort',
                    	      	    'placeholder' => 'Effort to Resolve',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>
                    			</div>
                		</div>";
            	}
                $form_body .= "<div class='new_effort'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                
                break;
            /****************************************
*edit********* $section = 'assign'                    *****************************************************************
             ****************************************/
            case 'assign':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Assign to a specialist</b><br>
                    Early attempts to resolve were unsuccessful.  Time for the experts.<br>";
                break;
            /****************************************
*edit********* $section = 'accept'                    *****************************************************************
             ****************************************/
            case 'accept':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Accept</b><br>
                    Accept the situation and move on.<br>";
                break;
            /****************************************
*edit********* $section = 'complete'                 *****************************************************************
             ****************************************/
            case 'complete':
                unset($form_body, $hidden);
                
                $form_body .= "<b>Task is complete and can be closed</b><br>";
                break;
            /****************************************
*edit********* $section = 'milestones'               *****************************************************************
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
            /****************************************
*edit********* $section = 'legacy'                   *****************************************************************
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
                
                if (!empty($variables)){
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
*view********* $section = 'main'                     *****************************************************************
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
*view********* $section = 'schedule'                 *****************************************************************
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
*view********* $section = 'things'                   *****************************************************************
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
*view********* $section = 'steps'                    *****************************************************************
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
*view********* $section = 'documents'                *****************************************************************
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
*view********* $section = 'gallery'                  *****************************************************************
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
*view********* $section = 'expand'                *****************************************************************
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
*view********* $section = 'instruction'              *****************************************************************
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


/**************************************
 * Generalized Sections
 */
generalize:

Switch ($section){
/****************************************
 $section = 'main'                     *****************************************************************
****************************************/
            case 'main':
                $form_body .= $buttons;
                Switch($presentation){
                    case 'full':
                        $style = 'width:100%';
                        break;
                    case 'compact':
                        $style = 'width:550px';
                        break;
                    default:
                        $style = 'width:550px';
                }
                $form_body .= "<b>$title</b>
        	    <div class='rTable' style='$style'>
        			<div class='rTableBody'>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$title_field</div>
        				</div>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='padding: 0px 0px'>$description_field</div>
        				</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>Add details or expand this experience</div>
            			</div>
            			<div class='rTableRow'>
            				<div class='rTableCell' style='padding: 0px 0px'>$tabs</div>
            			</div>
            		</div>
        		</div>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
        		break;
/****************************************
$section = 'things'                   *****************************************************************
****************************************/
            case 'things':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<div panel='Things' guid=$guid aspect='attachments' guid=$guid id='Things_panel' class='elgg-head' $style>";
                $form_body .= "Things used during this experience";
                $form_body .= elgg_view('input/assetpicker', array('name'=>'jot[assets]', 'values'=>$quebx_assets, 'other_name' => 'jot[ghost_assets][title]', 'snapshot'=>true, 'placeholder'=>'Start typing the name of an item'));
                $form_body .= "$item_add_button
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
$section = 'documents'                *****************************************************************
****************************************/
            case 'documents':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<div panel='Documents' guid=$guid aspect='attachments' guid=$guid id='Documents_panel' class='elgg-head' $style>";
                $form_body .= elgg_view('input/dropzone', array(
                                    'name' => 'jot[documents]',
                                    'default-message'=> '<strong>Drop document files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));
                if (!empty($documents)){
                    $form_body .= elgg_list_entities(array(
                            'guids'            =>$documents,
                            'input_name'       => 'jot[documents][]',
                            'input_value_field'=> 'guid'
                    ));
                }
                $form_body .= "
				</div>";
                break;
/****************************************
$section = 'gallery'                  *****************************************************************
****************************************/
            case 'gallery':
                $input_shelf = 'placeholder';
                $input_tabs[] = array('title'=>'Shelf', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'shelf_media_input', 'guid'=>$guid, 'aspect'=>'media_input');
                $input_tabs[] = array('title'=>'New'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'new_media_input', 'guid'=>$guid, 'aspect'=>'media_input');
                $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'media_input', 'tabs'=>$input_tabs));
                $input        = "
                <div class='rTable' style='width:100%'>
                   <div class='rTableBody'>
                        <div class='rTableRow'>
                    		<div class='rTableCell' style='width:20%;padding:0px;vertical-align:top'>$input_tabs
                            </div>
                            <div class='rTableCell' style='width:80%;padding:0px;'>
                        		<div panel='shelf_media_input' guid=$guid aspect='media_input' style='display:block'>$input_shelf
                                </div>
                                <div panel='new_media_input' guid=$guid aspect='media_input' style='display:none'>$drop_media
                                </div>
                            </div>
                        </div>
                   </div>
                </div>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<div panel='Gallery' guid=$guid aspect='attachments' guid=$guid id='Jot_experience_gallery_panel' class='elgg-head' $style>";
                $form_body .= $input;
				if (!empty($item_image_guids)){
                    $form_body .= elgg_list_entities(array(
                                        'guids'            =>$item_image_guids,
                                        'list_type'        => 'list',
                                        'list_type_toggle' => true,
                                        'input_name'       => 'jot[images][]',
                                        'input_value_field'=> 'guid',
                    ));
                    }
                $form_body .= "
				</div>";
                break;
/****************************************
$section = 'expand'                     *****************************************************************
****************************************/
            case 'expand':
                $options  = array(array('id'    =>'expand_nothing',
                                        'label' =>'Nothing',
                                        'option'=>'nothing',
                                        'title' =>'Nothing else'),
                                  array('id'    =>'expand_instruction',
                                        'label' =>'Instructions',
                                        'option'=>'instruction',
                                        'title' =>'Convert this experience into an instruction'),
                                  array('id'    =>'expand_observation',
                                        'label' =>'Observation',
                                        'option'=>'observation',
                                        'title' =>'Convert this experience into an observation'),
                                  array('id'    =>'expand_event',
                                        'label' =>'Event',
                                        'option'=>'event',
                                        'title' =>'Convert this experience into an event'),
                                  array('id'    =>'expand_project',
                                        'label' =>'Project',
                                        'option'=>'project',
                                        'title' =>'Convert this experience into a project'),
                                  array('id'    =>'expand_issue',
                                        'label' =>'Issue',
                                        'option'=>'issue',
                                        'title' =>'Convert this experience into an issue'),
                                 );
                $selector = elgg_view('input/quebx_radio', array(
                        'id'      => 'expand',
                        'name'    => 'jot[aspect]',
                        'value'   => 'nothing',
                        'options2'=> $options,
                        'align'   => 'horizontal',
                ));
                $selector = 'Expand this experience ...'.$selector;
                $nothing_panel_display     = 'display: block;';
                $instruction_panel_display = 'display: none;';
                $observation_panel_display = 'display: none;';
                $event_panel_display       = 'display: none;';
                $project_panel_display     = 'display: none;';
                $effort_panel_display     = 'display: none;';
                if ($action = 'edit'){
                    if ($aspect != 'experience'){
                        $style                 = 'display: block;';
                        $nothing_panel_display = 'display: none;';
                        unset($selector);}
                    Switch ($aspect){
                        case 'instruction': $instruction_panel_display = 'display: block;'; break;
                        case 'observation': $observation_panel_display = 'display: block;'; break;
                        case 'event'      : $event_panel_display       = 'display: block;'; break;
                        case 'project'    : $project_panel_display     = 'display: block;'; break;
                        case 'effort'     : $effort_panel_display      = 'display: block;'; break;
                    }
                }
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "
                <div panel='Expand' guid=$guid id='Expand_panel' aspect='attachments' class='elgg-head' $style>";
                $form_body .= $selector;
                $form_body .= "
                    <div id ='expand_nothing_panel'     style='$nothing_panel_display'>
                        $nothing_panel
                	</div>
                    <div id ='expand_instruction_panel' style='$instruction_panel_display'>
                        $instruction_panel
                	</div>
                    <div id ='expand_observation_panel' style='$observation_panel_display'>
                        $observation_panel
                	</div>
                    <div id ='expand_event_panel'       style='$event_panel_display'>
                        $event_panel
                	</div>
                    <div id ='expand_project_panel'     style='$project_panel_display'>
                        $project_panel
                	</div>
                    <div id ='expand_effort_panel'     style='$effort_panel_display'>
                        $effort_panel
                	</div>";
                $form_body .= "
				</div>";
                break;
/****************************************
$section = 'instruction'                *****************************************************************
****************************************/
            case 'instruction':
                unset($input_tabs);
                $input_tabs[]    = array('title'=>'Material', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'instruction_material'  , 'guid'=>$guid, 'aspect'=>'instruction_input');
                $input_tabs[]    = array('title'=>'Tools'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'instruction_tools'     , 'guid'=>$guid, 'aspect'=>'instruction_input');
                $input_tabs[]    = array('title'=>'Steps'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'instruction_steps'     , 'guid'=>$guid, 'aspect'=>'instruction_input');
                $input_tabs      = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'instruction_input', 'tabs'=>$input_tabs));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "
                <div id='instruction_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Instructions</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='instruction_material'   guid=$guid aspect='instruction_input' id='instruction_material_panel'   class='elgg-head' style='display:block'>
                                		    $material_panel</div>
                                		<div panel='instruction_tools'   guid=$guid aspect='instruction_input' id='instruction_tools_panel'   class='elgg-head' style='display:none'>
                                		    $tools_panel</div>
                                		<div panel='instruction_steps' guid=$guid aspect='instruction_input' id='instruction_steps_panel' class='elgg-head' style='display:none'>
                                		    $steps_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                break;
/****************************************
$section = 'event'                      *****************************************************************
****************************************/
            case 'event':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<div id='event_schedule_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Event</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%'>Stage
                                    </div>
                					<div class='rTableCell' style='width:85%'>$stage_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='event_planning'   guid=$guid aspect='event_input' id='event_planning_panel'   class='elgg-head' style='display:block'>
                                		    $planning_panel</div>
                                		<div panel='event_in_process' guid=$guid aspect='event_input' id='event_in_process_panel' class='elgg-head' style='display:none'>
                                		    $in_process_panel</div>
                                        <div panel='event_postponed'  guid=$guid aspect='event_input' id='event_postponed_panel'  class='elgg-head' style='display:none;'>
                                            $postponed_panel</div>
                                        <div panel='event_cancelled'  guid=$guid aspect='event_input' id='event_cancelled_panel'  class='elgg-head' style='display:none;'>
                                            $cancelled_panel</div>
                                        <div panel='event_complete'   guid=$guid aspect='event_input' id='event_complete_panel'   class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                break;
/****************************************
$section = 'project'                     *****************************************************************
****************************************/
            case 'project':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<div id='project_milestones_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Project</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%'>Stage
                                    </div>
                					<div class='rTableCell' style='width:85%'>$stage_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='project_planning'   guid=$guid aspect='project_input' id='project_planning_panel'   class='elgg-head' style='display:block;'>
                                		    $milestones_panel</div>
                                		<div panel='project_in_process' guid=$guid aspect='project_input' id='project_in_process_panel' class='elgg-head panel items_draggable clearfix' style='display:none'>
                                		    $in_process_panel</div>
                                        <div panel='project_blocked'    guid=$guid aspect='project_input' id='project_blocked_panel'    class='elgg-head' style='display:none;'>
                                            $blocked_panel</div>
                                        <div panel='project_cancelled'  guid=$guid aspect='project_input' id='project_cancelled_panel'  class='elgg-head' style='display:none;'>
                                            $cancelled_panel</div>
                                        <div panel='project_complete'   guid=$guid aspect='project_input' id='project_complete_panel'   class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                $form_body .= "
                </div>";
                break;
/****************************************
$section = 'observation'              *****************************************************************
****************************************/
            case 'observation':
                $state                  = $entity->state ?: 1;
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
                					                   array("label" => "Request",
                					                         "option" => 3,
                					                         "title" => "Request the help of a specialist"),
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
                                
                $input_tabs[] = array('title'=>'Discovery', 'selected' => $state == 1, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_discoveries', 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Resolve'  , 'selected' => $state == 2, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_efforts'    , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Request'  , 'selected' => $state == 3, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_request'     , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Accept'   , 'selected' => $state == 4, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_accept'     , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs[] = array('title'=>'Complete' , 'selected' => $state == 5, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_complete'   , 'guid'=>$guid, 'aspect'=>'observation_input');
                $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'observation_input', 'tabs'=>$input_tabs));

                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<h3>Observation</h3>";
                $form_body .="Details of the experience that may help to resolve issues.
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:10%'>Stage:
                                    </div>
                					<div class='rTableCell' style='width:90%'>$state_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:10%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:90%;padding:5px;'>
                                		<div panel='observation_discoveries' guid=$guid aspect='observation_input' id='observation_discoveries_panel' class='elgg-head' style='$selected_panel[1]'>
                                		    $discoveries_panel</div>
                                		<div panel='observation_efforts'     guid=$guid aspect='observation_input' id='observation_efforts_panel'     class='elgg-head' style='$selected_panel[2]'>
                                		    $resolve_panel</div>
                                        <div panel='observation_request'      guid=$guid aspect='observation_input' id='observation_request_panel'      class='elgg-head' style='$selected_panel[3]'>
                                            $assign_panel</div>
                                        <div panel='observation_accept'      guid=$guid aspect='observation_input' id='observation_accept_panel'      class='elgg-head' style='$selected_panel[4]'>
                                            $accept_panel</div>
                                        <div panel='observation_complete'    guid=$guid aspect='observation_input' id='observation_complete_panel'    class='elgg-head' style='$selected_panel[5]'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
            	$form_body .= "
                    <div id='line_store' style='display:none;'>
                    	<div class='discovery'>
                    		<div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>".quebx_view_icon('move')."</div>
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
//                    		        'style'       => 'height:17px',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                    </div>
                <hr>";
                break;
/****************************************
$section = 'observation_discoveries'              *****************************************************************
****************************************/
            case 'observation_discoveries':
                $options     = array('type'              => 'object',
                                     'subtype'           => 'observation',
                                     'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'discovery'),
                                     'container_guid'    => $guid,
                                     'order_by_metadata' => array('name' => 'sort_order', 'direction' => 'ASC', 'as' => 'integer'),
                               );
                $discoveries = elgg_get_entities_from_metadata($options);
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
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
            	if (!empty($discoveries)){
            	    foreach ($discoveries as $line=>$discovery) {
            	        $form_body  .= 
                		" 		<div class='rTableRow' style='cursor:move'>
                					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                					<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".
                					                           elgg_view('input/date', array(
                													'name'  => 'jot[observation][discovery][date][]',
                					                                'value' => $discovery->date,
                											))."</div>
                					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".
                					                           elgg_view('input/longtext', array(
                									        		'name' => 'jot[observation][discovery][action][]',
                					                                'value'=> $discovery->action,
                									        		'class'=> 'rTableform',
                				                                    'style'=> 'height:17px',
                									        ))."</div>
                					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".
                					                          elgg_view('input/longtext', array(
                									        		'name' => 'jot[observation][discovery][observation][]',
                					                                'value'=> $discovery->observation,
                									        		'class'=> 'rTableform',
                				                                    'style'=> 'height:17px',
                									        ))."</div>
                					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".
                					                          elgg_view('input/longtext', array(
                									        		'name' => 'jot[observation][discovery][discovery][]',
                					                                'value'=> $discovery->discovery,
                									        		'class'=> 'rTableform'.$last_class,
                				                                    'style'=> 'height:17px',
                									        ))."</div>
                					<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>".
                					                          elgg_view('input/hidden', array(
                					                                'name' => 'jot[observation][discovery][guid][]',
                					                                'value'=> $discovery->guid,
                					                        ))."</div>
                				</div>";
                	}
            	}
            	// Populate blank lines
            	for ($i = $n+1; $i <= $n+1; $i++) {
            	    if ($i == 3){
            	        $last_class = ' last_discovery';
            	    }
            		$form_body  .= 
            		" 		<div class='rTableRow' style='cursor:move'>
            					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>".quebx_view_icon('move')." </div>
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
$section = 'observation_resolve'                    *****************************************************************
****************************************/
            case 'observation_resolve':
                $effort_add_button = "<a title='add effort' class='elgg-button-submit-element add-effort' style='cursor:pointer;height:14px;width:30px'>+</a>";
                unset($form_body, $hidden, $last_class, $list_items);
                $efforts     = elgg_get_entities_from_metadata(array(
                                    'type'              => 'object',
                                    'subtype'           => 'observation',
                                    'metadata_name_value_pairs' => array(
                                            'name'      => 'aspect', 
                                            'value'     => 'effort'),
                                    'container_guid'    => $guid,
                                    'order_by_metadata' => array(
                                            'name'      => 'sort_order', 
                                            'direction' => 'ASC', 
                                            'as'        => 'integer'),
                                    'limit'             => 0,
                        ));
                if (!empty($efforts)){
                    foreach ($efforts as $effort){
                        $list_items .= "
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
/*                    		      elgg_view('input/longtext', array(
                    				'name'        => 'jot[observation][effort][description][]',
                    		        'value'       => $effort->description,
                    	      	    'placeholder' => 'Effort to resolve',
                    		        'style'       => 'height:17px;list-style:decimal'
                    			))
*/                    			   elgg_view('output/url', array(
                                        'text'  => $effort->title,
                                        'href'  => 'jot/edit/'.$effort->guid,
                                ))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>".
            					                          elgg_view('input/hidden', array(
            					                                'name' => 'jot[observation][effort][guid][]',
            					                                'value'=> $effort->guid,
            					                        ))."
            					</div>
                		</div>";
                    }
                }

                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<b>Efforts to Resolve</b><br>
                    Attempts made to resolve the issue.<br>
                    $effort_add_button <span id='effort_button_label'>add effort</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_observation_resolve', style='list-style:decimal;padding-left:20px'>
                            $list_items";
                
                // Populate blank lines
                $n=0;
            	for ($i = $n+1; $i <= $n+0; $i++) {
            		$form_body .= "
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => 'jot[observation][effort][title][]',
                    				'class'       => 'last_effort',
                    	      	    'placeholder' => 'Effort to Resolve',
                    		        'style'       => 'height:17px',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>
                    			</div>
                		</div>";
            	}
                $form_body .= "<div class='new_effort'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                
                break;
/****************************************
$section = 'observation_request'                    *****************************************************************
****************************************/
            case 'observation_request':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "
                	<p><b>Request the help of a specialist</b></p>
                    <p>Requesting assistance converts the Observation into a Request and routes it to the appropriate service provider.  Quebx routes the request based on the profile of the attached Things and on selections made below.</p><hr>
            		<div class='rTable' style='width:100%'>
                		<div class='rTableBody'>";
                $form_body .= $form_items;
                $form_body .= "
                	    </div>
                    </div>";
                break;
/****************************************
$section = 'observation_accept'                    *****************************************************************
****************************************/
            case 'observation_accept':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "
                    <b>Accept</b><br>
                    Accept the situation and move on.<br>
                	$form_items";
                break;
/****************************************
$section = 'observation_complete'                 *****************************************************************
****************************************/
            case 'observation_complete':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<p><b>Complete</b></p>
                	<p>Task is complete and can be closed</p>
                	$form_items";
                break;
/****************************************
$section = 'observation_effort_material'     *****************************************************************
****************************************/
            case 'observation_effort_material':
                $material_add_button = "<a title='add material item' class='elgg-button-submit-element add-material-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $material_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-material-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                $qty         = elgg_view('input/number', array('name'=> 'jot[effort][material][qty][]', 'value'=> 1, 'max'  => 0,     ));
                $units       = elgg_view('input/text', array('name'=> 'jot[effort][material][units][]',                               ));
                $material    = elgg_view('input/text', array('name'=> 'jot[effort][material][title][]', 'class'=> 'last_material_item'));
                
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body  .= "Materials<br>
                <div class='rTable' style='width:100%'>
            		<div id='sortable_instruction_material' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$material_add_button</div>
            				<div class='rTableCell' style='width:15%'>Qty</div>
            				<div class='rTableCell' style='width:15%'>Units</div>
            				<div class='rTableCell' style='width:65%'>Material</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>
                        $line_items
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>";
                $form_body  .= "<div class='new_material_item'></div>";
                $form_body  .= "
                </div>
                    </div>";
                $form_body  .= "
                <div style='visibility:hidden'>
                	<div class='material_item'>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
/****************************************
$section = 'observation_effort_tools'          *****************************************************************
****************************************/
            case 'observation_effort_tools':
                $tool_add_button = "<a title='add tool' class='elgg-button-submit-element add-tool-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $tool_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-tool-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Tools<br>
                    $tool_add_button <span id='tool_button_label'>add tool</span>
                    <div class='rTable' style='width:100%'>
                		<div id='sortable_instruction_tool' class='rTableBody'>
                            $line_items
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[effort][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                $form_body .= "<div class='new_tool_item'></div>";
                $form_body .= "
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='tool_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[effort][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
/****************************************
$section = 'observation_effort_steps'          *****************************************************************
****************************************/
            case 'observation_effort_steps':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Steps<br>
                    $step_add_button <span id='step_button_label'>add step</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_instruction_step' style='list-style:decimal;padding-left:20px'>
                            $steps
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                	            <li>".
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[effort][step][description][]',
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
                					'name'        => 'jot[effort][step][description][]',
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
$section = 'instruction_material'     *****************************************************************
****************************************/
            case 'instruction_material':
                $material_add_button = "<a title='add material item' class='elgg-button-submit-element add-material-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $material_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-material-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                $qty         = elgg_view('input/number', array('name'=> 'jot[instruction][material][qty][]', 'value'=> 1, 'max'  => 0,     ));
                $units       = elgg_view('input/text', array('name'=> 'jot[instruction][material][units][]',                               ));
                $material    = elgg_view('input/text', array('name'=> 'jot[instruction][material][title][]', 'class'=> 'last_material_item'));
                
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body  .= "Material<br>
                <div class='rTable' style='width:100%'>
            		<div id='sortable_instruction_material' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$material_add_button</div>
            				<div class='rTableCell' style='width:15%'>Qty</div>
            				<div class='rTableCell' style='width:15%'>Units</div>
            				<div class='rTableCell' style='width:65%'>Material</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>
                        $line_items
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>";
                $form_body  .= "<div class='new_material_item'></div>";
                $form_body  .= "
                </div>
                    </div>";
                $form_body  .= "
                <div style='visibility:hidden'>
                	<div class='material_item'>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
/****************************************
$section = 'instruction_tools'          *****************************************************************
****************************************/
            case 'instruction_tools':
                $tool_add_button = "<a title='add tool' class='elgg-button-submit-element add-tool-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $tool_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-tool-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Tools<br>
                    $tool_add_button <span id='tool_button_label'>add tool</span>
                    <div class='rTable' style='width:100%'>
                		<div id='sortable_instruction_tool' class='rTableBody'>
                            $line_items
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[instruction][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                $form_body .= "<div class='new_tool_item'></div>";
                $form_body .= "
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='tool_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[instruction][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
/****************************************
$section = 'instruction_steps'          *****************************************************************
****************************************/
            case 'instruction_steps':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Steps<br>
                    $step_add_button <span id='step_button_label'>add step</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_instruction_step' style='list-style:decimal;padding-left:20px'>
                            $steps
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
$section = 'event_planning'                 *****************************************************************
****************************************/
            case 'event_planning':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                    }
                }
                $form_body  .= "<p></p>
                <div class='rTable' style='width:100%'>
            		<div id='sortable_interval' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$interval_add_button</div>
            				<div class='rTableCell' style='width:15%'>Start</div>
            				<div class='rTableCell' style='width:15%'>End</div>
            				<div class='rTableCell' style='width:65%'>Activity</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>";
                
                if ($action == 'edit'){
                    if (!empty($schedule)){
                        unset($last_item_class, $n);
                        foreach ($schedule as $schedule_item){
                            unset($set_start_date, $set_end_date, $set_activity, $set_hidden);
                            if (++$n == $schedule_item_count){
                                $last_item_class = 'last_schedule_item';}
                            $set_start_date  = elgg_view('input/date', array('name'=> 'jot[schedule][start_date][]', 'value'=>$schedule_item->start_date, 'placeholder'=>'start date'));
                            $set_end_date    = elgg_view('input/date', array('name'=> 'jot[schedule][end_date][]'  , 'value'=>$schedule_item->end_date  , 'placeholder'=>'end date'));
                            $set_activity    = elgg_view('input/text', array('name'=> 'jot[schedule][title][]'     , 'value'=>$schedule_item->title     , 'placeholder' => 'Activity Name', 'class'=>$last_item_class));
                            $set_delete = elgg_view("output/confirmlink",array(
                    			    	'href' => "action/jot/delete?guid=".$schedule_item->getGUID(),
                    			    	'text' => elgg_view_icon('delete-alt'),
                    			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
                    			    	'encode_text' => false,
                    			    ));
                            $set_hidden['jot[schedule][guid][]']         = $schedule_item->guid;
                            foreach($set_hidden as $field=>$value){
                                $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                            }
                            $form_body .= "<div class='rTableRow' style='cursor:move'>
                				<div class='rTableCell' style='width:15%;padding:0px'>$set_start_date</div>
                				<div class='rTableCell' style='width:15%;padding:0px'>$set_end_date</div>
                				<div class='rTableCell' style='width:65%;padding:0px'>$set_activity</div>
                				<div class='rTableCell' style='width:5%;padding:0px 10px'>$set_delete</div>
                    		</div>";
                        }
                    }
                }
                
                $form_body .="
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
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
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
/****************************************
$section = 'event_in_process'              *****************************************************************
****************************************/
            case 'event_in_process':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event in process'; 
                break;
/****************************************
$section = 'event_postponed'         *****************************************************************
****************************************/
            case 'event_postponed':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event postponed';
                break;
/****************************************
$section = 'event_cancelled'              *****************************************************************
****************************************/
            case 'event_cancelled':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event cancelled';
                break;
/****************************************
$section = 'event_complete'              *****************************************************************
****************************************/
            case 'event_complete':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event complete';
                break;
/****************************************
$section = 'project_milestones'       *****************************************************************
****************************************/
            case 'project_milestones':
                $target_date     = elgg_view('input/date', array('name'=> 'jot[project][milestone][target_date][]', 'placeholder'=>'target date'));
                $milestone_add_button = "<a title='add milestone' class='elgg-button-submit-element add-milestone-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project milestones<br>';
                $form_body .= "$milestone_add_button <span id='milestone_button_label'>add milestone</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_project_milestones' style='list-style:decimal;padding-left:20px'>
                        	<div class='milestone_item'>";
                if (!empty($milestones)){
                    foreach ($milestones as $milestone){
                        $form_body .= "<div class='rTableRow' style='cursor:move'>
                        			<div class='rTableCell' style='width:70%;padding:0px'>
                    	            <li>".
                    			      elgg_view('input/text', array(
                    					'name'        => 'jot[project][milestone][title][]',
                    			        'value'       => $milestone->title,
                        			    'style'       => 'height:17px',
                    				))."
                				    </li>
                    				</div>
                    				<div class='rTableCell' style='width:200px;text-align:right;padding:0px 10px'>".
                    				    elgg_view('input/date', array(
                    				            'name'=> 'jot[project][milestone][target_date][]',
                    				            'value'=>$milestone->target_date,
                    				            'placeholder'=>'target date'))."
                    		        </div>
                    				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                        		</div>
                    		</div>";
                    }
                }
                $form_body .= "<div class='rTableRow' style='cursor:move'>
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
/****************************************
$section = 'project_in_process'              *****************************************************************
****************************************/
                        case 'project_in_process':
				switch($snippet){
					case 'marker':
						$cid          = 'c263';
						$view_id      = 'view274';
						$data_id      = '147996415';

						$edit_details = "	<div>
											   <div id='$view_id' class='edit details'>
												   <section class='edit' data-aid='StoryDetailsEdit' tabindex='-1'>
													  <section class='model_details'>
														  <section class='story_or_epic_header'>
															<a class='autosaves collapser' id='story_collapser_$cid' tabindex='-1'></a>
															<fieldset class='name'>
																<div data-reactroot='' class='AutosizeTextarea___2iWScFt62'>
																	<div class='AutosizeTextarea__container___31scfkZp'>
																	   <textarea data-aid='name' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='story[name]'></textarea>
																	</div>
																</div>
															 </fieldset>
														  </section>
														  <aside>
															<div class='wrapper'>
															  <nav class='edit'>
																  <section class='controls'>
																	  <div class='persistence use_click_to_copy'>
																		<button class='autosaves button std close' type='button' tabindex='-1'>Close</button>
																	  </div>
																	  <div class='actions'>
																		  <div class='bubble'></div>
																		  <button type='button' title='Copy this story&#39;s link to your clipboard' data-clipboard-text='https://www.pivotaltracker.com/story/show/147996415' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1'></button>
																		<div class='button_with_field'>
																		  <button type='button' title='Copy this story&#39;s ID to your clipboard' data-clipboard-text='147996415' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1'></button>
																		  <input type='text' readonly='' class='autosaves id text_value' value='#147996415' tabindex='-1'>
																		</div>
																		<button type='button' title='Clone this story' class='autosaves clone_story hoverable left_endcap' tabindex='-1'></button>
																		<button type='button' title='View the history of this story' class='autosaves history hoverable capped' tabindex='-1'></button>
																		<button type='button' title='Delete this story' class='autosaves delete hoverable right_endcap remove-progress-marker' tabindex='-1'></button>
																	  </div>
																	</section>
															  </nav>
															  <div class='story info_box' style='display:block'><!-- hidden -->
																<div class='info'>
																	<div class='type row'>
																	  <em>Story Type</em>
																	  <div class='dropdown story_type'>
																		<input aria-hidden='true' type='hidden' name='story[story_type]' value='feature'>
																		<input aria-hidden='true' type='text' id='story_type_dropdown_c1058_honeypot' tabindex='0' class='honeypot'>
																		<a id='story_type_dropdown_c1058' class='selection item_feature' tabindex='-1'><span>feature</span></a>
																		<a id='story_type_dropdown_c1058_arrow' class='arrow target' tabindex='-1'></a>
																	  <section>
																		<div class='dropdown_menu search'>
																			  <div class='search_item'><input aria-label='search' type='text' id='story_type_dropdown_c1058_search' class='search'>
																			  </div>
																		  <ul>
																			  <li class='no_search_results hidden'>No results match.</li>
																			  <li data-value='feature' data-index='1' class='dropdown_item selected'>
																				  <a class='item_feature ' id='feature_story_type_dropdown_c1058' href='#'>
																					  <span>
																						  <span class='dropdown_label'>feature</span></span></a></li>
																			  <li data-value='bug' data-index='2' class='dropdown_item'><a class='item_bug ' id='bug_story_type_dropdown_c1058' href='#'><span><span class='dropdown_label'>bug</span></span></a></li>
																			  <li data-value='chore' data-index='3' class='dropdown_item'><a class='item_chore ' id='chore_story_type_dropdown_c1058' href='#'><span><span class='dropdown_label'>chore</span></span></a></li>
																			  <li data-value='release' data-index='4' class='dropdown_item'><a class='item_release ' id='release_story_type_dropdown_c1058' href='#'><span><span class='dropdown_label'>release</span></span></a></li>
																		  </ul>
																		</div>
																	  </section>
																	</div>
																</div>
																<div class='estimate row'>
																  <em>Points</em>
																	<div class='dropdown story_estimate'>
																	<input aria-hidden='true' type='hidden' name='story[estimate]' value='-1' data-type='number'>
																	<input aria-hidden='true' type='text' id='story_estimate_dropdown_c1058_honeypot' tabindex='0' class='honeypot'>
																	<a id='story_estimate_dropdown_c1058' class='selection item_-1' tabindex='-1'><span>unestimated</span></a>
																	<a id='story_estimate_dropdown_c1058_arrow' class='arrow target' tabindex='-1'></a>
																  <section>
																	<div class='dropdown_menu search'>
																		<div class='search_item'><input aria-label='search' type='text' id='story_estimate_dropdown_c1058_search' class='search'></div>
																		<ul>
																		  <li class='no_search_results hidden'>No results match.</li>
																		  <li data-value='-1' data-index='1' class='dropdown_item selected'><a class='item_-1 ' id='-1_story_estimate_dropdown_c1058' href='#'><span><span class='dropdown_label'>unestimated</span></span></a></li>
																		  <li data-value='0' data-index='2' class='dropdown_item'><a class='item_0 ' id='0_story_estimate_dropdown_c1058' href='#'><span><span class='dropdown_label'>0 points</span></span></a></li>
																		  <li data-value='1' data-index='3' class='dropdown_item'><a class='item_1 ' id='1_story_estimate_dropdown_c1058' href='#'><span><span class='dropdown_label'>1 point</span></span></a></li>
																		  <li data-value='2' data-index='4' class='dropdown_item'><a class='item_2 ' id='2_story_estimate_dropdown_c1058' href='#'><span><span class='dropdown_label'>2 points</span></span></a></li>
																		  <li data-value='3' data-index='5' class='dropdown_item'><a class='item_3 ' id='3_story_estimate_dropdown_c1058' href='#'><span><span class='dropdown_label'>3 points</span></span></a></li>
																	  </ul>
																	</div>
																  </section>
																</div>
																</div>
																<div class='state row'>
																  <em>State</em>
																  <div class='dropdown story_current_state disabled'>
																	<input aria-hidden='true' type='hidden' name='story[current_state]' value='unstarted' disabled='disabled'>
																	<input aria-hidden='true' type='text' id='story_current_state_dropdown_c1058_honeypot' tabindex='-1' class='honeypot'>
																	<a id='story_current_state_dropdown_c1058' class='selection item_unstarted' tabindex='-1'><span>unscheduled</span></a>
																	<a id='story_current_state_dropdown_c1058_arrow' class='arrow target' tabindex='-1'></a>
																  <section>
																	<div class='dropdown_menu search'>
																		  <div class='search_item'><input aria-label='search' type='text' id='story_current_state_dropdown_c1058_search' class='search'></div>
																	  <ul>
																		  <li class='no_search_results hidden'>No results match.</li>
																		  <li data-value='unstarted' data-index='1' class='dropdown_item'><a class='item_unstarted ' id='unstarted_story_current_state_dropdown_c1058' href='#'><span><span class='dropdown_label'>unstarted</span></span></a></li>
																		  <li data-value='started' data-index='2' class='dropdown_item'><a class='item_started ' id='started_story_current_state_dropdown_c1058' href='#'><span><span class='dropdown_label'>started</span></span></a></li>
																		  <li data-value='finished' data-index='3' class='dropdown_item'><a class='item_finished ' id='finished_story_current_state_dropdown_c1058' href='#'><span><span class='dropdown_label'>finished</span></span></a></li>
																		  <li data-value='delivered' data-index='4' class='dropdown_item'><a class='item_delivered ' id='delivered_story_current_state_dropdown_c1058' href='#'><span><span class='dropdown_label'>delivered</span></span></a></li>
																		  <li data-value='rejected' data-index='5' class='dropdown_item'><a class='item_rejected ' id='rejected_story_current_state_dropdown_c1058' href='#'><span><span class='dropdown_label'>rejected</span></span></a></li>
																		  <li data-value='accepted' data-index='6' class='dropdown_item'><a class='item_accepted ' id='accepted_story_current_state_dropdown_c1058' href='#'><span><span class='dropdown_label'>accepted</span></span></a></li>
																	  </ul>
																	</div>
																  </section>
																</div>
																	<input aria-hidden='true' type='hidden' id='story_state_unscheduled_c1058' name='story[current_state]' value='unscheduled'>
																	  <label class='autosaves state button disabled start' id='story_state_button_c1058_started' data-to='started' tabindex='-1'>Start</label>
															 </div>
																<div class='requester row'>
																  <em>Requester</em>
																  <div class='dropdown story_requested_by_id'>
																	<input aria-hidden='true' type='hidden' name='story[requested_by_id]' value='2936271' data-type='number'>
																	<input aria-hidden='true' type='text' id='story_requested_by_id_dropdown_c1058_honeypot' tabindex='0' class='honeypot'>
																	<a id='story_requested_by_id_dropdown_c1058' class='selection item_2936271' tabindex='-1'><span><div class='name'>Scott Jenkins</div>
																	<div class='initials'>SJ</div></span></a>
																	<a id='story_requested_by_id_dropdown_c1058_arrow' class='arrow target' tabindex='-1'></a>
																  <section>
																	<div class='dropdown_menu search'>
																		  <div class='search_item'><input aria-label='search' type='text' id='story_requested_by_id_dropdown_c1058_search' class='search'></div>
																	  <ul>
																		  <li class='no_search_results hidden'>No results match.</li>
																		  <li data-value='2936271' data-index='1' class='dropdown_item selected'><a class='item_2936271 ' id='2936271_story_requested_by_id_dropdown_c1058' href='#'><span><span class='dropdown_label'>Scott Jenkins</span><span class='dropdown_description'> SJ</span></span></a></li>
																	  </ul>
																	</div>
																  </section>
																</div>
															  </div>
																<div class='owner row'>
																  <em>Owners</em>
																  <div class='story_owners'>
																  <input aria-hidden='true' type='text' id='story_owner_ids_c1058_honeypot' tabindex='0' class='honeypot'>
																  <a id='add_owner_c1058' class='add_owner selected' tabindex='-1'>
																	  <span class='none'>&lt;none&gt;</span>
																  </a>
																</div>
															  </div>
															</div>
															<div class='integration_wrapper'>
															</div>
															 <div class='followers_wrapper'>
																 <div class='following row'>
																	<em>Follow this story</em>
																	<input type='hidden' name='story[following]' value='0'>
																	<input type='checkbox' id='c1058_following' checked='checked' disabled='true' class='autosaves std value' name='story[following]' value='on'>
																	<span class='count not_read_only' data-cid='$cid'>1 follower</span>
																</div>
															  </div>
															  <div class='row timestamp_wrapper'>
																<div class='timestamp'>
																  <div class='timestamps clickable'>
																	<div class='saving timestamp_row'><span>Saving�</span>
																	</div>
																  <div class='updated_at timestamp_row'>Updated: <span data-millis='1500719603000'>22 Jul 2017, 5:33am</span></div>
																  <div class='requested_at timestamp_row'>Requested: <span data-millis='1498652212000'>28 Jun 2017, 7:16am</span></div>
																</div>
															  </div>
															</div>
														  </div>
														  <div class='mini attachments'></div>
														</div>
													  </aside>
													</section>
													<section class='description full'>
														<div data-reactroot='' data-aid='Description' class='Description___3oUx83yQ'><h4>Description </h4>
															<div data-focus-id='DescriptionShow--c1058' data-aid='renderedDescription' tabindex='0' class='DescriptionShow___3-QsNMNj tracker_markup DescriptionShow__placeholder___1NuiicbF'>Add a description
															</div>
															<div class='DescriptionEdit___1FO6wKeX' style='display:none'>
																<div data-aid='editor' class='textContainer___2EcYJKlD'>
																	<div class='AutosizeTextarea___2iWScFt6'>
																		<div class='AutosizeTextarea__container___31scfkZp'>
																			<textarea aria-label='Description' data-aid='textarea' data-focus-id='DescriptionEdit--c1058' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' placeholder='Add a description'>
																			</textarea>
																		</div>
																		<div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt editor___1qKjhI5c tracker_markup'>
																			<span><!-- react-text: 11 --><!-- /react-text --></span>
																			<span>w</span>
																		</div>
																	</div>
																	<div class='controls___2K44hJCR'>
																		<div class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe'>
																			<span data-aid='AddEmoji' title='Add emoji to description' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg&quot;) center center no-repeat;'>
																			</span>
																		</div>
																		<div style='display:none'><!--hidden-->
																			<button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small button__save___2XVnhUJI' data-aid='save' type='button'>Add Description</button>
																			<button class='SMkCk__Button ibMWB__Button--open _3olWk__Button--small' data-aid='cancel' type='button'>Cancel</button>
																		</div>
																	</div>
															   </div>
															   <div class='markdownHelpContainer___32_mTqNL'>
																   <a class='markdownHelp___2yFSQCip' href='/help/markdown' target='_blank' tabindex='-1'> Formatting help </a>
															   </div>
														   </div>
														</div>
													</section>
													<section class='media full'>
														<div data-reactroot='' class='Activity___2ZLT4Ekd'>
															<h4 class='tn-comments__activity'>Media</h4>
															<ol class='comments all_activity' data-aid='comments'></ol>
															<div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'>
																<div class='CommentEdit__commentBox___21QXi4py'>
																	<div class='CommentEdit__textContainer___2V0EKFmS'>
																		<div data-aid='CommentGutter' class='CommentGutter___1wlvO_PP'>
																			<div data-aid='Avatar' class='_2mOpl__Avatar'>SJ</div>
																		 </div>
																		 <div class='CommentEdit__textEditor___3L0zZts-' data-aid='CommentV2TextEditor'>
																			 <div class='MentionableTextArea___1zoYeUDA'>
																				 <div class='AutosizeTextarea___2iWScFt6'>
																					 <div class='AutosizeTextarea__container___31scfkZp'>
																						 $drop_media
																					 </div>
																					 <div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt tracker_markup MentionableTextArea__textarea___2WDXl0X6 GLOBAL__no_save_on_enter CommentEdit__textarea___2Rzdgkej'>
																						 <span><!-- react-text: 16 --><!-- /react-text --></span>
																						 <span>w</span>
																					 </div>
																				 </div>
																			 </div>
																		 </div>
																	 </div>
																 </div>
															 </div>
														 </div>
													 </section>
		<!--hidden in css-->
													<section class='activity full'>
														<div data-reactroot='' class='Activity___2ZLT4Ekd'>
															<h4 class='tn-comments__activity'>Activity</h4>
															<ol class='comments all_activity' data-aid='comments'></ol>
															<div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'>
																<div class='CommentEdit__commentBox___21QXi4py'>
																	<div class='CommentEdit__textContainer___2V0EKFmS'>
																		<div data-aid='CommentGutter' class='CommentGutter___1wlvO_PP'>
																			<div data-aid='Avatar' class='_2mOpl__Avatar'>SJ</div>
																		 </div>
																		 <div class='CommentEdit__textEditor___3L0zZts-' data-aid='CommentV2TextEditor'>
																			 <div class='MentionableTextArea___1zoYeUDA'>
																				 <div class='AutosizeTextarea___2iWScFt6'>
																					 <div class='AutosizeTextarea__container___31scfkZp'>
																						 <textarea id='comment-edit-c818' data-aid='Comment__textarea' data-focus-id='CommentEdit__textarea--c818' class='AutosizeTextarea__textarea___1LL2IPEy tracker_markup MentionableTextArea__textarea___2WDXl0X6 GLOBAL__no_save_on_enter CommentEdit__textarea___2Rzdgkej' placeholder='Add a comment or paste an image'></textarea>
																					 </div>
																					 <div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt tracker_markup MentionableTextArea__textarea___2WDXl0X6 GLOBAL__no_save_on_enter CommentEdit__textarea___2Rzdgkej'>
																						 <span><!-- react-text: 16 --><!-- /react-text --></span>
																						 <span>w</span>
																					 </div>
																				 </div>
																			 </div>
																		 </div>
																	 </div>
																	 <div class='CommentEdit__action-bar___3dyLnEWb'>
																		<div class='CommentEdit__button-group___2ytpiQPa'>
																			<button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small' data-aid='comment-submit' type='button'>Post comment</button>
																		</div>
																		<div class=''>
																			<span class='CommentEditToolbar__container___3LKaxfw8' data-aid='CommentEditToolbar__container'>
																				<div class='CommentEditToolbar__action___3t8pcxD7'>
																					<button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-mention' aria-label='Mention person in comment'>
																						<span title='Mention person in comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/8846f168-mention.svg&quot;) center center no-repeat;'>
																						</span>
																					</button>
																				</div>
																				<div class='CommentEditToolbar__action___3t8pcxD7'>
																					<a class=''>
																						<div data-aid='attachmentDropdownButton' tabindex='0' title='Add attachment to comment' class='DropdownButton__icon___1qwu3upG CommentEditToolbar__attachmentIcon___48kfJPfH' aria-label='Add attachment'>
																						</div>
																					</a>
																					<input type='file' data-aid='CommentEditToolbar__fileInput' title='Attach file from your computer' name='file' multiple='' tabindex='-1' style='display: none;'>
																				</div>
																				<div class='CommentEditToolbar__action___3t8pcxD7'>
																					<button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-emoji' aria-label='Add emoji to comment'>
																						<span title='Add emoji to comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg&quot;) center center no-repeat;'>
																						</span>
																					</button>
																				</div>
																			</span>
																		</div>
																	 </div>
																 </div>
		<!--hidden in css-->                       	             <a class='CommentEdit__markdown_help___lvuA4kSr' href='/help/markdown' target='_blank' tabindex='-1' title='Markdown help' data-focus-id='FormattingHelp__link--c818'>
																 Formatting help</a>
															 </div>
														 </div>
													 </section>
												 </section>
											   </div>
											</div>";
						
						$story_model .= elgg_view('output/div', ['class'  =>'rTableRow story',
																'content'=>elgg_view('output/div', ['class'  =>'rTableCell',
																									'options'=>['style'=>'width:100%; padding: 0px 0px;vertical-align:top'],
																									'content'=>elgg_view('output/div',  ['class'  =>'story model item pin',
																																		 'content'=>/*$view_summary.*/$edit_details])])]);
						$form_body .= elgg_view('output/div', ['options'=>['id'   =>'line_store',
												   'style'=>'display:none;'],
									   'content'=> elgg_view('output/div',['class'  =>'progress_marker_line_items',
																		   'content'=> $story_model])]);
					break;
					default:
						if (!empty($hidden)){                
							foreach($hidden as $field=>$value){
								$hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						// loading styles inline to raise their priority
						$form_body .= elgg_view('css/quebx/user_agent', ['element'=>'experience']);
						$add_story_button = elgg_view('output/url', array(
												'title'    => 'Add Progress Marker',
												'text'     => '+',
												'href'     => '#',
												'class'    => 'add-progress-marker addButton___3-z3g3BH',
												'tabindex' => '-1' 
												));
						$a1             = elgg_view('output/url',    ['href'=>'','title'=>'this title']);
						$story_span     = elgg_view('output/span',   ['content'=>'dig?','class'=>'story_name']);
						$story_header   = elgg_view('output/div',    ['class'  =>'tn-PanelHeader__inner___3Nt0t86w tn-PanelHeader__inner--single___3Nq8VXGB',
								                                      'options' => ['data-aid'=>'highlight',
								                                      		        'style'=>'border-top-color: #c0c0c0'],
								                                      'content' => elgg_view('output/div',['class'  =>'tn-PanelHeader__actionsArea___EHMT4f1g undraggable',
								                                      		                               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__action___3zvuQp6Z',
								                                      		                               		                               'content'=> $add_story_button])])
								                                                 . elgg_view('output/div', ['class'  =>'tn-PanelHeader__titleArea___1DRH-oDF',
								                                                 	  	                    'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__name___2UfJ8ho9',
								                                                 	  	                    		                            'options'=>['data-aid'=>'PanelHeader__name'],
								                                                   		                    		                            'content'=>'Progress Markers'])])
								                                                 . elgg_view('output/div', ['class'  =>'tn-PanelHeader__actionsArea___EHMT4f1g undraggable',
								                                                   	  	                    'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__action___3zvuQp6Z',
								                                                   		                    	 	                            'options'=>['data-aid'=>'PanelHeader__name'],
								                                                   		                    		                            'content'=>'<a class="" data-aid="DropdownButton">
																																						 		 <div title="Panel actions" class="tn-DropdownButton___nNklb3UY"></div>
																																						    </a>'])])]);
					   $panel_header    = elgg_view('output/header',  ['class'  =>'tn-PanelHeader___c0XQCVI7 tn-PanelHeader--single___2ns28dRL pin',
																	   'options'=>['style'   =>'background-color: #dddddd; color: #000000',
																	 			   'data-bb' =>'PanelHeader',
																				   'data-aid'=>'PanelHeader'],
																	   'content'=>$story_header]);
                       $preview         = elgg_view('output/span',  ['content'=>$story_span,'class'=>'name tracker_markup']);
                       $view_summary    = elgg_view('output/header',['content'=>$a1.$expander.$preview.$delete, 'class'=>'preview collapsed']);
					   $quick_input     = elgg_view('input/text',   ['name'   =>'story[name]', 'data-aid'=>'name', 'data-focus-id'=>'NameEdit--c1037']);
					   $new_line_items  = elgg_view('output/div',   ['class'  =>'new_line_items']);
					   $items_container = elgg_view('output/div',   ['class'  =>'items panel_content',
																 	 'options'=>['id'=>'view217'],
																	 'content'=>elgg_view('output/div', ['class'   =>'tn-panel__loom',
																										 'options' =>['data-reactroot'=>''],
																										 'content' =>$new_line_items])]);
						$in_process_marker = elgg_view('forms/experiences/edit', [
													  'action'         => 'add',
													  'section'        => 'project_in_process',
													  'snippet'        => 'marker',
													  'guid'           => $guid,]);
						$edit_details      = elgg_view('output/div', [
													  'class'          => 'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
													  'content'        => $view_summary.$in_process_marker]);
						$form_body .= elgg_view('output/div', ['class'=>'panel c133 icebox_2068141 icebox items_draggable visible', 
															   'options' => ['id'       =>'panel_icebox_2068141',
																			 'data-aid' =>'Panel',
																			 'data-cid' =>'c133',
																			 'data-type'=>'icebox',],
															   'content' => elgg_view('output/div', ['class'   => 'container droppable sortable tn-panelWrapper___fTILOVmk',
																									 'options' => ['data-reactroot'=>''],
																									 'content' => $panel_header.
																												  elgg_view('output/div', ['class'  => 'rTable',
																																		   'options'=> ['style'=>'width:100%'],
																																		   'content'=> elgg_view('output/div',['class'=>'rTableBody ui-sortable',
																																											   'options'=>['id'=>'sortable_item'],
																																											   'content'=>$new_line_items.$edit_details])])])]);
						//$form_body .= $edit_details;

						break;
				}
				break;
/****************************************
$section = 'project_blocked'              *****************************************************************
****************************************/
            case 'project_blocked':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project Blocked';
                break;
/****************************************
$section = 'project_cancelled'              *****************************************************************
****************************************/
            case 'project_cancelled':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project Cancelled';
                break;
/****************************************
$section = 'project_complete'              *****************************************************************
****************************************/
            case 'project_complete':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project Complete';
                break;
/****************************************
$section = 'observation_effort'                     *****************************************************************
****************************************/
            case 'observation_effort':
                unset($input_tabs);
                $input_tabs[]    = array('title'=>'Materials', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'observation_effort_material'  , 'guid'=>$guid, 'aspect'=>'observation_effort_input');
                $input_tabs[]    = array('title'=>'Tools'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_effort_tools'     , 'guid'=>$guid, 'aspect'=>'observation_effort_input');
                $input_tabs[]    = array('title'=>'Steps'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_effort_steps'     , 'guid'=>$guid, 'aspect'=>'observation_effort_input');
                $input_tabs      = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'observation_effort_input', 'tabs'=>$input_tabs));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
//                $form_body .= $effort_header;
                $form_body .= "
                <div id='observation_effort_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Effort</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='observation_effort_material'   guid=$guid aspect='observation_effort_input' id='observation_effort_material_panel'   class='elgg-head' style='display:block'>
                                		    $material_panel</div>
                                		<div panel='observation_effort_tools'   guid=$guid aspect='observation_effort_input' id='observation_effort_tools_panel'   class='elgg-head' style='display:none'>
                                		    $tools_panel</div>
                                		<div panel='observation_effort_steps' guid=$guid aspect='observation_effort_input' id='observation_effort_steps_panel' class='elgg-head' style='display:none'>
                                		    $steps_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                break;
        }
echo $form_body;
//echo $display;
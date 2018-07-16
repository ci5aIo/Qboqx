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

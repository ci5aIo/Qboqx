<?php 
/*
 * ver 2
 */
$panel      = $vars['panel'];
$action     = elgg_extract('action', $vars, 'edit');
$task_steps = elgg_extract('task_steps', $vars, false);
$task_guid  = elgg_extract('task_guid', $vars);
$asset      = elgg_extract('asset', $vars);
$parts      = elgg_extract('parts', $vars, false);
$entity     = get_entity($task_guid);
/******************************/
if ($task_steps){
    foreach ($task_steps as $task_step){
        Switch ($task_step->getSubtype()){
            case 'task': $element_type = 'task'; break;
            case 'task_step': $element_type = 'step'; break;
        };
        $delete = elgg_view("output/url",array(
			    	'href' => "action/tasks/delete?guid=".$task_step->getGUID(),
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
        $task_link = elgg_view('output/url', array('text' => $task_step->title, 'href' =>  "tasks/view/$task_step->guid"));
        $tasks .= "<div class='rTableRow'>
                    <div class='rTableCell' style='width:10px;cursor:move'>$delete</div>
    				<div class='rTableCell' style='width:575px'>$task_link</div>".
    				elgg_view('input/hidden', array('name'=>'task_step[guid][]', 'value'=>$task_step->getGUID())).
    				elgg_view('input/hidden', array('name'=>'task_step[title][]', 'value'=>$task_step->title))."
    	        </div>";
    }
}
else {$tasks = false;}

//Tasks Add
    $tasks_add .= elgg_view('input/hidden', array('name'=>'element_type'        , 'value'=>$vars['element_type']));
    $tasks_add .= elgg_view('input/hidden', array('name'=>'aspect'              , 'value'=>$vars['aspect']));
    $tasks_add .= elgg_view('input/hidden', array('name'=>'task[guid]'          , 'value'=>$task_guid));
    $tasks_add .= elgg_view('input/hidden', array('name'=>'task[container_guid]', 'value'=>$vars['container_guid']));
    $tasks_add .= elgg_view('input/hidden', array('name'=>'task[parent_guid]'   , 'value'=>$vars['parent_guid']));
    $tasks_add .= "
    <div class='rTable' style='width:100%'>
		<div id='sortable' class='rTableBody'>";
    $tasks_add .= $tasks;
    $tasks_add .= "<div class='new_task_item'></div>";
    $tasks_add .= "
    </div>
        </div>";

    if ($action == 'view'){
        $tasks_add .= elgg_view('input/submit', array('value'=>'add tasks',
    	                                                 'class' => 'elgg-button-submit-element',
                                                         'style' => 'width:75px',));
    }
    $tasks_add .= "
    <div id ='store' style='visibility:hidden'>
    	<div class='task_item'>
    	    <div class='rTableRow'>
				<div class='rTableCell' style='width:10px;cursor:move'><a href='#' class='remove-node'>".elgg_view_icon('delete')."</a></div>
    			<div class='rTableCell' style='width:575px'>".
			      elgg_view('input/text', array(
					'name'        => 'task_step[title][]',
					'class'       => 'last_task_item',
		      	    'placeholder' => 'Step Name',
				))."</div>
    		</div>
    	</div>
	</div>";

/******************************/
//Schedule Panel
Switch ($action){
    case 'view':
        $start_date = $entity->start_date;
        $due_date   = $entity->due_date;
        $end_date   = $entity->end_date;
        Switch ($entity->status){
            case 'not_specified': $status = 'Not Specified'; break;
            case 'needs_action':  $status = 'Needs Action';  break;
            case 'in_process':    $status = 'In Process';    break;
            case 'completed':     $status = 'Completed on';  break;
            case 'cancelled':     $status = 'Cancelled';     break;
        }
        $complete   = 0+$entity->percent_done."%&nbsp;complete";
    break;
    case 'edit':
        $start_date = elgg_view('input/date'  , array('name'=>'task[start_date]', 'value'=>$entity->start_date,));
        $due_date   = elgg_view('input/date'  , array('name'=>'task[due_date]'  , 'value'=>$entity->due_date,));
        $end_date   = elgg_view('input/date'  , array('name'=>'task[end_date]'  , 'value'=>$entity->end_date,));
        $status     = elgg_view('input/select'  , array('name'   =>'task[status]' ,
                                                      'options_values'=>array('not_specified'=>'Not Specified',
                                                                              'needs_action' =>'Needs Action',
                                                                              'in_process'   =>'In Process',
                                                                              'completed'    =>'Completed on',
                                                                              'cancelled'    =>'Cancelled',),
                                                      'value'  =>$entity->status,));
        $complete          = elgg_view('input/text', array('name'=>'task[percent_done]'  , 'value' =>$entity->percent_done,))."%";
        
    break;
}
$schedule = "
    <div class='rTable' style='width:550px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Start</div>
				<div class='rTableCell' style='width:155px'>$start_date</div>
				<div class='rTableCell' style='width:150px'><div class='rTable'>
                                                        		<div class='rTableBody'>
                                                        			<div class='rTableRow'>
                                                        				<div class='rTableCell'>Status</div>
                                                        				<div class='rTableCell'>$status</div>
                                                        			</div>
                                                        		</div>
                                                        	</div></div>
				<div class='rTableCell' style='width:155px'><div class='rTable'>
                                                        		<div class='rTableBody'>
                                                        			<div class='rTableRow'>
                                                        				<div class='rTableCell' style='text-align:right'>Complete</div>
                                                        				<div class='rTableCell' style='white-space:nowrap'>$complete</div>
                                                        			</div>
                                                        		</div>
                                                        	</div></div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Due</div>
				<div class='rTableCell' style='width:155px'>$due_date</div>
			    <div class='rTableCell' style='width:150px'><div class='rTable'>
                                                        		<div class='rTableBody'>
                                                        			<div class='rTableRow'>
                                                        				<div class='rTableCell'>Done</div>
                                                        				<div class='rTableCell'>$end_date</div>
                                                        			</div>
                                                        		</div>
                                                        	</div></div>
				<div class='rTableCell' style='width:155px'></div>
			</div>			
		</div>
	</div>";

/******************************/

if ($panel == 'tasks_add_panel'){echo $tasks_add;}
if ($panel == 'parts_panel'){echo $parts_panel;}
if ($panel == 'schedule_panel'){echo $schedule;}
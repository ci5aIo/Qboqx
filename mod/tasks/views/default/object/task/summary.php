View: tasks\views\default\object\task\summary.php
<?php
$entity  = $vars['entity'];
$section = $vars['this_section'];
$action  = $vars['action'];
$task_guid = $entity->guid;

$params = array(
	'entity' => $task,
	'tags' => $tags,
);
$params = $params + $vars;
/**
 * Task summary
 *
 */
	$worker    = get_entity($entity->assigned_to);
	$owner     = $entity->getOwnerEntity();
	$container = get_entity($entity->getContainerGUID());
	$parent_guid = $entity->parent_guid; 
	$friendlytime = elgg_view_friendly_time($entity->time_created);
	$metadata  = elgg_extract('metadata', $vars, '');
	$urlTaskOwner = elgg_get_site_url()."tasks/owner/".$container->username;
	
$jots = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'assigned_to',
	'relationship_guid' => $entity->guid,
    'inverse_relationship' => false,
	'limit' => false,
));	
$subtasks = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'task',
	'limit' => false,
	'metadata_name_value_pairs' => array(
		'name' => 'parent_guid',
		'value' => $task_guid
	)
));
$task_steps = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'process_step',
	'limit' => false,
	'metadata_name_value_pairs' => array(
		'name' => 'parent_guid',
		'value' => $task_guid
	)
));
$subtasks = array_merge($subtasks, $task_steps);
$jot = $jots[0];
if ($jot){
    $asset = get_entity($jot->asset); 
}
if($parent_guid){
    $parent_task  = get_entity($task_guid);
    $parent_task_link = elgg_view('output/url', array('text' => $parent_task->title,'href' =>  "tasks/view/$parent_task->guid"));
    $parent_task_link_display = "<div class='rTableRow'>
                    				<div class='rTableCell' style='width:90px'>Parent Task</div>
                    				<div class='rTableCell' style='width:460px'>$parent_task_link</div>
                    			</div>";
}
$status_options = elgg_view('input/dropdown', array('options_values' => array(1 => 'Not specified',
                                                                              2 => 'Needs action',
                                                                              3 => 'In process',
                                                                              4 => 'Completed on',
                                                                              5 => 'Cancelled'),
                                                    'name'           => 'status'
));
//echo '$action:'.$action.'<br>';
//echo '$section:'.$section.'<br>';
//echo elgg_dump($vars);
Switch ($action){
    case 'view':
        $title = $entity->title;
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
        $complete   = $entity->complete;
        $description_panel = $entity->description;
        $tasks_panel = 'tasks panel';
        $action    = 'tasks/add/tasks';
        $form_vars = array('name'    => 'tasks_add', 
                           'enctype' => 'multipart/form-data', 
                           'action'  => 'action/tasks/edit2',
        /*                   'onsubmit'=>"window.open('http://test.quebx.smarternetwork.com?s=<?php echo elgg_dump(".$categories.") ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true",*/    
        );
        
        $body_vars = array('element_type'   => 'task',
                           'aspect'         => 'subtask',
                           'container_guid' => $entity->guid,
                           'parent_guid'    => $entity->guid, 
                           'panel'          => 'tasks_add_panel');
        $tasks_add .= elgg_view_form($action, $form_vars, $body_vars);
        $tasks_add_button = "<a id='tasks_add' title='add tasks' class='elgg-button-submit-element' style='cursor:pointer;height:14px;width:30px'>+</a>";
        
        if ($subtasks){
            $tasks = '<ul>';
            foreach ($subtasks as $subtask){
                $task_link = elgg_view('output/url', array('text' => $subtask->title,'href' =>  "tasks/view/$subtask->guid"));
                $tasks .= "<li>$task_link</li>";
            }
            $tasks .= '</ul>';
        }
         
        
        break;
    case 'edit':
        $hidden     = elgg_view('input/hidden', array('name'=>'task[guid]',
                                                     'value'=>$entity->guid));
        $hidden    .= elgg_view('input/hidden', array('name'=>'container_guid',
                                                     'value'=>$container->guid));
        $title      = elgg_view('input/text'  , array('name'=>'task[title]',
                                                      'value'=>$entity->title));
        $start_date = elgg_view('input/date'  , array('name'=>'task[start_date]',
                                                      'value'=>$entity->start_date,));
        $due_date   = elgg_view('input/date'  , array('name'=>'task[due_date]',
                                                      'value'=>$entity->due_date,));
        $end_date   = elgg_view('input/date'  , array('name'=>'task[end_date]',
                                                      'value'=>$entity->end_date,));
        $status   = elgg_view('input/select'  , array('name'   =>'task[status]',
                                                      'options_values'=>array('not_specified'=>'Not Specified',
                                                                              'needs_action' =>'Needs Action',
                                                                              'in_process'   =>'In Process',
                                                                              'completed'    =>'Completed on',
                                                                              'cancelled'    =>'Cancelled',),
                                                      'value'  =>$entity->status,));
        $complete = elgg_view('input/text', array('name'=>'task[complete]',
                                                  'value' =>$entity->complete));
        $description_panel = elgg_view('input/longtext', array('name'=>'task[description]',
                                                               'value' =>$entity->description));
        $view_menu[1] = new ElggMenuItem('1view', 'View', "tasks/view/$entity->guid");
		elgg_register_menu_item('task', $view_menu[1]);
		$task_menu = elgg_view_menu('task');
		
		$buttons = '<hr>'.elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'jot[do]')).
                   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'jot[do]')).
                    '<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>';
		
		        
        break;
}

echo "$hidden $task_menu
      <div class='rTable' style='width:550px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Title</div>
				<div class='rTableCell' style='width:460px'>$title</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Asset</div>
				<div class='rTableCell' style='width:460px'>$asset->title</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Observation</div>
				<div class='rTableCell' style='width:460px'>$jot->title</div>
			</div>
			$parent_task_link_display			
		</div>
	</div>
<hr>";
echo "<div class='rTable' style='width:550px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Start</div>
				<div class='rTableCell' style='width:155px'>$start_date</div>
				<div class='rTableCell' style='width:150px'>Due</div>
				<div class='rTableCell' style='width:155px'>$due_date</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Status</div>
				<div class='rTableCell' style='width:155px'>$status</div>
			    <div class='rTableCell' style='width:150px'>$end_date</div>
				<div class='rTableCell' style='width:155px'>$complete</div>%&nbsp;complete
			</div>			
		</div>
	</div>
<hr>";
?>
<script> 
$(document).ready(function(){
    $("#Description_tab").click(function(){
        $("#Description_panel").slideDown("fast");
        $("#Description_tab").addClass("elgg-state-selected");
        
        $("#Documents_panel").slideUp("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Tasks_panel").slideUp("fast");
        $("#Tasks_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
    });
    $("#Documents_tab").click(function(){
        $("#Documents_panel").slideDown("fast");
        $("#Documents_tab").addClass("elgg-state-selected");
        
        $("#Description_panel").slideUp("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Tasks_panel").slideUp("fast");
        $("#Tasks_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
    });
    $("#Tasks_tab").click(function(){
        $("#Tasks_panel").slideDown("fast");
        $("#Tasks_tab").addClass("elgg-state-selected");

        $("#Documents_panel").slideUp("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").slideUp("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
    });
    $("#Gallery_tab").click(function(){
        $("#Gallery_panel").slideDown("fast");
        $("#Gallery_tab").addClass("elgg-state-selected");

        $("#Documents_panel").slideUp("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").slideUp("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Tasks_panel").slideUp("fast");
        $("#Tasks_tab").removeClass("elgg-state-selected");
    });
});
$(document).ready(function(){
    $("#tasks_add").click(function(){
        $("#tasks_add_panel").slideToggle("slow");
    });
});

</script>

<style> 
#Documents_panel, #Tasks_panel, #Gallery_panel, #tasks_add_panel{
	display: none;
}
</style>
<?php
$tabs = elgg_view('quebx/menu', array('guid' =>$entity->guid, 'this_section' => 'Description', 'action'=>'display'));
echo $tabs;
echo "<div id='Description_panel' class='elgg-head'>
        $description_panel
</div>";

echo "<div id='Documents_panel' class='elgg-head'>
         $documents_panel
      </div>";

        
        $tasks_panel = "<br>$tasks_add_button <b>Add Tasks</b>
    	<div class='rTable' style='width:100%'>
    		<div class='rTableBody'>
                <div class='rTableRow'>
    				<div class='rTableCell' style='width:100%;padding:0px'>
        				<div id='tasks_add_panel'>$tasks_add</div>
    	            </div>
    	        </div>
             </div>
        </div>";

echo "<div id='Tasks_panel' class='elgg-head'>
	     $tasks_panel $tasks
	 </div>";

echo "<div id='Gallery_panel' class='elgg-head'>
	     $gallery_panel
	  </div>";
echo $buttons;
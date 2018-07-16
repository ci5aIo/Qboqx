View: tasks\views\default\tasks\display\summary.php
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
	'subtypes' => array('task', 'process_step'),
	'limit' => false,
	'metadata_name_value_pairs' => array(
		'name' => 'parent_guid',
		'value' => $task_guid
	),
	'order_by_metadata'    => array(
	        'name' => 'sort_order',
			'direction' => ASC,
			'as' => 'integer'),
));
$parts = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'part_item',
	'limit' => false,
    'container_guid'=>$task_guid,
	'order_by_metadata'    => array(
	        'name' => 'sort_order',
			'direction' => ASC,
			'as' => 'integer'),
));
$jot = $jots[0];
if ($jot){
    $asset = get_entity($jot->asset); 
}
if($parent_guid){
    $parent_task  = get_entity($parent_guid);
    $parent_task_link = elgg_view('output/url', array('text' => $parent_task->title,'href' =>  "tasks/view/$parent_task->guid"));
    $parent_task_link_display = "<div class='rTableRow'>
                    				<div class='rTableCell' style='width:90px'>Parent Task</div>
                    				<div class='rTableCell' style='width:460px'>$parent_task_link</div>
                    			</div>";
}
else {
    $asset_link               = elgg_view('output/url', array('text' => $asset->title,'href' =>  "market/view/$asset->guid"));
    $asset_link_display       ="<div class='rTableRow'>
                    				<div class='rTableCell' style='width:90px'>Asset</div>
                    				<div class='rTableCell' style='width:460px'>$asset_link</div>
                    			</div>";
    $observation_link         = elgg_view('output/url', array('text' => $jot->title,'href' =>  "jot/view/$jot->guid"));
    $observation_link_display = "<div class='rTableRow'>
                    				<div class='rTableCell' style='width:90px'>Observation</div>
                    				<div class='rTableCell' style='width:460px'>$observation_link</div>
                    			</div>";
}

//echo '<br>$action:'.$action.'<br>';
//echo '$section:'.$section.'<br>';
//echo elgg_dump($vars);
Switch ($action){
    case 'view':
        $title = $entity->title;
        $description_panel = $entity->description;
        $tasks_add = elgg_view_form('tasks/add/tasks', 
                                      array('name'    => 'tasks_add', 
                                            'enctype' => 'multipart/form-data', 
                                            'action'  => 'action/tasks/edit2',
                                           ), 
                                      array('element_type'   => 'task',
                                            'aspect'         => 'subtask',
                                            'action'         => $action,
                                            'task_guid'      => $entity->guid,
                                            'container_guid' => $entity->getContainerGUID(),
                                            'parent_guid'    => $entity->parent_guid, 
                                            'panel'          => 'tasks_add_panel',
                                            'task_steps'     => $task_steps));
        if ($worker){
            switch ($worker->getType()){
                case 'user':
                    $assigned_to = elgg_view('output/url', array('text' => $worker->name,'href' =>  "profile/$worker->username"));
                    break;
                case 'group':
                    $assigned_to = elgg_view('output/url', array('text' => $worker->name,'href' =>  "groups/profile/$worker->guid"));
                    break;
            }
        }
        else {
            $assigned_to = "<span title='Put this task on the shelf'>". elgg_view('output/url', array('text' => 'Pick','href' =>  elgg_add_action_tokens_to_url("action/shelf/load?guid=$entity->guid")))."</span>";
        }
        break;
        
    case 'edit':
        $title      = elgg_view('input/text'  , array('name'=>'task[title]'     , 'value'=>$entity->title));
        $description_panel = elgg_view('input/longtext', array('name'=>'task[description]', 'value' =>$entity->description));
        $tasks_add         = elgg_view('forms/tasks/add/tasks',array(
                                            'element_type'   => 'task',
                                            'aspect'         => 'subtask',
                                            'action'         => $action,
                                            'task_guid'      => $entity->guid,
                                            'container_guid' => $entity->getContainerGUID(),
                                            'parent_guid'    => $entity->parent_guid, 
                                            'panel'          => 'tasks_add_panel',
                                            'task_steps'     => $task_steps,
        ));
        
        $buttons = '<hr>'.elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'jot[do]')).
                   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'jot[do]')).
                    '<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>';
        break;
}

$tasks_add_button = "<a title='add step' class='elgg-button-submit-element add-task-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
$parts_add_button = "<a title='add part' class='elgg-button-submit-element add-part-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
$steps_panel      = "$tasks_add_button add step $tasks_add";
$schedule_panel   = elgg_view('forms/tasks/add/tasks',array(
                                    'action'         => $action,
                                    'task_guid'      => $entity->guid, 
                                    'panel'          => 'schedule_panel',
));
$parts_panel      = "$parts_add_button add part".
                    elgg_view('forms/tasks/add/tasks',array(
                                    'action'         => $action,
                                    'asset'          => $asset,
                                    'parts'          => $parts,
                                    'task_guid'      => $entity->guid, 
                                    'panel'          => 'parts_panel',
));

$contents .= "$hidden
      <div class='rTable' style='width:550px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Title</div>
				<div class='rTableCell' style='width:460px'>$title</div>
			</div>
			$asset_link_display
			$observation_link_display
			$parent_task_link_display
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Assigned to</div>
				<div class='rTableCell' style='width:460px'>$assigned_to</div>
			</div>			
		</div>
	</div>";
$tabs = elgg_view('quebx/menu', array('guid' =>$entity->guid, 'this_section' => 'Steps', 'action'=>'display'));

echo $contents;
echo $tabs;
echo "<div style='min-height:50px'>";
echo "<div id='Schedule_panel' class='elgg-head'>
        $schedule_panel
      </div>";

echo "<div id='Description_panel' class='elgg-head'>
        $description_panel
      </div>";

echo "<div id='Parts_panel' class='elgg-head'>
         $parts_panel
      </div>";

echo "<div id='Documents_panel' class='elgg-head'>
         $documents_panel
      </div>";

echo "<div id='Steps_panel' class='elgg-head'>
	     $steps_panel
	 </div>";

echo "<div id='Gallery_panel' class='elgg-head'>
	     $gallery_panel
	  </div>";
echo "</div>";
echo $buttons;

?>
<script> 
$(document).ready(function(){
    $("#Description_tab").click(function(){
        $("#Description_panel").show("fast");
        $("#Description_tab").addClass("elgg-state-selected");
        
        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Parts_panel").hide("fast");
        $("#Parts_tab").removeClass("elgg-state-selected");
    });
    $("#Documents_tab").click(function(){
        $("#Documents_panel").show("fast");
        $("#Documents_tab").addClass("elgg-state-selected");
        
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Parts_panel").hide("fast");
        $("#Parts_tab").removeClass("elgg-state-selected");
    });
    $("#Steps_tab").click(function(){
        $("#Steps_panel").show("fast");
        $("#Steps_tab").addClass("elgg-state-selected");

        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Parts_panel").hide("fast");
        $("#Parts_tab").removeClass("elgg-state-selected");
    });
    $("#Gallery_tab").click(function(){
        $("#Gallery_panel").show("fast");
        $("#Gallery_tab").addClass("elgg-state-selected");

        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Parts_panel").hide("fast");
        $("#Parts_tab").removeClass("elgg-state-selected");
    });
    $("#Schedule_tab").click(function(){
        $("#Schedule_panel").show("fast");
        $("#Schedule_tab").addClass("elgg-state-selected");
        
        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Parts_panel").hide("fast");
        $("#Parts_tab").removeClass("elgg-state-selected");
    });
    $("#Parts_tab").click(function(){
        $("#Parts_panel").show("fast");
        $("#Parts_tab").addClass("elgg-state-selected");
        
        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
    });
});

$(function () {
	//Add input fields when TAB pressed.  Source: http://jsbin.com/amoci/123/edit
	$(document).on('keydown', 'input.last_task_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_task_item");
		  $(this).trigger({type: 'keypress', which: 9, keyCode: 9}); // supposed to send the TAB keystroke, but doesn't work ... yet.
		  var html = $('.task_item').html();
		  $(html).insertBefore('.new_task_item');
	    } 
	});
	$(document).on('keydown', 'input.last_part_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_part_item");
		  var html = $('.part_item').html();
		  $(html).insertBefore('.new_part_item');
	    } 
	});
	// Add input field when [+] pressed
	$('.add-task-item').on('click', function(e){
		e.preventDefault();
		
		var html = $('.task_item').html();
		$(html).insertBefore('.new_task_item');
	});
	$('.add-part-item').on('click', function(e){
		e.preventDefault();
		
		var html = $('.part_item').html();
		$(html).insertBefore('.new_part_item');
	});
	
	// remove a node
	$('.remove-node').on('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
    $( "#sortable" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#sortable_parts" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#draggable" ).draggable({
        revert: false,
        connectToSortable: "#sortable",
      });
});
</script>

<style> 
#Parts_panel, #Schedule_panel, #Documents_panel, #Description_panel, #Gallery_panel, #tasks_add_panel{
	display: none;
}
</style>
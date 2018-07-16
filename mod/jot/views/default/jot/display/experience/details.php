<?php
/*
 * Experience Details
 * ver 1
*/ 
$entity       = $vars['entity'];
$jot          = $entity;
$section      = $vars['this_section'];
$asset        = $entity->asset;
$item_guid    = $entity->guid;
$subtype      = $entity->getSubtype();
//$fields       = jot_prepare_brief_view_vars($subtype, $entity, $section);
$title        = $entity->title;
$description  = $entity->description;
$referrer     = "jot/$subtype/$item_guid/$section";
$owner        = elgg_get_logged_in_user_guid();
$state_params = array("name"    => "state",
                      'align'   => 'horizontal',
					  "value"   => $entity->state,
					  "options" => array("Planning" => 1, "In Process" => 2, "Cancelled" => 3, "Postponed" => 4, "Complete" => 5),
				      'default' => 1,
					 );

echo "<!--Section = $section-->";

if (!$asset){
	$asset = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'observation',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => false,
		'limit' => false,
		));
}

/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$observations = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'observation',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$causes = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'cause',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$steps_arrival = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'step',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$steps_departure = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'next_step',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$efforts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'effort',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$discovery_efforts = elgg_get_entities_from_relationship(array(
	'type'                 => 'object',
	'relationship'         => 'discovery_effort',
	'relationship_guid'    => $item_guid,
	'inverse_relationship' => true,
	// Discovery effort items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
	'order_by_metadata'    => array('name' => 'sort_order', 
			                        'direction' => ASC, 
			                        'as' => 'integer'),
	'limit'                => false,
	));
$projects = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'assigned_to',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$support_groups = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'support_group_of',
	'relationship_guid' => $asset,
	'inverse_relationship' => true,
	'limit' => false,
	));
$assignment_options = array();
if ($support_groups){
	foreach($support_groups as $group){
		$assignment_options[$group->guid] = $group->name;
	}
}
$assigned_group = elgg_view("output/url",array(
	'href' => "groups/profile/$entity->assigned_to",
	'text' => elgg_view_icon('users'),
));
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
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
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
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
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
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
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
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
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
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
    });
    $("#Things_tab").click(function(){
        $("#Things_panel").show("fast");
        $("#Things_tab").addClass("elgg-state-selected");
        
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
#Things_panel, #Schedule_panel, #Documents_panel, #Description_panel, #Gallery_panel, #tasks_add_panel{
	display: none;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	// clone effort node
	$('.clone-planning-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.plan').html();
		$(html).insertBefore('.new_plan_item');
	});
	// remove a node
	$('.remove-node').on('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
});
</script>
<?php

// Input form
$form_action = "action/jot/edit";
$form_name   = $entity->getSubtype().'_'.$section;
//$form_class = 'elgg-module elgg-module-aside elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $form_action);

$schedule_panel   = elgg_view('forms/experiences/edit',array(
                        'action'         => $action,
                        'guid'           => $entity->guid, 
                        'section'        => 'schedule',));
$things_panel     = elgg_view('forms/experiences/edit',array(
                        'action'         => $action,
                        'guid'           => $entity->guid, 
                        'section'        => 'things',));
$steps_panel      = elgg_view('forms/experiences/edit',array(
                        'action'         => $action,
                        'guid'           => $entity->guid, 
                        'section'        => 'steps',));
$documents_panel  = elgg_view('forms/experiences/edit',array(
                        'action'         => $action,
                        'guid'           => $entity->guid, 
                        'section'        => 'documents',));
$gallery_panel    = elgg_view('forms/experiences/edit',array(
                        'action'         => $action,
                        'guid'           => $entity->guid, 
                        'section'        => 'gallery',));
$tabs             = elgg_view('quebx/menu', array('guid' =>$entity->guid, 'this_section' => 'Steps', 'action'=>'display'));
$tabs            .= "<div style='min-height:100px'>";
$tabs            .= "<div id='Schedule_panel' class='elgg-head'>
                                $schedule_panel
                              </div>";
$tabs            .= "<div id='Things_panel' class='elgg-head'>
                                $things_panel
                              </div>";
$tabs            .= "<div id='Steps_panel' class='elgg-head'>
                                 $steps_panel
                              </div>";
$tabs            .= "<div id='Documents_panel' class='elgg-head'>
                                 $documents_panel
                              </div>";
$tabs            .= "<div id='Gallery_panel' class='elgg-head'>
                        	     $gallery_panel
                        	  </div>";
$tabs            .= "</div>";
$form_vars        = array('action' => $form_action, 'name'=>$form_name, 'class'=>$form_class);
$body_vars        = array('action' => $action,
                          'guid'   => $entity->guid,
                          'tabs'   => $tabs, 
                          'section'=> 'main',);
$main_panel       = elgg_view_form('experiences/edit',$form_vars, $body_vars);

echo $main_panel;
<!-- View: tasks\views\default\tasks\display\que\schedule.php<br>-->
<?php
$entity   = $vars['entity'];
$section  = $vars['this_section'];
$que_guid = $entity->getGUID();
$asset    = get_entity($entity->asset);
$owner    = $asset->getOwnerEntity();

$support_groups = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'support_group_of',
	'relationship_guid' => $asset,
    'inverse_relationship' => true,
	'limit' => false,
));

$support_groups = array_merge($support_groups, elgg_get_entities_from_relationship(array(
													'type' => 'group',
													'relationship' => 'support_group_of',
													'relationship_guid' => $que_guid,
												    'inverse_relationship' => true,
													'limit' => false,
												))
);


if ($support_groups) {
echo "
	<div class='rTable' style='width:375px'>
		<div class='rTableBody'><label>Available Support Groups</label>";

foreach ($support_groups as $i) {
	$element_type = 'support_group';
	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid/$i->name"));
	
	if ($i->canEdit()) {
		$detach = elgg_view("output/confirmlink",array(
	    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$asset}",
	    	'text' => elgg_view_icon('unlink'),
	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'support group'),
	    	'encode_text' => false,
	    ));
	}
	echo "<div class='rTableRow'>
				<div class='rTableCell highlight' style='width:85px;padding:0px 0px 0px 0px;'>$link</div>
	            <div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>$detach</div>
	      </div>";
	}
echo "
<div class='rTable' style='width:375px'>
	<div class='rTableBody'>
<p>";

}	

$scheduled_tasks = elgg_get_entities_from_relationship(array(
		'relationship'         => 'que',
		'relationship_guid'    => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));

$scheduled_task = $scheduled_tasks[0];

$link = elgg_view('output/url', array(
	      'text' => $scheduled_task['title'],
	      'href' =>  "tasks/view/{$scheduled_task->guid}"));
$pick_task = "<span title='Select task'>".
					elgg_view('output/url', array(
						'text' => 'pick', 
					    'class' => 'elgg-button-submit-element elgg-lightbox',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
				        'href' => "que/pick/task/$que_guid/1/schedule")).
				"</span>";
$new_task = "<span title='Select task'>".
					elgg_view('output/url', array(
						'text' => 'new', 
					    'class' => 'elgg-button-submit-element elgg-lightbox',
					    'data-colorbox-opts' => '{"width":500, "height":525}',
				        'href' => "tasks/add/60?element_type=task&container_guid=$que_guid")).
				"</span>";


echo "<label>Scheduled Task</label><br>
      $link<br>
      $pick_task<br>";

echo "Create new task<br>
	$new_task";

$until_option = "";
switch ($entity->until_option){
	case 1:
		$until_option = " until $entity->end_date";
		break;
	case 2:
		$until_option = " until $owner->name no longer owns this item";
		break;
	case 3:
		$until_option = " until performed $entity->cycles times";
		break;
	default:
		$until_option = "";
		break;	
}

echo "
	<div class='rTable' style='width:500px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:500px; padding:0px 0px 0px 0px;'>Starting $entity->start_date $until_option</div>
		    </div>
		</div>
	</div>";

		    
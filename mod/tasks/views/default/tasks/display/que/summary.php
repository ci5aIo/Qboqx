<!-- View: tasks\views\default\tasks\display\que\summary.php<br>-->
<?php
$entity   = $vars['entity'];
$section  = $vars['this_section'];
$que_guid = $entity->getGUID();
$asset    = get_entity($entity->asset);

$limit     = 3;
$schedules = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'scheduled_for',
	'relationship_guid' => $que_guid,
    'inverse_relationship' => false,
	'limit' => $limit,
));
$scheduled_tasks = elgg_get_entities_from_relationship(array(
		'relationship'         => 'que',
		'relationship_guid'    => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));

$scheduled_task = $scheduled_tasks[0];

$scheduled_link = elgg_view('output/url', array(
	      'text' => $scheduled_task['title'],
	      'href' =>  "tasks/view/{$scheduled_task->guid}"));

$valid_tags = elgg_get_registered_tag_metadata_names();

$asset_link = elgg_view('output/url', array(
	        		'text' =>  $asset->title,
	        		'href' =>  "market/view/".$asset->guid."/".$asset->title."/Maintenance",
	        ));

if (!empty($entity->last_done)){
	$last_done                 = number_format($entity->last_done, 0);
    $last_done_frequency_units = $entity->frequency_units;
    switch ($entity->frequency_units){
    	case 'miles':
    		$next_due = number_format($entity->last_done + $entity->frequency, 0);
    		$next_due_frequency_units = $last_done_frequency_units; 
    		break;
    	case 'months':
    		$next_due = $entity->last_done + $entity->frequency;
    		$next_due_frequency_units = '';
    		break;
    }
                  
}
else {$last_done = 'unknown';
      $next_due  = 'unknown';}
                                  
echo "<ul>Design list (future)
		<li>Last 10 occasions with dates and cost</li>
		<li>Preferred Provider</li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	  </ul>
	<p>
		";

echo "
	<div class='rTable' style='width:375px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Asset</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'><span title='View asset details'>$asset_link</span></div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Activity</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'><span title='Edit maintenance schedule'>".elgg_view('output/url', array('text' => $entity->title,'href'=>'jot/box/'.$entity->guid.'/schedule/edit','class'=>'elgg-lightbox','data-colorbox-opts'=>'{"width":500, "height":375}'))."</span></div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Task</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'><span title='View scheduled task'>".elgg_view('output/url', array('text' => $scheduled_task->title,'href'=>'jot/box/'.$scheduled_task->guid.'/scheduled/view','class'=>'elgg-lightbox','data-colorbox-opts'=>'{"width":500, "height":325}'))."</span></div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Que every</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>".number_format($entity->frequency, 0)." $entity->frequency_units</div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'>Last done @</div>
				<div class='rTableCell' style='width:285px; padding:0px 0px 0px 0px;'>$last_done $last_done_frequency_units</div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'>Next due @</div>
				<div class='rTableCell' style='width:285px; padding:0px 0px 0px 0px;'>$next_due $next_due_frequency_units</div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px; padding:0px 0px 0px 0px;'>Pace</div>
				<div class='rTableCell' style='width:285px; padding:0px 0px 0px 0px;'>".number_format($entity->pace, 0)." $entity->pace_units each $entity->pace_period</div>
		    </div>
		</div>
	</div>
</div>";
  		
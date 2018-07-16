<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
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
	$worker=get_entity($entity->assigned_to);
	$owner = $entity->getOwnerEntity();
	$container = get_entity($entity->getContainerGUID());
	$friendlytime = elgg_view_friendly_time($entity->time_created);
	$metadata = elgg_extract('metadata', $vars, '');
	$urlTaskOwner = elgg_get_site_url()."tasks/owner/".$container->username;
?>

<!--	<table width="100%" class="tasks" >
		<tr>
			<td width="33%">
				<h3><a href="<?php echo $entity->getURL(); ?>"><?php echo $entity->title; ?></a></h3>
			</td>
			<td width="33%">
				<a href="<?php echo $urlTaskOwner; ?>"><?php echo $container->name; ?></a>&nbsp;<?php echo $friendlytime; ?>
			</td width="33%">
			<td width="33%" style="text-align: right;">
				<?php if ($metadata) {	echo $metadata; } ?>
			</td>
		</tr>
	</table>
-->
<!--	<hr> -->
	<table width="100%" class="tasks" >
		<tr>
			<td width="50%">
			<label><?php 	echo elgg_echo('tasks:start_date'); ?></label>
			<?php   echo elgg_view('output/text',array('value' => $entity->start_date)); ?>
			</td>
			<td width="50%">
			<label><?php 	echo elgg_echo('tasks:end_date'); ?></label>
			<?php   echo elgg_view('output/text',array('value' => $entity->end_date)); ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label><?php echo elgg_echo('tasks:task_type'); ?></label>
			<?php echo elgg_view('output/text',array('value' => elgg_echo("tasks:task_type_{$entity->task_type}"))); ?>
			</td>
			<td width="50%">
			<label><?php echo elgg_echo('tasks:status'); ?></label>
			<?php echo elgg_view('output/text',array('value' => elgg_echo("tasks:task_status_{$entity->status}"))); ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label><?php 	echo elgg_echo('tasks:percent_done'); ?></label>
			<?php echo elgg_view('output/text',array('value' => elgg_echo("tasks:task_percent_done_{$entity->percent_done}"))); ?>
			</td>
			<td width="50%">
			<label><?php 	echo elgg_echo('tasks:work_remaining'); ?></label>
			<?php echo elgg_view('output/text',array('value' => $entity->work_remaining)); ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label><?php echo $worker ? elgg_echo('tasks:assigned_to') :""; ?></label>
			<?php if ($worker) { ?>
			<a href="<?php echo elgg_get_site_url(); ?>profile/<?php echo $worker->username; ?>"><?php echo $worker->name; ?></a>
			<?php } ?>
			</td>
		</tr>
		<tr>
			<td width="100%" colspan="2">
			<!--	<hr> -->
			<label><?php 	echo elgg_echo('tasks:description'); ?></label>
				<?php   echo elgg_view('output/longtext',array('value' => $entity->description)); ?>
			</td>
		</tr>
	</table>
	
<div class="resume">
	<div class="contentWrapper resume_contentWrapper" width=716>
	<p><a class="collapsibleboxlink resume_collapsibleboxlink">+</a></p>
	<h3>Test Section</h3>
		<div class="collapsible_box resume_collapsible_box">
			<p>content</p>
		</div>
	</div>
</div>

<!--	<hr> -->


<?php
$www_root = elgg_get_config('wwwroot');
        $area2 = elgg_view_title(elgg_echo('resume:my'));
        $area2 .= '<div class="resume">';
        $area2 .=  "<p class=\"profile_info_edit_buttons\"><a href=\"" . $www_root . "pg/profile/" . $owner->username . "\")>" . elgg_echo("resume:profile:goto") . "</a></p>";
        $area2 .=  "<p class=\"profile_info_edit_buttons\"><a href=\"#\"onclick=javascript:window.open(\"" . $www_root . "pg/resumesprintversion/" . $owner->username . "\")>" . elgg_echo("resume:profile:gotoprint") . "</a></p>";
        $area2 .= "<div class=\"clearfloat\"></div>";
        $area2 .= "<br />";
        // List Work experience objects
            $area2 .= '<div class="contentWrapper resume_contentWrapper" width=716>';
            $area2 .= "<p><a class=\"collapsibleboxlink resume_collapsibleboxlink\">" . "+" . "</a></p>";
            $area2 .= '<h3>' . elgg_echo('resume:works') . '</h3>';
            $area2 .= "<div class=\"collapsible_box resume_collapsible_box\">";
            $area2 .= elgg_list_entities($owner->getGUID(), 'rWork', 0, false, false, false);
            $area2 .= "</div>";
            $area2 .= "</div>";
        $area2 .= '</div>';

//$area0 = elgg_view("resume/search");
$area1 = elgg_view_title(sprintf(elgg_echo('resume:user'), $owner->name));

echo elgg_view_layout("popup", array('header' => $area0, 'body' =>$area1 . $area2));

//echo elgg_dump($entity);
//echo elgg_dump($params);
/*
	
	echo elgg_view('object/task/summary', $params);

*/
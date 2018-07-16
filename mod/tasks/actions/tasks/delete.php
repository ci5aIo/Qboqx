<?php
/**
 * Remove a task
 *
 * Subtasks are not deleted but are moved up a level in the tree
 *
 * @package ElggTasks
 */

$guid = get_input('guid');                                             //$display .= '$guid: '.$guid.'<br>';
$task = get_entity($guid);                                             //$display .= '$task->getSubtype(): '.$task->getSubtype().'<br>';
if ($task) {
	if ($task->canEdit() && (elgg_instanceof($task, "object", "task") || elgg_instanceof($task, "object", "task_top") || elgg_instanceof($task, "object", "process_step"))) {
		$container = get_entity($task->container_guid);

		// Bring all child elements forward
		$parent = $task->parent_guid;                                   //$display .= '$parent: '.$parent.'<br>';
		$children = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task', 'process_step'),
			'container_guid' => $task->getContainerGUID(),
			'limit' => false,
			'metadata_name_value_pairs' => array(
				'name' => 'parent_guid',
				'value' => $task->getGUID()
			)
		));
		if ($children) {                                                 //$display .= 'children exist<br>';
			foreach ($children as $child) {
				$child->parent_guid = $parent;
			}
		}
		
		if ($task->delete()) {
			system_message(elgg_echo('tasks:delete:success'));
    		if (elgg_instanceof($task, "object", "process_step")){
    			    goto eof;//do nothing
    			}
			if ($parent) {
				if ($parent = get_entity($parent)) {
					forward($parent->getURL());
				}
			}
			if (elgg_instanceof($container, 'group')) {
				forward("tasks/group/$container->guid/all");
			}
			else {
				forward("tasks/owner/$container->username");
			}
		}
	}
}

register_error(elgg_echo('tasks:delete:failure'));
forward(REFERER);
eof:
register_error($display);
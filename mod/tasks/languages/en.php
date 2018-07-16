<?php
/**
 * Tasks languages
 *
 * @package ElggTasks
 */

$english = array(

	/**
	 * Menu items and titles
	 */

	'tasks' => "Assignments",
	'tasks:owner' => "Projects and assignments for %s",
	'tasks:friends' => "Projects and assignments for my friends",
	'tasks:all' => "All projects and assignments",
	'tasks:add' => "Add project",

	'tasks:group' => "Group projects",
	'groups:enabletasks' => 'Enable group projects',

	'tasks:edit' => "Edit this assignment",
	'tasks:delete' => "Delete this assignment",
	'tasks:history' => "History",
	'tasks:view' => "View assignments",
	'tasks:revision' => "Revision",

	'tasks:navigation' => "Navigation",
	'tasks:via' => "via assignments",
	'item:object:task_top' => 'Projects',
	'item:object:task' => 'Assignments',
	'tasks:nogroup' => 'This group does not have any projects yet',
	'tasks:more' => 'More assignments in que',
	'tasks:none' => 'No assignments created yet',

	/**
	* River
	**/

	'river:create:object:task' => '%s created an assignment %s',
	'river:create:object:task_top' => '%s created a project %s',
	'river:update:object:task' => '%s updated an assignment %s',
	'river:update:object:task_top' => '%s updated a project %s',
	'river:close:object:task' => '%s closed an assignment %s',
	'river:close:object:task_top' => '%s closed a project %s',
	'river:comment:object:task' => '%s commented on this assignment: %s',
	'river:comment:object:task_top' => '%s commented on this project: %s',

	/**
	 * Form fields
	 */

	'tasks:title' => 'Assignment title',
	'tasks:description' => 'Description',
	'tasks:tags' => 'Tags',
	'tasks:access_id' => 'Read access',
	'tasks:write_access_id' => 'Write access',
	'tasks:transfer:myself' => 'Me',

	/**
	 * Status and error messages
	 */
	'tasks:noaccess' => 'No access to this assignment',
	'tasks:cantedit' => 'You cannot edit this assignment',
	'tasks:saved' => 'Assignment saved',
	'tasks:notsaved' => 'Assignment could not be saved',
	'tasks:error:no_title' => 'You must specify a title for this assignment.',
	'tasks:delete:success' => 'The assignment was successfully deleted.',
	'tasks:delete:failure' => 'The assignment could not be deleted.',

	/**
	 * Task
	 */
	'tasks:strapline' => 'Last updated %s by %s',

	/**
	 * History
	 */
	'tasks:revision:subtitle' => 'Revision created %s by %s',

	/**
	 * Widget
	 **/

	'tasks:num' => 'Number of assignments to display',
	'tasks:widget:description' => "This is a list of your assignments.",

	/**
	 * Submenu items
	 */
	'tasks:label:view'     => "View assignment",
	'tasks:label:edit'     => "Edit assignment",
	'tasks:label:history'  => "Assignment history",

	/**
	 * Sidebar items
	 */
	'tasks:sidebar:this'   => "This assignment",
	'tasks:sidebar:children' => "Sub-tasks",
	'tasks:sidebar:parent' => "Parent",

	'tasks:newchild'       => "Create a sub-task",
	'tasks:backtoparent'   => "Back to '%s'",
	
	'tasks:start_date'     => "Start",
	'tasks:end_date'       => "End",
	'tasks:percent_done'   => " Done",
	'tasks:work_remaining' => "Remain.",
	'tasks:task_type'      => 'Type',
	'tasks:status'         => 'Status',
	'tasks:assigned_to'    => 'Assigned to',

	'tasks:task_type_'     =>"",
	'tasks:task_type_0'    =>"",
	'tasks:task_type_1'    =>"Analysis",
	'tasks:task_type_2'    =>"Design",
	'tasks:task_type_3'    =>"Developement",
	'tasks:task_type_4'    =>"Test",
	'tasks:task_type_5'    =>"Production",

	'tasks:task_status_'   =>"",
	'tasks:task_status_0'  =>"",
	'tasks:task_status_1'  =>"Opened",
	'tasks:task_status_2'  =>"Assigned",
	'tasks:task_status_3'  =>"Accepted",
	'tasks:task_status_4'  =>"In Progress",
	'tasks:task_status_5'  =>"Closed",

	'tasks:task_percent_done_' =>"0%",
	'tasks:task_percent_done_0'=>"0%",
	'tasks:task_percent_done_1'=>"20%",
	'tasks:task_percent_done_2'=>"40%",
	'tasks:task_percent_done_3'=>"60%",
	'tasks:task_percent_done_4'=>"80%",
	'tasks:task_percent_done_5'=>"100%",

	'tasks:tasksboard'         =>"AssignmnentsBoard",
	'tasks:tasksmanage'        =>"Manage",
	'tasks:tasksmanageone'     =>"Manage an assignment",
);

add_translation("en", $english);
<?php
return array(

	/**
	 * Menu items and titles
	 */

	'places' => "Places",
	'places:owner' => "%s's places",
	'places:friends' => "Friends' places",
	'places:all' => "Every place",
	'places:add' => "Add a place",

	'places:group' => "Places",
	'groups:enableplaces' => 'Enable group places',

	'places:new' => "A new place",
	'places:edit' => "Edit this place",
	'places:delete' => "Delete this place",
	'places:history' => "History",
	'places:view' => "View place",
	'places:revision' => "Revision",
	'places:current_revision' => "Current Revision",
	'places:revert' => "Revert",

	'places:navigation' => "Navigation",

	'places:notify:summary' => 'New place called %s',
	'places:notify:subject' => "A new place: %s",
	'places:notify:body' =>
'%s added a new place: %s

%s

View and comment on the place:
%s
',
	'item:object:place_top' => 'Top-level places',
	'item:object:place' => 'places',
	'places:nogroup' => 'This group does not have any places yet',
	'places:more' => 'More places',
	'places:none' => 'No places created yet',

	/**
	* River
	**/

	'river:create:object:place' => '%s created a place %s',
	'river:create:object:place_top' => '%s created a place %s',
	'river:update:object:place' => '%s updated a place %s',
	'river:update:object:place_top' => '%s updated a place %s',
	'river:comment:object:place' => '%s commented on a place titled %s',
	'river:comment:object:place_top' => '%s commented on a place titled %s',

	/**
	 * Form fields
	 */

	'places:title' => 'Place title',
	'places:description' => 'Description',
	'places:tags' => 'Tags',
	'places:parent_guid' => 'Parent place',
	'places:access_id' => 'Read access',
	'places:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'places:noaccess' => 'No access to place',
	'places:cantedit' => 'You cannot edit this place',
	'places:saved' => 'Place saved',
	'places:notsaved' => 'Place could not be saved',
	'places:error:no_title' => 'You must specify a name for this place.',
	'places:delete:success' => 'The place was successfully deleted.',
	'places:delete:failure' => 'The place could not be deleted.',
	'places:revision:delete:success' => 'The place revision was successfully deleted.',
	'places:revision:delete:failure' => 'The place revision could not be deleted.',
	'places:revision:not_found' => 'Cannot find this revision.',

	/**
	 * Page
	 */
	'places:strapline' => 'Last updated %s by %s',

	/**
	 * History
	 */
	'places:revision:subtitle' => 'Revision created %s by %s',

	/**
	 * Widget
	 **/

	'places:num' => 'Number of places to display',
	'places:widget:description' => "Here are your places",

	/**
	 * Submenu items
	 */
	'places:label:view' => "View place",
	'places:label:edit' => "Edit place",
	'places:label:history' => "Place history",

	/**
	 * Sidebar items
	 */
	'places:sidebar:this' => "This place",
	'places:sidebar:children' => "Interior Spaces",
	'places:sidebar:parent' => "Parent",

	'places:newchild' => "Create an interior space",
	'places:backtoparent' => "Back to '%s'",
);

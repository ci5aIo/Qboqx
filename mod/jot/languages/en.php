<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$english = array(
    // Site terms -- Replace default terms from search/languages/en.php
	'search:results' => 'Results for %s',


    // Site terms -- Replace default terms from pages/languages/en.php
	'pages:tags' => 'Quebs',


	// Menu items and titles
	'jot:hoverhelp:Components'   => "Components are distinct, manageable items that are part of this %s item. Examples of Components for a car include Wheels, Engine or Body.  Components can have components themselves.  Any work done to a component rolls up to its parent.</p><p>Enter the name of the Component and click [add !] to add a new component item.  Edit the item details at your convenience.",
	'jot:hoverhelp:Accessories'  => "Accessories are distinct, manageable items that connect to this %s item. Examples of Acccessories for a car include trailer, luggage rack or fuzzy dice.  Any work done to an accessory rolls up to its parent.</p><p>Enter the name of the accessory and click [add !] to add a new accessory item.  Edit the item details at your convenience.",
	'jot:hoverhelp:Component of' => "%s is a component of these items.", 
	'jot:hoverhelp:Attached to'  => "%s attaches to these items.",
	'jot:hoverhelp:Observation'  => "Observations about this item.  Each observation can have further observations, discoveries and causes. An observation may initiate an issue or task.  All observations roll up to the containing observation.</p><p>Enter your observation and click [add !] to add a new observation.",
	'jot:hoverhelp:Issues'       => "Issues related to this item.  An issue must originate from an Observation.  Select the appropriate observation from the list above.",
	'jot'                    => "Jots",
	'jot:title'              => "Jots",
	'jot:title:purchase'     => "New Purchase",
	'jot:title:receipt'      => "Sales Receipt",
	'jot:title:return'       => "Return Items",
	'jot:title:schedule'     => "Scheduled Maintenance",
	'jot:title:scheduled'    => "Scheduled Task",
	'jot:title:observation'  => "Observation",
	'jot:title:accessory'    => "Accessory",
    'jot:title:experience'   => "Experience",
	'jot:add'                => "New jot",
	'jot:add:title'          => "Add a new jot",
	'jot:add:all'            => 'Add a jot',
	'jot:quick'              => "QuicAdd",
	'jot:quick:title'        => "New jot",
	'jot:edit'               => "Edit %s",
	'jot:text'               => "Brief description of your artifact",
	'jot:uploadimages'       => "Add a photo",
	'jot:image'              => "Item image",
	'jot:save'               => 'Save',
	'jot:save:observation'   => 'Save Observation',
	'jot:save:purchase'      => 'Save Purchase',
	'jot:save:component'     => 'Save Component',
	'jot:save:receipt'       => 'Save Receipt',
	'jot:save:return'        => 'Return',
	'jot:save:define'        => 'Define ...',
	'jot:set:schedule'       => 'Que ...',
	'jot:none:found'         => 'No artifacts found',
	'jot:text:help'          => "(No HTML and max. 250 characters)",
	'jot:title:help'         => "(1-3 words)",
	// 'jot:tags'            => "Tags",
	'jot:tags'               => "Labels",
	'jot:tags:help'          => "(Separate labels with commas)",
	'jot:access:help'        => "(Who can see this artifact)",
	'jot:replies'            => "Replies",
	'jot:created:gallery'    => "Created by %s <br>at %s",
	'jot:created:listing'    => "Created by %s at %s",
	'jot:showbig'            => "Show larger picture",
	'jot:type'               => "Type",
	'jot:charleft'           => "characters left",
	'jot:widget'             => "Jots",


	// jot river
	'river:create:object:jot' => '%s created %s',
	'river:update:object:jot' => '%s updated %s',
	'river:comment:object:jot' => '%s commented on %s',
	'river:create:object:transfer' => '%s created %s for %s',
	'river:create:object:component' => '%s created %s for %s',
	'river:update:object:transfer' => '%s updated %s',
	'river:comment:object:transfer' => '%s commented on %s',		

	// Status messages
	'jot:posted'             => "Jot added",
	'jot:deleted'            => "Jot deleted",
	'jot:uploaded'           => "Image added.",
	'jot:detach'             => "Detach %s",
	'jot:detach:confirm'     => "Detach %s?",
	'jot:delete:confirm'     => "Delete %s?",
	
	// Error messages
	'jot:save:failure' => "Your jot could not be saved. Please try again.",
	'jot:blank' => "Sorry; you need to fill in the title before you can add a jot.",
	'jot:notfound' => "Sorry; we could not find that jot.",
	'jot:notdeleted' => "Sorry; we could not delete this jot.",

	// jot categories
	'jot:categories' => 'Aspects',
	'jot:categories:choose' => 'Artifact type: ',
	'jot:categories:settings' => 'Aspects:',
	'jot:categories:explanation' => 'Set aspects for enhancing QuebX items. Each aspect represents a type of artifact that one can connected to an item. Remember not to use special characters in aspects and put them in your language files as jot:<i>aspectname</i>',
	'jot:categories:save:success' => 'Aspects were successfully saved.',
	'jot:categories:settings:categories' => 'Aspects',
	'jot:all' => "Everything",
	'jot:category' => "Aspect",
	'jot:category:title' => "%s",
	'jot:aspect:Comment' => 'Comment',
	'jot:aspect:Request' => 'Request',
	'jot:aspect:Issue' => 'Issue',
	'jot:aspect:Activity' => 'Activity',
	'jot:aspect:Event' => 'Event',
	'jot:aspect:Research' => 'Research',
	'jot:aspect:Journal' => 'Journal',
	'jot:aspect:Transfer' => 'Transfer',

	// Custom select
	'jot:custom:select' => "Item condition",
	'jot:custom:text' => "Condition",
	'jot:custom:activate' => "Enable Custom Select:",
	'jot:custom:settings' => "Custom Select Choices",
	'jot:custom:choices' => "Set some predefined choices for the custom select dropdown box.<br>Choices could be \"jot:new,jot:used...etc\", seperate each choice with commas - remember to put them in your language files",

	'jot:add_more' => "Details",
	'jot:edit_more:invalid_guid' => "This is not a valid jot.",
	'jot:edit_more:response' => "The jot was connected to your item.",
	'jot:category:all' => "Everything",

	// Jot Labels
	// Settings
	'jot:settings:status' => "Status",
	'jot:settings:desc' => "Description",
	'jot:comments' => "Allow comments",
	'jot:custom' => "Custom field",
	'jot:unlimited' => "Unlimited",
	'jot:pmbutton' => "Enable private message button",
	'jot:pmbuttontext' => "Send Private Message",
	'jot:numchars' => "Max. number of characters in jot",
	'jot:allowhtml' => "Allow HTML in jots",
	'jot:adminonly' => "Only admin can create jots",
	'jot:max:jots' => "Max. number of jots per user",
	'jot:title:issue' => "Issue",
	'jot:title:request' => "Request",
	'jot:title:component' => "New Component",
	'jot:post' => "Jot Box",
	'jot:post:subtitle' => "Leave a comment",
	'jot:post:subtitle:authorized' => " or create a new ticket",
	'jot:post:button' => "Jot",
	'jot:routing:explanation'=>"Use this powerful card to leave a simple comment on '%s' or to create a whole new action to manage it.  Just select your action above and click the Jot button below.",
	
	// Issues Labels
	'issues:edit' => "Edit Issue",

	// Observations Labels
	'observations:title'            => "Title",
	'observations:description'      => 'Description',
	'observations:asset'            => "Asset",
	'observations:assigned_to'      => "Assigned to",
	'observations:number'           => "Observation #",
	'observations:moment' => "Observation date",
	'observations:type'             => "Observation type",
	'observations:observer'         => "Observed by",
	'observations:state'            => "State",
	'observations:tags'             => "Labels",
	'observations:access_id'        => "Visibility",
	'observations:write_access_id'  => "Edit access",
	
	'jot:title:insight'             => "Insight",
	'jot:save:insight'              => "Save",
	
	// Transfers Labels
	'transfer:title'                => "Transfer title",
	'transfer:transfer_to'          => "Transfer to",
	'transfer:value_received'       => "Value received",
	'transfer:offer_date'           => "Offer date",
	'transfer:request_date'         => "Request date",
	'transfer:acceptance_date'      => "Acceptance date",
	'transfer:delivery_date'        => "Delivery date",
	'transfer:received_date'        => "Received date",
	'transfer:selling_cost'         => "Selling cost",
	'transfer:deposit_account'      => "Deposit account",
	'transfer:transaction_number'   => "Transaction number",
	'transfer:description'          => "Description",
	'jot:save:transfer'             => "Transfer",
);

add_translation("en",$english);

?>

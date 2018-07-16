<?php
/**
 * Elgg QuebX Plugin
 * @package quebx
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$english = array(
    'table_columns:fromView:checkbox'=>"",
    'table_columns:fromView:radio'=>"",
    // Site terms -- Replace default terms from languages/en.php
	'profile:field:tags' => 'Labels',
	'tagcloud' => "Labelsxxx",
	'tagcloud:allsitetags' => "Label Cloud",
	'tags' => "Labels",
	'tag_names:tags' => 'Labels',
	'tags:site_cloud' => 'Label Cloud',
	'tag:search:startblurb' => "Items labeled as '%s':",

    // Site terms -- Replace default terms from search/languages/en.php
	'search:results' => 'Results for %s',
	'search:results:tags' => "The '%s' Box",

    // Site terms -- Replace default terms from mod/tagcloud/languages/en.php
	'tagcloud:widget:title' => 'Label Array',
	'tagcloud:widget:description' => 'Label array',
	'tagcloud:widget:numtags' => 'Number of labels to show',

    // Site terms -- Replace default terms from pages/languages/en.php
	'pages:tags' => 'Boxes',

	'tasks' => "Que",
	
	'items:group'            => 'Items',
	'groups:enableitems'     => 'Enable group items',
	'groups:subtype'         => 'Group type',
    'groups:categories'      => 'Categories',
              
	// Menu items and titles
	'quebx' => "Item",
	'quebx:posts' => "Items",
	'quebx:title' => "Quebx",
	'quebx:user:title' => "%s's stuff",
	'quebx:user' => "%s's Stuff",
	'quebx:user:friends' => "%s's friends stuff",
	'quebx:user:friends:title' => "%s's friends stuff",
	'quebx:ours' => 'Our Stuff',
	'quebx:mine' => "My Stuff",
	'quebx:mine:title' => "My things",
	'quebx:posttitle' => "%s's things: %s",
	'quebx:friends' => "Friends' Stuff",
	'quebx:friends:title' => "My friends' stuff",
	'quebx:everyone:title' => "Everything on QuebX",
	'quebx:shared' => 'Our Stuff',
	'quebx:shared:title' => 'Our things',
	'quebx:everyone' => "Everything",
	'quebx:read' => "View post",
	'quebx:add' => "Add Item",
	'quebx:add:now' => "Add!",
	'quebx:add:title' => "Add a new item",
	'quebx:auto:title' => "Add an automobile",
	'quebx:add:antiques' => "Add an antique",
	'quebx:add:appliances' => "Add an appliance",
	'quebx:add:art' => "Add art",
	'quebx:add:auto' => "Add an automobile",
	'quebx:add:baby' => "Add baby and kids stuff",
	'quebx:add:luggage' => 'Add luggage',
	'quebx:add:media' => 'Add media',
	'quebx:add:clothes' => 'Add clothes',
	'quebx:add:collectibles' => 'Add a collectible',
	'quebx:add:computers' => 'Add computer stuff',
	'quebx:add:electronics' => 'Add electronics',
	'quebx:add:furniture' => 'Add furniture',
	'quebx:add:home' => 'Add home & garden',
	'quebx:add:jewelry' => 'Add jewelry',
	'quebx:add:instruments' => 'Add an instrument',
	'quebx:add:office' => 'Add office equipment',
	'quebx:add:sports' => 'Add sports equipment',
	'quebx:add:tools' => "Add tools",
	'quebx:add:video' => 'Add video equipment',
	'quebx:add:all' => 'Add an item',
	'quebx:quick' => "QuicAdd",
	'quebx:quick:title' => "New item",
	'quebx:edit' => "Edit item",
	'quebx:imagelimitation' => "Must be JPG, GIF or PNG.",
	'quebx:text' => "Brief description of your item",
	'quebx:uploadimages' => "Add photo for your item",
	'quebx:image' => "Item image",
	'quebx:custom1' => "Custom Characteristic",
	'quebx:custom2' => "Another Custom Characteristic",
	'quebx:imagelater' => "",
	'quebx:strapline' => "Added",
	'item:object:quebx' => 'QuebX items',
	'quebx:save' => 'Save item',
	'quebx:none:found' => 'No items found',
	'quebx:pmbuttontext' => "Send Private Message",
	'quebx:price' => "Monetary value",
	'quebx:price:help' => "(in %s)",
	'quebx:text:help' => "(No HTML and max. 250 characters)",
	'quebx:title:help' => "(1-3 words)",
	// 'quebx:tags' => "Tags",
	'quebx:tags' => "Labels",
	'quebx:tags:help' => "(Separate labels with commas)",
	'quebx:access:help' => "(Who can see this item)",
	'quebx:replies' => "Replies",
	'quebx:created:gallery' => "Created by %s <br>at %s",
	'quebx:created:listing' => "Created by %s at %s",
	'quebx:showbig' => "Show larger picture",
	'quebx:type' => "Type",
	'quebx:charleft' => "characters left",
	'quebx:accept:terms' => "I have read and accepted the %s of use.",
	'quebx:terms' => "terms",
	'quebx:terms:title' => "Terms of use",
	'quebx:terms' => "<li class='elgg-divide-bottom'>QuebX is for maintaining or exchanging items among members.</li>
			<li class='elgg-divide-bottom'>No more than %s Market posts are allowed pr. user at the same time.</li>

			<li class='elgg-divide-bottom'>A QuebX post may only contain one item, unless it's part of a matching set.</li>
			<li class='elgg-divide-bottom'>Commercial advertising is limited to those who have signed a promotional agreement with QuebX.</li>
   			<li class='elgg-divide-bottom'>We reserve the right to delete any items violating our terms of use.</li>
			<li class='elgg-divide-bottom'>Terms are subject to change over time.</li>
			",

	// Status messages
	'quebx:posted' => "Your item was successfully added to QuebX.",
	'quebx:deleted' => "Your item was successfully deleted from QuebX.",
	'quebx:uploaded' => "Your image was succesfully added.",

	// Error messages
	'quebx:save:failure' => "Your item could not be saved. Please try again.",
	'quebx:blank' => "Sorry; you need to fill in both the title and body before you can add an item.",
	'quebx:tobig' => "Sorry; your file is bigger then 1MB, please upload a smaller file.",
	'quebx:notjpg' => "Please make sure the picture inculed is a .jpg, .png or .gif file.",
	'quebx:notuploaded' => "Sorry; your file doesn't apear to have uploaded.",
	'quebx:notfound' => "Sorry; we could not find the specified item.",
	'quebx:notdeleted' => "Sorry; we could not delete this item.",
	'quebx:tomany' => "Error: Too many items",
	'quebx:tomany:text' => "You have reached the maximum number of items per user. Please delete some first!",
	'quebx:accept:terms:error' => "You must accept the terms of use!",

	// Settings
	'quebx:settings:status' => "Status",
	'quebx:settings:desc' => "Description",
	'quebx:max:posts' => "Max. number of items per user",
	'quebx:unlimited' => "Unlimited",
	'quebx:currency' => "Currency ($, €, DKK or something)",
	'quebx:allowhtml' => "Allow HTML in QuebX posts",
	'quebx:numchars' => "Max. number of characters in item description (only valid without HTML)",
	'quebx:pmbutton' => "Enable private message button",
	'quebx:adminonly' => "Only admin can create items",
	'quebx:comments' => "Allow comments",
	'quebx:custom' => "Custom field",

	// quebx categories
	'quebx:categories' => 'Categories',
	'quebx:categories:choose' => 'Choose category',
	'quebx:categories:settings' => 'Categories:',
	'quebx:categories:explanation' => 'Set some predefined categories for posting to QuebX.<br>Categories could be "clothes, footwear or buy,sell etc...", seperate each category with commas - remember not to use special characters in categories and put them in your language files as quebx:<i>categoryname</i>',
	'quebx:categories:save:success' => 'Aspects were successfully saved.',
	'quebx:categories:settings:categories' => 'Categories',
	'quebx:all' => "Everything",
	'quebx:category' => "Category",
	'quebx:category:title' => "%s",
//	'quebx:category:title' => "Category: %s",
//	'quebx:category:title' => "%s Items",

	// Categories
	'quebx:buy' => "Buying",
	'quebx:sell' => "Selling",
	'quebx:swap' => "Swap",
	'quebx:free' => "Free",
		//Products
		'quebx:category:antiques' => 'Antiques',
		'quebx:category:appliances' => 'Appliances',
		'quebx:category:art' => 'Arts & Crafts',
		'quebx:category:auto' => 'Automobiles',
		'quebx:category:baby' => 'Baby & Kid Stuff',
		'quebx:category:luggage' => 'Bags & Luggage',
		'quebx:category:media' => 'CDs & DVDs',
		'quebx:category:car' => 'Cars & Trucks',
		'quebx:category:clothes' => 'Clothes & Accessories',
		'quebx:category:collectibles' => 'Collectibles',
		'quebx:category:computers' => 'Computers & Acc.',
		'quebx:category:electronics' => 'Electronics',
		'quebx:category:furniture' => 'Furniture',
		'quebx:category:home' => 'Home & Garden',
		'quebx:category:jewelry' => 'Jewelry',
		'quebx:category:instruments' => 'Musical Instruments',
		'quebx:category:office' => 'Office & Biz',
		'quebx:category:sports' => 'Sports & Bicycles',
		'quebx:category:tools' => 'Tools',
		'quebx:category:video' => 'Video Games and Consoles',

	// Custom select
	'quebx:custom:select' => "Item condition",
	'quebx:custom:text' => "Condition",
	'quebx:custom:activate' => "Enable Custom Select:",
	'quebx:custom:settings' => "Custom Select Choices",
	'quebx:custom:choices' => "Set some predefined choices for the custom select dropdown box.<br>Choices could be \"quebx:new,quebx:used...etc\", seperate each choice with commas - remember to put them in your language files",

	// Custom choices
	 'quebx:na' => "No information",
	 'quebx:new' => "New",
	 'quebx:unused' => "Unused",
	 'quebx:used' => "Used",
	 'quebx:good' => "Good",
	 'quebx:fair' => "Fair",
	 'quebx:poor' => "Poor",

	 'quebx:add_more' => "Details",
	 'quebx:edit_more:invalid_guid' => "This is not a valid item.",
	 'quebx:edit_more:response' => "The information was added to your item.",
	 'quebx:category:all' => "Everything",

   'quebx:category:bicycles' => "Bicycles",
   'quebx:category:shoes' => "Shoes",
   'quebx:category:clothing' => "Clothing",
	
	 // shoe example
	 'quebx:edit_more:shoes:family:label' => "Type",
	 'quebx:edit_more:shoes:color:label' => "Color",
	 'quebx:edit_more:shoes:size:label' => "Size",
	 'quebx:edit_more:shoes:gender:label' => 'Gender',

	 'quebx:family:shoes:dress' => "Dress shoes",
	 'quebx:family:shoes:athletic' => "Athletic shoes",

	 'quebx:edit_more:shoes:running:brand:label' => "Brand",
	 'quebx:edit_more:shoes:running:price:label' => "Price",

	 'quebx:edit_more:shoes:dress:brand:label' => "Brand",
	 'quebx:edit_more:shoes:dress:price:label' => "Price",


    //Quebx Labels
		'quebx:asset_title' => "Title*",
		'quebx:asset_category' => "Category",
		'quebx:asset_manufacturer' => "Manufacturer",
		'quebx:asset_brand' => "Brand",
		'quebx:asset_model' => "Model #",
		'quebx:asset_part' => "Part #",
		'quebx:asset_description' => "Description*",
		'quebx:asset_collection' => "Collection",
		'quebx:asset_warranty' => "Warranty",
		'quebx:asset_warrantor' => "Warrantor",
		'quebx:asset_owner' => "Owner",
		'quebx:asset_lifecycle' => "Lifecycle",
		'quebx:asset_quantity' => "Quantity",
		'quebx:asset_length' => "Length",
		'quebx:asset_width' => "Width",
		'quebx:asset_height' => "Height",
		'quebx:asset_weight' => "Weight",
		'quebx:asset_features' => "Features",
		'quebx:asset_modelyear' => "Model Year",
		'quebx:auto_drive' => "Drive",
		'quebx:auto_displacement' => "Engine Size",
		'quebx:auto_cylinders' => "Cylinders",
		'quebx:auto_horsepower' => "Horsepower",
		'quebx:auto_transmission' => "Transmission",
		'quebx:auto_torque' => "Torque",
		'quebx:auto_tires' => "Tires",
		'quebx:auto_mpg' => "MPG",
		'quebx:auto_fuel' => "Fuel",
		'quebx:auto_bore' => "Bore",
		'quebx:auto_stroke' => "Stroke",
		'quebx:auto_wheelbase' => "Wheelbase",
		'quebx:auto_legroom' => "Leg Room",
		'quebx:auto_headroom' => "Head Room",
		'quebx:auto_cargocapacity' => "Cargo Capacity for Cars",
		'quebx:auto_towingcapacity' => "Towing Capacity for Trucks",
		'quebx:auto_payloadcapacity' => "Payload Capacity for Trucks",
		'quebx:asset_location' => "Location",
		'quebx:asset_serial01' => "Manufacturer’s Serial #",
		'quebx:asset_serial02' => "Owner’s Serial #",
		'quebx:asset_colorexterior' => "Exterior Color",
		'quebx:asset_colorinterior' => "Interior Color",
		'quebx:auto_vin' => "VIN",
		'quebx:asset_sku' => "SKU",
);

add_translation("en",$english);

?>

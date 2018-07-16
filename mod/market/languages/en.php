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
	// Menu items and titles
	'market'                 => "Item",
	'market:posts'           => "Items",
	'market:title'           => "Things",
	'market:user:title'      => "%s's stuff",
	'market:user'            => "%s's Stuff",
	'market:user:friends'    => "%s's friends stuff",
	'market:user:friends:title' => "%s's friends stuff",
	'market:ours'            => 'Our Things',
	'market:mine'            => "My Things",
	'market:mine:title'      => "My things",
	'market:posttitle'       => "%s's things: %s",
	'market:friends'         => "Friends' Things",
	'market:friends:title'   => "My friends' stuff",
	'market:everyone:title'  => "Everything on QuebX",
	'market:shared'          => 'Our Things',
	'market:shared:title'    => 'Our things',
	'market:everyone'        => "Everything",
	'market:read'            => "View post",
	'market:add'             => "Add Item",
	'market:add:all'         => 'Add an item',
	'market:add:title'       => "Add a new item",
	'market:auto:title'      => "Add an automobile",
	'market:add:antiques'    => "Add an antique",
	'market:add:appliances'  => "Add an appliance",
	'market:add:art'         => "Add art",
	'market:add:auto'        => "Add an automobile",
	'market:add:baby'        => "Add baby and kids stuff",
	'market:add:car'         => "Add a vehicle",
	'market:add:clothes'     => 'Add clothes',
	'market:add:collectibles' => 'Add a collectible',
	'market:add:computers'   => 'Add computer stuff',
	'market:add:electronics' => 'Add electronics',
	'market:add:furniture'   => 'Add furniture',
	'market:add:home'        => 'Add home & garden',
	'market:add:jewelry'     => 'Add jewelry',
	'market:add:instruments' => 'Add an instrument',
	'market:add:luggage'     => 'Add luggage',
	'market:add:media'       => 'Add media',
	'market:add:office'      => 'Add office equipment',
	'market:add:sports'      => 'Add sports equipment',
	'market:add:tools'       => "Add tools",
	'market:add:video'       => 'Add video equipment',
	'market:list:list'       => 'Switch to list view',
	'market:list:gallery'    => 'Switch to gallery view',
	'market:list:select'     => 'Switch to select view',
	'market:quick'           => "QuicAdd",
	'market:quick:title'     => "New item",
	'market:edit'            => "Edit item",
	'market:imagelimitation' => "(jpg gif png)",
	'market:text'            => "Brief description",
	'market:uploadimages'    => "Photo",
	'market:image'           => "Item image",
	'market:custom1'         => "Custom Characteristic",
	'market:custom2'         => "Another Custom Characteristic",
	'market:imagelater'      => "",
	'market:strapline'       => "Added",
	'item:object:market'     => 'QuebX items',
	'market:save'            => 'Add item',
    'market:submit:add_more' => 'Add details...',
	'market:none:found'      => 'No items found',
	'market:pmbuttontext'    => "Send Private Message",
	'market:price'           => "Monetary value",
	'market:price:help'      => "(in %s)",
	'market:text:help'       => "(No HTML and max. 250 characters)",
	'market:title:help'      => "(1-3 words)",
	// 'market:tags'         => "Tags",
	'market:tags'            => "Labels",
	'market:tags:help'       => "(Separate labels with commas)",
	'market:access:help'     => "(Who can see this item)",
	'market:replies'         => "Replies",
	'market:created:gallery' => "Created by %s <br>at %s",
	'market:created:listing' => "Created by %s at %s",
	'market:showbig'         => "Show larger picture",
	'market:type'            => "Type",
	'market:charleft'        => "characters left",
	'market:accept:terms'    => "I have read and accepted the %s of use.",
	'market:terms'           => "terms",
	'market:terms:title'     => "Terms of use",
	'market:terms'           => "<li class='elgg-divide-bottom'>QuebX is for maintaining or exchanging items among members.</li>
			<li class='elgg-divide-bottom'>No more than %s Market posts are allowed pr. user at the same time.</li>

			<li class='elgg-divide-bottom'>A QuebX post may only contain one item, unless it's part of a matching set.</li>
			<li class='elgg-divide-bottom'>Commercial advertising is limited to those who have signed a promotional agreement with QuebX.</li>
   			<li class='elgg-divide-bottom'>We reserve the right to delete any items violating our terms of use.</li>
			<li class='elgg-divide-bottom'>Terms are subject to change over time.</li>
			",

	// market widget
	'market:widget'          => "My Things",
	'market:widget:description' => "Showcase your items on QuebX",
	'market:widget:viewall'  => "View all my items",
	'market:num_display'     => "Number of items to display",
	'market:icon_size'       => "Icon size",
	'market:small'           => "small",
	'market:tiny'            => "tiny",

	// market river
	'river:create:object:market' => '%s acquired %s from %s',
	'river:update:object:market' => '%s updated %s',
	'river:comment:object:market' => '%s commented on %s',

	// Confirmation messages
	'market:confirm:delete' => "Are you sure you want to delete the %s %s?", // [component, accessory, remnant] [title]
	// Status messages
	'market:posted' => "Your item was successfully added to QuebX.",
	'market:deleted' => "% was successfully deleted from QuebX.",
	'market:uploaded' => "Your image was succesfully added.",

	// Error messages
	'market:save:failure' => "Your item could not be saved. Please try again.",
	'market:blank' => "Sorry; you need to fill in both the title and body before you can add an item.",
	'market:tobig' => "Sorry; your file is bigger then 1MB, please upload a smaller file.",
	'market:notjpg' => "Please make sure the picture inculed is a .jpg, .png or .gif file.",
	'market:notuploaded' => "Sorry; your file doesn't apear to have uploaded.",
	'market:notfound' => "Sorry; we could not find the specified item.",
	'market:notdeleted' => "Sorry; we could not delete this item.",
	'market:tomany' => "Error: Too many items",
	'market:tomany:text' => "You have reached the maximum number of items per user. Please delete some first!",
	'market:accept:terms:error' => "You must accept the terms of use!",

	// Settings
	'market:settings:status' => "Status",
	'market:settings:desc' => "Description",
	'market:max:posts' => "Max. number of items per user",
	'market:unlimited' => "Unlimited",
	'market:currency' => "Currency ($, €, DKK or something)",
	'market:allowhtml' => "Allow HTML in QuebX posts",
	'market:numchars' => "Max. number of characters in item description (only valid without HTML)",
	'market:pmbutton' => "Enable private message button",
	'market:adminonly' => "Only admin can create items",
	'market:comments' => "Allow comments",
	'market:custom' => "Custom field",

	// market categories
	'market:categories' => 'Categories',
	'market:categories:choose' => 'Choose category',
	'market:categories:settings' => 'Categories:',
	'market:categories:explanation' => 'Set some predefined categories for posting to QuebX.<br>Categories could be "clothes, footwear or buy,sell etc...", seperate each category with commas - remember not to use special characters in categories and put them in your language files as market:<i>categoryname</i>',
	'market:categories:save:success' => 'Aspects were successfully saved.',
	'market:categories:settings:categories' => 'Categories',
	'market:all' => "Everything",
	'market:category' => "Category",
	'market:category:title' => "%s",
//	'market:category:title' => "Category: %s",
//	'market:category:title' => "%s Items",

	// Categories
	'market:buy' => "Buying",
	'market:sell' => "Selling",
	'market:swap' => "Swap",
	'market:free' => "Free",
		//Products
		'market:category:' => 'Uncategorized',
		'market:category:1' => 'Uncategorized',
		'market:category:antiques' => 'Antiques',
		'market:category:appliances' => 'Appliances',
		'market:category:art' => 'Arts & Crafts',
		'market:category:auto' => 'Automobiles',
		'market:category:baby' => 'Baby & Kid Stuff',
		'market:category:luggage' => 'Bags & Luggage',
		'market:category:media' => 'CDs & DVDs',
		'market:category:car' => 'Cars & Trucks',
		'market:category:clothes' => 'Clothes & Accessories',
		'market:category:collectibles' => 'Collectibles',
		'market:category:computers' => 'Computers & Acc.',
		'market:category:electronics' => 'Electronics',
		'market:category:furniture' => 'Furniture',
		'market:category:home' => 'Home & Garden',
		'market:category:jewelry' => 'Jewelry',
		'market:category:instruments' => 'Musical Instruments',
		'market:category:office' => 'Office & Biz',
		'market:category:sports' => 'Sports & Bicycles',
		'market:category:tools' => 'Tools',
		'market:category:video' => 'Video Games and Consoles',

		'market:category:162' => 'Appliances',
		'market:category:166' => 'Cars & Trucks',

	// Custom select
	'market:custom:select' => "Item condition",
	'market:custom:text' => "Condition",
	'market:custom:activate' => "Enable Custom Select:",
	'market:custom:settings' => "Custom Select Choices",
	'market:custom:choices' => "Set some predefined choices for the custom select dropdown box.<br>Choices could be \"market:new,market:used...etc\", seperate each choice with commas - remember to put them in your language files",

	// Custom choices
	 'market:na' => "No information",
	 'market:new' => "New",
	 'market:unused' => "Unused",
	 'market:used' => "Used",
	 'market:good' => "Good",
	 'market:fair' => "Fair",
	 'market:poor' => "Poor",

	 'market:add_more' => "Details",
	 'market:edit_more:invalid_guid' => "This is not a valid item.",
	 'market:edit_more:response' => "The information was added to your item.",
	 'market:category:all' => "Everything",

   'market:category:bicycles' => "Bicycles",
   'market:category:shoes' => "Shoes",
   'market:category:clothing' => "Clothing",
	
	 // shoe example
	 'market:edit_more:shoes:family:label' => "Type",
	 'market:edit_more:shoes:color:label' => "Color",
	 'market:edit_more:shoes:size:label' => "Size",
	 'market:edit_more:shoes:gender:label' => 'Gender',

	 'market:family:shoes:dress' => "Dress shoes",
	 'market:family:shoes:athletic' => "Athletic shoes",

	 'market:edit_more:shoes:running:brand:label' => "Brand",
	 'market:edit_more:shoes:running:price:label' => "Price",

	 'market:edit_more:shoes:dress:brand:label' => "Brand",
	 'market:edit_more:shoes:dress:price:label' => "Price",


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

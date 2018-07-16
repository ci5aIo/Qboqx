<!-- Form: mod\quebx\views\default\groups\edit\profile.php  -->
<?php

/**
 * Group edit form
 *
 * This view contains the group profile field configuration
 *
 * @package ElggGroups
 */

$name                 = elgg_extract("name", $vars);
$group_profile_fields = elgg_get_config("group");
$guid                 = elgg_extract("guid", $vars);
$group                = get_entity($guid);
$group_icon           = elgg_view_entity_icon($group, 'small', array('href' => '','width' => '','height' => '',)); 
$group_icon_input     = elgg_view("input/file"    , array("name" => "icon"));
$group_name_input     = elgg_view("input/text"    , array("name" => "name"            , "value" => $name,));
$group_brief_description = elgg_view("input/text" , array("name" => "briefdescription"     , "value" => $group->briefdescription,  'placeholder'=>'Short Description',));
$group_brief_description.= elgg_view("input/hidden", array("name" => "jot[briefdescription]", "value" => $group->briefdescription));
$group_description    = elgg_view("input/longtext", array("name" => "description"     , "value" => $group->description,  'placeholder'=>'Group Description',));
$group_description   .= elgg_view("input/hidden"  , array("name" => "jot[description]", "value" => $group->description));
$group_website        = elgg_view("input/url", array("name" => "website"     , "value" => $group->website,  'placeholder'=>'http://',));
$group_address_1      = elgg_view("input/text" , array("name" => "addr_1"     , "value" => $group->addr_1,  'placeholder'=>'Address',));
$group_address_2      = elgg_view("input/text" , array("name" => "addr_2"     , "value" => $group->addr_2,));
$group_city           = elgg_view("input/text" , array("name" => "addr_city"     , "value" => $group->addr_city,  'placeholder'=>'City',));
$group_state          = elgg_view("input/text" , array("name" => "addr_state"     , "value" => $group->addr_state,  'placeholder'=>'State',));
$group_postal_code    = elgg_view("input/text" , array("name" => "addr_postal"     , "value" => $group->addr_postal,  'placeholder'=>'Postal Code',));
$group_categories     = elgg_view("input/checkboxes", array(
        'name'        => 'categories',
        'options'     => array('Vendor'           => 'Vendor',
                               'Manufacturer'     => 'Manufacturer',
                               'Service Provider' => 'Service Provider',
        		               'Supplier'         => 'supplier'
                                ),
        'default'     => false,
        'value'       => $group->categories,
        'align'       => 'horizontal'
));
$address_line3 = "<div class='rTable' style='width:510px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:170px'>$group_city</div>
					<div class='rTableCell' style='width:70px'>State</div>
					<div class='rTableCell' style='width:100px'>$group_state</div>
					<div class='rTableCell' style='width:70px'>Postal</div>
					<div class='rTableCell' style='width:100px'>$group_postal_code</div>
				</div>
            </div>
         </div>";

$form .= "<div class='rTable' style='width:630px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px'>Group name</div>
					<div class='rTableCell' style='width:510px'>$group_name_input</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px;'>Group categories</div>
					<div class='rTableCell' style='width:510px'>$group_categories</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px; vertical-align:top;'>Brief description</div>
					<div class='rTableCell' style='width:510px'>$group_brief_description</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px; vertical-align:top;'>Description</div>
					<div class='rTableCell' style='width:510px'>$group_description</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px'>Group icon</div>
					<div class='rTableCell' style='width:510px'>$group_icon_input</div>
					    
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px'></div>
					<div class='rTableCell' style='width:510px'>
					    <div class='elgg-image'>
					        <div class='groups-profile-icon'>$group_icon</div>
					    </div>
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px; vertical-align:top;'>Address</div>
					<div class='rTableCell' style='width:510px'>$group_address_1</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px; vertical-align:top;'></div>
					<div class='rTableCell' style='width:510px'>$group_address_2</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px; vertical-align:top;'>City</div>
					<div class='rTableCell' style='width:510px'>$address_line3</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:120px; vertical-align:top;'>Website</div>
					<div class='rTableCell' style='width:510px'>$group_website</div>
				</div>
			</div>
		</div>";
echo $form;
/*
?>
<div>
<label><?php echo elgg_echo("groups:icon"); ?></label><br />
	<?php echo elgg_view("input/file", array("name" => "icon")); ?>
</div>
<div>
	<label><?php echo elgg_echo("groups:name"); ?></label><br />
	<?php echo elgg_view("input/text", array(
		"name" => "name",
		"value" => $name,
	));
	?>
</div>
<?php
*/
// show the configured group profile fields
foreach ((array)$group_profile_fields as $shortname => $valtype) {
	if ($valtype == "hidden") {
		echo elgg_view("input/{$valtype}", array(
			"name" => $shortname,
			"value" => elgg_extract($shortname, $vars),
		));
		continue;
	}

// 	$line_break = ($valtype == "longtext") ? "" : "<br />";
// 	$label = elgg_echo("groups:{$shortname}");
// 	$input = elgg_view("input/{$valtype}", array(
// 		"name" => $shortname,
// 		"value" => elgg_extract($shortname, $vars),
// 	));

	echo "<div><label>$label</label>{$line_break}{$input}</div>";
}
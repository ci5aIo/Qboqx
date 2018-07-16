<!-- Form: mod\quebx\views\default\groups\edit\tools.php  -->
<?php

/**
 * Group edit form
 *
 * This view contains the group tool options provided by the different plugins
 *
 * @package ElggGroups
 */

$tools = elgg_get_config("group_tool_options");
if ($tools) {
    
    $form .= "<div class='rTable' style='width:630px'>
			<div class='rTableBody'>";
    
	usort($tools, create_function('$a, $b', 'return strcmp($a->label, $b->label);'));
	
	foreach ($tools as $group_option) {
		$group_option_toggle_name = $group_option->name . "_enable";
		$value = elgg_extract($group_option_toggle_name, $vars);
		
		$form .= "<div class='rTableRow'>
					<div class='rTableCell' style='width:510px'>$group_option->label</div>
					<div class='rTableCell' style='width:120px'>".elgg_view("input/switchbox", array(
	                                                            			"value" => $value
	                                                            		))."</div>
				</div>";
/*@EDIT 2018-03-18 - SAJ - Replaced by the switchbox control above
	    $form .= "<div class='rTableRow'>
					<div class='rTableCell' style='width:510px'>$group_option->label</div>
					<div class='rTableCell' style='width:120px'>".elgg_view("input/radio", array(
                                                            			"name" => $group_option_toggle_name,
                                                            			"value" => $value,
					                                                    "align" => "horizontal",
                                                            			"options" => array(
                                                            				elgg_echo("option:yes") => "yes",
                                                            				elgg_echo("option:no") => "no",
                                                            			),
                                                            		))."</div>
				</div>";
*/
/*		?>
<div>
	<label>
		<?php echo $group_option->label; ?><br />
	</label>
		<?php echo elgg_view("input/radio", array(
			"name" => $group_option_toggle_name,
			"value" => $value,
			"options" => array(
				elgg_echo("option:yes") => "yes",
				elgg_echo("option:no") => "no",
			),
		));
		?>
</div>
<?php*/
	}
	$form .= '</div></div>';
}

echo $form;
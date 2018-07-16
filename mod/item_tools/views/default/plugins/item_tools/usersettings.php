<?php

$plugin = elgg_extract("entity", $vars);

$options = array(
	"date" => elgg_echo("item_tools:usersettings:time:date"),
	"days" => elgg_echo("item_tools:usersettings:time:days")
);

$item_tools_time_display_value = $plugin->getUserSetting("item_tools_time_display", elgg_get_page_owner_guid());
if (empty($item_tools_time_display_value)) {
	$item_tools_time_display_value = elgg_get_plugin_setting("item_tools_default_time_display", "item_tools");
}

echo "<div>";
echo elgg_echo("item_tools:usersettings:time:description");
echo "</div>";

echo "<div>";
echo elgg_echo("item_tools:usersettings:time");
echo "&nbsp;" . elgg_view("input/dropdown", array(
	"name" => "params[item_tools_time_display]",
	"options_values" => $options,
	"value" => $item_tools_time_display_value
));
echo "</div>";

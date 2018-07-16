<!-- Form  : jot\views\default\forms\insights\add.php<br> -->
<!-- Parent: jot\pages\jot\add_box.php -->
<?php

$item_guid   = $vars['item_guid'];
$description = $vars['description'];
$referrer    = $vars['referrer'];
$item        = get_entity($item_guid);
$ts          = time();
$title       = elgg_echo("New Insight");
$tags        = "";

echo elgg_view('input/hidden', array('name' => 'item_guid', 'value' => $item_guid));
echo elgg_view('input/hidden', array('name' => 'referrer' , 'value' => $referrer));
echo elgg_view('input/hidden', array('name' => 'type'     , 'value' => 'object'));
echo elgg_view('input/hidden', array('name' => 'subtype'  , 'value' => 'insight'));
 
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('jot_allowhtml', 'jot');
$numchars = elgg_get_plugin_setting('jot_numchars', 'jot');
if($numchars == ''){
	$numchars = '250';
}


if (defined('ACCESS_DEFAULT')) {
	$access_id = ACCESS_DEFAULT;
} else {
	$access_id = 0;
}

?>

<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
	// if too long...trim it!
	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	} else {
		// otherwise, update 'characters left' counter
		$("#"+cntfield).html(maxlimit - field.value.length);
	}
}
</script>

<?php
echo '<table width = "100%"><tr>
      <td><label>Title</label></td>
      <td>'.elgg_view("input/text", array(
			"name" => "title",
			"value" => $title,
			)).'</td>
      </tr></table>';

echo '<p><label>Describe your insight</label>';
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("jot:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='description' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.jotForm.description,"jot-remLen1",{$numchars}'
  onKeyUp='textCounter(document.jotForm.description,"jot-remLen1",{$numchars})'>{$description}</textarea><br />
HTML;
	echo "<div class='jot_characters_remaining'><span id='jot-remLen1' class='jot_charleft'>{$numchars}</span> " . elgg_echo("jot:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "description", "value" => $description));
}
echo "</label></p>";
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('jot:save:insight')));



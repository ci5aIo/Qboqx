Form: jot\views\default\forms\issues\add.php<br>
<?php

$variables = elgg_get_config('issues');
//$parent_guid = (int) get_input('parent_guid');
$item_guid = $vars['item_guid'];
$description = $vars['description'];
//$description = $jot['description'];
$referrer = $vars['referrer'];
$entity = $vars['entity'];
$item_entity = get_entity($item_guid);


// echo elgg_dump($vars);
echo 'Referrer: '.$referrer;
echo '<br>Description: '.$description;
echo '<br>item_guid: '.$item_guid;
echo '<br>Title: '.$title;
echo '<br>'.elgg_dump($variables);

echo elgg_view('input/hidden', array(
	'name' => 'item_guid',
	'value' => $item_guid
));

echo elgg_view('input/hidden', array(
	'name' => 'referrer',
	'value' => $referrer
));

echo elgg_view('input/hidden', array('name'=> 'type', 'value' => 'object'));
echo elgg_view('input/hidden', array('name'=> 'subtype', 'value' => 'issue'));
 
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('jot_allowhtml', 'jot');
$numchars = elgg_get_plugin_setting('jot_numchars', 'jot');
if($numchars == ''){
	$numchars = '250';
}

// Set title, form destination
$ts = time();
$title = elgg_echo("New Issue");
$tags = "";

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
      <td WIDTH=8%><label>Title</label></td>
      <td WIDTH=48%>'.elgg_view("input/text", array(
								"name" => "title",
								"value" => $title,
								)).'</td>
	  <td nowrap WIDTH=9% ALIGN="RIGHT"><b>Status:&nbsp;</b></td>
	  <td nowrap WIDTH=12%>'.elgg_view("input/text", array(
								"name" => "status",
								"value" => $status,
								)).'</td>
      <td nowrap WIDTH=10%><label>Issue #:&nbsp;</label></td>
      <td nowrap WIDTH=13%>ISSUE##</td>
      </tr>
      <tr><td WIDTH=8%><label>Asset	</label></td>
      <td WIDTH=92%>'.elgg_view('output/url', array(
                                 'name' => 'asset',
                                 'text' => $item_entity->title,
                                 'href' => $referrer
//                                 'href' => 'market/view/'.$item_guid
								)).'</td>
      </tr></table>';

echo '<p><label>What is the issue?</label>';
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

echo '<p></p><p><label>Attachments</label><br>Upload screenshots or other helpful documentation.';
echo elgg_view('input/file', array(
	'name' => $ts
));
echo "</label></p>";


echo "<p><label>" . elgg_echo("jot:tags") . "&nbsp;<small><small>" . elgg_echo("jot:tags:help") . "</small></small><br />";
echo elgg_view("input/tags", array(
				"name" => "tags",
				"value" => $tags,
				));
echo "</label></p>";

echo "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("jot:access:help") . "</small></small><br />";
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
echo "</label></p>";

echo "<p>";
echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('jot:save'), 'value' => elgg_echo('jot:save')));



<!--Form: market\views\default\forms\component\add.php-->
<!-- Parent: jot\pages\jot\add_box.php -->
<?php

//$parent_guid = (int) get_input('parent_guid');
$item_guid = elgg_extract('item_guid', $vars);
//$item_guid = $vars['item_guid'];
$description = $vars['description'];
$referrer = $vars['referrer'];
$item = get_entity($item_guid);
$ts = time();
$title = 'New Component';
$tags = "";

//echo elgg_dump($vars);
echo "<!--referrer: $referrer-->";

// echo elgg_dump($vars);
//echo 'Referrer: '.$referrer;
//echo 'Description: '.$description;
//echo 'item_guid: '.$item_guid;
//echo '<br>Title: '.$title;

//echo elgg_view('input/hidden', array('name' => 'item_guid', 'value' => $item_guid));
echo elgg_view('input/hidden', array('name' => 'guid'      , 'value' => $item_guid));
echo elgg_view('input/hidden', array('name' => 'referrer' , 'value' => $referrer));
//echo elgg_view('input/hidden', array('name' => 'type'     , 'value' => 'object'));
//echo elgg_view('input/hidden', array('name' => 'subtype'  , 'value' => 'item'));
echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => 'component'));
//echo elgg_view('input/hidden', array('name' => 'state'    , 'value' => '1'));
 
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('jot_allowhtml', 'jot');
$numchars = elgg_get_plugin_setting('jot_numchars', 'jot');
if($numchars == ''){
	$numchars = '250';
}


if (defined('ACCESS_PUBLIC')) {
	$access_id = ACCESS_PUBLIC;
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
      <tr><td><label>Asset	</label></td>
      <td>'.elgg_view('output/url', array(
                                 'name' => 'asset',
                                 'text' => $item->title,
                                 'href' => $referrer
								)).'</td>
      </tr></table>';
	  
/*echo '<table width = "100%"><tr>
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
      <td nowrap WIDTH=10%><label>observation #:&nbsp;</label></td>
      <td nowrap WIDTH=13%>observation##</td>
      </tr>
      <tr><td WIDTH=8%><label>Asset	</label></td>
      <td WIDTH=92%>'.elgg_view('output/url', array(
                                 'name' => 'asset',
                                 'text' => $item->title,
                                 'href' => $referrer
								)).'</td>
      </tr></table>';
*/

echo '<p><label>Description</label>';
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
echo "<p>";
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('jot:save:component')));



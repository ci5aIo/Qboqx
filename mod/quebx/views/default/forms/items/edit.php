<?php
/**
 * Elgg QuebX Plugin
 * @package quebx
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 *
 * Modified by Kevin Jardine for arckinteractive.com
 */

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('quebx_allowhtml', 'quebx');
$numchars = elgg_get_plugin_setting('quebx_numchars', 'quebx');
if($numchars == ''){
	$numchars = '250';
}

$title = $vars['itemtitle'];
$body = $vars['itembody'];
$tags = $vars['itemtags'];
$category = $vars['itemcategory'];
$access_id = $vars['access_id'];

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
if ($vars['entity']) {
  echo elgg_view('input/hidden',array('name'=>'parent_guid','value'=>$vars['entity']->container_guid));
}

//echo var_dump($relationships);
echo 'edit.php<br><label>';
echo elgg_echo("title");
echo '&nbsp;<small><small>' . elgg_echo('quebx:title:help') . '</small></small><br />';
echo elgg_view("input/text", array(
				"name" => "itemtitle",
				"value" => $title,
				));
echo "</label></p>";

$quebxcategories = elgg_view('quebx/quebxcategories',$vars);
if (!empty($quebxcategories)) {
	echo "<p>{$quebxcategories}</p>";
}

echo "<p><label>" . elgg_echo("quebx:text") . "<br>";
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("quebx:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='itembody' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.quebxForm.itembody,"quebx-remLen1",{$numchars}'
  onKeyUp='textCounter(document.quebxForm.itembody,"quebx-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	echo "<div class='quebx_characters_remaining'><span id='quebx-remLen1' class='quebx_charleft'>{$numchars}</span> " . elgg_echo("quebx:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "itembody", "value" => $body));
}
echo "</label></p>";

echo "<p><label>" . elgg_echo("quebx:image") . "<br />";
echo elgg_view('quebx/thumbnail', array('itemguid' => $vars['entity']->guid, 'size' => 'large', 'tu' => $vars['entity']->time_updated));
echo "<br /></label></p>";

echo "<p><label>here" . elgg_echo("quebx:uploadimages") . "<br /><small><small>" . elgg_echo("quebx:imagelimitation") . "</small></small><br />";
echo elgg_view("input/file",array('name' => 'upload'));
echo "</label></p>";

echo "<p><label>" . elgg_echo("quebx:tags") . "&nbsp;<small><small>" . elgg_echo("quebx:tags:help") . "</small></small><br />";
echo elgg_view("input/tags", array(
				"name" => "itemlabels",
				"value" => $tags,
				));
echo "</label></p>";

echo "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("quebx:access:help") . "</small></small><br />";
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
echo "</label></p>";

echo "<p>";

if ($vars['entity']) {
  echo elgg_view('input/hidden',array('name'=>'guid','value'=>$vars['entity']->guid));
}
echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('quebx:save'), 'value' => elgg_echo('quebx:save')));



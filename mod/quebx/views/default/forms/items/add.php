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

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('quebx_allowhtml', 'quebx');
$currency = elgg_get_plugin_setting('quebx_currency', 'quebx');
$numchars = elgg_get_plugin_setting('quebx_numchars', 'quebx');
if($numchars == ''){
	$numchars = '250';
}

// Set title, form destination
$title = elgg_echo("quebx:addpost");
$tags = "";
$title = "";
$price = "";
$custom = "";
$description = "";
if (defined('ACCESS_DEFAULT')) {
	$access_id = ACCESS_DEFAULT;
} else {
	$access_id = 0;
}

// Just in case we have some cached details
if (isset($vars['itemtitle'])) {
	$title = $vars['itemtitle'];
	$body = $vars['itembody'];
	$price = $vars['itemprice'];
	$custom = $vars['itemcustom'];
	$tags = $vars['itemlabels'];
}


?>
<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
	// if too long...trim it!
	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	} else {
		// otherwise, update 'characters left' counter
		cntfield.value = maxlimit - field.value.length;
	}
}

</script>
<?php
echo "mod/quebx/views/default/forms/items/add.php<br><label>";
echo elgg_echo("title");
echo "&nbsp;<small><small>" . elgg_echo("quebx:title:help") . "</small></small><br />";
echo elgg_view("input/text", array(
				"name" => "itemtitle",
				"value" => $title,
				));
echo "</label></p>";

$quebxcategories = elgg_view('quebx/quebxcategories',$vars);
if (!empty($quebxcategories)) {
	echo "<p>{$quebxcategories}</p>";
}

if(elgg_get_plugin_setting('quebx_custom', 'quebx') == 'yes'){
	$quebxcustom = elgg_view('quebx/custom',$vars);
	if (!empty($quebxcustom)) {
		echo "<p>{$quebxcustom}</p>";
	}
}

echo "<p><label>" . elgg_echo("quebx:text") . "<br>";
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("quebx:text:help"), $numchars) . "</small></small><br />";
	echo "<textarea name='itembody' class='mceNoEditor' rows='8' cols='40' onKeyDown='textCounter(document.quebxForm.itembody,document.quebxForm.remLen1,{$numchars}' onKeyUp='textCounter(document.quebxForm.itembody,document.quebxForm.remLen1,{$numchars})'>{$body}</textarea><br />";
	echo "<div class='quebx_characters_remaining'><input readonly type='text' name='remLen1' size='3' maxlength='3' value='{$numchars}' class='quebx_charleft'>" . elgg_echo("quebx:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "itembody", "value" => $body));
}
echo "</label></p>";

echo "<table border=2, width=100%><tr>";
echo "<td width=100>"; 
echo "<label>" . elgg_echo("quebx:price") . "&nbsp;<small><small>" . elgg_echo("quebx:price:help", array($currency)) . "</small></small><br />";
echo elgg_view("input/text", array(
				"name" => "itemprice",
				"value" => $price,
				));
			
echo "</label>";
echo "</td><td width = 20></td>";
echo "<td>";
echo "<p><label>" . elgg_echo("quebx:tags") . "&nbsp;<small><small>" . elgg_echo("quebx:tags:help") . "</small></small><br />";
echo elgg_view("input/tags", array(
				"name" => "itemlabels",
				"value" => $tags,
				));
echo "</label></p>";
echo "</tr><tr><td colspan=3>"; 
echo "</td></tr></table>";

echo "<p></p><p><label>" . elgg_echo("quebx:uploadimages") . "<br /><small><small>" . elgg_echo("quebx:imagelimitation") . "</small></small><br />";
echo elgg_view("input/file",array('name' => 'upload'));
echo "</label></p>";

echo "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("quebx:access:help") . "</small></small><br />";
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
echo "</label></p>";

echo "<p>";

echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('quebx:save')));



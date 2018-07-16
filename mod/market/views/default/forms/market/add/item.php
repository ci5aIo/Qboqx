<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 *
 * Modified by Kevin Jardine for arckinteractive.com
 */

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}


// Set title, form destination
$title = elgg_echo("market:addpost");
$tags = "";
$title = "";
$description = "";
if (defined('ACCESS_PUBLIC')) {
	$access_id = ACCESS_PUBLIC;
} else {
	$access_id = 0;
}

// Just in case we have some cached details
if (isset($vars['markettitle'])) {
	$title = $vars['markettitle'];
	$body = $vars['marketbody'];
	$tags = $vars['markettags'];
}

//elgg_dump($vars);

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
function acceptTerms() {
	error = 0;
	if(!(document.marketForm.accept_terms.checked) && (error==0)) {
		alert('<?php echo elgg_echo('market:accept:terms:error'); ?>');
		document.marketForm.accept_terms.focus();
		error = 1;
	}
	if(error == 0) {
		document.marketForm.submit();
	}
}
</script>
<?php
echo "add/item.php<br><label>";
echo elgg_echo("title");
echo "&nbsp;<small><small>" . elgg_echo("market:title:help") . "</small></small><br />";
echo elgg_view("input/text", array(
				"name" => "markettitle",
				"value" => $title,
				));
echo "</label></p>";
/*
$id = 162;
$something = get_entity($id);
*/
//$item_class = elgg_extract('user', $vars, '');
//$name = elgg_extract('name', $vars);
//echo "<p>{$name}</p>";
//echo "<p>{$item_class}here</p>";
/*echo "<p>{$something}</p>";
if (!$something){
	echo "nothing";
}*/
//echo "{$something['title']}, {$something['description']}";
//echo elgg_dump($something);
//echo elgg_dump($item_class);
//echo elgg_dump($vars);

$marketcategories = elgg_view('market/marketcategories',$vars);

//Override market/categories with input/catetory from hypeCategories module.  Uses sitewide categories.
/*$marketcategories = elgg_view('input/category', array(
	'name_override' => 'marketcategory', 
	'multiple' => false, // specifies whether users should have an option to select multiple categories
	'entity' => $entity, // an entity, which is being edited (will be used to obtain currently selected categories, unless 'value' parameter is present)
//	'value' => array(), // an array of category GUIDs to be selected by default
));*/
if (!empty($marketcategories)) {
	echo "<p><label>Category</label>: {$marketcategories}</p>";
}

echo "<p><label>" . elgg_echo("market:tags") . "&nbsp;<small><small>" . elgg_echo("market:tags:help") . "</small></small><br />";
echo elgg_view("input/tags", array(
				"name" => "markettags",
				"value" => $tags,
				));
echo "</label></p>";

echo "<p><label>" . elgg_echo("market:text") . "<br>";
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='marketbody' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	echo "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "marketbody", "value" => $body));
}
echo "</label></p>";

echo "<p></p><p><label>" . elgg_echo("market:uploadimages") . "<br /><small><small>" . elgg_echo("market:imagelimitation") . "</small></small><br />";
echo elgg_view("input/file",array('name' => 'upload'));
echo "</label></p>";

echo "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("market:access:help") . "</small></small><br />";
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
echo "</label></p>";

echo "<p>";
// Terms checkbox and link
/* 07/06/2012 - Removed terms from form
 * $termslink = elgg_view('output/url', array(
			'href' => "mod/market/terms.php",
			'text' => elgg_echo('market:terms:title'),
			'class' => "elgg-lightbox",
			));
$termsaccept = sprintf(elgg_echo("market:accept:terms"),$termslink);
echo "</p>";
echo "<input type='checkbox' name='accept_terms'><label>{$termsaccept}</label></p>";
*/
echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('market:save'), 'value' => elgg_echo('market:save')));
echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('market:submit:add_more'), 'value' => elgg_echo('market:submit:add_more')));



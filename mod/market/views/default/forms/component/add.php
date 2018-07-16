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
echo elgg_view('input/hidden', array('name' => 'jot[container_guid]', 'value' => $item_guid));
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

$content = "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Title</div>
				<div class='rTableCell' style='width:460px'>".elgg_view("input/text", array(
								"name" => "jot[title]",
								"value" => $title,
								))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Asset</div>
				<div class='rTableCell' style='width:460px'>".elgg_view('output/url', array(
                                 'name' => "jot[asset]",
                                 'text' => $item->title,
                                 'href' => $referrer
								))."</div>
			</div>			
		</div>
	</div>";
$content .= '<label>Description</label>';
if ($allowhtml != 'yes') {
	$content .= "<small><small>" . sprintf(elgg_echo("jot:text:help"), $numchars) . "</small></small><br />";
	$content .= <<<HTML
<textarea name='description' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.jotForm.description,"jot-remLen1",{$numchars}'
  onKeyUp='textCounter(document.jotForm.description,"jot-remLen1",{$numchars})'>{$description}</textarea><br />
HTML;
	$content .= "<div class='jot_characters_remaining'><span id='jot-remLen1' class='jot_charleft'>{$numchars}</span> " . elgg_echo("jot:charleft") . "</div>";
} else {
	$content .= elgg_view("input/longtext", array("name" => "jot[description]", "value" => $description));
}
$content .= "</label></p>";
$content .= "<p>";
$content .= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('jot:save:component')));

echo $content;



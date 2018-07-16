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
$item      = $vars['entity'];
$title     = $vars['markettitle'];
$body      = $vars['marketbody'];
$tags      = $vars['markettags'];
$category  = $vars['marketcategory'];
$access_id = $vars['access_id'];
$model_no  = $item->model_no;
$serial_no = $item->serial_no;
$asset_guid= $item->guid;

elgg_set_context('edit_item');
//echo elgg_dump($item);

$characteristics = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'characteristic',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$add_characteristic = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=characteristic&guid=$item_guid&asset=$asset"));

	/*
	 * This is one way to display menu items specific to a page.
	 * May also include in start.php.  See mod/pages/start.php for an example.  
	 */
	
	$view_menu[1] = new ElggMenuItem("0inventory", "Manage Inventory", "market/edit_more/{$item_guid}/{$category}/family");
	$view_menu[2] = new ElggMenuItem('1component', 'Add component', elgg_add_action_tokens_to_url("action/jot/add/element?element_type=component&guid=" . $item_guid));
	$view_menu[3] = new ElggMenuItem('2accessory', 'Add accessory', elgg_add_action_tokens_to_url("action/jot/add/element?element_type=accessory&guid=" . $item_guid));
	$view_menu[4] = new ElggMenuItem('3document', 'Add document', "file/add?element_type=document&guid=" . $item_guid);
	$view_menu[5] = new ElggMenuItem('4issue', 'Add issue', "jot/add/$item_guid/issue");
	$view_menu[6] = new ElggMenuItem('5task', 'Add task', "tasks/add/$owner_guid?element_type=task&container_guid=$item_guid");
	elgg_register_menu_item('page', $view_menu[1]);
	elgg_register_menu_item('page', $view_menu[2]);
	elgg_register_menu_item('page', $view_menu[3]);
	elgg_register_menu_item('page', $view_menu[4]);
	elgg_register_menu_item('page', $view_menu[5]);
	elgg_register_menu_item('page', $view_menu[6]);
	
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
//echo elgg_dump($vars);
//echo elgg_dump($vars['entity']);
echo '<!--View: mod\market\views\default\forms\market\edit.php--><br>';
echo '<label>'.elgg_echo("title").'</label>
      &nbsp;<small><small>' . 
      elgg_echo('market:title:help') . 
      '</small></small><br />'.
      elgg_view("input/text", array(
				"name" => "markettitle",
				"value" => $title,
				)).
	'</p>';

$marketcategories = elgg_view('market/marketcategories',$vars);
if (!empty($marketcategories)) {
	echo "<p><label>Category: </label>$marketcategories</p>";
}
echo "<p><label>Model #:</label>".
     elgg_view('input/text', array('name' => 'model_no','value' => $model_no)).
     "</p>";

echo "<p><label>Serial #:</label>".
     elgg_view('input/text', array('name' => 'serial_no','value' => $serial_no)).
     "</p>";

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

echo "<p><label>" . elgg_echo("market:image") . "<br />";
echo elgg_view('market/thumbnail', array('marketguid' => $vars['entity']->guid, 'size' => 'large', 'tu' => $vars['entity']->time_updated));
echo "<br /></label></p>";

echo "<p><label>" . elgg_echo("market:uploadimages") . "<br /><small><small>" . elgg_echo("market:imagelimitation") . "</small></small><br />";
echo elgg_view("input/file",array('name' => 'upload'));
echo "</label></p>";

echo "<p><label>" . 
      elgg_echo("market:tags") . 
      "&nbsp;<small><small>" . 
      elgg_echo("market:tags:help") . 
      "</small></small><br />".
      elgg_view("input/tags", array(
				"name" => "markettags",
				"value" => $tags,
				))."
	  </label></p>";

	$url = elgg_get_site_url() . "labels/$asset_guid";
	$url = elgg_add_action_tokens_to_url($url);
	$item = elgg_view('output/url', array(
	                  "href" => $url,
	                   "text" => "add label",
	                   "class" => "elgg-lightbox"
	        ));

echo $item;
//echo '<br>$asset_guid: '.$asset_guid;

// Characteristics clone
// Taken from mod\market\views\default\forms\market\edit\car\profile.php 
echo '<div class="characteristics">';
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_names][]',
		'style' => 'width: 25%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</div>';
echo '</div>'; // end of Characteristics clone

// characteristics
// Taken from mod\jot\views\default\jot\display\observation\details.php`
	echo '<table width = 100%><tr>
	        <td colspan=2><b>Characteristics</b>&nbsp;
		        <span class="hoverhelp">[?]
		        <span style="width:500px;"><p></span>
		        </span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan=2>'.$add_characteristic.'
	        </td>
	      </tr>';

if ($characteristics) {
foreach ($characteristics as $i) {
			$element_type = 'characteristic';
			if ($i->canEdit() && $entity->state <= 3) {
				$delete = elgg_view("output/confirmlink",array(
			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$item_guid&tab=$section",
			    	'text' => elgg_view_icon('delete'),
			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
			    	'encode_text' => false,
			    ));
			}
	echo '<tr class="highlight">
	        <td>'.$i->title.'</td>
	        <td>'.$delete.'
	      </tr>';
    }
}
else {
	echo '<tr>
	        <td>Enter new characteristic and click [add!]</td>
	        <td>
	      </tr>';	
     }	
echo '</table><br>';

echo "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("market:access:help") . "</small></small><br />";
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
echo "</label></p>";

echo "<p>";

if ($vars['entity']) {
  echo elgg_view('input/hidden',array('name'=>'guid','value'=>$vars['entity']->guid));
}
echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('market:save'), 'value' => elgg_echo('market:save')));



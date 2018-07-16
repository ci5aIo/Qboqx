<?php
/**
 * Asset Picker.  Sends an array of group guids.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['values'] Array of asset guids for already selected assets or null
 * @uses $vars['limit'] Limit number of assets (default 0 = no limit)
 * @uses $vars['name'] Name of the returned data array (default "assets")
 * @uses $vars['handler'] Name of page handler used to power search (default "livesearch")
 *
 * Defaults to lazy load group lists in alphabetical order. User needs
 * to type two characters before seeing the group popup list.
 *
 * As groups are selected they move down to a "groups" box.
 * When this happens, a hidden input is created to return the GUID in the array with the form
 */

elgg_load_js('jquery.ui.autocomplete.html');

if (empty($vars['name'])) {
	$vars['name'] = 'assets';
}
$placeholder = elgg_extract('placeholder', $vars, 'Item Name');
$container_guid = elgg_extract('container_guid', $vars);
$name = $vars['name'];
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

$assets = (array)elgg_extract('values', $vars, array());
$label = elgg_extract('value', $vars);

$handler = elgg_extract('handler', $vars, 'livesearch_asset');
$handler = htmlspecialchars($handler, ENT_QUOTES, 'UTF-8');

$limit = (int)elgg_extract('limit', $vars, 0);
$take_snapshot = elgg_extract('snapshot', $vars, false); 

?>
<div class="elgg-asset-picker ui-front" data-limit="<?php echo $limit ?>" data-name="<?php echo $name ?>" data-handler="<?php echo $handler ?>">
	<input type="text" class="elgg-input-asset-picker" size="30" placeholder="<?php echo $placeholder ?>" name="<?php echo $name ?>" value="<?php echo $label ?>"/>
	<ul class="elgg-asset-picker-list">
		<?php
		foreach ($assets as $asset) {
		    if (elgg_entity_exists($asset)){
		        $entity     = get_entity($asset);
		        $input_name = $vars['name'];
		    }
		    else {
		        $entity     = $asset;
		        $input_name = $vars['other_name'];
		    }
			if ($entity) {
				echo elgg_view('input/assetpicker/item', array(
					'entity'     => $entity,
					'container_guid'=>$container_guid,
					'input_name' => $input_name,
				    'snapshot'   => $take_snapshot,
				));
			}
		}
		?>
	</ul>
</div>
<script type="text/javascript">
require(["elgg", "jquery"], function (elgg, $) {
	// make sure the jQueryUI Autocomplete lib is available in ajax loaded views
	if (typeof(filter) !== "function") {
		$.getScript(elgg.get_site_url() + "vendors/jquery/jquery.ui.autocomplete.html.js");
	}
	
	require(['quebx/AssetPicker'], function (AssetPicker) {
		AssetPicker.setup('.elgg-asset-picker[data-name="<?php echo $name ?>"]');
	})
});
</script>

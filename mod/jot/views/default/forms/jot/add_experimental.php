<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

$title = elgg_extract('title', $vars, '');
$item_guid   = $vars['item_guid'];
$description = $vars['description'];
$subtype     = elgg_extract('subtype', $vars, '');
$aspect      = elgg_extract('aspect', $vars, '');
$asset       = elgg_extract('asset', $vars, '');
$element_type= elgg_extract('element_type', $vars, '');
$referrer    = elgg_extract('referrer', $vars,'');
$item        = get_entity($item_guid);
$jot         = elgg_extract('jot', $vars);
//$jot         = elgg_extract('jot', $vars, $item);
$ts = time();
$title = elgg_echo("New jot");
$tags = "";
$variables = elgg_get_config("{$aspect}s");

$add_box .= elgg_view('input/hidden', array('name' => 'item_guid'   , 'value' => $item_guid));
$add_box .= elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[guid]'   , 'value' => $jot->guid));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[subtype]', 'value' => $subtype));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[aspect]' , 'value' => $aspect));
$add_box .= elgg_view('input/hidden', array('name' => 'jot[asset]'  , 'value' => $asset));
$add_box .= elgg_view('input/hidden', array('name' => 'referrer'    , 'value' => $referrer));
$add_box .= elgg_view('input/hidden', array('name' => 'state'       , 'value' => '1'));

		$pace_options       = array('hour', 'day' , 'week' , 'month' , 'year');
		$frequency_options  = array('hours', 'days', 'weeks', 'months', 'years');
		$frequency_units = elgg_view('input/select', array(
							'options' => $frequency_options,
							'name' => 'jot[frequency_units]',
							'value' => $default,
							));
		$pace_units = elgg_view('input/select', array(
							'options' => $frequency_options,
							'name' => 'jot[pace_units]',
							'value' => $default,
		                    ));
		// Give no options for frequency units or pace units if the item has a default frequency unit and the schedule had not been set.
		// After the schedule has been set, the user can select a different frequency unit, but it will default to the unit initially saved.
		// The user can select a different frequency unit for a set schedule to account for the possibility that the item default
		   // frequency unit may change. Any changes to the schedule must be intentional to avoid unit conflicts.
		   // For example, if the item default frequency unit changes from 'miles' to 'months', the schedule should remain at 'miles' until explicitly changed.
		if (!empty($item->frequency_units) && empty($jot->frequency_units)){
			$frequency_units = $item->frequency_units;
			$pace_units      = $item->frequency_units;
			$add_box .= elgg_view('input/hidden', array('name' => 'jot[frequency_units]', 'value' => $frequency_units));
			$add_box .= elgg_view('input/hidden', array('name' => 'jot[pace_units]'     , 'value' => $frequency_units));
		}
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save')));
		$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:set:schedule')));
//Switch ($aspect){
//	default:
	$add_box .= elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save')));
//}
echo $add_box;
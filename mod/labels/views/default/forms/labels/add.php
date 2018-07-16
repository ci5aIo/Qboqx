<?php

/*namespace Quebx\Labels;*/

$user      = $vars['user'];
$item_guid = $vars['friend'];
$approve   = $vars['approve'];
$item      = get_entity($item_guid);

$itemcollections = $item->gettags();

$options = array(
	'tag_name'  => 'tags',
	'item_guid' => $item_guid,
);

$tag_data = get_labels($options);

//get array of all label collections owned by the requesting user
$collections = get_user_label_collections($user->guid);

usort($collections, function($a, $b) {
	return strnatcmp(strtolower($a->string), strtolower($b->string));
});
$body = '<div class="row clearfix">';
	foreach ($collections as $collection) {

		if (in_array($collection->string, $itemcollections)) {
			$current_labels = array(
				'id' => 'rtag' . $collection->id,
				'name' => 'existing_rtag[]',
				'value' => $collection->string,
				'checked' => 'checked',
				'default' => false,
			);
	
//			$current_labels['checked'] = 'checked';
			$body .= "<div class=\"elgg-col elgg-col-1of3\">";
			$body .= elgg_view('input/checkbox', $current_labels);
			$body .= "<label for=\"rtag{$collection->id}\">" . $collection->string . "</label>";
			$body .= "</div>";
		}
	}
$body .= '</div><br>';
$body .= 'New Labels: ';
$body .= elgg_view('input/text', array(
	'name' => 'rtags',
	'id' => 'labels_rtags',
    'placeholder'=> 'Separate, new labels, with commas'
//	'value' => implode(', ', $itemcollections),
		));
//$body .= elgg_dump($current_labels);

if ($collections) {
	$body .= '<div class="row clearfix">';
	foreach ($collections as $collection) {

		if (!in_array($collection->string, $itemcollections)) {
		$checkbox_options = array(
			'id' => 'rtag' . $collection->id,
			'name' => 'existing_rtag[]',
			'value' => $collection->string,
			'default' => false
		);

//		if (in_array($collection->string, $itemcollections)) {
//			$checkbox_options['checked'] = 'checked';
//		}
		
		$body .= "<div class=\"elgg-col elgg-col-1of3\">";
		$body .= elgg_view('input/checkbox', $checkbox_options);
		$body .= "<label for=\"rtag{$collection->id}\">" . $collection->string . "</label>";
		$body .= "</div>";
	}
	}
	$body .= '</div><br>';
}

$body .= elgg_view('input/hidden', array('name' => 'item_guid','value' => $item->guid));
$body .= elgg_view('input/hidden', array('name' => 'approve', 'value' => $approve));
$body .= elgg_view('input/submit', array('value' => elgg_echo('labels:submit'))) . " ";

echo elgg_view_module('info', elgg_echo('labels:form:header'), $body);

echo elgg_view('output/longtext', array(
	'value' => elgg_echo('labels:form:instructions'),
	'class' => 'elgg-subtext'
));



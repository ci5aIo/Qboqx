<?php
/**
 * Sidebar extension for a single item
 */
$item = (int) get_input('item');
// note that the clone function is non-descriminatory
// it will clone everything but add unique guid
$add_another = elgg_view('output/url', array(
	'text' => 'Duplicate',
	'href' => elgg_add_action_tokens_to_url('action/quebx/clone?guid=' . $item)
));

echo $add_another.'<br><br>';

<?php

$guid = $vars['item_guid'];
$jot_text = $vars['jot'];
$referrer = $vars['referrer'];
$item = get_entity($guid);
$item_name = $item->title;

/*
$aspects = elgg_view('jot/input/aspects_radio',$vars);
if (!empty($aspects)) {
	echo "<p>{$aspects}</p>";
}*/

echo '<table>';
echo '<tr><td>'.elgg_view('input/radio', array(
									     'name' => 'aspect',
									     'align' => 'horizontal',
									     'value' => 'comment',
										 'options' => array('Comment'     => 'comment',
										                    'Request'     => 'request', 
										                    'Observation' => 'observation',
										                    'Purchase'    => 'purchase',
															)
								 )).'
      </td></tr></table><p></p><p>'.
	  sprintf(elgg_echo("jot:routing:explanation"), 
		      elgg_view('output/url', array('text' => $item_name,
									        'href' =>  $referrer
								))).
//	  sprintf(elgg_echo("jot:routing:explanation"), $item_name).
	    '<p><div>
		<label>Jot Description</label>';
echo elgg_view('input/longtext', array('name' => 'description',
		                                         'value' => $jot_text,
		)).
	'</div>';
echo elgg_view('input/hidden', array(
	'name' => 'item_guid',
	'value' => $guid
));

echo elgg_view('input/hidden', array(
	'name' => 'referrer',
	'value' => $referrer
));

echo elgg_view('input/submit', array('value' => elgg_echo("jot:post:button")));
//echo '<p>item_guid: '.$guid.'<br>item name: '.$item_name.'<br>referrer: '.$referrer.'</p>';

?>
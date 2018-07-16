<?php

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$guid = $vars['item_guid'];
$jot_text = $vars['jot'];
$referrer = $vars['referrer'];
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}

echo 'item_guid: '.$guid.'<br>';
echo 'referrer: '.$referrer.'</p>';

echo '<table>';
echo '<tr><td><label>Jot Type</label>'.elgg_view('input/radio', array(
											     'name' => 'jot_type',
											     'align' => 'horizontal',
											     'value' => 'comment',
												 'options' => array('Comment' => 'comment',
												                    'Request' => 'request', 
												                    'Issue' => 'issue',
												                    'Event' => 'event',
												                    'Note' => 'note',
												                    'Research' => 'research',
												                    'Transfer' => 'transfer',
												                    'Quickey' => 'quickey'
																	)
												 )).'
      </td></tr></table>';
?>
	<p><div>
		<label>Jot Description</label>
		<?php echo elgg_view('input/longtext', array('name' => 'description',
		                                             'value' => $jot_text,
		)); ?>
	</div>
<?php

echo elgg_view('input/hidden', array(
	'name' => 'item_guid',
	'value' => $guid
));

echo elgg_view('input/hidden', array(
	'name' => 'referrer',
	'value' => $referrer
));

echo elgg_view('input/submit', array('value' => elgg_echo("generic_comments:post")));

?>
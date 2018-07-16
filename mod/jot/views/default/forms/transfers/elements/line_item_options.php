<?php
$title     = elgg_echo("Line Item Options");
$referrer  = $vars['referrer'];
$vars['title'] = $title;
$aspect    = 'transfer';
$variables = jot_prepare_form_vars($aspect      = $aspect,
	                               $jot         = null, 
		                           $item_guid   = 0, 
		                           $referrer    = $referrer, 
		                           $description = null,
								   $section     = null);

echo elgg_view('input/hidden', array('name' => 'jot_type', 'value' => $aspect));

echo '<p><p><table width = "100%">';

foreach ($variables as $name => $type) {
echo '<tr>
		  <td>here<label>'.
		  elgg_echo("transfer:$name").
		  '</label></td>
		  <td>'.elgg_view("input/$type", array(
										'name' => $name,
										'value' => $vars[$name],
									));
           if ($name == 'payment_account'){
           	echo 'new';
           }
echo'		  </td>
	</tr>';
}
echo "</table>";
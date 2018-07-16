<?php
$item_guid = $vars['item_guid'];
$description = $vars['description'];
$referrer = $vars['referrer'];
$item = get_entity($item_guid);
$title = elgg_echo("New Offer");

echo '<p><p>
      <table width = "100%"><tr>
		      <td><label>Title</label></td>
		      <td>'.elgg_view("input/text", array(
										"name" => "title",
										"value" => $title,
										)).'</td>

          <tr><td><label>Asset	</label></td>
		      <td>'.elgg_view('output/url', array(
		                                 'name' => 'asset',
		                                 'text' => $item->title,
		                                 'href' => $referrer
										)).'</td>
		      </tr></table><p>';
echo "<p>";
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('jot:save:offer')));
		      
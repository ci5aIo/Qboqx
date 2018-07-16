<?php

// Translations
$yes = elgg_echo('option:yes');
$no = elgg_echo('option:no');

// Get settings
$customchoices = $vars['entity']->jot_custom_choices;
$jotcategories = $vars['entity']->jot_aspects;

echo "<hr>";
echo '<table class="elgg-table-alt">';
echo '<tr><th>' . elgg_echo('jot:settings:status') . '</th>';
echo '<th>' . elgg_echo('jot:settings:desc') . '</th></tr>';
echo "<tr><td>";
echo elgg_view('input/dropdown', array(
                        'name' => 'params[jot_max]',
                        'value' => $vars['entity']->jot_max,
                        'options_values' => array(
						'0' => elgg_echo('jot:unlimited'),
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'10' => '10',
						'20' => '20',
						'30' => '30',
						),
			));

echo "</td><td>" . elgg_echo('jot:max:jots') . "</td></tr>";

echo "<tr><td>";
echo elgg_view('input/dropdown', array(
			'name' => 'params[jot_adminonly]',
			'value' => $vars['entity']->jot_adminonly,
			'options_values' => array(
						'no' => $no,
						'yes' => $yes
						)
			));
echo "</td><td>" . elgg_echo('jot:adminonly') . "</td></tr>";

echo "<tr><td>";
echo elgg_view('input/dropdown', array(
			'name' => 'params[jot_allowhtml]',
			'value' => $vars['entity']->jot_allowhtml,
			'options_values' => array(
						'no' => $no,
						'yes' => $yes
						)
			));
echo "</td><td>" . elgg_echo('jot:allowhtml') . "</td></tr>";

echo "<tr><td>";
echo elgg_view('input/text', array(
			'name' => 'params[jot_numchars]',
			'class' => 'jot-admin-input',
			'value' => $vars['entity']->jot_numchars,
			));
echo "</td><td>" . elgg_echo('jot:numchars') . "</td></tr>";

echo "<tr><td>";
echo elgg_view('input/dropdown', array(
			'name' => 'params[jot_pmbutton]',
			'value' => $vars['entity']->jot_pmbutton,
			'options_values' => array(
						'no' => $no,
						'yes' => $yes
						)
			));
echo "</td><td>" . elgg_echo('jot:pmbutton') . "</td></tr>";

echo "<tr><td>";
echo elgg_view('input/dropdown', array(
			'name' => 'params[jot_comments]',
			'value' => $vars['entity']->jot_comments,
			'options_values' => array(
						'no' => $no,
						'yes' => $yes
						)
			));
echo "</td><td>" . elgg_echo('jot:comments') . "</td></tr>";

echo "</table>";

echo "<br><br>";

echo "<h3>" . elgg_echo('jot:categories') . "</h3><hr>";

	echo elgg_echo('jot:categories:explanation');
	echo "<br><br>";
	echo elgg_echo('jot:categories:settings:categories');

	echo elgg_view('input/tags',array('value' => $jotcategories, 'name' => 'params[jot_aspects]'));

echo "<br><br>";

echo "<h3>" . elgg_echo('jot:custom') . "</h3><hr>";

echo elgg_echo('jot:custom:activate');
echo elgg_view('input/dropdown', array(
			'name' => 'params[jot_custom]',
			'value' => $vars['entity']->jot_custom,
			'options_values' => array(
						'no' => $no,
						'yes' => $yes
						)
			));
echo "<br><br>";
echo elgg_echo('jot:custom:choices');
echo "<br><br>";
echo elgg_echo('jot:custom:settings');
echo elgg_view('input/tags',array('value' => $customchoices, 'name' => 'params[jot_custom_choices]'));


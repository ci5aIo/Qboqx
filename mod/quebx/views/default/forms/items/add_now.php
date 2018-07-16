<?php
/**
 * Elgg QuebX Plugin
 * @package quebx
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 */

echo elgg_view("input/text", array(
				"name" => "itemtitle",
				"value" => $title,
				));

echo elgg_view('input/submit', array(
               'name' => 'add', 
               'text' => elgg_echo('quebx:add'), 
               'class' => 'elgg-button-action',
			   ));

<?php

echo '<tr><td nowrap colspan=2>'. 
      elgg_view("input/text", array(
				"name" => "element_title",
				)).
//	  '</td><td align="right">'.
	  elgg_view('input/submit', array(
	            "value" => "add!",
	            "class" => 'elgg-button-submit-element',
	           )).
	   '</td></tr>';
<?php

echo '<tr><td nowrap width=100%>'. 
      elgg_view("input/text", array(
				"name" => "title",
				)).
	  '</td><td align="right">'.
	  elgg_view('input/submit', array(
	            "value" => "add!",
	            "class" => 'elgg-button-submit-element',
	           )).
	   '</td></tr>';
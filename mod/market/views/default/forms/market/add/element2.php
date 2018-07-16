<?php
$contents_add .="<div class='rTable' style='width:100%'>
		<div class='rTableBody'>";

$contents_add .="<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'>".
			      elgg_view('input/text', array(
					'name' => 'item[characteristic_names][]',
			      	 'placeholder'=>'Characteristic',
				))."</div>
				<div class='rTableCell' style='width:460px'>".
				elgg_view('input/text', array(
					'name' => 'item[characteristic_values][]',
					'class' => 'last_characteristic',
					'placeholder'=>'Value',
				))."</div>
				<div class='rTableCell' style='width:200px'>
	            </div>
			</div>";

$contents_add .= '<div class="new_characteristic"></div>';
$contents_add .= "</div>
			</div>";



// Characteristics clone
$contents_add .= "<div style='visibility:hidden'>";
$contents_add .= "<div class='characteristics'>
	    <div class='rTableRow'>
					<div class='rTableCell' style='width:250px'>".
				      elgg_view('input/text', array(
						'name'  => 'item[characteristic_names][]',
					))."</div>
					<div class='rTableCell' style='width:460px'>".
					elgg_view('input/text', array(
						'name'  => 'item[characteristic_values][]',
 						'class' => 'last_characteristic',
					))."</div>
		</div>
	</div>"; // end of Characteristics clone
$contents_add .= "</div>";

echo $contents_add;
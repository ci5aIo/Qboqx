<?php
/**
 * Elgg QuebX Plugin
 * @package quebx
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 */

$action = elgg_extract('do', $vars, 'add_item');
$title  = elgg_extract('title', $vars);
$category = elgg_extract('category', $vars);
$label    = elgg_extract('tags', $vars);
$referrer = current_page_url();
if ($category){
    $hidden_fields['item[category]'] = $category;
}
if ($label){
    $hidden_fields['item[label]']    = $label;
}
    $hidden_fields['referrer']       = $referrer;
foreach($hidden_fields as $name => $value){
    $input_hidden .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}
Switch ($action){
    case 'add_item':
		$add_box .= "
			<div class='rTable' style='width:100%'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:120px;padding:0px 0px 0px 0px;'>$title</div>
						<div class='rTableCell' style='width:10px;padding:0px 0px 0px 0px;'></div>
						<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>".elgg_view('input/text', array(
																		'name' => 'item[title]',
																		'autofocus' => false,
																		))."</div>
						<div class='rTableCell' style='width:10px;padding:0px 0px 0px 0px;'></div>
						<div class='rTableCell' style='width:100px;padding:0px 0px 0px 0px;'>".
						                                      elgg_view('input/submit', array(
																		'name' => 'apply',
								                                        'value'=> elgg_echo('Add'),
								                                        'class' => 'elgg-button-submit-element',
								                                        'href' =>'#',
																		)).
						                                      elgg_view('input/button', array(
																		'name' => 'fuggetit',
								                                        'value'=> elgg_echo('Bulk'),
								                                        'class' => 'elgg-button-submit-element',
								                                        'href' =>'#',
																		)).
															  $input_hidden."</div>
					</div>
				</div>
			</div>";
	break;
}
echo $add_box;


/*Archive****************************************
echo '<table width = 100%><tr nowrap><td width = 100%>';
echo elgg_view("input/text", array(
//echo elgg_view("input/autocomplete", array(
				"name" => "item_title",
				"value" => $title, 
                'autofocus' => true,
//                'match_on' => array('type' => 'object', 'subtype' => 'market'),
				));
echo '<td>';
echo elgg_view('input/submit', array(
               'name' => 'add_now', 
               'value' => elgg_echo('quebx:add:now'), 
               'class' => 'elgg-button-action',
			   ));
echo '</tr></table>';
*/
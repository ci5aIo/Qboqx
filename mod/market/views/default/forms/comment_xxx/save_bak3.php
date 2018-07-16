<!-- form: \market\views\default\forms\comment\save.php -->
<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 *
 * @uses ElggEntity $vars['entity'] The entity to comment on
 * @uses bool       $vars['inline'] Show a single line version of the form?
 */
//$referrer = "market/view/".$vars['entity']->getGUID();
$entity      = $vars['entity'];
$referrer    = $entity->geturl();
$entity_guid = $entity->getGUID();
$form_vars   = array('action'=>'action/jot/add');
$body_vars   = array('entity'=>$entity);

?>
<script type="text/javascript">
$(document).ready(function() {
	// clone line item node
	$('.clone-line-item-action').live('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.line_item').html();
		$(html).insertBefore('.new_line_item');
	});
	// remove a node
	$('.remove-node').live('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
});
</script>
<?php
if (isset($vars['entity']) && elgg_is_logged_in()) {
	
	$inline = elgg_extract('inline', $vars, false);
	if ($inline) {
		echo elgg_view('input/text', array('name' => 'jot_text', 'value' => 'Title'));
		echo elgg_view('input/submit', array('value' => elgg_echo('comment')));
	} else {

echo "<label>".elgg_echo('jot:post')."</label><hr style='color:rgb(248, 238, 238)'>
	<div class='rTable' style='width:100%;padding: 0px 0px'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:5%;padding: 0px 0px'>".
									elgg_view('output/url', array(
									    'text' => '+',
										'href' => '#',
										'class' => 'elgg-button-submit-element clone-line-item-action' // unique class for jquery
										))."</div>
				<div class='rTableCell' style='width:95%;padding: 0px 0px'>";
/*
echo"	            <div class='rTable' style='width:100%; border: 1px solid #999999;'>
			            <div class='rTableRow'>
							<div class='rTableHead' style='width:20%;padding: 0px 0px; border-right: 1px solid #999999;; border-bottom: 1px solid #999999;'>Date</div>
							<div class='rTableHead' style='width:60%;padding: 0px 0px; border-right: 1px solid #999999;; border-bottom: 1px solid #999999;'>Title</div>
							<div class='rTableHead' style='width:10%;padding: 0px 0px; border-right: 1px solid #999999;; border-bottom: 1px solid #999999;'>Jot Type</div>
							<div class='rTableHead' style='width:10%;padding: 0px 0px; border-bottom: 1px solid #999999;'>Value</div>
						</div>
						<div class='rTableRow'>
							<div class='rTableHead' style='width:20%;padding: 0px 0px; border-right: 1px solid #999999;'>Attchmt</div>
							<div class='rTableHead' style='width:60%;padding: 0px 0px'>
								<div class='rTable' style='width:100%'>
									<div class='rTableRow'>
										<div class='rTableCell' style='width:60%;padding: 0px 0px; border-right: 1px solid #999999;'>Operative</div>
										<div class='rTableCell' style='width:30%;padding: 0px 0px; border-right: 1px solid #999999;'>Category</div>
										<div class='rTableCell' style='width:10%;padding: 0px 0px; border-right: 1px solid #999999;'>Labels</div>
									</div>
								</div>
							</div>
							<div class='rTableHead' style='width:10%;padding: 0px 0px'></div>
							<div class='rTableHead' style='width:10%;padding: 0px 0px'></div>
						</div>
					</div>
				</div>
			</div>";
*/
// Populate blank lines
for ($i = $n+1; $i <= $n+1; $i++) {
	$pick = elgg_view('output/url', array(
			'text' => 'pick',
			'class' => 'elgg-button-submit-element elgg-lightbox',
			'data-colorbox-opts' => '{"width":500, "height":525}',
			'href' => "market/pick/item/" . $transfer_guid));
			//'href' => "market/pick?element_type=item&container_guid=" . $transfer_guid));
echo"		 <div class='rTableRow'>
				<div class='rTableCell' style='width:95%;padding: 0px 0px;border:0px'>
					".elgg_view_form('jot/jotbox_row', $form_vars, $body_vars)."
				</div>
			</div>";
}
echo '</div></div>';
// Populate form footer
echo"	<div class='new_line_item'></div>
</div>";

$cancel_button = elgg_view('input/button', array(
		'value' => elgg_echo('cancel'),
		'href' =>  $entity ? $referrer : '#',
));

echo
'<div class="elgg-foot">'.
     elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $transfer_guid,
	)).
   elgg_view('input/hidden', array(
		'name' => 'asset',
		'value' => $vars['asset'],
	)).
   elgg_view('input/hidden', array(
		'name' => 'container_guid',
		'value' => $vars['container_guid'],
	)).
   elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	)).
   elgg_view('input/hidden', array(
	     'name' => 'aspect', 
	     'value' => $aspect
	)).
	elgg_view('input/hidden', array(
		'name' => 'referrer',
		'value' => $referrer,
	)).
	elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	)).
		elgg_view('input/hidden', array(
		'name' => 'referral_path',
		'value' => $referrer
	)).
	
//   elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'submit')).
//   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply')).
//'<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>
'</div>';

echo
"<div id='line_store' style='visibility: hidden; display:inline-block;'>
	<div class='line_item'><p>
		<div class='rTableRow' style=''>
				<div class='rTableCell' style='width:5%;padding: 0px 0px'><a href='#' class='remove-node'>".elgg_view_icon('delete')."</a></div>
				<div class='rTableCell' style='width:95%;padding: 0px 0px'>
					".elgg_view_form('jot/jotbox_row', $form_vars, $body_vars)."
				</div>
			</div>
	</div>
</div>";
/* TODO Open Jot Routing in a lightbox.  Here's an ineffective attempt:
 *     echo elgg_view('input/button', array(
                    'value' => elgg_echo("jot:post:button"),
                    'href' => "jot/jot/{$vars['entity']->getGUID()}", 
//	                'class' => 'elgg-lightbox'
	));
*/	
//echo 'entity guid: '.$entity_guid.'<br>';
//echo 'referrer: '.$referrer.'<br>';
echo "</div>";

/*
echo elgg_view('input/submit', array('value' => elgg_echo("jot:post:button"), 
//	                'action'=> 'comments/add.php', 
	));*/
}
	
}

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

if (isset($vars['entity']) && elgg_is_logged_in()) {
	
	$inline = elgg_extract('inline', $vars, false);
	if ($inline) {
		echo elgg_view('input/text', array('name' => 'jot_text'));
		echo elgg_view('input/submit', array('value' => elgg_echo('comment')));
	} else {
?>
	<div>
		<label><?php echo elgg_echo("jot:post"); ?></label><br>
		<?php echo elgg_echo("jot:post:subtitle").elgg_echo("jot:post:subtitle:authorized"); 
			echo elgg_view('input/radio', array(
						     'name' => 'aspect',
						     'align' => 'horizontal',
						     'value' => 'comment',
							 'options' => array('Comment'     => 'comment',
							                    'Request'     => 'request', 
							                    'Observation' => 'observation',
							                    'Transfer'    => 'transfer',
												)
					 ));
		      echo elgg_view('input/longtext', array('name' => 'description', 'class'=>'jot_input')); ?>	</div>
	<div class="elgg-foot">
<?php

/* TODO Open Jot Routing in a lightbox.  Here's an ineffective attempt:
 *     echo elgg_view('input/button', array(
                    'value' => elgg_echo("jot:post:button"),
                    'href' => "jot/jot/{$vars['entity']->getGUID()}", 
//	                'class' => 'elgg-lightbox'
	));
*/	
//echo 'entity guid: '.$entity_guid.'<br>';
//echo 'referrer: '.$referrer.'<br>';

echo elgg_view('input/submit', array('value' => elgg_echo("jot:post:button"), 
//	                'action'=> 'comments/add.php', 
	));

?>
	</div>
<?php
	}
	
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
	echo elgg_view('input/hidden', array(
		'name' => 'referral_path',
		'value' => $referrer
	));
	echo elgg_view('input/hidden', array(
		'name' => 'view_type',
		'value' => 'page'
	));
}

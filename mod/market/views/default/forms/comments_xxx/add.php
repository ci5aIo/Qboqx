<!--                ****EXPERIMENTAL****
     **** Script to pop the submission to a new form in a lightbox. *****
     * source: http://community.sitepoint.com/t/passing-form-data-to-a-lightbox/28448/11 
-->
<script>
	$('#submitResponseToLightbox').submit(function(event) {
    var form = this,
        serializedData = $(form).serialize();
 
    $.post(form.action, serializedData)
    .done(function (response, textStatus, jqXHR) {
        $.facebox(response);
    });
 
    // prevent the default web page submission of the form
    event.preventDefault();
});
</script>

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
$referrer = $vars['entity']->geturl();

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
		      echo elgg_view('input/longtext', array('name' => 'jot_text')); ?>
	</div>
	<div class="elgg-foot">
<?php

	echo elgg_view('input/submit', array('value' => elgg_echo("jot:post:button"), 
//	                                      'class' => 'elgg-lightbox'
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
}

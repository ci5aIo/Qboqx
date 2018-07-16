<!--Form: jot\views\default\forms\transfers\edit.php-->
<?php
/**
 * transfers edit form body
 *
 * @package ElggPages	
 */

setlocale(LC_MONETARY, 'en_US');
$transfer_guid  = (int) get_input('guid');                             $display       .= '$transfer_guid='.$transfer_guid.'<br>';
$section        = get_input('section');                                $display       .= '$section='.$section.'<br>';
$aspect         = elgg_extract('aspect',$vars);                        $display        = '<br>$aspect='.$aspect.'<br>';
//$aspect         = $section;
//$aspect         = $vars['aspect'];
$asset          = $vars['asset'];
$container_guid = $vars['container_guid'];
$referrer       = $vars['referrer'];                                   $display       .= '$referrer='.$referrer.'<br>';
$shelf          = $vars['shelf'];
$subtype        = 'transfer';
$graphics_root  = elgg_get_site_url().'_graphics';
$presentation   = elgg_extract('presentation', $vars, 'full');         $display       .= '$presentation='.$presentation.'<br>';

$exists = true;
if ($transfer_guid == 0){
	$exists = false;
}
else {
	$entity         = get_entity($transfer_guid);
}

if ($aspect == 'receipt'){
    include __DIR__."/elements/receipt.php";    
}
if($aspect == 'ownership'){
    include __DIR__."/elements/ownership.php";    
}

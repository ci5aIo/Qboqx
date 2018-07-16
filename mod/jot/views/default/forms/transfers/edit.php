<!--Form: jot\views\default\forms\transfers\edit.php-->
<?php
/**
 * transfers edit form body
 *
 * @package ElggPages	
 */

setlocale(LC_MONETARY, 'en_US');
$transfer_guid  = (int) elgg_extract('guid', $vars);                   $display       .= '$transfer_guid='.$transfer_guid.'<br>';
$section        = elgg_extract('section', $vars);                      $display       .= '$section='.$section.'<br>';
$aspect         = elgg_extract('aspect',$vars);                        $display       .= '$aspect='.$aspect.'<br>';
$space          = elgg_extract('space', $vars);                        $display       .= '$space= '.$space.'<br>$selected= '.$vars['selected'].'<br>';
//$aspect         = $section;                                          
//$aspect         = $vars['aspect'];
$context        = elgg_extract('context', $vars);
$asset          = $vars['asset'];
$container_guid = $vars['container_guid'];
$referrer       = $vars['referrer'];                                   $display       .= '$referrer='.$referrer.'<br>';
$shelf          = $vars['shelf'];
$qid            = $vars['qid'];
$subtype        = 'transfer';
$presentation   = $vars['presentation'] ?: 'full';                    $display       .= '$presentation='.$presentation.'<br>';
$exists         = true;
$action         = elgg_extract('action', $vars);                                    $display .= '$action='.$action.'<br>';
$disable_save   = elgg_extract('disable_save', $vars, false);
if ($transfer_guid == 0){
	$exists = false;
}
else {
	$entity         = get_entity($transfer_guid);
}
$vars['subtype']=$subtype;
$vars['presentation']=$presentation;
$vars['transfer_guid']=$transfer_guid;
$vars['exists']=$exists;
$vars['entity']=$entity;

Switch ($aspect){
	case 'receipt':
	case 'receive':
		if ($entity->status == 'Received'){$vars['show_receive'] = false;}
		else                             {$vars['show_receive'] = true;}                         $display .='42 $vars[show_receive] = '.$vars['show_receive'].'<br>'; 
		$form_body = elgg_view("forms/transfers/elements/receipt", $vars);                       $display .= '44 $entity->status: '.$entity->status.'<br>';
    break;
	case 'return':
    	$content = elgg_view('output/div',['content'=> elgg_view("forms/transfers/elements/return", $vars),
    	                                     'options'=> ['id'=>$qid.'-body']]);
        $vars['show_title']=true;
        if ($presentation == 'qbox'){
        	$vars['content']      = $content;
        	$vars['disable_save'] = $disable_save;
			$form_body = elgg_view('jot/display/qbox',$vars);
        }
        else {
        	$form_body = $content;	
        }
    break;
	case 'replace':
		
		break;
/*    case 'ownership':
		$form_body = elgg_view("forms/transfers/elements/ownership", $vars);
    break;
    case 'donate':
    	$form_body = elgg_view("forms/transfers/elements/donate", $vars);
    break;
    case 'loan':
    case 'sell':
    	$form_body = elgg_view('output/div',['content'=> $aspect,
    	                                      'options'=> ['id'=>$qid]]);
    break;
*/    case 'trash':
    	$vars['selected']=$aspect;
    	$content = elgg_view('output/div',['content'=> elgg_view('forms/transfers/elements/transfer', $vars),
    	                                   'options'=> ['id'=>$qid.'-body']]);

        if ($presentation == 'qbox'){
        	$vars['content']      = $content;
        	$vars['disable_save'] = $disable_save;
        	if ($context == 'widgets'){$vars['position'] = 'relative';}
			$form_body = elgg_view_layout('qbox',$vars);
        }
        else {
        	$form_body = $content;	
        }                                                                    $display .= '86 $entity->status: '.$entity->status.'<br>';
    	break;
    default:
    	$transfer_vars             = $vars;
    	$transfer_vars['space']    = $space;
    	$transfer_vars['aspect']   = $aspect;
    	if ($aspect != 'transfer'){$transfer_vars['selected'] = $aspect;} // Sets the tab to the value of the selection
        $content = elgg_view('output/div',['content'=> elgg_view("forms/transfers/elements/transfer", $transfer_vars),
    	                                   'options'=> ['id'=>$qid]]);
		$vars['show_title']        = true;
        if ($presentation == 'qbox'){
        	$vars['content']      = $content;
        	$vars['disable_save'] = $disable_save;
        	if ($context == 'widgets'){$vars['position'] = 'relative';}
			$form_body = elgg_view_layout('qbox',$vars);
        }
        else {
        	$form_body = $content;	
        }
        
    	break;
}

echo $form_body;
//echo register_error($display);
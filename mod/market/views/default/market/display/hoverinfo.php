<?php
setlocale(LC_MONETARY, 'en_US');
$i = $vars['i'];
/*
$info = '<table><tr><td colspan=2><b>'.elgg_echo($i->title).'</b></td></tr>
       <tr><td>Owner:</td><td>'.elgg_echo($i->owner).'</td></tr>
       <tr><td>Submission:</td><td>'.'</td></tr>
       <tr><td>Source:</td><td>'.'</td></tr>
       <tr><td>Labels:</td><td>'.'</td></tr>
 </table>';
*/
// Replaces above

$guid    = $i->getguid();
$subtype = $i->getSubtype();
$aspect  = $i->aspect;
$merchant = $i->merchant;                                                                                              $display .= '17 $merchant'.$merchant.'<br>';
    if (elgg_entity_exists($merchant) && is_int($merchant)/*accounts for merchants whose name begins with a number*/){ $display .= '18 $merchant'.$merchant.'<br>';
        if (!empty($merchant) && elgg_entity_exists($merchant)){$merchants = array(get_entity($merchant));}            $display .= '19 $merchants[0]->name'.$merchants[0]->name.'<br>';
    	if (!empty($merchant) && empty($merchants)){
        	$merchants = elgg_get_entities_from_relationship(array(
        		'type'                 => 'group',
        		'relationship'         => 'merchant_of',
        		'relationship_guid'    => $transfer_guid,
        	    'inverse_relationship' => true,
        		'limit'                => false,
        	));
    	}                                                                                                              $display .= '28 $merchants[0]->name'.$merchants[0]->name.'<br>';
    	if (!empty($merchant) && empty($merchants)){
    		// provided for backward compatibility
    		$merchants = elgg_get_entities_from_relationship(array(
    				'type'                 => 'group',
    				'relationship'         => 'supplier_of',
    				'relationship_guid'    => $transfer_guid,
    				'inverse_relationship' => true,
    				'limit'                => false,
    		));
    	}
    }                                                                                                                $display .= '39 $merchants[0]->name'.$merchants[0]->name.'<br>';
    if (!empty($merchants)) {
        $merchant = $merchants[0]->name;}
    elseif (elgg_instanceof(get_entity($i->merchant), 'group')){
        $merchant = get_entity($i->merchant)->name;}
    else {$merchant = $i->merchant;}

if ($subtype == 'file'){
	$info = "<b>".elgg_echo($i->title)."</b>
		<div class='rTable' style='width:550px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:50px'>Owner</div>
					<div class='rTableCell' style='width:100px'>".elgg_echo(get_entity($i->owner_guid)->name)."</div>
				</div>			
				<div class='rTableRow'>
					<div class='rTableCell' style='width:50px'>Submission</div>
					<div class='rTableCell' style='width:100px'>".elgg_echo(strftime("%b %d %Y", ($i->time_created)))."</div>
				</div>			
				<div class='rTableRow'>
					<div class='rTableCell' style='width:50px'>Subtype</div>
					<div class='rTableCell' style='width:1060px'>{$i->getSubtype()}</div>
				</div>			
			</div>
		</div>";
	}
if ($subtype == 'transfer' && $aspect =='receipt'){
	$info = "<b>".elgg_echo($i->title)."</b>
		<div class='rTable' style='width:550px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:50px'>Merchant</div>
					<div class='rTableCell' style='width:100px'>$merchant</div>
				</div>			
				<div class='rTableRow'>
					<div class='rTableCell' style='width:50px'>Date</div>
					<div class='rTableCell' style='width:100px'>".strftime("%b %d %Y", strtotime($i->purchase_date))."</div>
				</div>			
				<div class='rTableRow'>
					<div class='rTableCell' style='width:50px'>Total</div>
					<div class='rTableCell' style='width:1060px'>".money_format('%#10n', $i->total_cost)."</div>
				</div>			
			</div>
		</div>";
	}
//echo $display;
echo $info;
?>
<?php
$jot = $vars['entity'];
$guid   = $jot->getGUID();
$title = $jot->title;
$context = elgg_get_context();
$space   = $jot->space ?: $jot->getSubtype();
if (!empty($jot->moment)){
	if ((bool)strtotime($jot->moment)){
		$moment   = date('m/d/Y', strtotime($jot->moment));
	}
	else {
		$moment   = date('m/d/Y', $jot->moment);
	};//date('m/d/Y', $jot->moment);// ?: $jot->time_created ?: $jot->time_updated);
}

$aspect = $jot->aspect;
$tags   = elgg_view('output/tags', ['tags' => $jot->tags]);
$status     = elgg_strtoupper(elgg_substr($jot->status,0,1));
$status_msg = $jot->status;
Switch ($aspect){
	case 'receipt':
		$from       = $jot->merchant;
		$to         = $jot->purchased_by;
		break;
	case 'donate':
	case 'trash':
		$from   = $jot->from;
		$to     = $jot->recipient;
		break;
}

if (elgg_entity_exists($from)){$from = get_entity($from)->name;}
// if (elgg_entity_exists($from)){$from = elgg_view('output/url', ['text' =>get_entity($from)->name, 'href'=>get_entity($from)->getURL()]);}
if (elgg_entity_exists($to))  {$to   = elgg_view('output/url', ['text' =>get_entity($to)->name, 'href'=>get_entity($to)->getURL()]);}

$transaction_link = elgg_view('output/url',['text'=>$title, 'class'=>'do title', 'data-guid'=>$guid, 'data-element'=>'qbox', 'data-space'=>$space, 'data-aspect'=>$aspect, 'data-context'=>$context, 'data-space'=>'transfer', 'data-qid'=>"q{$guid}", 'data-perspective'=>'view']); 
$transaction_value = money_format('%#10n', $jot->total);
$q_menu = elgg_view('output/div',['content'=>elgg_view('jot/menu',['menu'=>'q',
		                                                           'guid'=>$guid,
		                                                           'aspect'=>$aspect]),
		                          'class'=>'q-menu']);
$transaction_link_content = elgg_view('jot/menu',['menu'  => 'q',
		                                          'guid'  => $guid,
												  'class'=>'link-rollover',
                                                  'id'    => "link-rollover-$guid",
												  'form_view'=>$form_view,
					                              'aspect'=> $aspect,
                                                  'show'  => 'menu only']);

$list_body = "<table style='width:100%' class='ledger ledger-$guid' data-qid='q{$guid}'>
				<col><col><col><col><col><col>
                <tbody>
					<tr class='odd-row'>
						<td><span class='moment'>$moment</span></td>
                        <td class='trans'>$guid</td>
                        <td colspan=3>$transaction_link $transaction_link_content</td>
                        <td>$q_menu</td>
                    </tr>
					<tr class='even-row'>
						<td></td>
                        <td>$aspect</td>
                        <td><span class='from' title='From: $from'>$from</span></td>
                        <td><span class='to' title='To: $to'>$to</span></td>
                        <td class='status' title='$status_msg'>$status</td>
                        <td class='currency'><span class='total'>$transaction_value</span></td>
					</tr>
                 </tbody>
			</table>";
echo $list_body;
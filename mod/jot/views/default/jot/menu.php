<?php
/**
 * Section views menu
 *
 * @uses $vars['section']
 */

$selected  = elgg_extract('this_section', $vars, 'summary');                        $display .= '$params: '.print_r($vars, true);
$this_menu = elgg_extract('menu', $vars, 'tabs');
$guid      = elgg_extract('guid', $vars);
$qid_parent= elgg_extract('qid_parent',$vars);
$show_toggle = elgg_extract('show', $vars);
$class     = "drop-down ".elgg_extract('class', $vars);
$form_view = elgg_extract('form_view', $vars, false);
$view_type = elgg_extract('view_type', $vars);

$entity    = get_entity($guid);
$subtype   = $entity->getSubtype();
$status    = $entity->status;
$space     = $entity->space ?: $subtype;
$aspect    = elgg_extract('aspect', $vars, $entity->aspect);
$site_root = elgg_get_site_url();

Switch ($this_menu){
	// Add or remove aspects of this item
	case 'q':
		$context = elgg_get_context();
		Switch ($space){
			case 'hjalbumimage':
				$tabs = [['text'=>'View'       , 'aspect'=>'item'          , 'note'=>'View'  ,                                    'href'=>"gallery/view/$guid", 'perspective'=>'view', 'qid'=>"q{$guid}"],
						 ['text'=>'Edit'       , 'aspect'=>'item'          , 'note'=>'Edit'  ,                                    'href'=>"gallery/edit/$guid", 'perspective'=>'edit', 'qid'=>"q{$guid}"],
						 ['text'=>'Delete'     , 'aspect'=>'item'          , 'note'=>'Delete',    'class'=>'do',                  'href'=>"gallery/delete/object?guid=$guid", 'perspective'=>'delete', 'qid'=>"q{$guid}"]];
				$menu_header       = 'Que';
				$menu_title        = 'New';
				$anchor_right      = 'jq-dropdown-anchor-right';
				break;
			case 'transfer':
				Switch ($aspect){
					case 'give':
						$tabs = [['text'=>'Rescind', 'aspect'=>'rescind'    , 'note'=>'Rescind this donation', 'class'=>'do', 'element'=>'qbox']];
						$menu_header = 'Donation';
						break;
					case 'receipt':
						$received    = $entity->received ?: false;
						$preordered  = $entity->preorder_flag ?: false; 
					    if ($preordered && !$received){
							$tabs[] = ['text'=>'Receive', 'aspect'=>'receive'    , 'note'=>'Receive one or more items on this receipt','class'=>'do',    'element'=>'qbox', 'perspective'=>'edit','qid'=>"q{$guid}", 'space'=>$space,'aspect'=>'receive','guid'=>$guid,'context'=>$context,'presentation'=>'qbox'];
					    }
					    else {
					    	$tabs[] = ['text'=>'Received', 'note'=>'All items from this receipt have been received','class'=>'done'];
					    }
						$tabs[] = ['text'=>'Return' , 'aspect'=>'return'     , 'note'=>'Return one or more items on this receipt', 'class'=>'do',    'element'=>'qbox', 'perspective'=>'add'];
						$tabs[] = [                   'aspect'=>'divider'];

						if ($view_type == 'full'){
							$tabs[] = ['text'=>'View'   , 'aspect'=>'receipt'    , 'note'=>'View'  , 'href'=>"jot/view/$guid"                                             , 'perspective'=>'view',   'qid'=>"q{$guid}"];
							$tabs[] = ['text'=>'Edit'   , 'aspect'=>'receipt'    , 'note'=>'Edit'  , 'href'=>"jot/edit/$guid"                                             , 'perspective'=>'edit',   'qid'=>"q{$guid}"];
							$tabs[] = ['text'=>'Delete' , 'aspect'=>'receipt'    , 'note'=>'Delete', 'href'=>"jot/delete/$guid"                                           , 'perspective'=>'delete', 'qid'=>"q{$guid}"];
						}
						else {
							$tabs[] = ['text'=>'View'   , 'aspect'=>'receipt'    , 'note'=>'View'  ,                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'view',   'qid'=>"q{$guid}"];
							$tabs[] = ['text'=>'Edit'   , 'aspect'=>'receipt'    , 'note'=>'Edit'  ,                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'edit',   'qid'=>"q{$guid}"];
							$tabs[] = ['text'=>'Delete' , 'aspect'=>'receipt'    , 'note'=>'Delete',                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'delete', 'qid'=>"q{$guid}"];
						}
						$menu_header = 'Receipt';
						break;
					case 'return':
						$tabs = [['text'=>'Return' , 'aspect'=>'return'     , 'note'=>'Return one or more items on this receipt', 'class'=>'do', 'element'=>'qbox'],
						         [                   'aspect'=>'divider'],
						         ['text'=>'View'   , 'aspect'=>'receipt'    , 'note'=>'View'  ,                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'view',   'qid'=>"q{$guid}"],
						         ['text'=>'Edit'   , 'aspect'=>'receipt'    , 'note'=>'Edit'  ,                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'edit',   'qid'=>"q{$guid}"],
						         ['text'=>'Delete' , 'aspect'=>'receipt'    , 'note'=>'Delete',                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'delete', 'qid'=>"q{$guid}"]];
						break;
					default:
						$tabs = [['text'=>'View'   , 'aspect'=>$aspect    , 'note'=>'View'  ,                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'view',   'qid'=>"q{$guid}"],
						         ['text'=>'Edit'   , 'aspect'=>$aspect    , 'note'=>'Edit'  ,                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'edit',   'qid'=>"q{$guid}"],
						         ['text'=>'Delete' , 'aspect'=>$aspect    , 'note'=>'Delete',                                   'class'=>'do',    'element'=>'qbox', 'perspective'=>'delete', 'qid'=>"q{$guid}"]];
						break;
				}
				$anchor_right      = 'jq-dropdown-anchor-right';
				$menu_title        = 'Que';
				break;
			case 'market':
				$tabs = [['text'=>'New Experience' , 'aspect'=>'experience'     , 'note'=>'Describe what happened',       'class'=>'jot-q', 'presentation'=>'inline'],
				         ['text'=>'Add Pictures'   , 'aspect'=>'image'          , 'note'=>'Attach photos',                'class'=>'jot-q', 'element'=>'Gallery'],
				         ['text'=>'Add Documents'  , 'aspect'=>'document'       , 'note'=>'Attach documents',             'class'=>'jot-q', 'element'=>'Documents'],
						 [                           'aspect'=>'divider'],
/*				         ['text'=>'Issue'      , 'aspect'=>'issue'          , 'note'=>'What went wrong?',             'class'=>'jot-q'],
				         ['text'=>'Assemble'   , 'aspect'=>'assemble'       , 'note'=>'Connect and attach things',    'class'=>'jot-q'],
				         ['text'=>'Maintain'   , 'aspect'=>'maintenance'    , 'note'=>'Perform maintainance',         'class'=>'jot-q'],
				         ['text'=>'Manage'     , 'aspect'=>'management'     , 'note'=>'Decide who owns or maintains', 'class'=>'jot-q'],
				         ['text'=>'Move'       , 'aspect'=>'move'           , 'note'=>'Change the location',          'class'=>'jot-q'],
*/				         ['text'=>'Show Contents'   , 'aspect'=>'content'        , 'note'=>'Items inside',             'class'=>'jot-q', 'perspective'=>'list'],
						 ['text'=>'Show Experiences', 'aspect'=>'experience'     , 'note'=>'Show experiences'],
				         ['text'=>'Show Pictures'   , 'aspect'=>'image'          , 'note'=>'Show photos'],
				         ['text'=>'Show Documents'  , 'aspect'=>'document'       , 'note'=>'Show documents'],
				         ['text'=>'Show Transfers'  , 'aspect'=>'transfer'       , 'note'=>'Show transfers',            'class'=>'jot-q', 'perspective'=>'list'],
/*						 ['text'=>'Expenses'   , 'aspect'=>'xxx'            , 'note'=>'See expenses'],
						 ['text'=>'Maintenance', 'aspect'=>'xxx'            , 'note'=>'See maintenance history'],
						 ['text'=>'Issues'     , 'aspect'=>'issue'          , 'note'=>'See what went wrong'],
*/						 [                       'aspect'=>'divider'],
				         ['text'=>'Move'       , 'aspect'=>'transfer'       , 'note'=>'Give|Loan|Sell|Trash',         'class'=>'jot-q', 'perspective'=>'add'],
				         ['text'=>'Replace'    , 'aspect'=>'replace'        , 'note'=>'Replace parts or the whole thing', 'class'=>'jot-q', 'perspective'=>'replace'],
						 ['text'=>'Return'     , 'aspect'=>'return'         , 'note'=>'Return',                   'class'=>'jot-q', 'element'=>'qbox'],
				         [                       'aspect'=>'divider'],
				         ['text'=>'View'       , 'aspect'=>'item'          , 'note'=>'View'  ,                       'class'=>"do", 'perspective'=>'view', 'qid'=>"q{$guid}", 'element'=>'market', 'presentation'=>'inline'],
//				         ['text'=>'View'       , 'aspect'=>'item'          , 'note'=>'View'  ,                       'href'=>"market/view/$guid", 'perspective'=>'view', 'qid'=>"q{$guid}"],
						 ['text'=>'Edit'       , 'aspect'=>'item'          , 'note'=>'Edit'  ,                       'class'=>"do", 'perspective'=>'edit', 'qid'=>"q{$guid}", 'element'=>'market'],
//				         ['text'=>'Edit'       , 'aspect'=>'item'          , 'note'=>'Edit'  ,                       'href'=>"market/edit/$guid", 'perspective'=>'edit', 'qid'=>"q{$guid}"],
						 ['text'=>'Delete'     , 'aspect'=>'item'          , 'note'=>'Delete',                       'href'=>"market/delete?guid=$guid", 'perspective'=>'delete', 'qid'=>"q{$guid}"]];
				$menu_header       = 'Que';
				$menu_title        = 'New';
				$anchor_right      = 'jq-dropdown-anchor-right';
				break;
		}
		//Adjust for special circumstances.
		$n = 0;
		foreach($tabs as $key=>$tab){
			$n = ++$n;
			unset ($data_qid, $a);
			if ($tab['aspect']=='divider'){
				$menu .= elgg_format_element('li',['class'=>'jq-dropdown-divider']);
				$n = --$n;
				continue;
			}
			if ($tab['aspect']=='section'){
				$menu .= $tab['text'];
				$n = --$n;
				continue;
			}
			$data_element       = $tab['element']      ?: 'q';
			$qid                = $tab['qid']          ?: "q{$guid}_0{$n}";
			$data_perspective   = $tab['perspective']  ?  "data-perspective = {$tab['perspective']}" : null;
			$data_presentation  = $tab['presentation'] ?  "{$tab['presentation']}"                   : $data_presentation;
			
			if ($context            == 'view_item'){
				$data_presentation  = 'full_view';
			}
			if ($context            == 'view_item' && 
				$tab['perspective'] == 'view'){
				unset($tabs[$key]);
				continue;
			}
			$data_aspect  = $tab['aspect'] ? "data-aspect = {$tab['aspect']}" : null;
			$href         = $tab['href']   ? "href='{$site_root}{$tab['href']}'"          : null;
            if ($tab['class']=='jot-q'||
            	$tab['class']=='do'   ||
            	$tab['class']=='done'){
            	$a  = elgg_format_element(['#tag_name'         => 'a',
	           			                   '#text'             => $tab['text'],
             		                       'class'             => "elgg-menu-content {$tab['class']}",
	           			                   'data-qid'          => $qid,
	           			                   'data-guid'         => $guid,
	           			                   'data-element'      => $data_element,
	           			                   'data-space'        => $space,
	           			                   'data-aspect'       => $tab['aspect'],
	           			                   'data-perspective'  => $tab['perspective'],
	           			                   'data-presentation' => $data_presentation,
	           			                   'data-context'      => $context]);
	           			                    
           }
           else {
           	$confirm = false;
           	if ($tab['perspective'] == 'delete'){$confirm = true;}
           	$a = elgg_view('output/url',['text'=>$tab['text'],
           			                     'class'=> "elgg-menu-content {$tab['class']}",
           			                     'href' =>$site_root.$tab['href'],
           			                     'title' => $tab['note'],
           			                     'confirm'=> $confirm]);
           }
           $menu .= elgg_format_element('span',['title'  =>$tab['note']], elgg_format_element('li', [], $a));
		}
		$menu = elgg_format_element('ul', ['class'=>'jq-dropdown-menu'], $menu);
		
		Switch ($show_toggle){
			case 'menu only':
				$menu = $form_view ?: 'menu';
				$show = "<div class='$class'>
						     <div  id='link-rollover-$guid' class='quebx-dropdown jq-dropdown jq-dropdown-tip'>
							     <div class='jq-dropdown-menu'>$menu</div>
							 </div>
					     </div>";
				break;
			default:
				$show = "	<div class='drop-down'>
							   <a class='elgg-menu-content q-menu' data-jq-dropdown='#jq-dropdown-q-$guid' title='$menu_title'>Que</a>
							   <div  id='jq-dropdown-q-$guid' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative $anchor_right'>
							     $menu
							   </div>
							 </div>";
/*				$show = "	<div class='drop-down'>
							   <button class='dropbtn q-menu' data-jq-dropdown='#jq-dropdown-q-$guid'><span title='$menu_title'>Que</span></button>
							   <div  id='jq-dropdown-q-$guid' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative $anchor_right'>
							     $menu
							   </div>
							 </div>";
*/				break;
		}
// 		$show = "	<div class='drop-down'>
// 					   <button class='dropbtn'>Q</button>
// 					   <div class='dropdown-content'>
// 					     $menu
// 					   </div>
// 					 </div>";
 		break;
	case 'q_expand':
		$context = elgg_get_context();
		$tabs = [['text'=>'Instructions', 'aspect'=>'instruction' , 'note'=>'Describe a process'],
		         ['text'=>'Observation' , 'aspect'=>'observation' , 'note'=>'Make an observation'],
		         ['text'=>'Event'       , 'aspect'=>'event'       , 'note'=>'Create an event'],
		         ['text'=>'Project'     , 'aspect'=>'project'     , 'note'=>'Create a project'],
		         ['text'=>'Issue'       , 'aspect'=>'issue'       , 'note'=>'What went wrong?']];
		$selector = elgg_view('input/hidden', 
				               ['id'      => 'expand',
		                        'name'    => 'jot[aspect]',
		                        'value'   => 'nothing']);		
		$menu = "<ul class='elgg-tabs elgg-htabs'>";
		foreach($tabs as $key=>$tab){
			$n = $key + 1;
			$menu .="<li>
						<a class='elgg-menu-content 
                                  jot-q' 
                                  data-qid='{$qid_parent}_4_{$n}' 
                                  data-guid=$guid 
                                  data-element='q' 
                                  data-aspect={$tab['aspect']} 
			                      data-context=$context>
							<span title='{$tab['note']}'>{$tab['text']}</span>
                        </a>
					</li>";
		}
		$menu .= "</ul>";
		
		$show = "<div class='quebx-menu-q-expand' panel='Expand'>
                   $selector
	               <div class='menu-q-expand-content'>
					$menu
				   </div>
				</div>";
 		break;
	default:
//		$tabs = jot_tabs($vars, $selected);
		$show = elgg_view('navigation/tabs', array('tabs' => $tabs));
		break;
}
echo $show;
//register_error($display);
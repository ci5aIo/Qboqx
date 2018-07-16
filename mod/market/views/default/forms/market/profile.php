<!-- View: market/views/default/forms/market/profile.php -->
<?php

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}
$presentation = elgg_extract('presentation', $vars, 'full');
$perspective  = elgg_extract('perspective', $vars);		
$qid          = elgg_extract('qid', $vars);
$space        = elgg_extract('space',$vars);
$context      = elgg_extract('context', $vars);

$guid         = $vars['guid'];                                                        $display .= '10 $guid: '.$guid.'<br>';
$entity       = get_entity($guid);
$category     = get_entity($entity->category);
$entity_owner = get_entity($entity->owner_guid);
$record_stage = $entity->record_stage;                                                $display  .= '14 $record_stage:'.$record_stage.'<br>';
//if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level
  $content .= elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  $content .= elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  $content .= elgg_view('input/hidden', array('name'=>'level', 'value' => 'family'));
  $content .= elgg_view('input/hidden', array('name'=>'item[family]', 'value' => 'family'));
  $content .= elgg_view('input/hidden', array('name'=>'item[record_stage]', 'value' => $record_stage));
  $this_level = '/car';
  $next_level = '/family'; //probably not the right way to reference the next level.
//}

$category_content    = "forms/market/edit/category";
$family_content      = "forms/market/edit/family";        $family_header = 'Item Family';
$individual_content  = "forms/market/edit/individual";    $individual_header = 'Item';    
$acquisition_content = "forms/market/edit/acquisition";
$gallery_content     = "forms/market/edit/gallery";
$library_content     = "forms/market/edit/library";
  
$hierarchy           = hypeJunction\Categories\get_hierarchy($category->guid, true, true);
foreach(array_reverse($hierarchy) as $key=>$cat_guid){
    $this_category = get_entity($cat_guid);
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/category")){
        $category_content    = "forms/market/edit/".strtolower($this_category->title)."/category";
    }
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/family")){
        $family_content    = "forms/market/edit/".strtolower($this_category->title)."/family";
        if (substr($this_category->title, -1) == 's'){
            $family_header = substr($this_category->title, 0, -1)." Family";
        }
        else {
            $family_header     = "$this_category->title Family";
        }
    }
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/individual")){
        $individual_content    = "forms/market/edit/".strtolower($this_category->title)."/individual";
        $individual_header     = 'Individual';
    }
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/acquisition")){
        $acquisition_content    = "forms/market/edit/".strtolower($this_category->title)."/acquisition";
    }
}  

$title = $entity['title'];
$body = $entity['description'];
$tags = $entity->tags;
//$category = $entity->marketcategory;
$access_id = $entity['access_id']; 
	
$selected_category = $category->title;
$content .= "<div id='Category_panel' class='elgg-head'>".elgg_view($category_content, $vars)."</div>";

	$selected = 'family';
	$tabs[]=['title'=>'Family'     , 'aspect'=>'family'       , 'section'=>'us' , 'note'=>'Common characteristics'               , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_1", 'selected'=>$selected == 'family'];
    $tabs[]=['title'=>'Individual' , 'aspect'=>'individual'   , 'section'=>'this' , 'note'=>'Characteristics unique to this item', 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_2", 'selected'=>$selected == 'individual'];
    $tabs[]=['title'=>'Receipt'    , 'aspect'=>'receipt'      , 'section'=>'get' , 'note'=>'Acquisition'                         , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_3", 'selected'=>$selected == 'receipt'];
    $tabs[]=['title'=>'Gallery'    , 'aspect'=>'gallery'      , 'section'=>'pics', 'note'=>'Pictures'                            , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_4", 'selected'=>$selected == 'gallery'];
    $tabs[]=['title'=>'Library'    , 'aspect'=>'library'      , 'section'=>'docs', 'note'=>'Documents'                           , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_5", 'selected'=>$selected == 'library'];
    $panels[]=['aspect'=>'family'    , 'class'=>'option-panel family-option-panel'    , 'content'=> elgg_view($family_content, $vars)];
    $panels[]=['aspect'=>'individual', 'class'=>'option-panel individual-option-panel', 'content'=> elgg_view($individual_content, $vars)];
    $panels[]=['aspect'=>'receipt'   , 'class'=>'option-panel receipt-option-panel'   , 'content'=> elgg_view($acquisition_content, $vars)];
    $panels[]=['aspect'=>'gallery'   , 'class'=>'option-panel gallery-option-panel'   , 'content'=> elgg_view($gallery_content, $vars)];
    $panels[]=['aspect'=>'library'   , 'class'=>'option-panel library-option-panel'   , 'content'=> elgg_view($library_content, $vars)];

    $nav['tabs']  = $tabs;
    $nav['space'] = $space;
    $nav['qid']   = $qid;
    $nav['class'] ='quebx-tabs';
    $navigation   = elgg_view('navigation/tabs_slide', $nav);
    foreach($panels as $key=>$panel){
    	$n = $key+1;
    	$is_selected = $selected == $panel['aspect'];
    	$class       = $panel['class'];
	    if ($is_selected) {
			$class .= ' qbox-state-selected';
		}
    	$detail .= elgg_view('output/div',['content'=>$panel['content'], 'class'=>$class, 'options'=>['id'=>"q{$guid}_{$n}"]]);
    }
    $details = elgg_view('output/div',['content'=>$detail, 'class'=>"qbox-details qbox-$guid"]);
    
$content .= $navigation.$details;
/*
$content .= "<div id='Family_tab' style='cursor:pointer'><h3>$family_header</h3></div>";
$content .= "<div id='Family_panel' class='elgg-head'>".elgg_view($family_content, $vars)."</div>";

$content .= "<div id='Individual_tab' style='cursor:pointer'><h3>$individual_header</h3></div>";
$content .= "<div id='Individual_panel' class='elgg-head'>".elgg_view($individual_content, $vars)."</div>";

$content .= "<div id='Acquisition_tab' style='cursor:pointer'><h3>Acquisition</h3></div>";
$content .= "<div id='Acquisition_panel' class='elgg-head'>".elgg_view($acquisition_content, $vars)."</div>";

$content .= "<div id='Gallery_tab' style='cursor:pointer' data-guid=$guid><h3>Gallery</h3></div>";
//$content .= "<div id='Gallery_panel' class='elgg-head'></div>";

$content .= "<div id='Library_tab' style='cursor:pointer' data-guid=$guid><h3>Library</h3></div>";
$content .= "<div id='Library_panel' class='elgg-head'></div>";
//$content .= "<div id='Library_panel' class='elgg-head'>".elgg_view($library_content, $vars)."</div>";

*/
// add a submit button
$content .= '<br><br>
             <div class="elgg-foot">';
if ($presentation == 'full'||$presentation == 'full_view'){
	$content .= elgg_view('input/submit', array('value' => 'Save'));
}
if ($presentation == 'full'){
	$content .= elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply'));
}
$content .= '</div>';
/*
// Characteristics clone
$content .= "<div style='visibility:hidden'>";
$content .= "<div class='individual_features1'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[this_features][]',
						'value' => $feature,
					))."
		        </div>
				<div class='rTableCell'>
				<a href='#' class='remove-node'>remove</a>
				</div>
			</div>
		</div>"; // end of Feature clone

// Characteristics clone
$content .= "<div class='individual_characteristics1'>
	    <div class='rTableRow'>
					<div class='rTableCell' style='width:250px'>".
				      elgg_view('input/text', array(
						'name' => 'item[this_characteristic_names][]',
						'value' => $name,
					))."</div>
					<div class='rTableCell' style='width:460px'>".
					elgg_view('input/text', array(
						'name' => 'item[this_characteristic_values][]',
						'value' => $values[$key],
					))."</div>
					<div class='rTableCell' style='width:200px'>
					<a href='#' class='remove-node'>remove</a>
		            </div>
			</div>
		</div>"; // end of Characteristics clone
$content .= "</div>";*/

switch ($presentation){
	case 'full':
		echo $content;
		break;
	case 'inline':
		$save_icon = elgg_view_icon('save',['title'=>'Save', 'name'=>'apply']);
		$close_icon= elgg_view_icon('window-close',['title'=>'Close']);
		$save_button = "<button  class='do' type='submit' id='qboxSave' data-guid=$guid data-qid=$qid value='Save' data-perspective='save'
                                 style='right:16px'> $save_icon
						</button>";
		$close_button = "<button type='button' id='inlineClose' data-qid=$qid data-perspective='$perspective'>
							$close_icon
						</button>";
		$form_body = "<div id=$qid class='inline inline-visible' $pos_style role='data entry' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context'>
								<div id='inlineLoadedContent'>
										<div class='elgg-body inline-body'>
											<div class='elgg-layout elgg-layout-default clearfix'>
		                                        $content
		                                    </div>
		                                </div>
									</div>
		                            $close_button
									$save_button
								</div>
						</div>";
		echo $form_body;		
		break;
	case 'full_view':
		$close_icon= elgg_view_icon('window-close',['title'=>'Close']);
		$close_button = "<button type='button' id='inlineClose' data-qid=$qid data-perspective='$perspective'>
							$close_icon
						</button>";
		$form_body = "<div id=$qid class='inline inline-visible' $pos_style role='data entry' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context'>
								<div id='inlineLoadedContent'>
										<div class='elgg-body inline-body'>
											<div class='elgg-layout elgg-layout-default clearfix'>
		                                        $content
		                                    </div>
		                                </div>
									</div>
		                            $close_button
								</div>
						</div>";
		echo $form_body;		
		break;
}
	

//$content .= $display;
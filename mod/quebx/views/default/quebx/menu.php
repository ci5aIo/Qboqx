<?php
/**
 * Section views menu
 *
 * @uses $vars['section']
 */

$selected  = elgg_extract('this_section', $vars);
$this_guid = elgg_extract('guid'        , $vars);
$class     = elgg_extract('class'       , $vars);
$action    = elgg_extract('action'      , $vars);
$ul_style  = elgg_extract('ul_style'    , $vars);
$ul_aspect = elgg_extract('ul_aspect'   , $vars);
$li_style  = elgg_extract('li_style'    , $vars);
$li_aspect = elgg_extract('li_aspect'   , $vars);
$state     = elgg_extract('state'       , $vars, 'selected');
$expand_tabs = elgg_extract('expand_tabs', $vars, false);
/***/
$selected_queb     = elgg_extract('this_label'   , $vars);
$selected_category = elgg_extract('this_collection', $vars);
$selected_owner    = elgg_extract('this_owner'   , $vars);
$list_type         = elgg_extract('list_type'    , $vars);
$attachment_counts = elgg_extract('attachments'  , $vars);
$presentation      = elgg_extract('presentation' , $vars);

if ($attachment_counts){
    $things_count  = (int) elgg_extract('things', $attachment_counts, 0);
    $documents_count = (int) elgg_extract('documents', $attachment_counts, 0);
    $images_count  = (int) elgg_extract('gallery', $attachment_counts, 0);
}
$things_count_label= $things_count ? "($things_count)" : NULL;
$documents_count_label = $documents_count ? "($documents_count)" : NULL;
$images_count_label= $images_count ? "($images_count)": NULL;
    
if (empty($selected)) {$selected = 'Summary';}
$scope = empty($this_guid) ? 'collective' : 'single';

Switch ($scope){
    case 'single':
        $sections  = array();
        $queb      = get_entity($this_guid);
        $subtype   = elgg_extract('subtype'     , $vars) ?: $queb->getSubtype();   $display .= '39 $subtype: '.$subtype.'<br>';
        $aspect    = $queb->aspect;                                                $display .= '40 $aspect: '. $aspect.'<br>';
            
        switch ($subtype) {
        	case 'task_top':
        		switch ($action) {
        			case 'display':
        				$url = elgg_get_site_url() . "task/view";
        				$sections[] = 'Schedule';
        				$sections[] = 'Description';
        				$sections[] = 'Parts';
        				$sections[] = 'Steps';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				break;
        			case 'edit':
        				$url = elgg_get_site_url() . "task/edit";
        				$sections[] = 'Schedule';
        				$sections[] = 'Description';
        				$sections[] = 'Parts';
        				$sections[] = 'Steps';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				break;
        			default:
        				$url = elgg_get_site_url() . "task/view";
        				$sections[] = 'Schedule';
        				$sections[] = 'Description';
        				$sections[] = 'Parts';
        				$sections[] = 'Steps';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				break;
        		}
        		break;
        	case 'task':
        		switch ($action) {
        			case 'display':
        				$url = elgg_get_site_url() . "task/view";
        				$sections[] = 'Description';
        				$sections[] = 'Steps';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				break;
        			case 'edit':
        				$url = elgg_get_site_url() . "task/edit";
        				$sections[] = 'Description';
        				$sections[] = 'Steps';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				break;
        			default:
        				$url = elgg_get_site_url() . "task/view";
        				$sections[] = 'Description';
        				$sections[] = 'Steps';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				break;
        		}
        		break;
        	case 'experience':
        	    switch ($aspect){
        	        case 'instruction':
        	            $expand_tab = 'Instructions';
        	            break;
        	        case 'observation':
        	            $expand_tab = 'Observation';
        	            break;
        	        case 'event':
        	            $expand_tab = 'Event';
        	            break;
        	        case 'project':
        	            $expand_tab = 'Project';
        	            break;
        	        case 'issue':
        	            $expand_tab = 'Issue';
        	            break;
        	        case 'effort':
        	            $expand_tab = 'Effort';
        	            break;
        	        case 'experience':
        	            if ($action != 'view'){
        	                $expand_tab = 'Expand...';
        	            }
        	            break;
        	        default:
        	            $expand_tab = 'Expand...';
        	    }
        		switch ($action) {
        			case 'add':
        				$selected   = $selected ?: $expand_tab;
        				$url        = elgg_get_site_url() . "jot/view";
        				$sections[] = 'Things';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
/* @EDIT 2018-05-28 - SAJ - Remove Expand tab until developed further
        				$sections[] = 'Expand...';
*/
// @EDIT 2018-07-12 - SAJ - Add Issue tab.  Will later integrate into Expand tab
        				$sections[] = 'Issue';
        				$sections[] = 'Project';
        				break;
        			case 'edit':
        	            $selected   = $selected ?: $expand_tab;
        				$url        = elgg_get_site_url() . "jot/edit";
        				$sections[] = 'Things';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				if (!empty($expand_tab)){
        				    $sections[] = $expand_tab;
        				}
        				break;
        			case 'view':
        				$sections[] = 'Things';
        				$sections[] = 'Documents';
        				$sections[] = 'Gallery';
        				if (!empty($expand_tab)){
        				    $sections[] = $expand_tab;
        				}
        				break;
        		}
        		break;
        	case 'problem':
                $tabs = quebx_problem_tabs($vars, $selected);
        		break;
        	case 'request':
                $tabs = quebx_request_tabs($vars, $selected);
        		break;
        	case 'maintenance':
        		if (empty($title)) {
        			 $title = $queb->title;
        		}
        		switch ($action) {
        			case 'display':
        				$url = elgg_get_site_url() . "que/set";
        				$sections[] = 'Summary';
        				$sections[] = 'Details';
        				$sections[] = 'Schedule';
        				$sections[] = 'Inventory';
        				$sections[] = 'Management';
        				$sections[] = 'Accounting';
        				$sections[] = 'Gallery';
        				$sections[] = 'Reports';
        				$sections[] = 'Timeline';
        				break;
        			case 'edit':
        				$url = elgg_get_site_url() . "que/edit";
        				$sections[] = 'Schedule';
        				$sections[] = 'Inventory';
        				$sections[] = 'Management';
        				$sections[] = 'Accounting';
        				$sections[] = 'Gallery';
        				$sections[] = 'Reports';
        				$sections[] = 'Timeline';
        				break;
        			default:
        				$url = elgg_get_site_url() . "que/set";
        				$sections[] = 'Summary';
        				$sections[] = 'Details';
        				$sections[] = 'Schedule';
        				$sections[] = 'Inventory';
        				$sections[] = 'Management';
        				$sections[] = 'Accounting';
        				$sections[] = 'Gallery';
        				$sections[] = 'Reports';
        				$sections[] = 'Timeline';
        				break;
        		}
        /*		$tabs = array();
        		
        		foreach ($sections as $section) {
        			$id = key($sections);
        			$tabs[] = array(
        				'title'    => elgg_echo("$section"),
        				'url'      => "$url/$this_guid/$section",
        				'selected' => $section == $selected,
        				'id'       => $section."_tab",
        			);
        		}
        */		break;
        	case 'image':
        		$sections = array();
        		switch ($action) {
        			case 'edit':
        				$url = elgg_get_site_url() . "que/edit";
        				$sections[] = 'Edit';
        				$sections[] = 'Add';
        				break;
        		}
        /*		$tabs = array();
        		
        		foreach ($sections as $section) {
        			$id = key($sections);
        			$tabs[] = array(
        				'title'    => elgg_echo("$section"),
        				'url'      => "$url/$this_guid/$section",
        				'selected' => $section == $selected,
        				'id'       => $section."_tab",
        			);
        		}
        */		break;
        }
        $tabs = array();
        if ($presentation == 'qbox'){
      	    $li_class = 'qbox-q';
        }
        foreach ($sections as $section) {
            unset($count, $panel, $id);
            if ($subtype == 'experience' && $section == 'Gallery'){
                $id = 'Jot_experience_gallery_tab';
                $panel = $section;
            }
            elseif ($subtype == 'experience' && ($section == 'Expand...' || $section == 'Instructions' || $section == 'Observation' || $section == 'Event' || $section == 'Project'  || $section == 'Issue')){
                $id = 'Expand_tab';
                $panel = 'Expand';
            }
            else {
                $id = $section."_tab";
                $panel = $section;
        	}
        	Switch($section){
        	    case 'Things':
        	        if ($subtype == 'experience' && $things_count<=0 && $action == 'view'){
        	            unset ($sections[$section]);
        	            continue 2;
        	        }
        	        $count = $things_count_label;
        	        break;
        	    case 'Documents':
        	        if ($subtype == 'experience' && $documents_count <= 0 && $action == 'view'){
        	            unset ($sections[$section]);
        	            continue 2;
        	        }
        	        $count = $documents_count_label;
        	        break;
        	    case 'Gallery':
        	        $count = $images_count_label;
        	        if ($subtype == 'experience' && $images_count <= 0 && $action == 'view'){
        	            unset ($sections[$section]);
        	            continue 2;
        	        }
        	        break;
        	}        	
        	$tabs[] = array(
        		'title'    => elgg_echo("$section"),
        		'selected' => $section == $selected && $state == 'selected',
        		'id'       => $id,
        	    'style'    => $li_style,
        	    'class'    => $li_class,
        	    'count'    => $count,
        	    'guid'     => $this_guid,
        	    'panel'    => $panel,
        	    'aspect'   => $subtype,
        	    'section'  => $section,
        		'expand_tabs'=>$expand_tabs,
        	); 
        }
        $vars['tabs']=$tabs;
        $vars['style']=$ul_style;
        $vars['aspect']=$ul_aspect;
        $view = elgg_view('navigation/tabs_slide', $vars);
        break; 
/*****************************/        
    case 'collective':
        if (!empty($list_type)){
                $list_type = "&list_type=$list_type";
            }
        $user = elgg_get_logged_in_user_entity();
        
        if (empty($selected_owner)) {
        	 $tab_selected = 'everyone';
        }
        elseif ($selected_owner == $user->guid) {
            $tab_selected = 'mine';
        }
        
        //set the url
        $url = elgg_get_site_url(). "queb?";
        
        $owners[] = 'everyone';
        $owners[] = 'mine';
        
        foreach ($owners as $owner){
            switch ($owner){
                case 'everyone':
                    $filter = "x=$selected_queb&y=$selected_category";
                    break;
                case 'mine':
                    $filter = "x=$selected_queb&y=$selected_category&z=$user->guid";
                    break;
                default:
                    $filter = $owner."/$user->username";
                    break;
            }
            $tabs[] = array(
                    'title'=> elgg_echo("market:$owner"),
                    'url'  => $url.$filter.$list_type,
                    'selected' => $tab_selected == $owner,
                );
        }
        
        $view = elgg_view('navigation/tabs', array('tabs' => $tabs));
        break;
}
echo $view;
eof:
//echo $display;
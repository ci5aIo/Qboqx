<?php
/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal
 * @uses string $vars['class'] Additional class to add to 'ul'
 * @uses string $vars['aspect'] Additional class to add to all 'li'
 * @uses array $vars['tabs'] A multi-dimensional array of tab entries in the format array(
 * 	'text' => string, // The string between the <a></a> tags
 * 	'href' => string, // URL for the link
 * 	'class' => string  // Class of the li element
 * 	'id' => string, // ID of the li element
 * 	'selected' => bool // if this tab is currently selected (applied to li element)
 * 	'link_class' => string, // Class to pass to the link
 * 	'link_id' => string, // ID to pass to the link
 * )
 */
$options = _elgg_clean_vars($vars);

$type         = elgg_extract('type', $vars, 'horizontal');
$presentation = elgg_extract('presentation', $vars,'full');
$element      = elgg_extract('element', $vars, 'qbox');
$space        = elgg_extract('space', $vars);

if ($type == 'horizontal') {
	$options['class'] = "elgg-tabs elgg-htabs";
} else {
	$options['class'] = "elgg-tabs elgg-vtabs";
}
if (isset($vars['class'])) {
	$options['class'] = "{$options['class']} {$vars['class']}";
}
if (isset($vars['style'])) {
	$options['style'] = "{$vars['style']}";
}
if (isset($vars['qid'])){
	$qid = $vars['qid'];
}

unset($options['tabs']);
unset($options['type']);

$attributes = elgg_format_attributes($options);

if (isset($vars['tabs']) && is_array($vars['tabs']) && !empty($vars['tabs'])) {
	?>
	<ul <?php echo $attributes; ?>>
		<?php
		foreach ($vars['tabs'] as $key=>$info) {
			$class    = elgg_extract('class'   , $info, '');
			$style    = elgg_extract('style'   , $info, '');
			$id       = elgg_extract('id'      , $info, '');
			$guid     = elgg_extract('guid'    , $info, false);
			$panel    = elgg_extract('panel'   , $info, false);
			$count    = elgg_extract('count'   , $info, false);
			$selected = elgg_extract('selected', $info, false);
			$expand_tabs = elgg_extract('expand_tabs', $info, false);
			$section  = elgg_extract('section' , $info);
			$aspect   = elgg_extract('aspect'  , $info, false);
			$note     = elgg_extract('note'    , $info, false);
			$cursor   = 'cursor:pointer';
			$style    = ($style) ? $style.';'.$cursor : $cursor;
			$n        = $key+1;
			
			if ($selected) {
				Switch ($space){
					case 'transfer':
						$class  = 'elgg-state-selected';
						break;
/*					case 'market':
						$class .= ' qbox-state-selected';
						break;*/
					default:
						$class .= ' elgg-state-selected';// Selected plus the received class
						break;
				}
			}

			$class_str  = ($class)  ? "class=\"$class\""   : '';
			$style_str  = ($style)  ? "style=\"$style\""   : '';
			$id_str     = ($id)     ? "id=\"$id\""         : '';
			$guid_str   = ($guid)   ? "guid=\"$guid\""     : '';
			$panel_str  = ($panel)  ? "panel=\"$panel\""   : '';
			$aspect_str = ($aspect) ? "aspect=\"$aspect\"" : '';
			
			$options = $info;
			unset(//$options['class'], 
			      $options['id'], 
			      $options['selected'],
				  $options['section']);
/*
			if (!isset($info['href']) && isset($info['url'])) {
				$options['href'] = $info['url'];
				unset($options['url']);
			}
*/			
			if ($presentation == 'qbox'){
				$options['data-section'] = elgg_strtolower($section);
				$options['data-element'] = $element;
//				$options['class']        = 'qbox-q';
				if (isset($qid))           {$options['data-qid']="{$qid}_{$n}";}
				else                       {$options['data-qid']="q{$guid}_01_{$n}";}
//				if ($space == 'transfer')  {$options['class']='qbox-q2';}
// 				if ($space == 'transfer' && !$selected){$options['class']='qbox-q2';}
// 				if ($space == 'transfer' &&  $selected){$options['class']='elgg-state-selected';}
			}
            if (!isset($info['text']) && isset($info['title'])) {
				$options['text'] = $options['title'].$count;
				unset($options['title']);
			}
			if (isset($info['link_class'])) {
				$options['class'] = $options['link_class'];
				unset($options['link_class']);
			}
			if (isset($info['link_style'])) {
				$options['style'] = $options['link_style'];
				unset($options['link_style']);
			}

			if (isset($info['link_id'])) {
				$options['id'] = $options['link_id'];
				unset($options['link_id']);
			}
			$link = elgg_view('output/url', $options);
			if ($panel == 'Expand'){$text = $link.$expand_tabs;}
			else                   {$text = $link;}
			if ($note)             {$text = elgg_view('output/span',['content'=>$text, 'options'=>['title'=>$note]]);}

			echo "<li $aspect_str $guid_str $panel_str $style_str $id_str $class_str>$text</li>";
		}
		?>
	</ul>
	<?php
}

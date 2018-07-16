<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$wwwroot = elgg_get_config('wwwroot');
//the page owner
$owner = get_user($vars['entity']->owner_guid);

//the number of files to display
$num = (int) $vars['entity']->num_display;
if (!$num) {
	$num = 4;
}		
		
$jots = elgg_get_entities(array('type'=>'object','subtype'=>'transfer', 'owner_guid' => $owner->guid, 'limit'=>$num));

echo '<ul class="elgg-list">';		
// display the jots
if (is_array($jots) && sizeof($jots) > 0) {

	if (!$size || $size == 1){
		foreach($jots as $jot) {
			echo "<li class=\"pvs\">";
			$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo($jot->category);
			$comments_count = $jot->countComments();
			$text = elgg_echo("comments") . " ($comments_count)";
			$comments_link = elgg_view('output/url', array(
						'href' => $jot->getURL() . '#market-comments',
						'text' => $text,
						));
			$market_img = elgg_view('output/url', array(
						'href' => "jot/view/{$jot->guid}/" . elgg_get_friendly_title($jot->title),
						'text' => elgg_view('market/thumbnail', array('marketguid' => $jot->guid, 'size' => 'small')),
						));

			$subtitle = "{$category}<br><b>" . elgg_echo('market:price') . ":</b> {$jot->price}";
			$subtitle .= "<br>{$author_text} {$date} {$comments_link}";
			$params = array(
				'entity' => $jot,
				'metadata' => $metadata,
				'subtitle' => $subtitle,
				'tags' => $tags,
				'content' => $excerpt,
			);
			$params = $params + $vars;
			$list_body = elgg_view('object/elements/summary', $params);
			echo elgg_view_image_block($market_img, $list_body);
			echo "</li>";
		}
			
	}
	echo "</ul>";
	echo "<div class=\"contentWrapper\"><a href=\"" . $wwwroot . "jot/home/" . $owner->username . "\">" . elgg_echo("jot:widget:viewall") . "</a></div>";

}


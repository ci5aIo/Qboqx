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
$dbprefix = elgg_get_config('dbprefix');
$options = array('type'=>'object',
                  'subtype'=>'market', 
                  'owner_guid' => $owner->guid,
                  'wheres'    => array("NOT EXISTS (SELECT *
	                                  FROM {$dbprefix}metadata md
            	                      JOIN {$dbprefix}metastrings ms1 ON ms1.id = md.name_id
            	                      JOIN {$dbprefix}metastrings ms2 ON ms2.id = md.value_id
            	                      WHERE ms1.string = 'visibility_choices'
            	                        AND ms2.string = 'hide_in_catalog'
            	                        AND e.guid = md.entity_guid)"), 
        'limit'=>$num);
    
$posts = elgg_get_entities($options);

echo '<ul class="elgg-list">';		
// display the posts, if there are any
if (is_array($posts) && sizeof($posts) > 0) {

	if (!$size || $size == 1){
		foreach($posts as $post) {
			echo "<li class=\"pvs\">";
			$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo($post->marketcategory);
			$comments_count = $post->countComments();
			$text = elgg_echo("comments") . " ($comments_count)";
			$comments_link = elgg_view('output/url', array(
						'href' => $post->getURL() . '#market-comments',
						'text' => $text,
						));
			$market_img = elgg_view('output/url', array(
						'href' => "market/view/{$post->guid}/",
						'text' => elgg_view('market/thumbnail', array('marketguid' => $post->guid, 'size' => 'small', 'width'=> '40', 'height' =>'40')),
						));

			$subtitle = "{$category}<br><b>" . elgg_echo('market:price') . ":</b> {$post->price}";
			$subtitle .= "<br>{$author_text} {$date} {$comments_link}";
			$params = array(
				'entity' => $post,
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
	echo "<div class=\"contentWrapper\"><a href=\"" . $wwwroot . "market/owned/" . $owner->username . "\">" . elgg_echo("market:widget:viewall") . "</a></div>";

}


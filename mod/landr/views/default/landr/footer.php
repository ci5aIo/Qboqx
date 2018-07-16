<?php
/**
 * Landr footer section
 * 
 */

// Get social plugin settings
$social_facebook = elgg_get_plugin_setting('social_facebook', 'landr');
$social_twitter = elgg_get_plugin_setting('social_twitter', 'landr');
$social_googleplus = elgg_get_plugin_setting('social_googleplus', 'landr');
$social_instagram = elgg_get_plugin_setting('social_instagram', 'landr');
$contact_text = elgg_get_plugin_setting('contact_text', 'landr');
$contact_mail = elgg_get_plugin_setting('contact_mail', 'landr');
$contact_phone = elgg_get_plugin_setting('contact_phone', 'landr');
$contact_location = elgg_get_plugin_setting('contact_location', 'landr');

$wires = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' =>  3,
	'full_view' => false,
	'pagination' => false,
));
 
$wires_owner = elgg_get_entities(array(
	'type' => 'user',
	'limit' =>  3,
	'full_view' => false,
	'pagination' => false,
)); 

$merge = array_merge($wires, $wires_owner);
 
?>

<div class="landr-section landr-tb-padding landr-footer">
    <div class="landr-container">
        <div class="landr-columns .landr-sitemap"> 
            <h1><?php echo elgg_echo('landr:footer:sitemap'); ?></h1>
            <?php echo elgg_view_menu('site'); ?> 
            <div class="landr-social">
                <?php if ($social_facebook) { echo '<a href="' . $social_facebook . '" target="_blank"><i class="fa fa-facebook"></i></a>'; } ?>
                <?php if ($social_twitter) { echo '<a href="' . $social_twitter . '" target="_blank"><i class="fa fa-twitter"></i></a>'; } ?>
                <?php if ($social_googleplus) { echo '<a href="' . $social_googleplus . '" target="_blank"><i class="fa fa-google-plus"></i></a>'; } ?>
                <?php if ($social_instagram) { echo '<a href="' . $social_instagram . '" target="_blank"><i class="fa fa-instagram"></i></a>'; } ?>
            </div>
        </div>
        <div class="landr-columns .landr-wires"> 
            <h1><?php echo elgg_echo('landr:footer:latestwires'); ?></h1>
            <?php
                echo '<ul>';
                    foreach ($wires as $wire) {
                        $user = get_user($wire->owner_guid);
                        $image = $user->getIconURL('small');
                        echo '<li class="landr-wires-img"><a href="profile/'.$user->username.'"><img src="'.$image.'" />'.$user->name.'</a><span>'.elgg_get_friendly_time($wire->time_created).'</span></li>'; 
                        echo '<li class="landr-wires-content">'.$wire->description.'</li>';
                    }
                echo '</ul><br />';
            ?>
        </div>
        <div class="landr-columns landr-contact">
            <h1><?php echo elgg_echo('landr:footer:contactus'); ?></h1> 
            <i class="fa fa-comments comments"></i>
            <p><?php if ($contact_text) { echo $contact_text; } else { echo elgg_echo('landr:footer:contactmessage'); } ?></p> 
            <ul>
                <li><i class="fa fa-envelope"></i><span><?php if ($contact_mail) { echo '<a href="mailto:' . $contact_mail . '">' . $contact_mail . '</a>'; } else { echo 'finding@nemo.com'; } ?></span></li> 
                <li><i class="fa fa-phone"></i><span><?php if ($contact_phone) { echo $contact_phone; } else { echo '867-5309'; } ?></span></li> 
                <li><i class="fa fa-map-marker"></i><span><?php if ($contact_location) { echo $contact_location; } else { echo 'P. Sherman 42 Wallaby Way, Sydney'; } ?></span></li>
        </div>
    </div>
</div>
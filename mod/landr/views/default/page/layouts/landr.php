<?php
/**
 * Landr custom index
 * 
 */

// Retrieve plugin settings
$welcome_message = elgg_get_plugin_setting('welcome_message', 'landr');
$fa1 = elgg_get_plugin_setting('fa1', 'landr');
$fa2 = elgg_get_plugin_setting('fa2', 'landr');
$fa3 = elgg_get_plugin_setting('fa3', 'landr');
$intro_heading_1 = elgg_get_plugin_setting('intro_heading_1', 'landr');
$intro_heading_2 = elgg_get_plugin_setting('intro_heading_2', 'landr');
$intro_heading_3 = elgg_get_plugin_setting('intro_heading_3', 'landr');
$intro_heading_text1 = elgg_get_plugin_setting('intro_heading_text1', 'landr');
$intro_heading_text2 = elgg_get_plugin_setting('intro_heading_text2', 'landr');
$intro_heading_text3 = elgg_get_plugin_setting('intro_heading_text3', 'landr'); 
$member_heading = elgg_get_plugin_setting('member_heading', 'landr');
$member_heading_font_weight = elgg_get_plugin_setting('member_heading_font_weight', 'landr');
$menu_style = elgg_get_plugin_setting('menu_style', 'landr');

// Necessary Elgg functions executed only on Landr custom page
elgg_unextend_view('page/elements/header', 'search/header');
elgg_unregister_plugin_hook_handler('prepare', 'menu:site', '_elgg_site_menu_setup');
elgg_unregister_menu_item('footer', 'powered');
elgg_extend_view('page/elements/footer', 'landr/footer');

// If user logs in then forward to activity
if (elgg_is_logged_in()) {
	header("Location: activity");
}

if ($menu_style == '' || $menu_style == 'mobile') {
    elgg_extend_view('page/elements/header', 'landr/menu');
}
else {
    elgg_extend_view('page/elements/header', 'landr/menu_horizontal');
}

// Remodal
elgg_register_css('remodal', 'mod/landr/lib/jquery.remodal.css');
elgg_load_css('remodal');
elgg_register_js('remodal', 'mod/landr/lib/jquery.remodal.js');
elgg_load_js('remodal');

// Landr CSS & JS
elgg_extend_view('page/elements/head', 'landr/css'); 
elgg_require_js('landr/landr');

?>
    
<div class="landr-slider">
    <div class="landr-slider-content">
    <?php if ($welcome_message) { echo $welcome_message; } else { ?>
        <h1><?php echo elgg_echo('landr:message:welcome'); ?></h1> 
        <p><?php echo elgg_echo('landr:lorem:ipsum'); ?></p>
    <?php } ?>
        <div class="landr-buttons">
            <a href="#login"><?php echo elgg_echo('landr:button:login'); ?></a>
            <a href="#register"><?php echo elgg_echo('landr:button:register'); ?></a>
        </div>
    </div>
</div>

<div class="landr-section row1">
    <div class="landr-container">
        <div class="landr-columns"> 
            <i class="fa fa-<?php if ($fa1) { echo $fa1; } else { echo 'mobile-phone'; } ?>"></i> 
            <h2><?php if ($intro_heading_1) { echo $intro_heading_1; } else { echo elgg_echo('landr:message:responsive'); } ?></h2>
            <?php if ($intro_heading_text1) { echo $intro_heading_text1; } else { echo '<p>'.elgg_echo('landr:lorem:ipsum').'</p>'; } ?>
        </div>
        <div class="landr-columns">
            <i class="fa fa-<?php if ($fa2) { echo $fa2; } else { echo 'cube'; } ?>"></i>
            <h2><?php if ($intro_heading_2) { echo $intro_heading_2; } else { echo elgg_echo('landr:message:flatui'); } ?></h2>
            <?php if ($intro_heading_text2) { echo $intro_heading_text2; } else { echo '<p>'.elgg_echo('landr:lorem:ipsum').'</p>'; } ?>
        </div>
        <div class="landr-columns">
            <i class="fa fa-<?php if ($fa3) { echo $fa3; } else { echo 'code'; } ?>"></i> 
            <h2><?php if ($intro_heading_3) { echo $intro_heading_3; } else { echo elgg_echo('landr:message:easycustomizations'); } ?></h2>
            <?php if ($intro_heading_text3) { echo $intro_heading_text3; } else { echo '<p>'.elgg_echo('landr:lorem:ipsum').'</p>'; } ?>
        </div>
    </div>
</div>

<div class="landr-section row2">
    <h1><?php if ($member_heading) { echo $member_heading; } else { echo elgg_echo('landr:message:members'); } ?></h1>
    <div class="landr-container"> 
        <?php 

    		$newest_members = elgg_get_entities_from_metadata(array(
    			'metadata_names' => 'icontime', 
    			'type' => 'user',
    			'limit' =>  3,
    			'full_view' => false,
    			'pagination' => false,
    		));
        
        	echo '<ul>';
        		foreach ($newest_members as $m) {
        			$image = $m->getIconURL('master'); 
                	echo '<li class="landr-columns"><a href="profile/' . $m->username .'"><div class="landr-member-img" style="background: url(' . $image . ');background-size:cover"></div>' . '<h3>' . $m->name . '</h3>' . '</a></li>';
                } 
        	echo '</ul>';
        	
    	?>
    </div>
</div>

<div class="remodal landr-login" data-remodal-id="login">
	<?php echo elgg_view('core/account/login_box'); ?>
</div>

<div class="remodal landr-register" data-remodal-id="register">
	<?php echo elgg_view_form('register'); ?>
</div>
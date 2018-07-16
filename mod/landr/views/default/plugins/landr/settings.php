<?php
/**
 * Landr admin settings
 * 
 */

// Landr CSS & JS
elgg_extend_view('page/elements/head', 'landr/settings');
elgg_require_js('landr/settings');

// Color picker libraries
elgg_register_css('colorpicker', 'mod/landr/lib/colorpicker.css');
elgg_load_css('colorpicker');
elgg_register_js('colorpicker', 'mod/landr/lib/colorpicker.js');
elgg_load_js('colorpicker'); 

// Font Awesome
elgg_register_css('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
elgg_load_css('font-awesome');

// Ajax Uploader
elgg_register_js('lite-uploader', 'mod/landr/lib/jquery.liteuploader.js');
elgg_load_js('lite-uploader');

// Get plugin settings
$primary_color = elgg_get_plugin_setting('primary_color', 'landr');
$secondary_color = elgg_get_plugin_setting('secondary_color', 'landr');
$heading_font_family = elgg_get_plugin_setting('heading_font_family', 'landr');
$general_font_family = elgg_get_plugin_setting('general_font_family', 'landr');
$general_text_color = elgg_get_plugin_setting('general_text_color', 'landr');
$menu_style = elgg_get_plugin_setting('menu_style', 'landr');
$slider_image = elgg_get_plugin_setting('slider_image', 'landr');
$slider_tint = elgg_get_plugin_setting('slider_tint', 'landr'); 
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
$member_icon_border = elgg_get_plugin_setting('member_icon_border', 'landr');
$member_icon_color = elgg_get_plugin_setting('member_icon_color', 'landr');
$social_facebook = elgg_get_plugin_setting('social_facebook', 'landr');
$social_twitter = elgg_get_plugin_setting('social_twitter', 'landr');
$social_googleplus = elgg_get_plugin_setting('social_googleplus', 'landr');
$social_instagram = elgg_get_plugin_setting('social_instagram', 'landr');
$contact_text = elgg_get_plugin_setting('contact_text', 'landr');
$contact_mail = elgg_get_plugin_setting('contact_mail', 'landr');
$contact_phone = elgg_get_plugin_setting('contact_phone', 'landr');
$contact_location = elgg_get_plugin_setting('contact_location', 'landr');

$image_file = elgg_view('input/file', array(
    'name' => 'landr-slider',
    'style' => 'display: none'
));
$image_tmp = elgg_view('input/text', array(
    'name' => 'params[slider_image]',
    'id' => 'landr-slider-tmp',
    'value' => $slider_image,
    'placeholder' => elgg_get_site_url() . 'mod/landr/img/bg.jpg',
));
$image_button = elgg_view('input/button', array(
    'value' => 'Upload',
    'id' => 'landr-slider-upload',
    'class' => 'elgg-button'
));
 
?>
 
<div class="landr-update-bar" style="display: none;">Save</div>  
 
<div class="landr-left">
    <div class="demyx"><div class="logo"><a href="http://demyx.com" target="_blank">[demyx]</a></div></div>
    <div class="landr-left-content"> 
        <ul>
            <li><i class="fa fa-thumbs-up"></i> <span><a href="https://community.elgg.org/profile/manacim" target="_blank">Elgg</a></span></li>
            <li><i class="fa fa-facebook"></i> <span><a href="https://facebook.com/demyxco" target="_blank">Facebook</a></span></li>
            <li><i class="fa fa-instagram"></i> <span><a href="https://instagram.com/cimcard" target="_blank">Instagram</a></span></li> 
        </ul>
    </div>
</div>
<div class="landr-right">
    <ul class="landr-float-left">
        <li><label>Primary Color</label></li>
        <li><span>Applies to topbar, borders, some icons, and some headings</span></li> 
    </ul>
    <input type="text" name="params[primary_color]" id="primary_color" placeholder="<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>" value="<?php echo $primary_color; ?>">
    
    <div class="landr-gap"></div>
    
    <ul class="landr-float-left">
        <li><label>Secondary Color</label></li>
        <li><span>Applies to some headings, some icons, and container backgrounds</span></li>
    </ul>
    <input type="text" name="params[secondary_color]" id="secondary_color" placeholder="<?php if ($secondary_color) { echo $secondary_color; } else { echo '00000'; } ?>" value="<?php echo $secondary_color; ?>">
    
    <div class="landr-gap"></div>
    
    <ul class="landr-float-left">
        <li><label>General Text Color</label></li>
        <li><span>Changes the color of normal text</span></li>
    </ul>
    <input type="text" name="params[general_text_color]" id="general_text_color" placeholder="<?php if ($general_text_color) { echo $general_text_color; } else { echo '333333'; } ?>" value="<?php echo $general_text_color; ?>">
    
    <div class="landr-gap"></div>       
   
    <ul class="landr-float-left">
        <li><label>General Font Family</label></li>
        <li><span>Applies to any text inside body tag</span></li>
    </ul>
    <select name="params[general_font_family]">
        <option value="lato" <?php if ($general_font_family == 'lato') { echo 'selected'; } ?>>Lato</option>
        <option value="opensans" <?php if ($general_font_family == 'opensans') { echo 'selected'; } ?>>Open Sans</option>
        <option value="roboto" <?php if ($general_font_family == 'roboto') { echo 'selected'; } ?>>Roboto</option>
        <option value="ubuntu" <?php if ($general_font_family == 'ubuntu') { echo 'selected'; } ?>>Ubuntu</option>
        <option value="oswald" <?php if ($general_font_family == 'oswald') { echo 'selected'; } ?>>Oswald</option>
        <option value="raleway" <?php if ($general_font_family == 'raleway') { echo 'selected'; } ?>>Raleway</option>
    </select>     
    
    <div class="landr-gap"></div>       
   
    <ul class="landr-float-left">
        <li><label>Heading Font Family</label></li>
        <li><span>Applies to only h1, h2, and h3 tags</span></li>
    </ul>
    <select name="params[heading_font_family]">
        <option value="lato" <?php if ($heading_font_family == 'lato') { echo 'selected'; } ?>>Lato</option>
        <option value="opensans" <?php if ($heading_font_family == 'opensans') { echo 'selected'; } ?>>Open Sans</option>
        <option value="roboto" <?php if ($heading_font_family == 'roboto') { echo 'selected'; } ?>>Roboto</option>
        <option value="ubuntu" <?php if ($heading_font_family == 'ubuntu') { echo 'selected'; } ?>>Ubuntu</option>
        <option value="oswald" <?php if ($heading_font_family == 'oswald') { echo 'selected'; } ?>>Oswald</option>
        <option value="raleway" <?php if ($heading_font_family == 'raleway') { echo 'selected'; } ?>>Raleway</option>
    </select> 
    
    <div class="landr-gap"></div>       
   
    <ul class="landr-float-left">
        <li><label>Menu Style</label></li>
        <li><span>Mobile or horizontal menu</span></li>
    </ul>
    <select name="params[menu_style]">
        <option value="mobile" <?php if ($menu_style == 'mobile') { echo 'selected'; } ?>>Mobile</option>
        <option value="horizontal" <?php if ($menu_style == 'horizontal') { echo 'selected'; } ?>>Horizontal</option>
    </select> 
    
    
    <div class="landr-divider"></div>

    <div id="upload-image" style="display: none; margin-bottom: 30px;"><img id="uploaded-image" width="100%" /></div>
    <div id="upload-message" style="display: none; margin-bottom: 30px; text-align: center;"></div>    

    <ul class="landr-float-left">
        <li><label>Slider Image</label></li>
        <li><span>You may use an external URL for your image or upload your own using the uploader</span>
        <?php echo $image_tmp . $image_button . $image_file; ?>
        </li>
        <li><br /><label>Current Slider</label></li>  
        <li><span><img class="current-slider-image" src="<?php if ($slider_image) { echo $slider_image; } else { echo elgg_get_site_url() . 'mod/landr/img/bg.jpg'; } ?>" width="50%" /></span></li> 
    </ul>
    
    <div class="landr-gap"></div>  
    
    <ul class="landr-float-left">
        <li><label>Overlay Tint</label></li>
        <li><span>Darken the slider image or make it transparent</span></li>
    </ul>
    <select name="params[slider_tint]">
        <option value="0" <?php if ($slider_tint == '0') { echo 'selected'; } ?>>0 (Transparent)</option>
        <option value="0.1" <?php if ($slider_tint == '0.1') { echo 'selected'; } ?>>0.1</option>
        <option value="0.2" <?php if ($slider_tint == '0.2') { echo 'selected'; } ?>>0.2</option>
        <option value="0.3" <?php if ($slider_tint == '0.3') { echo 'selected'; } ?>>0.3</option>
        <option value="0.4" <?php if ($slider_tint == '0.4') { echo 'selected'; } ?>>0.4</option>
        <option value="0.5" <?php if ($slider_tint == '0.5') { echo 'selected'; } ?>>0.5</option>
        <option value="0.6" <?php if ($slider_tint == '0.6') { echo 'selected'; } ?>>0.6</option>
        <option value="0.7" <?php if ($slider_tint == '0.7') { echo 'selected'; } ?>>0.7</option>
        <option value="0.8" <?php if ($slider_tint == '0.8') { echo 'selected'; } ?>>0.8</option>
        <option value="0.9" <?php if ($slider_tint == '0.9') { echo 'selected'; } ?>>0.9</option>
        <option value="1" <?php if ($slider_tint == '1') { echo 'selected'; } ?>>1 (Solid)</option> 
    </select> 
    
    <div class="landr-divider"></div>
    
    <ul>
        <li><label>Welcome Message</label></li>
        <li><span>Change the text above login and register buttons (keep it short as possible)</span></li>  
    </ul>
    
    <?php echo elgg_view('input/longtext',array('name' => 'params[welcome_message]', 'value' => $welcome_message)); ?>  

    <div class="landr-divider"></div>
    
    <ul class="landr-margin-bottom"> 
        <li><label>Heading Icons</label><span style="float:right">Icon list: <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a></span></li> 
        <li><span>Type the icon names that you see from the list</span></li>   
    </ul> 

    <div class="landr-icons-container" style="overflow: auto; margin-bottom: 30px;"> 
        <div class="landr-icons">
            <i id="icon-preview-1" class="fa fa-<?php if ($fa1) { echo $fa1; } else { echo 'mobile-phone'; } ?>"></i>
            <input id="icon-preview-text1" type="text" name="params[fa1]" placeholder="<?php if ($fa1) { echo $fa1; } else { echo 'mobile-phone'; } ?>" value="<?php echo $fa1; ?>">
        </div>
        <div class="landr-icons">
            <i id="icon-preview-2" class="fa fa-<?php if ($fa2) { echo $fa2; } else { echo 'cube'; } ?>"></i>
            <input id="icon-preview-text2" type="text" name="params[fa2]" placeholder="<?php if ($fa2) { echo $fa2; } else { echo 'cube'; } ?>" value="<?php echo $fa2; ?>">
        </div>
        <div class="landr-icons">
            <i id="icon-preview-3" class="fa fa-<?php if ($fa3) { echo $fa3; } else { echo 'code'; } ?>"></i>
            <input id="icon-preview-text3" type="text" name="params[fa3]" placeholder="<?php if ($fa3) { echo $fa3; } else { echo 'code'; } ?>" value="<?php echo $fa3; ?>"> 
        </div>
    </div> 

    <div class="landr-gap"></div>

    <ul class="landr-float-left">
        <li><label>Heading Left</label></li>
        <li><span>Change left column heading and paragraph text</span></li> 
    </ul>
    <input type="text" name="params[intro_heading_1]" placeholder="<?php if ($intro_heading_1) { echo $intro_heading_1; } else { echo 'Responsive'; } ?>" value="<?php echo $intro_heading_1; ?>">
    <div class="landr-gap"></div> 
    <?php echo elgg_view('input/longtext',array('name' => 'params[intro_heading_text1]', 'value' => $intro_heading_text1)); ?>
    
    <div class="landr-gap"></div><div class="landr-gap"></div>
    
    <ul class="landr-float-left">
        <li><label>Heading Middle</label></li>
        <li><span>Change middle column heading and paragraph text</span></li> 
    </ul>
    <input type="text" name="params[intro_heading_2]" placeholder="<?php if ($intro_heading_2) { echo $intro_heading_2; } else { echo 'Flat UI'; } ?>" value="<?php echo $intro_heading_2; ?>">
    <div class="landr-gap"></div> 
    <?php echo elgg_view('input/longtext',array('name' => 'params[intro_heading_text2]', 'value' => $intro_heading_text2)); ?>
    
    <div class="landr-gap"></div><div class="landr-gap"></div>

    <ul class="landr-float-left">
        <li><label>Heading Right</label></li>
        <li><span>Change right column heading and paragraph text</span></li> 
    </ul>
    <input type="text" name="params[intro_heading_3]" placeholder="<?php if ($intro_heading_3) { echo $intro_heading_3; } else { echo 'Easy Customizations'; } ?>" value="<?php echo $intro_heading_3; ?>">
    <div class="landr-gap"></div> 
    <?php echo elgg_view('input/longtext',array('name' => 'params[intro_heading_text3]', 'value' => $intro_heading_text3)); ?>
    
    <div class="landr-divider"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Member Heading</label></li> 
        <li><span>Change the text for member heading</span></li>  
    </ul> 
    <input type="text" name="params[member_heading]" placeholder="<?php if ($member_heading) { echo $member_heading; } else { echo 'MEET OUR RECENT MEMBERS'; } ?>" value="<?php echo $member_heading; ?>">

    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Member Heading Font Weight</label></li> 
        <li><span>Change the font weight for member heading</span></li>  
    </ul> 
    <select name="params[member_heading_font_weight]">
        <option value="normal" <?php if ($member_heading_font_weight == 'normal') { echo 'selected'; } ?>>Normal</option>
        <option value="bold" <?php if ($member_heading_font_weight == 'bold') { echo 'selected'; } ?>>Bold</option>
    </select> 
    
    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Member Icon Border</label></li> 
        <li><span>Change the member icon border's color and width</span></li>  
    </ul> 
    <input type="text" name="params[member_icon_border]" placeholder="<?php if ($member_icon_border) { echo $member_icon_border; } else { echo '10'; } ?>" value="<?php echo $member_icon_border; ?>">
     <div class="landr-gap"></div> 
    <input type="text" name="params[member_icon_color]" id="member_icon_color" placeholder="<?php if ($member_icon_color) { echo $member_icon_color; } else { echo 'ffffff'; } ?>" value="<?php echo $member_icon_color; ?>">

    <div class="landr-divider"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Facebook</label></li> 
        <li><span>Enter your Facebook URL</span></li>  
    </ul> 
    <input type="text" name="params[social_facebook]" placeholder="<?php if ($social_facebook) { echo $social_facebook; } else { echo 'https://facebook.com'; } ?>" value="<?php echo $social_facebook; ?>">

    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Twitter</label></li> 
        <li><span>Enter your Twttier URL</span></li>  
    </ul> 
    <input type="text" name="params[social_twitter]" placeholder="<?php if ($social_twitter) { echo $social_twitter; } else { echo 'https://twitter.com'; } ?>" value="<?php echo $social_twitter; ?>">

    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Google Plus</label></li> 
        <li><span>Enter your Google Plus URL</span></li>  
    </ul> 
    <input type="text" name="params[social_googleplus]" placeholder="<?php if ($social_googleplus) { echo $social_googleplus; } else { echo 'https://plus.google.com'; } ?>" value="<?php echo $social_googleplus; ?>">

    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Instagram</label></li> 
        <li><span>Enter your Instagram URL</span></li>  
    </ul> 
    <input type="text" name="params[social_instagram]" placeholder="<?php if ($social_instagram) { echo $social_instagram; } else { echo 'https://instagram.com'; } ?>" value="<?php echo $social_instagram; ?>">

    <div class="landr-divider"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Contact</label></li> 
        <li><span>Change the text for contact below the comments icon</span></li>  
    </ul> 
    <input type="text" name="params[contact_text]" placeholder="<?php if ($contact_text) { echo $contact_text; } else { echo 'Questions? Comments? Concerns? Don\'t be afraid to contact us, we like to hear/read it all!'; } ?>" value="<?php echo $contact_text; ?>">

    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Email</label></li> 
        <li><span>Your public email</span></li>  
    </ul> 
    <input type="text" name="params[contact_mail]" placeholder="<?php if ($contact_mail) { echo $contact_mail; } else { echo 'finding@nemo.com'; } ?>" value="<?php echo $contact_mail; ?>">

    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Phone</label></li> 
        <li><span>Your public phone</span></li>  
    </ul> 
    <input type="text" name="params[contact_phone]" placeholder="<?php if ($contact_text) { echo $contact_phone; } else { echo '867-5309'; } ?>" value="<?php echo $contact_phone; ?>">

    <div class="landr-gap"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Location</label></li> 
        <li><span>Your public location</span></li>  
    </ul> 
    <input type="text" name="params[contact_location]" placeholder="<?php if ($contact_location) { echo $contact_location; } else { echo 'P. Sherman 42 Wallaby Way, Sydney'; } ?>" value="<?php echo $contact_location; ?>">

</div>
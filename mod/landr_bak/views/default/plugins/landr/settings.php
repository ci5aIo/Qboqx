<?php
/**
 * Landr admin settings
 * 
 */

// Color picker libraries
elgg_register_css('colorpicker', 'mod/landr/lib/colorpicker.css');
elgg_load_css('colorpicker');
elgg_register_js('colorpicker', 'mod/landr/lib/colorpicker.js');
elgg_load_js('colorpicker'); 

// Font Awesome
elgg_register_css('font-awesome', 'mod/landr/lib/font-awesome.css');
elgg_load_css('font-awesome');

// Ajax uploader
elgg_register_js('ajaxfileupload', 'mod/landr/lib/ajaxfileupload.js');
elgg_load_js('ajaxfileupload'); 

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
$google_ad = elgg_get_plugin_setting('google_ad', 'landr');
 
?>

<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic);
@import url(http://fonts.googleapis.com/css?family=Oswald);

.demyx {
    background: black;
    color: white;
    text-align: center; 
}
.demyx .logo {
    display:block;
    position:relative;
    top:50%;
    left:50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    font-family: 'Oswald', sans-serif;
    font-size: 50px;
}
.demyx .logo a {
    color: white;
}
.demyx .logo a:hover {
    color: white;
    text-decoration: none; 
}
.elgg-main {
    background: none;
    border: 0;
    padding: 0; 
    color: #333;
    line-height: 1.4em;
    font-family: "Helvetica Neue", Helvetica, "Lucida Grande", Arial, sans-serif;
    backface-visibility: visible;
}
.landr-right input, .landr-right p, .landr-right label, .landr-right span, .landr-right select {
    font-family: 'Lato', sans-serif;
}
.elgg-form-settings {
    max-width: none; 
}
input {
    font-size: 90%;
    float: right;
    padding: 10px; 
}
input:focus {
    outline-offset: 0;
}
select {
    float: right; 
}
label {
    font-weight: normal;
}
span {
    color: #999;  
}
button {
    float: right; 
}
.elgg-foot {
    padding: 10px 0;
    display: inline-block;
    float: right;
}
.landr-left {
    width: 26%;
    float: left;
    background: #f1c40f;
}
.landr-left-content {
    padding: 20px;
}
.landr-right {
    width: 70%;
    float: left;
    background: white;
    padding: 20px;
}
.landr-right .landr-float-left {
    float: left;
}
.landr-gap {
    display: inline-block;
    height: 20px; 
    width: 100%;
}
.landr-divider {
    display: inline-block;
    height: 1px;
    width: 100%;
    background: #ebebeb;
    margin: 35px 0;
}
.slider-container, .right-container {
    float: right;
}
.slider-container li {
    overflow: auto;
    margin-bottom: 10px;
}
.stretch-slider-image {
    width: 350px; 
}
.landr-icons {
    float: left;
    width: 30.6%;
    margin-right: 4%;
}
.landr-icons:last-child {
    margin-right: 0;
}
.landr-margin-bottom {
    margin-bottom: 30px;
}
.landr-icons {
    text-align: center;
}
.landr-icons i {
    width: 200px;
    height: 200px;
    line-height: 200px;
    font-size: 80px;
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    border-radius: 50%;
    margin-bottom: 30px;
}
.landr-icons input {
    float: none;
}
.upload-error {
    background: #c0392b;
    color: white;
    padding: 20px;
}
.upload-success {
    background: #2ecc71;
    color: white;
    padding: 20px;
}
.landr-left-content span {
    color: black; 
}
.landr-left-content li {
    margin-bottom: 10px;
}
.landr-left-content li:last-child {
    margin-bottom: 0;
}
.landr-left-content i {
    text-align: center;
    width: 30px;
    height: 30px;
    line-height: 30px; 
    background: black;
    color: #f1c40f;
    border-radius: 50%; 
    margin-right: 10px;
}
.landr-update-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 50px;
    background: rgba(0,0,0,0.5);
    color: white;
    cursor: pointer;
    text-transform: uppercase;
    line-height: 50px;
    text-align: center; 
    font-family: 'Lato', sans-serif;
    font-size: 24px; 
}
.landr-update-bar:hover {
    background: #f1c40f;
    color: black;
}
.ace_editor {
    height: 200px; 
}

@media screen and (max-width: 1024px) {
    .landr-left, .landr-right { 
        float: none;
        width: auto; 
    }
    fieldset > div {
        margin-bottom: 20px; 
    }
    input, select {
        float: none;
        margin-top: 10px;
        width: 100%; 
    }
    .current-slider-image {
        width: 100%;
    }
    .slider-container {
        width: 100%;
    }
    .landr-icons {
        float: none;
        width: auto; 
        margin-right: 0;
        margin-bottom: 30px;
    }
    .landr-icons:last-child {
        margin-bottom: 0;
    }
}

</style>
 
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
    <?php if ($google_ad == 'yes' || $google_ad == '') { ?>  
        <div style="text-align: center; padding: 20px 0;">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Vertical -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:160px;height:600px"
                 data-ad-client="ca-pub-3208184232656816"
                 data-ad-slot="6194983284"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div> 
    <?php } ?> 
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

    <ul class="landr-float-left" style="max-width: 300px;">
        <li><label>Slider Image</label></li>
        <li><span>You may use an external URL for your image or upload your own using the uploader</span></li>
        <li><br /><label>Current Slider</label></li>  
        <li><span><a href="<?php echo $slider_image; ?>" target="_blank"><img class="current-slider-image" src="<?php if ($slider_image) { echo $slider_image; } else { echo elgg_get_site_url() . 'mod/landr/img/bg.jpg'; } ?>" value="<?php echo $slider_image; ?>" width="50%" /></a></span></li> 
    </ul> 
    
    <div class="slider-container"> 
        <ul>
            <li><input type="text" name="params[slider_image]" id="slider_image" placeholder="<?php if ($slider_image) { echo $slider_image; } else { echo elgg_get_site_url() . 'mod/landr/img/bg.jpg'; } ?>" value="<?php echo $slider_image; ?>"></li>
            <li><img id="loading" src="<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader.gif" style="display:none; float: right;"></li>
            <li><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input"></li>
            <li><button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button></li>
        </ul>
    </div>
    
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

    <div class="landr-divider"></div>
    
    <ul class="landr-float-left"> 
        <li><label>Advertisement</label></li> 
        <li><span>Support me by keeping the ad on, it will only appear for the LANDR settings</span></li>   
    </ul> 

    <select name="params[google_ad]">
        <option value="yes" <?php if ($google_ad == 'yes') { echo 'selected'; } ?>>On</option>
        <option value="no" <?php if ($google_ad == 'no') { echo 'selected'; } ?>>Off</option>
    </select>

</div>

<script type="text/javascript">
$( document ).ready(function() {

	function responsive() {
	
        // Make the logo container into a perfect square
        $('.demyx').css('height', $('.demyx').width() + 'px');

		if ($(window).width() > 1024) {
		
            // Stretches #slider-image on click
            $("#slider_image").hover(
              function() {
                $(this).addClass('stretch-slider-image');
              }, function() {
                $(this).removeClass('stretch-slider-image');
              }
            );
            
            // Color picker
            $('#primary_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#primary_color").val(hex);
                    $('#icon-preview-1, #icon-preview-2, #icon-preview-3').css('background-color', '#' + hex);  
                }
            });
            $('#secondary_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#secondary_color").val(hex);
                    $('#icon-preview-1, #icon-preview-2, #icon-preview-3').css('color', '#' + hex); 
                }
            });
            $('#member_icon_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#member_icon_color").val(hex); 
                }
            });
            $('#general_text_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#general_text_color").val(hex); 
                }
            });

		}

		if ($(window).width() < 1024) {

			
		}
		
	}
	
	responsive();

	$(window).resize(function() {
	    responsive();
	});

    // Remove plugin title
    $('.elgg-head').remove();
    
    // Live preview for icons
    $('#icon-preview-text1').on('keyup', function(){
        var text1 = 'fa fa-' + $(this).val();
            $('#icon-preview-1').removeClass();
            $('#icon-preview-1').addClass(text1);
    });
    $('#icon-preview-text2').on('keyup', function(){
        var text2 = 'fa fa-' + $(this).val();
            $('#icon-preview-2').removeClass();
            $('#icon-preview-2').addClass(text2); 
    });
    $('#icon-preview-text3').on('keyup', function(){
        var text3 = 'fa fa-' + $(this).val();
            $('#icon-preview-3').removeClass();
            $('#icon-preview-3').addClass(text3);
    });
    
    // Show update topbar when you scroll down and then update when clicked
    $('.landr-update-bar').appendTo($('body'));
    $('.landr-update-bar').click(function() {
       $('.elgg-button-submit').click();   
    });
    var top = $('.landr-right').offset().top - parseFloat($('.landr-right').css('marginTop').replace(/auto/, 500));

	$(window).scroll(function (event) {
		var y = $(this).scrollTop();
		if (y >= top) {
			$('.landr-update-bar').show(); 
		} else {
			$('.landr-update-bar').hide();
		}
	});
    
});
</script>

<script>
// Slider ajax uploader
function ajaxFileUpload() {
	$("#loading")
	.ajaxStart(function(){
		$(this).show();
	})
	.ajaxComplete(function(){
		$(this).hide();
	});

	$.ajaxFileUpload
	(
		{
			url:'<?php echo elgg_get_site_url(); ?>ajax/view/landr/upload', 
			secureuri:false,
			fileElementId:'fileToUpload',
			dataType: 'json',
			data:{name:'logan', id:'id'},
			success: function (data, status) {
                if (typeof(data.error) != 'undefined') {
					if (data.error == 'exist') {
						if(confirm("Image already exist, do you want to use it again?")) {
                            $('#slider_image').val(data.proxy);
                        } else { 
                            $('#upload-message').addClass('upload-error').show();
                            $('#upload-message').html('Nothing was uploaded'); 
                        }
					} else if (data.error == 'nothing') {
					    $('#upload-message').addClass('upload-error').show(); 
                        $('#upload-message').html('No image was selected');  
				    } else if (data.error == 'allowed') {
					    $('#upload-message').addClass('upload-error').show(); 
                        $('#upload-message').html('Sorry, only jpg, png & gif files are allowed');   
				    } else if (data.error == 'size') {
					    $('#upload-message').addClass('upload-error').show(); 
                        $('#upload-message').html('Sorry, the file limit size is 2mb (2048)');    
				    } else {
				        $('#upload-message').removeClass('upload-error').addClass('upload-success').show();
				        $('#upload-message').html('Success!'); 
				        $('#uploaded-image').attr('src', data.proxy);
				        $('#upload-image').show();
						$('#slider_image').val(data.proxy); 
					} 
					setTimeout(function() {
                        $('#upload-message').fadeOut('slow', function() {
                            $(this).hide();
                        });
                    }, 2000);
				} 
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)
	
	return false;

}
</script> 
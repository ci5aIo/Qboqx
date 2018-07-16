<?php
/**
 * Internal CSS so we can pause PHP variables for user customizations
 * 
 */

$primary_color = elgg_get_plugin_setting('primary_color', 'landr'); 
$secondary_color = elgg_get_plugin_setting('secondary_color', 'landr');
$heading_font_family = elgg_get_plugin_setting('heading_font_family', 'landr');
$general_font_family = elgg_get_plugin_setting('general_font_family', 'landr');
$general_text_color = elgg_get_plugin_setting('general_text_color', 'landr');
$menu_style = elgg_get_plugin_setting('menu_style', 'landr');  
$slider_image = elgg_get_plugin_setting('slider_image', 'landr');
$slider_tint = elgg_get_plugin_setting('slider_tint', 'landr');
$member_heading_font_weight = elgg_get_plugin_setting('member_heading_font_weight', 'landr');
$member_icon_border = elgg_get_plugin_setting('member_icon_border', 'landr');
$member_icon_color = elgg_get_plugin_setting('member_icon_color', 'landr');
 
if ($heading_font_family == 'opensans') {
    $heading_font_families = 'Open Sans';
} 
elseif ($heading_font_family == 'roboto') {
    $heading_font_families = 'Roboto';
} 
elseif ($heading_font_family == 'ubuntu') {
    $heading_font_families = 'Ubuntu';
} 
elseif ($heading_font_family == 'oswald') {
    $heading_font_families = 'Oswald';
} 
elseif ($heading_font_family == 'raleway') {
    $heading_font_families = 'Raleway';
} 
else {
    $heading_font_families = 'Lato'; 
}
 
?>

<style type="text/css"> 

<?php 
    if ($heading_font_family == 'opensans') {
        echo '@import url(http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800);';
    } 
    elseif ($heading_font_family == 'roboto') {
        echo '@import url(http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic);';
    } 
    elseif ($heading_font_family == 'ubuntu') {
        echo '@import url(http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic);';
    }
    elseif ($heading_font_family == 'oswald') {
        echo '@import url(http://fonts.googleapis.com/css?family=Oswald:400,700,300);';
    }
    elseif ($heading_font_family == 'raleway') {
        echo '@import url(http://fonts.googleapis.com/css?family=Raleway:400,900,800,700,600,500,300,200,100);';
    }
    else {
        echo '@import url(http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic);';
    }
?>

body {
    color: #<?php if ($general_text_color) { echo $general_text_color; } else { echo '333333'; } ?>;
    font-size: 90%;
    line-height: 1.4em;
    font-family: "<?php if ($general_font_family) { echo $general_font_family; } else { echo 'Helvetica Neue'; } ?>", Helvetica, "Lucida Grande", Arial, sans-serif;
    backface-visibility: visible;
} 

a {
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
}

a:focus {
    text-decoration: none;
}

a:hover {
    color: #555;
    text-decoration: none;
}

h1 {
    display: inline;
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: 400;
}

h2 {
    padding: 20px;
    color: #333;
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: 400;
}

#login-dropdown, .elgg-menu-site {
    display: none;
}

.landr-menu-horizontal .elgg-menu-site {
    display: inline;
}

.elgg-page-body {
    padding: 0;
}

.fixed {
    position: fixed;
    top: 0;
    z-index: 2;
    width: 100%;
    background: rgba(0,0,0,0.5) !important;
}

.fixed a, .fixed .landr-menu-horizontal .elgg-menu-site-default li a {
    color: white; 
}

.fixed i {
    color: white;
}

.elgg-system-messages {
    position: absolute;
    left: 0;
    right: 0;
    top: 90px;
    max-width: none;
}

.elgg-system-messages li {
    margin-top: 0;
    box-shadow: none;
    border-radius: 0;
    font-weight: 400;
    text-align: center;
    padding: 20px 0;
}

.elgg-state-success {
    background: #2ecc71;
}

.elgg-state-error {
    background: #c0392b;
}

.elgg-page-header {
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>; 
}

.elgg-heading-site, .elgg-heading-site:hover {
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    text-shadow: none;
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-style: normal;
    line-height: 90px;
    text-transform: uppercase;
    font-weight: 400;
    font-size: 1.5em; 
}

.elgg-heading-site:hover {
    color: white;
}

.elgg-page-default .elgg-page-body .elgg-inner {
    max-width: none;
}

.landr-slider {
    width: 100%;
    height: 600px;
    background: url(<?php if ($slider_image) { echo $slider_image; } else { echo elgg_get_site_url() . 'mod/landr/img/bg.jpg'; } ?>) fixed; 
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    position: relative;
    text-align: center;
    z-index: 1;
}

.landr-slider:before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,<?php if ($slider_tint) { echo $slider_tint; } else { echo '0.5'; } ?>); 
    -moz-transition: background .3s linear;
    -webkit-transition: background .3s linear;
    -o-transition: background .3s linear;
    transition: background .3s linear;
}

.landr-slider-content {
    position: absolute;
    top: 50%;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -o-transform: translateY(-50%);
    transform: translateY(-50%);
    width: 100%;
    color: white;
}

.landr-buttons a {
    color: white;
    padding: 15px 24px;
    border: 1px solid #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    border-radius: 3px;
    margin: 0 10px;
    transition: all 0.3s ease 0s;
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
}

.landr-buttons a:hover {
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    border: 1px solid #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    transition: all 0.3s ease 0s;
}

.landr-slider-content h1 {
    color: white;
    font-size: 36px;
} 

.landr-slider-content p {
    font-size: 18px;
    padding: 10px 0 20px;
    margin: 0 20% 20px; 
} 

.landr-login, .landr-register {
    text-align: left;
    max-width: 400px !important;
}

.landr-login .elgg-head {
    display: none;
}

.landr-login label, .elgg-menu-content.forgot_link, .landr-register label {
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: 400;
}
 
.elgg-button-submit {
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    border: 1px solid #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    padding: 10px;
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    text-shadow: none;
    text-transform: uppercase;
    transition: all 0.3s ease 0s;
}

.elgg-button-submit:hover {
    background: none;
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    border: 1px solid #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    transition: all 0.3s ease 0s;
}

.landr-menu, .landr-menu-horizontal {
    float: right;
}

.landr-menu a:focus, .landr-menu-horizontal a:focus {
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;  
}

.landr-menu i {
    padding: 0 30px;
    height: 90px;
    line-height: 90px;
}

.landr-menu i:hover {
    color: white;
}

.remodal-overlay {
    background: rgba(0,0,0,0.8);
}

.remodal {
    max-width: none;
    background: none;
    padding: 20px 0;
}

.remodal-close{
    display: none;
}

.remodal .elgg-menu-site-default {
    position: static;
    display: inline-block;
    width: 100%;
    height: 100%;
}

.landr-menu-horizontal .elgg-menu-site-default {
    position: static;
}
 
.remodal .elgg-menu-site-default li {
    float: none;
    margin-right: 0;
}

.landr-menu-horizontal .elgg-menu-site-default li a {
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: normal;
    text-transform: uppercase;
    line-height: 90px; 
    display: inline; 
    padding: 5px 10px;
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
}

.remodal .elgg-menu-site-default li a {
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: normal;
    padding: 20px 0;
    text-transform: uppercase;
    font-size: 36px;
    height: auto;
    color: white; 
}

.landr-menu-horizontal .elgg-menu-site-default li:hover a {
    border-radius: 3px;
    box-shadow: none;
    background: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
}

.remodal .elgg-menu-site-default li:hover a {
    border-radius: 0;
    box-shadow: none;
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
}

.landr-section { 
    width: 100%;
} 

.row1 {
    padding: 50px 0; 
    position: relative;
}

.row2 {
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    position: relative;
}

.landr-container {
    width: 990px;
    margin: 0 auto;
    overflow: auto;
}

.landr-main-color-bg {
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    width: 100%;
}

.landr-columns {
    width: 30.666%;
    float: left;
    text-align: center;
    margin-right: 4%;
}

.landr-columns:last-child {
    margin-right: 0;
}

.row1 i {
    font-size: 80px; 
    width: 200px;
    height: 200px;
    line-height: 200px;
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    border-radius: 50%;
}

.landr-columns p {
    color: #7f8c8d;
}

.landr-tb-padding {
    padding: 50px 0;
}

.row2 {
    text-align: center;
    padding: 50px 0;
}

.row2 h1 {
    color: white;
    text-transform: uppercase;
    background: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    width: 100%;
    padding: 25px 0;
    display: block;
    position: relative;
    top: -50px;
    font-weight: <?php if ($member_heading_font_weight) { echo $member_heading_font_weight; } else { echo '400'; } ?>;
}

.row2 h1:after {
    top: 100%;
    left: 50%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-color: rgba(0, 0, 0, 0);
    border-top-color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    border-width: 30px;
    margin-left: -30px;
}

.landr-member-img {
    display: inline-block;
    width: 250px;
    height: 250px;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    border: <?php if ($member_icon_border) { echo $member_icon_border; } else { echo '10'; } ?>px solid #<?php if ($member_icon_color) { echo $member_icon_color; } else { echo 'ffffff'; } ?>;
    border-radius: 50%;
}

.row2 h3 {
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: 400;
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
    padding: 20px 0 0;
}

.row2 .landr-container {
    overflow: auto;
}

.elgg-page-footer {
    padding: 0;
    background: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>;
}

.elgg-page-default .elgg-page-footer > .elgg-inner {
    border: 0;
    padding: 0;
}

.landr-footer {
    overflow: auto;
}

.landr-footer .landr-columns {
    text-align: left; 
}

.landr-footer .landr-container {
    overflow: visible;
}

.landr-footer h1 {
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: 400;
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    padding-bottom: 20px; 
    display: block;
    text-transform: uppercase;
}

.landr-footer .elgg-menu-site-default {
    position: static;
    display: inline-block;
    width: 100%;
    height: 100%;
}
 
.landr-footer .elgg-menu-site-default li {
    float: none;
    margin-right: 0;
}

.landr-footer .elgg-menu-site-default li a {
    font-family: '<?php if ($heading_font_families) { echo $heading_font_families; } else { echo 'Lato'; } ?>', sans-serif;
    font-weight: normal;
    padding: 10px 0;
    text-transform: uppercase;
    height: auto;
}

.landr-footer .elgg-menu-site-default li:hover a {
    border-radius: 0;
    box-shadow: none;
    background: none;
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
}

.landr-footer .elgg-menu-site-default li:before {
    content: '•';
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    float: left;
    padding: 10px 0;
    padding-right: 5px;
}

.elgg-list, .elgg-list > li {
    border: 0;
}

.elgg-subtext {
    color: white;
    font-style: normal;
}

.elgg-subtext a {
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
}

.landr-wires-img img {
    border-radius: 50%;
    border: 2px solid white; 
    margin-right: 10px;
    float: left;
}

.landr-wires-img {
    display: inline-block;
    padding-bottom: 10px;
    line-height: 40px;
    width: 100%;
}

.landr-wires-img span {
    font-size: 80%; 
    margin-left: 10px !important;
    color: #7f8c8d;
}

.landr-wires-img a {
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
}

.landr-wires-img a:hover { 
    color: white;
}

.landr-wires-content {
    padding-bottom: 20px;
    color: white;
    word-break: break-all;
}

.landr-wires-content:last-child {
    padding-bottom: 0; 
}

.landr-social {
    margin-top: 20px;
}

.landr-social i {
    font-size: 30px;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    margin-right: 10px;
    border: 1px solid #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>; 
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    border-radius: 50%;
}

.landr-social i:hover {
    background: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>; 
    border: 1px solid #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    color: #<?php if ($secondary_color) { echo $secondary_color; } else { echo '000000'; } ?>; 
    cursor: pointer; 
}

.landr-footer .landr-columns p {
    color: white;  
}

.landr-contact i.comments {
    background: none;
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>;
    font-size: 300px;
}

.landr-contact span {
    margin-left: 10px;
    color: white;
}

.landr-contact li {
    margin-bottom: 10px;
}

.landr-contact li i {
    width: 20px;
    height: 20px;
    line-height: 20px;
    text-align: center;
    font-size: 16px;
    color: #<?php if ($primary_color) { echo $primary_color; } else { echo 'f1c40f'; } ?>; 
}

.landr-contact a {
    color: white;
}

/* Responsively hide menu horizontal menu and then show mobile */
<?php if ($menu_style == 'mobile' || $menu_style == '') { 
    echo '.landr-menu-horizontal { display: none; }';
} ?>
<?php if ($menu_style == 'horizontal') { 
    echo '.landr-menu { display: none; }';
} ?>

@media screen and (max-width: 1024px) {
    .landr-menu {
        display: inline;
    }
    .landr-menu-horizontal {
        display: none;
    }
    .elgg-page-default {
        min-width: initial;  
    }
    .remodal .elgg-menu-site-default li a {
        font-size: 24px;
        padding: 10px 0;
    }
    .landr-slider {
        height: 300px;
    }
    .landr-slider-content h1 {
        font-size: 24px;
        padding: 0 20px;
    }
    .landr-slider-content p {
        font-size: 16px;
        margin: 0 10px 20px; 
    }
    .landr-login, .landr-register {
        padding: 20px !important;
    }
    .landr-container {
        width: auto;
    }
    .landr-columns {
        float: none;
        width: auto; 
        margin-right: 0;
        padding: 0 20px 20px;
    }
    .landr-columns:last-child {
        padding-bottom: 0;
    }
    .row2 h1 {
        line-height: 1em;
    }
    .landr-social {
        margin: 20px 0;
        text-align: center;
    }
    .remodal {
        min-height: initial; 
    }
    .landr-contact i.comments {
        font-size: 250px; 
    }
}

</style>
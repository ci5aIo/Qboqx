<?php
// let us grab the onwner icons or groups icons

$owner = elgg_get_page_owner_entity(); // depending on which view you are using this function in


if ($owner instanceof ElggEntity) {
 
// $icon = elgg_view("profile/icon",array('entity' => $owner,  'size' => 'medium', 'hover-menu' => false ,'user_hover' => false));
// echo $icon; 


$user_entity = elgg_extract('entity', $vars, $owner);
$icon_size = elgg_extract('size', $vars, 'medium');
if (!in_array($icon_size, array('topbar', 'tiny', 'small', 'medium', 'large', 'master'))) {
$icon_size = 'medium';
}

$icon_url = getOwnerIconUrl();

}

 


function getOwnerIconUrl() {
 
 
 $owner = elgg_get_page_owner_entity();
 
 if ($owner){
 $owner_icon_url = $owner->getIconUrl();
 
 
 return elgg_normalize_url($owner_icon_url);
 
 }
 }


 function getsmartphonecoverphotoIconUrl() {

	$size = elgg_strtolower($size);



$owner = elgg_get_page_owner_entity();



//$user_guid = $user->
$icon_time = $owner->coverphototime;  //TM:  coverphototime 



// Get the size
$size = strtolower(get_input('size'));

// 'babu'  // topbar
// 'nyanya' // tiny

// 'dada'  //  small

// 'kaka'  // Medium
// 'shangazi'// large

// 'mjomba' // large

if (!in_array($size, array('mjomba', 'shangazi', 'kaka', 'dada', 'nyanya', 'babu'))) {
$size = 'mjomba';   // Here we select the ouput image size
}

// If user exist, return default icon
if ($icon_time) {

	      $uploaded_url = "coveravatar/view/$owner->username/$size/$icon_time";
			
			
			
	       return elgg_normalize_url($uploaded_url);
		
		
		} else {
		
		   
	         $default_url = "mod/timeline_avatar/graphics/timelineicons/default/$size.jpg"; 
		   
		return elgg_normalize_url($default_url);
	
		
		}

		

}  // Tm. End of the smartphone icon
$user_coverphoto = getsmartphonecoverphotoIconUrl();


$user_avatar = elgg_view('output/img', array( 
//'src' => $vars['entity']->geticonURL('kaka'),
'src' => getsmartphonecoverphotoIconUrl(),
//'src' =>  'http://twiganex.com/coveravatar/view/gokubwa/nyanya/1405549352', 
//'src' =>  'http://twiganex.com/coveravatar/view/gokubwa/kaka/$icon_time',  
//'alt' => elgg_echo('timlineavatar'),
'class' => ' aGb hXa z3Hx4b Zindex',
));

 
 
 // Let us grab the verified user icon
   $verified_user_image_path = "mod/timeline_avatar/images/verified.png ";
   $pleaseloadverifiedimage =  $CONFIG->wwwroot . $verified_user_image_path ; // works well too
// $pleaseloadverifiedimage = elgg_get_site_url().$verified_user_image_path; // Work well
 
// echo $user_avatar; // works


?>
<style>

.Zindex {z-index: 1001;} /* Not shure if it works */

/**************************/


.YFb, .YFb:focus {
    border-radius: 3px;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    box-shadow: none;
    background-image: none;
    color: #FFF;
    cursor: pointer;
    font-size: 14px;
    margin-right: 0px;
        margin-right-value: 0px;
        margin-right-ltr-source: physical;
        margin-right-rtl-source: physical;
    outline: medium none;
        outline-width: medium;
        outline-style: none;
        outline-color: -moz-use-text-color;
    padding: 3px 8px;
        padding-top: 3px;
        padding-right-value: 8px;
        padding-bottom: 3px;
        padding-left-value: 8px;
        padding-left-ltr-source: physical;
        padding-left-rtl-source: physical;
        padding-right-ltr-source: physical;
        padding-right-rtl-source: physical;
    position: static;
    background-color: rgba(0, 0, 0, 0.7);
}
.b-c-R {
    box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
    background-color: #FFF;
    background-image: -moz-linear-gradient(center top , transparent, transparent);
    color: #404040;
    border: 1px solid rgba(0, 0, 0, 0.15);
        border-top-width: 1px;
        border-right-width-value: 1px;
        border-right-width-ltr-source: physical;
        border-right-width-rtl-source: physical;
        border-bottom-width: 1px;
        border-left-width-value: 1px;
        border-left-width-ltr-source: physical;
        border-left-width-rtl-source: physical;
        border-top-style: solid;
        border-right-style-value: solid;
        border-right-style-ltr-source: physical;
        border-right-style-rtl-source: physical;
        border-bottom-style: solid;
        border-left-style-value: solid;
        border-left-style-ltr-source: physical;
        border-left-style-rtl-source: physical;
        border-top-color: rgba(0, 0, 0, 0.15);
        border-right-color-value: rgba(0, 0, 0, 0.15);
        border-right-color-ltr-source: physical;
        border-right-color-rtl-source: physical;
        border-bottom-color: rgba(0, 0, 0, 0.15);
        border-left-color-value: rgba(0, 0, 0, 0.15);
        border-left-color-ltr-source: physical;
        border-left-color-rtl-source: physical;
        -moz-border-top-colors: none;
        -moz-border-right-colors: none;
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        border-image-source: none;
        border-image-slice: 100% 100% 100% 100%;
        border-image-width: 1 1 1 1;
        border-image-outset: 0 0 0 0;
        border-image-repeat: stretch stretch;
}
.b-c {
    border-radius: 2px;
    cursor: default;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    white-space: nowrap;
    margin-right: 16px;
        margin-right-value: 16px;
        margin-right-ltr-source: physical;
        margin-right-rtl-source: physical;
    height: 28px;
    line-height: 28px;
    min-width: 54px;
    outline: 0px none;
        outline-width: 0px;
        outline-style: none;
        outline-color: -moz-use-text-color;
    padding: 0px 8px;
        padding-top: 0px;
        padding-right-value: 8px;
        padding-bottom: 0px;
        padding-left-value: 8px;
        padding-left-ltr-source: physical;
        padding-left-rtl-source: physical;
        padding-right-ltr-source: physical;
        padding-right-rtl-source: physical;
}
.d-k-l {
    position: relative;
    display: inline-block;
}






.ega .ENc {
    height: 100%;
    margin: 0px auto;
        margin-top: 0px;
        margin-right-value: auto;
        margin-bottom: 0px;
        margin-left-value: auto;
        margin-left-ltr-source: physical;
        margin-left-rtl-source: physical;
        margin-right-ltr-source: physical;
        margin-right-rtl-source: physical;
    width: 0px;
}
.U9b {
    color: #FFF;
}
.he {
    white-space: normal;
}


.ega .ZFb {
    right: 5%;
    top: auto;
    bottom: 9%;
}
.ZFb {
    display: none;
    position: absolute;
    vertical-align: top;
}
/******************************************************/

/**********/


/******/


html {
}
/*
body {
color:#333;
font-family: 'Segoe UI', Tahoma, Segoe UI, Arial, sans-serif;
font-size:13px;
margin:0;
padding:0;
background: #e8ebed;
}

*/

img {  }
/*
h1 { font-size: 52px; margin: 0; padding: 5px 15px;}
h3 { font-size: 28px; margin: 0; }
h4 { font-size: 24px; margin: 10px 0 0 0; }

h1, h3, h4, h5 {
font-family: 'Segoe UI', Tahoma, Arial, Verdana, sans-serif;
font-weight: 300;
}

*/
a {
color:#333;
text-decoration: none;
}

a:hover {
color:#000;
text-decoration: underline;
}
code, pre {
word-wrap: break-word;
}
code.api-request {
background: #f2f2f2;
}
option {
font-weight: bold;
}
option:hover {
cursor: pointer;
}
table
{
border-collapse:collapse;
}
table, td, th
{
border: 1px solid #CCC;
padding: 3px;
}

img { border: 0 }

hr {
border:none;
color:white;
height:1px;
background:#ccc;
background: -webkit-gradient(radial, 50% 50%, 0, 50% 50%, 600, from(#ccc), to(#fff)); 
}

form { margin: 0; }

/* Selectie */
::selection {
background:#000;
color:#fff;
}
::-moz-selection {
background:#000;
color:#fff;
}
::-webkit-selection {
background:#000;
color:#fff;
}
input[type="submit"] {
font-family: Verdana, Tahoma, sans-serif;
color: #6b6b6b;
font-size: 12px;
background: #F3F3F3;
background: -webkit-gradient(linear,left top,left bottom,from(whiteSmoke),to(#F1F1F1));
background: -moz-linear-gradient(top,whiteSmoke,#F1F1F1);
background: -o-linear-gradient(top,whiteSmoke,#F1F1F1);
border: 1px solid #b1b1b1;
text-align: center;
width: auto;
padding: 5px 5px;
text-decoration: none;
margin: 0;
border-radius: 2px;
cursor: pointer;
}
input[type="submit"]:hover {
border-color: #a7a7a7;
color: #333;
box-shadow: 0px 1px 1px #ccc;
}
input[type="submit"]:active{
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#EEE), to(#E0E0E0));
border-color: #BBB;
box-shadow: rgb(204, 204, 204) 0px 1px 5px inset;
-webkit-box-shadow: rgb(204, 204, 204) 0px 1px 5px inset;
z-index: 2;
color: #000;
}
.row-top {
background: #fff;
padding: 20px 0 10px 0;
border-bottom: 1px solid #c3c3c3;
overflow: auto;
}
.row-page {
border-bottom: 0;
width: 100%;
margin: 0 auto;
max-width: 920px;
overflow: auto;
}
.row-body {
border-bottom: 0;
width: 100%;
margin: 0 auto;
max-width: 920px;
overflow: hidden;
}
.row-footer {
padding: 20px 0 10px 0;
overflow: auto;
}
.row-body .one { width: 8.333%; }
.row-body .two { width: 16.667%; }
.row-body .three { width: 25%; }
.row-body .four { width: 33.333%; }
.row-body .five { width: 41.667%; }
.row-body .six { width: 50%; }
.row-body .seven { width: 58.333%; }
.row-body .eight { width: 66.667%; }
.row-body .nine { width: 75%; }
.row-body .ten { width: 83.333%; }
.row-body .eleven { width: 91.667%; }
.row-body .twelve { width: 100%; }

.row-page .three { width: 25%; float: right; }
.row-page .nine { width: 75%; float: left; }
.row-page .stats-container { width: 25%; padding-top: 10px;}
.row-page .twelve { width: 100%; }
.row-page .table-id { width: 7%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-user { width: 33%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-user img { height: 15px; width:15px; border-radius: 3px; margin-right: 5px; vertical-align: middle; }
.row-page .table-mail { width: 45%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-edit { width: 8%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-delete { width: 7%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-report-id { width: 7%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-report-type { width: 13%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-user { width: 33%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-user img { height: 15px; width:15px; border-radius: 3px; margin-right: 5px; vertical-align: middle; }
.row-page .table-report-message { width: 20%; padding: 5px 0; word-wrap: break-word; }
.row-page .table-report-safe { width: 10%; padding: 5px 0; word-wrap: break-word;}
.admin-rows { width: 100%; float: left; }
.admin-rows a { cursor: pointer }

.row-top .threeads {
width: 33.3333%;
}
.row-one {
float: left;
width: 15%;
}
.row-two {
float: left;
width: 10%;
}
.row-three {
float: left;
width: 60%;
word-break:break-all;
}
.row-four {
float: left;
width: 15%;
text-align: right;
}
.row-welcome {
border-bottom: 0;
width: 100%;
margin: 0 auto;
overflow: auto;
margin-top: -20px;
background: url('images/background.png');
background-position: 50% 50%;
background-size: cover;
border-bottom: 1px solid #1e346a;
}

.row {
width: 100%;
margin: 0 auto;
max-width: 920px;
overflow: auto;
}
.row .one { width: 8.333%; }
.row .two { width: 16.667%; }
.row .three { width: 25%; }
.row .four { width: 33.333%; }
.row .five { width: 41.667%; }
.row .six { width: 50%; }
.row .seven { width: 58.333%; }
.row .eight { width: 66.667%; }
.row .nine { width: 75%; }
.row .ten { width: 83.333%; }
.row .eleven { width: 91.667%; }
.row .twelve { width: 100%; }

.column, .columns {
float: left;
min-height: 1px;
position: relative;
}
[class*="column"] + [class*="column"]:last-child { float: right; }

[class*="column"] + [class*="column"].end { float: left; }

.header {
margin: 0 auto;
max-width: 900px;
padding: 0 10px;
}
.topbar {
position: fixed;
top: 0;
right: 0;
left: 0;
z-index: 1000;
height: 45px;
background: #4c66a4 url(<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/header.png) repeat-x;
margin-bottom: 40px;
border-bottom: 1px solid #1e346a;
}
.topbar_margin {
width: 100%;
height: 42px;
}



.menu {
float: right;
height: 45px;
font-size: 12px;
color: #fff;
font-family: 'Segoe UI', 'wf_SegoeUILight', 'Segoe UI', 'Segoe UI Light', 'Segoe WP Light', 'Tahoma', Arial, Verdana, sans-serif;
padding: 0 10px;
}
.menu:hover {
background: rgba(0, 0, 0, .12);
}
.menu:active {
background: rgba(0, 0, 0, .35);
}
.menu_hover_messages, .menu_hover_notifications {
background: rgba(0, 0, 0, .12);
}
.menu_name {
float: right;
margin-top: 15px;
padding-right: 10px;
color: #fff;
}
.menu_visitor {
float: right;
margin-top: 15px;
padding-right: 10px;
color: #fff;
}
.menu_img {
float: right;
width: 26px;
height: 26px;
margin-top: 9px;
}
.menu_img img {
width: 26px;
height: 26px;
border-radius: 3px;
}
.menu_btn {
padding: 0 6px;
float: right;
height: 45px;
}
.menu_btn img {
margin-top: 14px;
width: 16px;
height: 16px;
}
.menu_btn:hover {
background: rgba(0, 0, 0, .12);
}
.menu_btn:active {
background: rgba(0, 0, 0, .35);
}
.search-input {
float: left;
}
.search-input input {
border-radius: 3px;
outline: none;
border: 1px solid #3B4E7C;
padding: 5px;
margin-top: 8px;
background: #F6F6F6;
width: 185px;
}
.search-input input:hover, .search-input input:active {
background: #FFF;
}
.search-container, .search-list-container {
max-width: 920px;
width: 100%;
display: none;
top: 46px;
margin: 0 auto;
}
.search-container a:hover {
text-decoration: underline;
cursor: pointer;
}
.search-content {
padding: 0 10px;
z-index: 999;
top: 46px;
}
.search-results {
background: #FFF;
border-bottom-left-radius: 3px;
border-bottom-right-radius: 3px;
border: 1px solid #BFBFBF;
width: 100%;
float: left;
margin-top: 1px;
}
.search-results .subscribe_btn, .search-results .edit_profile_btn, .search-results .sub-loading, .search-results .message_btn {
position: inherit;
}
.search-results .message-inner {
border-top: 1px solid #dee0e3;
}
.hashtag {
border-top: 1px solid #dee0e3;
overflow: auto;
}
.hashtag-inner {
padding: 10px;
}
.hashtag a {
cursor: pointer;
float: left;
width: 100%;
overflow: auto;
}
.hashtag a:hover {
background: #f2f2f2;
text-decoration: none;
}
.content {
background: #FFF;
overflow: auto;
border-left: 1px solid #CCC;
border-right: 1px solid #CCC;
}
.cover-container {
padding: 0 1px 1px 1px;
}
.cover-content {
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
background: #fff;
color: #6B6B6B;
border-radius: 3px;
overflow: auto;
}
.cover-image {
float: left;
top: 0;
padding-top: 288px; /* TM: Adjust the cover-image height   */
width: 100%;
cursor: pointer;
}
.cover-image img {
width: 100%;
}
.cover-description {
width: 100%;
padding-bottom: 10px;
float: left;
overflow: auto;
min-height: 35px;
}
.cover-description-content {
margin-left: 126px;
padding-bottom: 5px;
margin-right: 10px;
}
.cover-avatar-content {
width: 106px;
margin: 0 0 10px 10px;
overflow: auto;
}
.cover-avatar {
float: left;
background: #fff;
border-radius: 50%;
padding: 3px;
position: absolute;
margin-top: -70px;
box-shadow: 0px 1px 1px #CCC;
width: 106px;
cursor: pointer;
}
.cover-avatar a {
overflow: hidden;
 height: 106px;
float: left;
}
.cover-avatar img {
border-radius: 50%;
width: 100%;
margin-bottom: -3px;
}
.cover-username {
float: left;
font-size: 18px;
margin-right: 60px;
}
.cover-username a {
color: #6B6B6B;
}
.cover-username a:hover {
color: #333;
}
.cover-username img {
margin: 0 0 -2px 7px;
width: 17px;
height: 17px;
}
.message-container {
padding: 0 20px 10px 10px;
}
.message-container a {
color: #3C5C9E;
}
.message-content {
font-family: 'Segoe UI', Tahoma, Verdana, Arial, sans-serif;
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
background: #fff;
color: #6B6B6B;
border-radius: 3px;
overflow: auto;
}
.message-header {
background: #f8f8f8;
padding: 10px;
font-family: Tahoma, Verdana, Arial, sans-serif;
font-size: 13px;
font-weight: bold;
color: #777;
border-bottom: 1px solid #EEE;
}
.message-inner {
padding: 10px;
}
.message-divider {
height:1px;
width: 100%;
background: #dee0e3;
float: left;
}
.message-actions {
border-bottom: 1px solid #EEE;
float: left;
width: 100%;
overflow: auto;
}
.message-actions-content {
padding: 5px 10px;
}
.message-actions-content a {
color: #666;
}
.message-actions-content a:hover {
color: #333;
}
.message-actions a {
cursor: pointer;
}
.message-avatar {
width: 42px;
float: left;
overflow:auto;
}
.message-avatar img {
border-radius: 3px;
width: 42px;
}
.message-author {

}
.message-author a {
color: #3A5796;
cursor: pointer;
font-weight: bold;
font-family: Tahoma, Arial, 'Segoe UI', Verdana, sans-serif;
}
.message-message {
padding-top: 5px;
word-wrap: break-word;
}
.message-top {
padding: 5px 0 5px 52px;
}
.message-time {
overflow: auto;
}
.message-replies {
background: #F9F9F9;
width: 100%;
float: left;
overflow: auto;
}
.message-type-map {
float: left;
}
.message-type-map img, .message-type-map iframe {
width: 100%;
float: left;
}
.message-type-image {
float: left;
width: 100%;
cursor: pointer;
}
.message-type-image img {
width: 100%;
float: left;
}
.image-container-padding {
padding: 10px 0 0 10px;
overflow: auto;
}
.image-thumbnail-container {
float: left;
width: 33.3%;
}
.image-thumbnail {
padding: 0 10px 10px 0;
overflow: auto;
}
.image-thumbnail img {
cursor: pointer;
}
.message-type-player {
float: left;
width: 100%;
}
.message-type-player iframe {
float: left;
}
.message-type-general {
color: #898f9c;
font-family: Georgia, serif;
font-style: italic;
font-size: 14px;
border-left: 3px solid #898f9c;
padding: 7px;
line-height: 22px;
}
.message-type-general img {
vertical-align: bottom;
margin-right: 5px;
margin-left: 3px;
width: 24px;
height: 24px;
}
.message-reply-container {
width: 100%;
margin-top: 7px;
overflow: auto;
border-bottom: 1px solid #EEE;
}
.message-reply-author a {
color: #3A5796;
cursor: pointer;
font-weight: bold;
font-family: Tahoma, Arial, 'Segoe UI', Verdana, sans-serif;
}
.message-reply-message {
padding: 0 15px 5px 42px;
word-wrap: break-word;
}
.message-comment-box-container {
padding: 10px;
background: #F9F9F9;
overflow: auto;
}
.message-comment-box-form {
padding: 0 0 5px 42px;
}
.comment-reply-textarea {
padding: 5px;
font-family: 'Segoe UI', Tahoma, Arial, Verdana, sans-serif;
color: #6B6B6B;
font-size: 13px;
overflow: hidden;
width: 100%;
resize: none;
box-sizing: border-box;
-moz-box-sizing: border-box;
-webkit-box-sizing: border-box;
border: 1px solid #ccc;
max-width: 100%;
outline: 0;
min-height: 30px;
border-radius: 0px;
height: 25px;
}
.comment-reply-textarea:focus {
border: 1px solid #AAA;
}
.message-reported {
padding-bottom: 5px;
}
.blocked-button {
float: right;
font-weight: normal;
}
.blocked-button a {
cursor: pointer;
color: #999;
text-decoration: none;
}
.blocked-button a:hover {
color: #666;
text-decoration: underline;
}
.unblock-button a {
color: #666;
}
.unblock-button a:hover {
color: #000;
}
.like_btn {
width: auto;
padding-left: 17px;
height: 17px;
float: right;
background: url('images/icons/like_n.png');
background-size: 17px;
background-repeat: no-repeat;
}
.like_btn img {
vertical-align: middle;
width: 16px;
height: 16px;
margin-top: -4px;
}
.like_btn_extended {
float: none;
display: inline;
margin-right: 6px;
}
.like_text_snippet {
color: #999;
font-style: italic;
margin-top: 2px;
}
.comment-btn {
float: right;
display: none;
}
.comment-btn a {
float: right;
background: #4e69a8 url('images/comment_btn.png') repeat-x;
border-radius: 3px;
padding: 5px;
color: #fff;
cursor: pointer;
text-decoration: none;
border: 1px solid;
border-color: #475d91 #3c5389 #3a5186;
text-shadow: 0 -1px 0 rgba(0, 0, 0, .2);
}
.comment-btn a:hover {
border-color: #3c5389 #324675 #304473;
background: #4e69a8 url('images/comment_btn_h.png') repeat-x;
}
.comment-btn a:active {
background: #4e69a8;
box-shadow: #40568a 0px 1px 5px inset;
}
.delete_btn {
width: 17px;
height: 17px;
float: right;
background: url('images/delete.png');
background-size: 17px;
cursor: pointer;
}
.delete_btn:hover {
background: url('images/delete_h.png');
background-size: 17px;
}
.delete_btn:active {
background: url('images/delete_a.png');
background-size: 17px;
}
.report_btn {
width: 17px;
height: 17px;
float: right;
background: url('images/report.png');
background-size: 17px;
cursor: pointer;
}
.report_btn:hover {
background: url('images/report_h.png');
background-size: 17px;
}
.report_btn:active {
background: url('images/report_a.png');
background-size: 17px;
}
.private_btn {
width: 17px;
height: 17px;
float: right;
background: url('images/private.png');
background-size: 17px;
cursor: pointer;
}
.private_btn:hover {
background: url('images/private_h.png');
background-size: 17px;
}
.private_btn:active {
background: url('images/private_a.png');
background-size: 17px;
}
.public_btn {
width: 17px;
height: 17px;
float: right;
background: url('images/public.png');
background-size: 17px;
cursor: pointer;
}
.public_btn:hover {
background: url('images/public_h.png');
background-size: 17px;
}
.public_btn:active {
background: url('images/public_a.png');
background-size: 17px;
}
.edit_btn {
width: 17px;
height: 17px;
float: right;
background: url('images/edit.png');
background-size: 17px;
cursor: pointer;
}
.edit_btn:hover {
background: url('images/edit_h.png');
background-size: 17px;
}
.edit_btn:active {
background: url('images/edit_a.png');
background-size: 17px;
}
.download_btn {
width: 17px;
height: 17px;
float: right;
background: url('images/download.png');
background-size: 17px;
cursor: pointer;
}
.download_btn:hover {
background: url('images/download_h.png');
background-size: 17px;
}
.download_btn:active {
background: url('images/download_a.png');
background-size: 17px;
}
.subscribe_btn {
border: 1px solid #BFBFBF;
background-color: #fff;
background-image: url(images/subscribe.png);
background-size: 25px;
background-position: center;
color: #6B6B6B;
border-radius: 3px;
width: 25px;
height: 25px;
float: right;
cursor: pointer;
margin-left: 5px;
}
.subscribe_btn:hover {
box-shadow: 0px 1px 1px #CCC;
background-image: url(images/subscribe_h.png);
}
.subscribe_btn:active, .subscribe_btn:focus {
background-image: url(images/subscribe_h.png);
box-shadow: #CCC 0px 1px 5px inset;
border-color: #AAA;
}
.unsubscribe {
background-image: url(images/unsubscribe.png);
}
.unsubscribe:hover, .unsubscribe:active {
background-image: url(images/unsubscribe_h.png);
}
.sub-loading {
border: 1px solid #BFBFBF;
background-color: #fff;
background-image: url(images/privacy.gif);
background-size: 20px;
background-position: center;
background-repeat: no-repeat;
color: #6B6B6B;
border-radius: 3px;
width: 25px;
height: 25px;
float: right;
cursor: pointer;
margin-left: 5px;
}
.edit_profile_btn {
border: 1px solid #BFBFBF;
background-color: #fff;
background-image: url(images/profile_edit.png);
background-size: 25px;
background-position: center;
color: #6B6B6B;
border-radius: 3px;
width: 25px;
height: 25px;
float: right;
cursor: pointer;
margin-left: 5px;
}
.edit_profile_btn:hover {
box-shadow: 0px 1px 1px #CCC;
background-image: url(images/profile_edit_h.png);
}
.edit_profile_btn:active, .edit_profile_btn:focus {
background-image: url(images/profile_edit_h.png);
box-shadow: #CCC 0px 1px 5px inset;
border-color: #AAA;
}
.message_btn {
border: 1px solid #BFBFBF;
background-color: #fff;
background-image: url(images/message_btn.png);
background-size: 25px;
background-position: center;
color: #6B6B6B;
border-radius: 3px;
width: 25px;
height: 25px;
float: right;
cursor: pointer;
}
.message_btn:hover {
box-shadow: 0px 1px 1px #CCC;
background-image: url(images/message_btn_h.png);
}
.message_btn:active, .message_btn:focus {
background-image: url(images/message_btn_h.png);
box-shadow: #CCC 0px 1px 5px inset;
border-color: #AAA;
}
.privacy_loader {
background: url('images/privacy.gif') no-repeat;
background-size: 15px;
float: right;
width: 15px;
height: 15px;
margin-top: 1px;
}
.load-more-comments, .load-more-chat {
border-bottom: 1px solid #eee;
padding: 5px 0;
text-align: center;
}
.load-more-comments a, .load-more-chat a {
color: #999;
text-decoration: none;
cursor: pointer;
}
.load-more-comments a:hover, .load-more-chat a:hover {
color: #666;
}
.load-more-comments img, .load-more-chat img {
width: 85px;
}
.last-comment-wrong {
border-bottom: 1px solid #eee;
padding-bottom: 5px;
}
.comments_preloader {
width: 100%;
}
.delete_preloader img, .comments_preloader img {
width: 85px;
}
.message-reply-avatar {
width: 32px;
float: left;
overflow:auto;
}
.message-reply-avatar img {
border-radius: 3px;
width: 32px;
height: 32px;
}
.message-replies-content {
overflow: auto;
padding: 0 10px 0 10px;
}
.load_more {
text-align: center;
padding: 3px;
height: 13px; /* The height of preloader-retina-large */
}
.load_more a {
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
background: #fff;
color: #6B6B6B;
border-radius: 3px;
overflow: auto;
padding: 5px 10px;
cursor: pointer;
text-decoration: none;
}
.load_more a:hover {
border: 1px solid #AAA;
color: #888;
}
.load_more a:active {
box-shadow: #CCC 0px 1px 3px inset;
}
.load_more img {
width: 85px;
}
.new-message {
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
background: #fff;
color: #6B6B6B;
border-radius: 3px;
overflow: auto;
padding: 5px 10px;
cursor: pointer;
text-decoration: none;
text-align: center;
}
.new-message:hover {
border: 1px solid #AAA;
color: #888;
}
.new-message:active {
box-shadow: #CCC 0px 1px 3px inset;
}
.new-message-url a {
color: #898F9C;
text-decoration: none;
}
.new-message-url a:hover {
color: #666;
}
.timeago, .timeago-standard {
color: #999;
float: left;
}
.timeago:hover, .timeago-standard:hover {
text-decoration: underline;
color: #666
}
.verified-small img {
width: 13px;
height: 13px;
margin: 0 0 -2px 3px;
}
.sidebar-container {
padding: 0 10px 10px 0;
}
.sidebar-content {
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
background: #fff;
color: #6B6B6B;
border-radius: 3px;
overflow: hidden;
}
.sidebar-header {
background: #f8f8f8;
padding: 10px;
font-family: Tahoma, Verdana, Arial, sans-serif;
font-size: 13px;
font-weight: bold;
color: #777;
border-bottom: 1px solid #EEE;
}
.sidebar-header a {
color: #777;
}
.sidebar-header a:hover {
color: #222;
}
.sidebar-header-light {
font-weight: normal;
}
.sidebar-inner {
padding: 10px;
}
.sidebar-subscriptions {
width: 50%;
float: left;
}
.sidebar-subscriptions img {
width: 100%;
margin-bottom: -4px;
}
.sidebar-title-container {
color: #FFF;
display: block;
position: absolute;
width: 50%;
word-wrap: break-word;
}
.sidebar-title-container a {
text-decoration: none;
color: #FFF;
}
.sidebar-title-name {
padding: 5px;
font-size: 11px;
font-family: Tahoma, Verdana, Arial, sans-serif;
}
.sidebar-places-name {
padding: 5px;
font-size: 11px;
font-family: Tahoma, Verdana, Arial, sans-serif;
color: #222;
}
.sidebar-link img {
vertical-align: middle;
margin-right: 7px;
height: 24px;
width: 24px;
}
.sidebar-link a {
margin-top: -1px;
padding: 5px 10px 5px 10px;
border-top: 1px solid #EEE;
display: block;
text-decoration: none;
color: #898F9C;
height: 23px;
}
.sidebar-link a:hover {
background: #f7f7f7;
color: #666;
}
.sidebar-users {
cursor: pointer;
}
.sidebar-users img {
vertical-align: middle;
margin-right: 7px;
}
img.sidebar-status-icon {
height: 25px;
width: 12px;
}
.sidebar-users a {
margin-top: -1px;
padding: 5px 10px 5px 10px;
border-top: 1px solid #EEE;
display: block;
text-decoration: none;
color: #898F9C;
}
.sidebar-users a:hover {
background: #f7f7f7;
color: #666;
}
.sidebar-chat-list {
max-height: 456px;
overflow: auto;
}
.sidebar-header input {
outline: 0;
border: 1px solid #f8f8f8;
background: #f8f8f8 url('images/icons/search.png') no-repeat top right;
background-size: 12px;
width: 100%;
margin: 0;
padding: 0;
}
.sidebar-divider {
height:1px;
width: 100%;
background: #dee0e3;
float: left;
}
.sidebar-list {
margin-top: -1px;
padding: 7px 10px;
border-top: 1px solid #EEE;
font-size: 12px;
}
.sidebar-list img {
width: 20px;
height: 20px;
margin-left: 3px;
vertical-align: middle;
}
.sidebar-list a {
color: #6B6B6B;
}
.sidebar-avatar {
width: 50px;
float: left;
}
.sidebar-avatar img {
border-radius: 3px;
}
.sidebar-avatar-desc {
height: 42px;
margin-left: 60px;
padding-top: 8px;
font-size: 12px;
}
.sidebar-avatar-edit a {
font-size: 12px;
color: #666;
font-weight: normal;
}
.message-form-content {
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
background: #fff;
color: #6B6B6B;
border-radius: 3px;
overflow: auto;
}
.message-form-header, .page-header {
background: #f8f8f8;
padding: 10px;
font-family: Tahoma, Arial, Verdana, sans-serif;
font-size: 13px;
font-weight: bold;
color: #777;
border-bottom: 1px solid #EEE;
}
.message-form-user img, .page-header img {
width: 18px;
height: 18px;
margin-right: 10px;
border-radius: 2px;
float: left;
}
.message-form-private {
float: right;
}
.message-form-inner {
padding: 10px;
border-bottom: 1px solid #EEE;
}
.message-form-inner textarea {
border: 0;
outline: 0;
font-family: Tahoma, Arial, Verdana, sans-serif;
width: 100%;
resize: none;
height: 38px;
min-height: 38px;
max-width: 100%;
overflow: hidden;
}
.message-form-input {
border-bottom: 1px solid #EEE;
padding: 5px 10px;
display: none;
}
.message-form-input input {
border: 0;
outline: 0;
width: 100%;
}
.selected-files {
border-bottom: 1px solid #EEE;
padding: 5px 10px;
display: none;
color: #a9a9a9;
}
.message-btn {
float: right;
padding: 6px 10px 7px 0;
}
.message-btn a {
float: right;
background: #4e69a8 url('images/comment_btn.png') repeat-x;
border-radius: 3px;
padding: 4px 5px;
color: #fff;
cursor: pointer;
text-decoration: none;
border: 1px solid;
border-color: #475d91 #3c5389 #3a5186;
text-shadow: 0 -1px 0 rgba(0, 0, 0, .2);
}
.message-btn a:hover {
border-color: #3c5389 #324675 #304473;
background: #4e69a8 url('images/comment_btn_h.png') repeat-x;
}
.message-btn a:active {
background: #4e69a8;
box-shadow: #40568a 0px 1px 5px inset;
}
.message-loader {
float: right;
}
.chat-container {
height: 410px;
padding: 0 10px;
overflow: auto;
}
.chat-form-inner {
padding: 10px;
border-bottom: 1px solid #EEE;
}
.chat-form-inner input {
border: 0;
outline: 0;
font-family: 'Segoe UI', Tahoma, Arial, Verdana, sans-serif;
width: 100%;
resize: none;
height: 25px;
min-height: 25px;
max-width: 100%;
overflow: hidden;
}
.chat-error {
padding: 10px 0;
}
textarea {
width: 100%;
max-width: 500px;
height:100px;
}



/* ELGG COVER PHOTO LOGO    TOPBAR LOGO  */


/*
.logo {
    background: url(<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo.png) no-repeat scroll 0% 0% transparent;
        background-color: transparent;
        background-image: url(<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo.png);
        background-repeat: no-repeat;
        background-attachment: scroll;
        background-position: 0% 0%;
        background-clip: border-box;
        background-origin: padding-box;
        background-size: auto auto;
    height: 45px;
    width: 185px;
    float: left;
    margin-right: 10px;
        margin-right-value: 10px;
        margin-right-ltr-source: physical;
        margin-right-rtl-source: physical;
}

.logo-small {
    display: block;
}
.logo-small {
    height: 45px;
    width: 45px;
    float: left;
    display: none;
    margin-right: 5px;
        margin-right-value: 5px;
        margin-right-ltr-source: physical;
        margin-right-rtl-source: physical;
    background: url(<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo_small.png) no-repeat scroll 0% 0% / 45px auto transparent;
        background-color: transparent;
        background-image: url(<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo_small.png);
        background-repeat: no-repeat;
        background-attachment: scroll;
        background-position: 0% 0%;
        background-clip: border-box;
        background-origin: padding-box;
        background-size: 45px auto;
}

*/
.logo {
height: 45px;
width: 215px;
float: left;
margin-right: 10px;
background: url('<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo.png') no-repeat;
}
.logo-small {
height: 45px;
width: 45px;
float: left;
background: url('<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo_small.png') no-repeat;
background-size: 45px;
display: none;
margin-right: 5px;
}
.logo-small:hover {
background: rgba(0, 0, 0, .12) url('<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo_small.png') no-repeat;
background-size: 45px;
}
.logo-small:active {
background: rgba(0, 0, 0, .35) url('<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/logo_small.png') no-repeat;
background-size: 45px;
}
.despartitor {
height: 1px;
width: 100%;
background: #DADADA;
float: left;
margin: 10px 0;
}
.info {
border: 1px solid #38A8FF;
background: #BEDBFF;
border-radius: 3px;
color: #2A4E9B;
width: 91%;
padding: 5px 7px;
}
.page-settings-container {
padding: 0 20px 15px 10px;
}
.page-login-container {
padding: 0 10px 15px 10px;
}
.page-container {
padding: 0 10px 15px 10px;
}
.page-content {
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
background: #fff;
color: #6B6B6B;
border-radius: 3px;
overflow: auto;
}
.page-inner {
padding: 10px;
overflow: auto;
}
.page-header {
font-weight: normal;
}
.page-header a {

}
.page-input-container {
width: 100%;
float: left;
padding: 10px 0;
}
.page-input-container input, .page-input-container select, .page-input-container textarea {
padding: 5px;
border: 1px solid #ccc;
border-radius: 2px;
outline: none;
margin: 0;
}
.page-input-container input:hover, .page-input-container input:focus, .page-input-container select:hover, .page-input-container select:focus, .page-input-container textarea:hover, .page-input-container textarea:focus {
border: 1px solid #888;
}
.page-input-container input[type="submit"] {
margin-top: 0;
}
.page-input-container input[type="file"] {
border: 1px solid #ccc;
border-radius: 3px;
padding: 5px;
}
.page-input-container input[type="file"]:hover {
border: 1px solid #AAA;
}
.page-input-content {
float: left;
}
.page-input-title, .page-input-title-img {
text-align: right;
width: 75px;
margin-right: 10px;
margin-top: 4px;
display: inline-block;
vertical-align: top;
float: left;
color: #444;
}
.page-input-title-img {
margin-right: 20px;
}
.page-input-title-img img {
border-radius: 3px;
margin-top: 5px;
}
.page-input-sub {
color: #666;
font-size: 11px;
padding-top: 3px;
width: 350px;
}
.page-title {
font-size: 18px;
}
.page-input-title-img-wall {
width: 100%;
}
.page-input-title-img-wall img {
width: 100%;
}
.stats-boxes-container {
overflow: auto;
width: 100%;
padding-bottom: 10px;
}
.backgrounds-container img {
border-radius: 3px;
margin: 2px
}
.backgrounds-container img:hover {
border-radius: 3px;
margin: 0;
border: 2px solid #888;
}
.container-short {
overflow: auto;
}
.container {
padding: 15px 0;
overflow: auto;
width: 100%;
float: left;
}
.chart-container {
padding-left: 15px;
}
.chart img {
width: 100%;
}
.preloader {
padding: 10px 0;
width: 36px;
height: 36px;
margin: 0 auto;
}
.preloader-normal {
width: 110px;
height: 10px;
background: url('images/preloader.gif');
}
.preloader-retina {
width: 85px;
height: 8px;
background: url('images/preloader_small.gif');
background-size: 85px 8px;
}
.preloader-retina-large {
width: 110px;
height: 10px;
background: url('images/preloader_small.gif');
background-size: 110px 10px;
}
.preloader-center {
margin: 0 auto;
}
.preloader-left {
display: inline-block;
}
.retrieving-results {
display: inline;
}
.footer {
padding: 10px 10px 0 10px;
}
.footer-container {
border-top: 1px solid #ccc;
font-size: 13px;
font-family: Tahoma, Verdana, Arial, sans-serif;
color: #7E7E7E;
padding: 10px 0;
}
.footer-links {
padding-bottom: 5px;
}
.footer-links a {
color: #375490;
}
.footer-languages {
padding-bottom: 5px;
}
.footer-languages a {
color: #375490;
}
.pages-content {
padding: 20px;
}
.text-inputs {
padding: 3px 0;
}
.ads1 {
margin-top: 10px;
width: 100%;
height: auto;
float: left;
}
.info-message {
width: 100%;
text-align: center;
padding: 20px 0;
font-size: 16px;
}
.error {
font-family: Verdana, Tahoma, Arial, sans-serif;
padding: 5px;
border: 1px solid #cc0000;
background: #ffc0cf;
color: #b40a34;
border-radius: 3px;
margin: 0 0 10px 0;
}
.theme-icon {
width: 60px;
float: left;
overflow: auto;
}
.theme-icon img {
border-radius: 3px;
width: 60px;
}
.theme-top {
padding: 5px 0 5px 67px;
}
.theme-active {
font-family: Tahoma, Arial, 'Segoe UI', Verdana, sans-serif;
color: #3e7b1a;
font-weight: bold;
}
.theme-activate a {
color: #999;
font-weight: bold;
}
.theme-activate a:hover {
color: #333;
}
.pages-content textarea {
outline: 0;
padding: 5px;
width: 178px;
height: 50px;
font-family: Arial, 'Segoe UI', Tahoma, Verdana, sans-serif;
border: 1px solid #CCC;
}
.pages-content textarea:focus, .pages-content textarea:active, .pages-content textarea:hover {
border: 1px solid #666;
}
.pages-content input {
outline: 0;
padding: 5px;
width: 178px;
font-family: Arial, 'Segoe UI', Tahoma, Verdana, sans-serif;
border: 1px solid #CCC;
}
.pages-content input:focus, .pages-content input:active, .pages-content input:hover {
border: 1px solid #666;
}
.pages-content input[type="submit"] {
padding: 5px;
width: auto;
font-family: Arial, 'Segoe UI', Tahoma, Verdana, sans-serif;
}
.forgot-password a {
color: rgba(255, 255, 255, .4);
}
.forgot-password a:hover {
color: rgba(255, 255, 255, .8);
}
.welcome-captcha img {
vertical-align: bottom;
border-radius: 5px;
margin-right: 5px;
}
.welcome-full {
width: 100%;
}
.welcome-inner {
padding: 10px;
overflow: auto;
}
.welcome-user {
width: 70px;
height: 70px;
float: left;
border: 5px solid #fff;
border-radius: 5px;
margin-right: 11px;
box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
margin-top: 5px;
}
.welcome-user:last-child {
margin-right: 0;
}
.welcome-user a {
float: left;
height: 70px;
overflow: hidden;
}
.welcome-user img {
width: 70px;
height: 70px;
}
.welcome-message {
float: left;
width: 50%;
color: #fff;
font-family: "wf_SegoeUILight","wf_SegoeUI","Segoe UI Light","Segoe WP Light","Segoe UI","Segoe","Segoe WP","Tahoma","Verdana","Arial","sans-serif";
}
.welcome-title {
font-weight: lighter;
font-size: 60px;
margin-top: 30px;
}
.welcome-desc {
font-weight: lighter;
font-size: 24px;
}
.welcome-about {
font-weight: lighter;
font-size: 24px;
margin-top: 100px;
}
.welcome-inputs {
width: 50%;
float: right;
}
.welcome-inputs form {
padding: 10px 0;
width: 100%;
max-width: 300px;
overflow: auto;
float: right;
}
.welcome-inputs input {
width: 100%;
padding: 10px;
margin-bottom: 10px;
font-size: 14px;
background: rgba(0, 0, 0, .2);
color: #FFF;
border: none;
outline: 0;
border-radius: 5px;
-moz-box-shadow: 0px 1px 1px rgba(255, 255, 255, 0.1);
-webkit-box-shadow: 0px 1px 1px rgba(255, 255, 255, 0.1);
box-shadow: 0px 1px 1px rgba(255, 255, 255, 0.1);
-moz-box-sizing: border-box;
-webkit-box-sizing: border-box;
box-sizing: border-box;
}
.welcome-inputs input:focus {
background: rgba(0, 0, 0, .4);
}
.welcome-inputs input[type="checkbox"] {

}
.register-button {
font-family: 'Segoe UI', Arial, Tahoma, sans-serif;
margin: 0 10px 0 0;
color: #FFF;
border-radius: 5px;
padding: 10px;
text-transform: uppercase;
background: #58AA16;
border: 0;
outline: 0;
cursor: pointer;
height: 37px;
}
.register-button:hover {
background: #6abc20;
}
.register-button:active {
background: #539518;
}
.login-button {
font-family: 'Segoe UI', Arial, Tahoma, sans-serif;
margin: 0 10px 0 0;
color: #FFF;
border-radius: 5px;
padding: 10px;
text-transform: uppercase;
background: #1197B8;
border: 0;
outline: 0;
cursor: pointer;
height: 37px;
}
.login-button:hover {
background: #23B1D5;
}
.login-button:active {
background: #0E83A0;
}
.notification-box {
padding: 5px 10px 10px 10px;
border: 0px solid;
position: relative;
margin: 10px 20px;
color: #fff;
}
.box-transparent {
padding: 5px 10px 10px 10px;
margin: 0 0 5px 0;
color: #fff;
border-radius: 5px;
}
.notification-box p {
margin: 0;
}
.notification-box h5 {
font-size: 20px;
margin: 0;
}
.notification-box-error {
background: #B91D1D;
}
.notification-box-success {
background: #099709;
}
.notification-box-info {
background: #1B68A0;
}
.notification-box-transparent {
background: rgba(0, 0, 0, 0.2);
}
.notification-box > p:first-child {
margin: 0;
}
a.notification-close {
padding: 2px 7px 5px 7px;
position: absolute;
right: 5px;
top: 5px;
text-decoration: none !important;
font-weight: bold;
font-size: 14px;
line-height: normal;
color: #fff;
}
a.close-transparent {
right: 3px;
top: 3px;
border-radius: 3px;
}
a:hover.notification-close {
background-color: rgba(0, 0, 0, .12);
}
a:active.notification-close {
background-color: rgba(0, 0, 0, .52);
}
.notification-container {
max-width: 900px;
width: 100%;
display: none;
top: 46px;
position: absolute;
}
.notification-content {
background: #fff;
border-bottom-left-radius: 3px;
border-bottom-right-radius: 3px;
border: 1px solid #BFBFBF;
max-width: 350px;
width: 100%;
float: right;
}
.notification-inner {
padding: 10px;
}
.notification-row {
float: left;
width: 100%;
border-top: 1px solid #dee0e3;
}
.notification-image {
float: left;
}
.notification-text {
padding-left: 35px;
}
.notification-text img {
vertical-align: top;
}
.notification-text .chat-snippet {
font-style: italic;
word-wrap: break-word;
}
.notification-unread {
background: #F1F1F1;
}
.notification-padding {
padding: 5px 5px 3px 5px;
overflow: auto;
}
#notifications-content .timeago {
float: none;
}
#notifications-content .timeago:hover {
text-decoration: none;
}
#notifications-content img.notifications {
height: 33px;
width: 33px;
border-radius: 3px;
margin-right: 5px;
}
#notifications-content {
max-height: 435px;
overflow: auto;
}
#notifications-page .timeago {
float: none;
}
#notifications-page .timeago:hover {
text-decoration: none;
}
#notifications-page img.notifications {
height: 33px;
width: 33px;
border-radius: 3px;
margin-right: 5px;
}
.sidebar-fa-image {
float: left;
}
.sidebar-fa-image img {
width: 30px;
height: 30px;
}
.sidebar-fa-text {
font-size: 12px;
padding-left: 35px;
}
.sidebar-fa-text .timeago {
float: none;
display: inline-block;
}
.sidebar-fa-content {
max-height: 336px;
overflow: auto;
}
.input_hidden {
position: absolute;
left: -9999px;
}
.selected {
background-color: #DFDFDF;
}
#values {
float: left;
}
#values label {
display: inline-block;
cursor: pointer;
}
#values label:hover {
background-color: #efefef;
}
#values label img {
padding: 7px 5px 6px 5px;
width: 24px;
height: 24px;
pointer-events: none;
}
.button-image {
width: 34px;
height: 40px;
background-image: url('images/icons/events/camera.png');
background-repeat: no-repeat;
background-position: 50% 35%;
overflow: hidden;
float: left;
cursor: pointer;
margin-right: 4px;
background-size: 24px;
}
.button-image:hover {
background-color: #efefef;
}
.inputImage {
opacity: 0.0;
height: 40px;
cursor: pointer;
width: 34px; /* Move it to the left so that the cursor: pointer gets active (it sends the Choose file outside of the view) */
}
/* The webkit pseudo selector has been added put cursor style on upload button, alternatively can be fixed using padding-left: 100%; on inputImage */
.inputImage::-webkit-file-upload-button { cursor: pointer; }
.message-private-btn {
background: url('images/icons/private_post.png') no-repeat;
background-size: 16px;
width: 16px;
height: 16px;
cursor: pointer;
}
.message-private-btn:hover {
background: url('images/icons/private_post_h.png') no-repeat;
background-size: 16px;
width: 16px;
height: 16px;
}
#notifications_btn, #messages_btn {
cursor: pointer;
width: 16px;
}
.notificatons-number-container {
float: left;
margin-right: 5px;
}
.notifications-number {
background: #f84049; /* Old browsers */
background: -moz-linear-gradient(top, #f84049 0%, #da121c 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f84049), color-stop(100%,#da121c)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #f84049 0%,#da121c 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #f84049 0%,#da121c 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #f84049 0%,#da121c 100%); /* IE10+ */
background: linear-gradient(to bottom, #f84049 0%,#da121c 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f84049', endColorstr='#da121c',GradientType=0 ); /* IE6-9 */
border-radius: 3px;
padding: 2px 3px;
font-size: 10px;
position: absolute;
margin: -30px 0 0 9px;
color: #fff;
-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.4);
}
.message-private-active {
background: url('images/icons/private_post_a.png') no-repeat;
background-size: 16px;
}
.message-private-active:hover {
background: url('images/icons/private_post_a.png') no-repeat;
background-size: 16px;
}
#more_users, #more_reports {
width: 100%;
float: left;
padding: 10px 0 5px 0;
}
#share {
position:fixed;
z-index: 100;  
top:50%;  
left:50%;  
margin:-75px 0 0 -150px;  
width: 300px;  
height: 150px; 
display: none;
}
.share-container {
background: #fff;
width: 300px;  
height: 144px;
box-shadow: 0px 1px 1px #CCC;
border: 1px solid #BFBFBF;
border-radius: 3px;
}
.share-inner {
padding: 10px;
}
.share-title {
font-size: 16px;
}
.share-desc {
height: 35px;
}
.share-desc img {
width: 85px;
}
.share-btn, .share-close {
float: right;
margin: 10px 10px 0 0;
}
.share-close {
display: none;
}
.share-btn a, .share-close a {
float: right;
background: #4e69a8 url('images/comment_btn.png') repeat-x;
border-radius: 3px;
padding: 4px 5px;
color: #fff;
cursor: pointer;
text-decoration: none;
border: 1px solid;
border-color: #475d91 #3c5389 #3a5186;
text-shadow: 0 -1px 0 rgba(0, 0, 0, .2);
}
.share-btn a:hover, .share-close a:hover {
border-color: #3c5389 #324675 #304473;
background: #4e69a8 url('images/comment_btn_h.png') repeat-x;
}
.share-btn a:active, .share-close a:active {
background: #4e69a8;
box-shadow: #40568a 0px 1px 5px inset;
}
.share-cancel {
float: right;
margin: 10px 10px 0 0;
}
.share-cancel a {
float: right;
background: #F3F3F3;
background: -webkit-gradient(linear,left top,left bottom,from(#F5F5F5),to(#F1F1F1));
background: -moz-linear-gradient(top,#F5F5F5,#F1F1F1);
background: -o-linear-gradient(top,#F5F5F5,#F1F1F1);border-radius: 3px;
padding: 4px 5px;
color: #6B6B6B;
cursor: pointer;
text-decoration: none;
border: 1px solid #B1B1B1;
}
.share-cancel a:hover {
border-color: #A7A7A7;
color: #333;
box-shadow: 0px 1px 1px #CCC;
}
.share-cancel a:active {
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#EEE), to(#E0E0E0));
border-color: #BBB;
box-shadow: #CCC 0px 1px 5px inset;
-webkit-box-shadow: #CCC 0px 1px 5px inset;
z-index: 2;
color: #000;
}
#profile-card {
background: #fff;
border: 1px solid #BFBFBF;
width: 300px;
margin: 0 auto;
border-radius: 3px;
position: absolute;
display: none;
padding: 0 0 10px 0;
}
.profile-card-padding {
padding: 20px 10px 10px 10px;
}
.profile-card-cover img {
width: 100%;
}
.profile-card-avatar {
text-align: center;
margin-top: -40px;
}
.profile-card-avatar img {
width: 80px;
height: 80px;
border-radius: 50%;
}
.profile-card-info {
text-align: center;
}
.profile-card-info .cover-username {
float: none;
margin: 0;
}
.profile-card-buttons {
padding: 0 10px 0 10px;
overflow: auto;
}
.profile-card-buttons-container {
width: 60px;
margin: 0 auto;
}
.profile-card-divider {
width: 280px;
height: 1px;
background: #dee0e3;
margin: 10px auto;
}
.profile-card-buttons .edit_profile_btn {
float: none;
margin: 0 auto;
}
.profile-card-bio {
padding: 0 10px;
color: #AAA;
text-align: center;
}
#gallery-close {
position: absolute;
top: 0;
right: 0;
bottom: 0;
left: 0;
}
#gallery {  
display:none;  
background: rgba(0, 0, 0, 0.9); 
position: fixed;  
top: 0;  
left: 0;
bottom: 0;
right: 0;
z-index: 1001; 
}
.image-container {
margin: 5% auto;
max-width: 1000px;
width: 100%;
height: 100%;
position: relative;
}
.gallery-container {
margin: 0 auto;
max-width: 100%;
width: 100%;
z-index: 1002;
border-radius: 4px;
}
.gallery-footer {
float: left;
width: 100%;
background: #F8F8F8;
font-family: Tahoma, Verdana, Arial, sans-serif;
font-size: 13px;
color: #777;
height: 62px;
}
.gallery-footer-container {
padding: 10px;
}
.image-content {
background: #000;
width: 100%;
float: left;
text-align: center;
height: 538px;
}
.image-content img {
max-width: 100%;
max-height: 100%;
display: inline-block;
margin-bottom: -3px;
}
#gallery-prev {
background: url('images/icons/gallery_prev.png') no-repeat left bottom;
float: left;
width: 20%;
position: absolute;
left: 0;
background-position: left center;
z-index: 99999;
cursor: pointer;
}
#gallery-next {
background: url('images/icons/gallery_next.png') no-repeat right bottom;
float: right;
width: 20%;
position: absolute;
right: 0;
background-position: right center;
z-index: 99999;
cursor: pointer;
}
#gallery-prev:hover {
background: url('images/icons/gallery_prev_h.png') no-repeat left center;
}
#gallery-next:hover {
background: url('images/icons/gallery_next_h.png') no-repeat right center;
}
.close_btn {
width: 25px;
height: 25px;
float: right;
background: rgba(0, 0, 0, .12) url('images/icons/close.png') no-repeat center center;
background-size: 25px;
cursor: pointer;
position: absolute;
right: 0;
padding: 5px;
}
.close_btn:hover {
background: rgba(0, 0, 0, .32) url('images/icons/close_h.png') no-repeat center center;
background-size: 25px;
}
.close_btn:active {
background: rgba(0, 0, 0, .62) url('images/icons/close_h.png') no-repeat center center;
background-size: 25px;
}

@media only screen and (max-device-width: 1002px), only screen and (device-width: 1024px) and (device-height: 1002px), only screen and (width: 1280px) and (orientation: landscape), only screen and (device-width: 1002px), only screen and (max-width: 1002px) {	
	.row-body {
	border: 0;
	overflow: auto;
	}
	.row-footer {
	border-left: 0;
	border-right: 0;
	border-bottom: 0;
	margin: 0 auto;
	overflow: auto;
	}
	.row-top .threeads img {
	width: 100%;
	}
}
/* ------------------- !MOBILE ----------------------- */
@media only screen and (max-device-width: 600px), only screen and (device-width: 600px) and (device-height: 600px), only screen and (width: 600px) and (orientation: landscape), only screen and (device-width: 600px), only screen and (max-width: 600px) {
	.logo {
	display: none;
	}
	.logo-small {
	display: block;
	}
	.row-body .two {
	width: 100%;
	}
	.row-body .ten {
	width: 100%;
	}
	.row-body .twenty {
	width: 100%;
	}
	.row .three {
		width: 100%;
	}
	.row-body .threeads {
		width: 100%;
	}
	.row-body .threeads img {
		width: auto;
		height: auto;
	}
	.row-body .nine {
		width: 100%;
	}
	.row-body .three {
		width: 100%;
	}
	.row .four {
		width: 100%;
	}
	.row .eight {
		width: 100%;
	}
	.row .six { 
		width: 50%; 
	}
	.row .seven {
		width: 100%;
	}
	.row-page .three {
	width: 100%;
	}
	.row-page .nine {
	width: 100%;
	}
	.body {
	border:0;
	}
	.footer-container a {
	padding: 0 0 5px 0 !important;
	width: 100%;
	}
	.inputs-container {
	padding: 30px 10px 30px 20px;
	}
	.row-body .five {
	width: 100%;
	}
	.row-one {
	float: left;
	width: 100%;
	padding: 3px 0;
	}
	.row-two {
	float: left;
	width: 100%;
	padding: 3px 0;
	}
	.row-three {
	float: left;
	width: 100%;
	padding: 3px 0;
	text-align: left;
	}
	.row-four {
	float: left;
	width: 100%;
	padding: 3px 0;
	text-align: left;
	}
	.menu_name {
	display: none;
	}
	.message-container {
	padding: 0 10px 10px 10px;
	}
	.cover-container {
	height: auto;
	}
	.cover-username {
	font-size: 15px;
	margin: 0 0 5px 0;
	}
	.cover-username img {
	width: 14px;
	height: 14px;
	margin: 0 0 -2px 4px;
	}
	.cover-description-buttons {
	float: right;
	overflow: auto;
	display: block;
	width: 65px;
	}
	.sidebar-container {
	padding: 10px;
	}
	#values {
	width: 230px;
	}
	.page-input-title {
	width: 100%;
	text-align: left;
	padding: 3px 0;
	}
	.page-input-sub {
	padding: 3px 0;
	width: 100%;
	min-width: 100%;
	}
	.page-input-content {
	width: 90%;
	}
	.page-settings-container {
	padding: 10px;
	}
	.row-page .stats-container {
	width: 100%;
	}
	.admin-rows { border-bottom: 1px solid #ccc; }
	.row-page .table-id, .row-page .table-user, .row-page .table-mail, .row-page .table-edit, .row-page .table-delete { width: 100%; }
	.row-page .table-report-id, .row-page .table-report-type, .row-page .table-user, .row-page .table-report-message, .row-page .table-report-safe { width: 100%; }
	.subslist {
	right: 20px;
	}
	.subslist_message {
	right: 53px;
	}
	.notification-container {
	position: static !important; /* Overrides the JS */
	}
	.notification-content {
	margin: 1px -1px 0 0;
	}
	.search-input input {
	width: 90px;
	}
	.menu {
	display: none;
	}
	.search-results {
	}
	.chat-container  {
	height: 300px;
	}
	.sidebar-chat-list {
	max-height: 350px;
	}
	.cover-description-content {
	margin-left: 106px;
	}
	.cover-avatar {
	width: 86px;
	margin-top: -50px;
	}
	.cover-avatar a {
	float: left;
	overflow: hidden;
	height: 86px;
	}
	.welcome-message {
	width: 100%;
	display: none;
	}
	.welcome-inputs {
	width: 100%;
	}
	.welcome-user {
	margin-top: 0;
	margin-bottom: 11px;
	}
	.welcome-user:last-child {
	display: none;
	}
}







</style>


<!--  TopBar starts here  -->

<?php  if (! elgg_is_logged_in()) { ?>

<div class="topbar">
	<div class="header">
		<a href="<?php echo elgg_get_site_url(); ?>"><div class="logo"></div><div class="logo-small"></div></a>
		
		
		<a href="<?php echo elgg_get_site_url(); ?>"><div class="menu_btn" title="Register"><img src="<?php echo elgg_get_site_url(); ?>mod/timeline_avatar/graphics/logo/register.png"></div></a>
		<a href="#"><div class="menu_visitor"> Welcome <strong>Visitor</strong></div></a>
	</div>
	<div class=""></div>
</div>


<div class="topbar_margin"></div>

<?php   } // close function =>  if (elgg_is_logged_in()) {}   ?>


<?php if ($owner instanceof ElggEntity) {   ?>


<!-- Cover photo Change cover starts here -->							
							 
<div class="ZFb" guidedhelpid="profile_change_cover_button"><div role="button" class="d-k-l b-c b-c-R xyb YFb" tabindex="0">Change cover</div></div>
							
<!-- Cover photo Change cover ends here -->	

<!-- Cover photo starts here -->

<div class="cover-container">
						<div class="cover-content">
							<a onclick="gallery('1218768621_1239598513_1352597288.png', '36emmastone', 'covers')" id="1218768621_1239598513_1352597288.png"><div class="cover-image" style="background-position: center; background-image: url(<?php  echo $user_coverphoto; ?>)">
							</div></a>
<!-- Cover photo Change cover starts here -->							
							 
<div class="ZFb" guidedhelpid="profile_change_cover_button"><div role="button" class="d-k-l b-c b-c-R xyb YFb" tabindex="0">Change cover</div></div>
							
<!-- Cover photo Change cover ends here -->							
							<div class="cover-description">
								<div class="cover-avatar-content">
									<div class="cover-avatar">
										<a onclick="gallery('2127044394_1717903007_649739594.png', '36emmastone', 'avatars')" id="2127044394_1717903007_649739594.png"><span id="avatar36emmastone"><img src="<?php  echo $icon_url;   ?>" title=""></span></a>
									</div>
								</div>
								<div class="cover-description-content">
	<span id="author36emmastone"></span><span id="time36emmastone"></span><div class="cover-username"><a href="<?php echo elgg_get_site_url();?>">
	<?php echo $owner->name ;?></a><img src="<?php echo $pleaseloadverifiedimage; ?>" title="Verified User"></div>
	
									<div class="cover-description-buttons"><div id="subscribe36"></div></div>
								</div>
							</div>
						</div>
					</div>
					
					
					
		<?php   } // close function =>  if ($owner instanceof ElggEntity) { }   ?>			
					
					
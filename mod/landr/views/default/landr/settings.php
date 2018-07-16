<?php ?>

<style>
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
    width: 25%;
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
#landr-slider-tmp {
    width: 100%;
    float: none;
    margin: 10px 0;
}
#landr-slider-upload {
    float: none;
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
<?php
?>
  <style>
		html {
		  width: 550px;
		  border: 1px solid;
		}
		body {
		  font-family: sans-serif;
		  color: rgba(0,0,0,.15);
		}
		body:after {
		  content: '';
		  display: block;
		  clear: both;
		}
		div {
		  position: relative;
		}
		div:after {
		  font-size: 200%;
		  position: absolute;
		  left: 0;
		  right: 0;
		  top: 0;
		  text-align: center;
		}
		.block-parent {
		  border: 3px solid blue;
		}
		.block-parent:after {
		  content: 'Block parent';
		  color: blue;
		}
		.float {
		  float: left;
		  border: 3px solid red;
		  height: 130px;
		  width: 150px;
		}
		.float:after {
		  content: 'Float';
		  color: red;
		}
  </style>
<div class="block-parent">
  <div class="float"></div>
  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit.
</div>
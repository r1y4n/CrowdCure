<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CrowdCure PPIdb</title>

	<script src="assets/js/jquery-1.12.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#search").click(function(){
				document.location.href = "search";
			});
		});
		$(document).ready(function(){
			$("#collect, #verify").click(function(){
				document.location.href = "login";
			});
		});
	</script>
	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
		text-align: center;
	}
	.button_container {
		margin: 10px auto;
		width: 60%;
		overflow: hidden;
		padding: 5px;
	}
	.custom_button {
		border: 1px solid #d0d0d0;
		box-shadow: 2px 2px 2px #D0D0D0;
	    cursor: pointer;
	    float: left;
	    height: 40px;
	    line-height: 3;
	    margin: 0 4px;
	    width: calc(33% - 8px);
	    background: #d0d0d0;
	    color: #000000;
	}
	.custom_button:hover {
		background: #ffffff;
		color: #4F5155;
		font-weight: bold;
	}
	</style>

</head>
<body>

<div id="container">
	<h1>CrowdCure PPIdb Login</h1>

	<div id="body">
		<?php echo validation_errors(); ?>
		<?php echo form_open('verifylogin'); ?>
			<input type="text" size="20" id="username" name="username" placeholder="Username" />
			
			<input type="password" size="20" id="passowrd" name="password" placeholder="Password" />
			
			<input type="submit" value="Login"/>
		<?php echo form_close(); ?>
	</div>

	<p class="footer"><a href="home">CrowdCure</a> | Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>

</body>
</html>
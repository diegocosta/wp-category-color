<?php
	/*
	Plugin Name: WP Category Color
	Plugin URI: http://www.diegocosta.com.br
	Description: Adds support to color picker in wordpress category.
	Author: Diego Costa
	Version: 1.0
	Author URI: http://www.diegocosta.com.br
	*/

	require_once "vendor/autoload.php";

	(new DiegoCosta\WP\CategoryColor('dc_cat_color'))
		->addColorColumnOnCategoryPage()
		->addColorInWPTermObject();

	

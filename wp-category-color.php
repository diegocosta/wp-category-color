<?php
	/*
	Plugin Name: WP Category Color
	Plugin URI:  https://wordpress.org/plugins/wp-category-color/
	Description: Adds support to color picker in wordpress category.
	Version:     1.0.0
	Author:      Diego Costa
	Author URI:  https://www.diegocosta.com.br
	License:     GPL2
	 
	WP Category Color is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.
	 
	WP Category Color is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	 
	You should have received a copy of the GNU General Public License
	along with WP Category Color. If not, see {License URI}.
	*/

	require_once "vendor/autoload.php";

	use DiegoCosta\WP\CategoryColor;

	CategoryColor::init('wp_category_color');
	CategoryColor::addColorInWPTermObject();
	CategoryColor::addColorColumnOnCategoryPage();

	function wp_category_color__uninstall() {
		CategoryColor::uninstall();
	}

	register_uninstall_hook(__FILE__, 'wp_category_color__uninstall');
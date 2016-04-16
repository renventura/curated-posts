<?php
/**
 * Plugin Name: Curated Posts
 * Plugin URI: https://www.engagewp.com
 * Description: Manually curate a list of featured posts for each post category.
 * Version: 0.1
 * Author: Ren Ventura
 * Author URI: https://www.engagewp.com
 * Text Domain: curated-posts
 * Domain Path: /languages/
 *
 * License: GPL 2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

/*
	Copyright 2016  Ren Ventura  (email : mail@engagewp.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	Permission is hereby granted, free of charge, to any person obtaining a copy of this
	software and associated documentation files (the "Software"), to deal in the Software
	without restriction, including without limitation the rights to use, copy, modify, merge,
	publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
	to whom the Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or
	substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Curated_Posts' ) ) :

class Curated_Posts {

	private static $instance;

	public $settings_page;

	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Curated_Posts ) ) {
			
			self::$instance = new Curated_Posts;

			self::$instance->constants();
			self::$instance->includes();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 *	Constants
	 */
	public function constants() {

		// Plugin file
		if ( ! defined( 'CURATED_POSTS_PLUGIN_VERSION' ) ) {
			define( 'CURATED_POSTS_PLUGIN_VERSION', '0.1' );
		}

		// Plugin file
		if ( ! defined( 'CURATED_POSTS_PLUGIN_FILE' ) ) {
			define( 'CURATED_POSTS_PLUGIN_FILE', __FILE__ );
		}

		// Plugin basename
		if ( ! defined( 'CURATED_POSTS_PLUGIN_BASENAME' ) ) {
			define( 'CURATED_POSTS_PLUGIN_BASENAME', plugin_basename( CURATED_POSTS_PLUGIN_FILE ) );
		}

		// Plugin directory path
		if ( ! defined( 'CURATED_POSTS_PLUGIN_DIR_PATH' ) ) {
			define( 'CURATED_POSTS_PLUGIN_DIR_PATH', trailingslashit( plugin_dir_path( CURATED_POSTS_PLUGIN_FILE )  ) );
		}

		// Plugin directory URL
		if ( ! defined( 'CURATED_POSTS_PLUGIN_DIR_URL' ) ) {
			define( 'CURATED_POSTS_PLUGIN_DIR_URL', trailingslashit( plugin_dir_url( CURATED_POSTS_PLUGIN_FILE )  ) );
		}

		// Templates directory
		if ( ! defined( 'CURATED_POSTS_PLUGIN_TEMPLATES_DIR_PATH' ) ) {
			define ( 'CURATED_POSTS_PLUGIN_TEMPLATES_DIR_PATH', CURATED_POSTS_PLUGIN_DIR_PATH . 'templates/' );
		}
	}

	/**
	 *	Include PHP files
	 */
	public function includes() {

		include_once 'includes/helper-functions.php';

		include_once 'includes/admin/settings-page.php';
		include_once 'includes/admin/process-settings.php';
	}

	/**
	 *	Action/filter hooks
	 */
	public function hooks() {

		add_action( 'init', array( $this, 'init' ), 1 );
	}

	/**
	 *	Initialize the admin
	 */
	public function init() {

		$this->settings_page = new RV_Admin_Menu_Page(
			'edit.php', // hook of the 'parent' (menu top-level page)
			__( 'Curated Posts Settings', 'curated-posts' ), // browser window title
			__( 'Curated Posts', 'curated-posts' ), // menu title
			'', // URL to an icon, or name of a Dashicons helper class to use a font icon
			'manage_options', // capability a user must have to see the page
			'curated_posts', // slug identifier for this page
			99, // priority for menu positioning
			'curated_posts_admin_settings_page_top', // callback that prints to the page, above the metaboxes
			false, // whether the meta boxes should be sortable
			true, // whether the meta boxes should be collapsable
			false, // whether the page utilizes the media uploader
			apply_filters( 'curated_posts_settings_page_tabs', array( // settings tabs
				'welcome' => __( 'Welcome', 'curated-posts' ),
				'categories' => __( 'Categories', 'curated-posts' ),
		)));

		// Settings page base
		if ( ! defined( 'CURATED_POSTS_PLUGIN_SETTINGS_PAGE_BASE' ) ) {
			define ( 'CURATED_POSTS_PLUGIN_SETTINGS_PAGE_BASE', 'posts_page_' . $this->settings_page->get_slug() );
		}

		// Settings page URL
		if ( ! defined( 'CURATED_POSTS_PLUGIN_SETTINGS_PAGE_URL' ) ) {
			define ( 'CURATED_POSTS_PLUGIN_SETTINGS_PAGE_URL', add_query_arg( 'page', $this->settings_page->get_slug(), get_admin_url( '', 'edit.php' ) ) );
		}
	}
}

endif;

/**
 *	Main function
 *	@return object Curated_Posts instance
 */
function Curated_Posts() {
	return Curated_Posts::instance();
}

/**
 *	Kick off!
 */
Curated_Posts();
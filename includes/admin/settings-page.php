<?php 
/**
 *	Settings submenu page
 *
 *	@package EngageWP Support
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require CURATED_POSTS_PLUGIN_DIR_PATH . 'lib/class-rv-admin-menu-page.php';

// Define the body content for the page (if callback is specified above)
function curated_posts_admin_settings_page_top(){

	if ( ! isset( $_GET['tab'] ) ) {
		return;
	}

	include_once CURATED_POSTS_PLUGIN_TEMPLATES_DIR_PATH . 'admin/intro-metaboxes.php';
}

// Add some metaboxes to the page
add_action( 'add_meta_boxes', 'curated_posts_admin_settings_page_meta_boxes' );
function curated_posts_admin_settings_page_meta_boxes() {

	// Get current screen
	$screen_id = get_current_screen()->id;

	// Bail if not on our settings page
	if ( $screen_id !== CURATED_POSTS_PLUGIN_SETTINGS_PAGE_BASE ) {
		return;
	}

	// Add the meta boxes
	if ( isset( $_GET['tab'] ) ) {

		switch ( $_GET['tab'] ) {

			case 'welcome':
				do_action( 'curated_posts_overview_meta_boxes' );
				break;

			case 'categories':
				$terms = curated_posts_get_terms();
				foreach ( $terms as $term ) {
					add_meta_box( "curated_posts_term_metabox_{$term->term_id}", "{$term->name}", 'curated_posts_category_metabox', $screen_id, 'normal', 'high', array(
						'term' => $term,
					) );
				}
				add_meta_box( 'curated_posts_save_metabox', __( 'Save Settings', 'curated-posts' ), 'curated_posts_save_metabox', $screen_id, 'side', 'high' );
				do_action( 'curated_posts_categories_meta_boxes' );
				break;
		}
	}
}

/**
 *	Render content for category meta boxes
 */
function curated_posts_category_metabox( $post, $term ) {

	$term_args = $term['args']['term'];
	$posts = curated_posts_get_posts();

	include CURATED_POSTS_PLUGIN_TEMPLATES_DIR_PATH . 'admin/categories-metaboxes.php';
}

/**
 *	Render content for the save meta box
 */
function curated_posts_save_metabox() {
	include CURATED_POSTS_PLUGIN_TEMPLATES_DIR_PATH . 'admin/save-metabox.php';
}

/**
 *	Enqueue scripts and styles on settings page
 */
add_action( 'admin_enqueue_scripts', 'curated_posts_settings_page_enqueues' );
function curated_posts_settings_page_enqueues() {

	// Get current screen
	$screen_id = get_current_screen()->id;

	// Bail if not on our settings page
	if ( $screen_id == CURATED_POSTS_PLUGIN_SETTINGS_PAGE_BASE && isset( $_GET['tab'] ) && $_GET['tab'] == 'categories' ) {

		$placeholder = __( 'Select a Post', 'curated-posts' );

		wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js', array('jquery'), '4.0.2', true );
		wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css', '', '4.0.2' );

		wp_enqueue_script( 'curated-posts-categories', CURATED_POSTS_PLUGIN_DIR_URL . 'assets/js/categories-tab.js', array('jquery'), CURATED_POSTS_PLUGIN_VERSION, true );	
		wp_localize_script( 'curated-posts-categories', 'curated_posts_repeater', array(
			'placeholder' => $placeholder,
			'allowClear' => false
		));
	}

	wp_enqueue_style( 'curated-posts-admin', CURATED_POSTS_PLUGIN_DIR_URL . 'assets/css/admin.css', '', CURATED_POSTS_PLUGIN_VERSION );	
}
<?php
/**
 *	Process the settings
 *
 *	@package Curated Posts
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Curated_Posts_Process_Settings' ) ) :

class Curated_Posts_Process_Settings {

	public function __construct() {

		$this->hooks();		
	}

	/**
	 *	Action/filter hooks
	 */
	public function hooks() {

		add_action( 'admin_init', array( $this, 'process' ), 15 );
	}

	/**
	 *	Process
	 */
	public function process() {

		if ( ! isset( $_GET[Curated_Posts()->settings_page->get_slug() . '_settings'] ) ) {
			return;
		}

		// Security check
		if ( ! isset( $_POST[Curated_Posts()->settings_page->get_slug() . '_settings_nonce'] ) || ! wp_verify_nonce( $_POST[Curated_Posts()->settings_page->get_slug() . '_settings_nonce'], Curated_Posts()->settings_page->get_slug() . '_settings_nonce' ) ) {
			wp_die( 'Security check failed.', 'curated-posts' );
		}

		$settings = isset( $_POST['curated_posts'] ) ? $_POST['curated_posts'] : '';

		// Bail if no settings are posted
		if ( ! $settings ) {
			return;
		}

		$posted_terms = isset( $settings['terms'] ) ? $settings['terms'] : '';
		$term_ids = curated_posts_get_term_ids();

		// Bail if no settings are posted
		if ( is_array( $posted_terms ) ) {

			foreach ( $posted_terms as $term_id => $posts ) {

				$sanitized_posts = array();

				$term_id = (int) $term_id;

				// Sanitize input
				foreach ( $posts as $key => $post ) {
					$sanitized_posts[$key]['post_id'] = intval( strip_tags( $post['post_id'] ) );
					$sanitized_posts[$key]['custom_title'] = sanitize_text_field( $post['custom_title'] );
					$sanitized_posts[$key]['custom_link'] = sanitize_text_field( $post['custom_link'] );
				}

				// Update or delete term meta
				if ( $sanitized_posts[$key]['post_id'] ) {
					update_term_meta( $term_id, 'curated_posts', array_values( $sanitized_posts ) );
				} elseif ( get_term_meta( $term_id, 'curated_posts', true ) ) {
					delete_term_meta( $term_id, 'curated_posts' );
				}				
			}
		}

		// Redirect
		wp_redirect( add_query_arg( array(
			'tab' => isset( $_GET['tab'] ) ? $_GET['tab'] : '',
			'settings-saved' => 'success'
		), CURATED_POSTS_PLUGIN_SETTINGS_PAGE_URL ) );
		exit;
	}
}

endif;

new Curated_Posts_Process_Settings;
<?php
/**
 *	Helper functions
 *
 *	@package Curated Posts
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *	Retrieve all published posts
 *
 *	@return (array) - Posts
 */
function curated_posts_get_posts() {

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);

	return get_posts( $args );
}

/**
 *	Retrieve all terms
 *
 *	@return (array) - Terms
 */
function curated_posts_get_terms() {

	$terms = get_terms( array(
		'taxonomy' => 'category',
		'hide_empty' => false,
	) );

	return $terms;
}

/**
 *	Retrieve all term IDs
 *
 *	@return (array) - Term IDs
 */
function curated_posts_get_term_ids() {

	$terms = curated_posts_get_terms();

	$ids = array();

	foreach ( $terms as $term ) {
		array_push( $ids, $term->term_id );
	}

	return $ids;
}

/**
 *	Get curated posts for a given post
 */
function curated_posts_get_curated_posts( $post_id = '', $first = true ) {

	if ( ! $post_id ) {
		global $post;
		$post_id = $post->ID;
	} else {
		$post = get_post( $post_id );
	}

	// Bail if not a post
	if ( $post->post_type !== 'post' ) {
		return;
	}

	// Get categories
	$terms = get_the_terms( $post_id, 'category' );

	$curated_posts = array();

	foreach ( $terms as $key => $term ) {
		
		if (  get_term_meta( $term->term_id, 'curated_posts', true ) ) {
			$curated_posts[$key] = get_term_meta( $term->term_id, 'curated_posts', true );
		}
	}

	if ( $first ) { // Return the first array element (lowest term_id)
		return isset( $curated_posts[0] ) ? $curated_posts[0] : '';
	} else { // Return entire array
		return $curated_posts;
	}
}

/**
 *	Output curated posts
 */
function curated_posts_output( $columns = 3, $column_class = 'one-third', $first_class = 'first' ) {

	global $post;

	$curated_posts = curated_posts_get_curated_posts();

	$count = 0;

	// Bail if no curated posts
	if ( ! $curated_posts ) {
		return;
	}

	foreach ( $curated_posts as $curated_post_data ) {

		// Skip if curated_post_data not set
		if ( ! $post_id = isset( $curated_post_data['post_id'] ) ? intval( $curated_post_data['post_id'] ) : '' ) {
			continue;
		}

		// Curated post we're working with
		$curated_post = get_post( $post_id );

		// Skip if the current post is in the list of curated posts
		if ( $curated_post->ID == $post->ID ) {
			continue;
		}

		$title = ! empty( $curated_post_data['custom_title'] ) ? sanitize_text_field( $curated_post_data['custom_title'] ) : $curated_post->post_title;
		$link = ! empty( $curated_post_data['custom_link'] ) ? sanitize_text_field( $curated_post_data['custom_link'] ) : get_permalink( $curated_post->ID );

		if ( $columns !== 0 ) { // Columns 

			if ( $count === 0 || 0 === $count % $columns ) {

				$classes = array(
					'curated-post',
					$column_class,
					$first_class
				);

				$classes = implode( ' ', $classes );

			} else {

				$classes = array(
					'curated-post',
					$column_class,
				);

				$classes = implode( ' ', $classes );
			}

		} else { // No columns

			$classes = array(
				'curated-post',
			);

			$classes = implode( ' ', $classes );
		}

		ob_start();

		printf( '<article class="%s">', $classes );

			printf( '<h2>%s</h2>', $title );
			printf( '<a href="%s">%s</a>', $link, __( 'Read Post', 'curated-posts' ) );

		echo '</article>';

		$output = ob_get_clean();

		/**
		 *	Filter the output
		 *
		 *	@param (string) $output - Output markup
		 *	@param (object) $curated_post - Curated post object
		 *	@param (object) $post - Current post object
		 *	@param (string) $title - Custom title setting
		 *	@param (string) $link - Custom link setting
		 *	@param (string) $classes - HTML/CSS classes for each curated post
		 */
		echo apply_filters( 'curated_posts_output', $output, $curated_post, $post, $title, $link, $classes );

		$count++;
	}
}
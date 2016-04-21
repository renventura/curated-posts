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
 *
 *	@param (int) $post_id - Post ID for which to retrieve curated posts
 *
 *	@return (array) - Curated posts for given post
 */
function curated_posts_get_curated_posts( $post_id ) {

	$post = get_post( $post_id );

	// Bail if not a post
	if ( $post->post_type !== 'post' ) {
		return;
	}

	// Get categories
	$terms = get_the_terms( $post_id, 'category' );

	$curated_posts = array();

	foreach ( $terms as $key => $term ) {

		if (  get_term_meta( $term->term_id, 'curated_posts', true ) ) {
			$curated_posts['term_id_'.$term->term_id] = get_term_meta( $term->term_id, 'curated_posts', true );
		}
	}

	return $curated_posts;
}

/**
 *	Output curated posts
 *
 *	@param (int) $max - Maximum posts to display
 *	@param (int) $columns - Number of columns (default 3)
 *	@param (int) $column_class - Class to be assigned to each column
 *	@param (int) $first_class - Class to be assigned to the first element of each column
 */
function curated_posts_output( $max = 3, $columns = 3, $column_class = 'one-third', $first_class = 'first' ) {

	global $post;

	// Bail if not a singular post
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	// Get curated posts for all categories vs. first category (lowest ID; default)
	$all = apply_filters( 'curated_posts_output_all_posts', false );

	// Get curated posts
	$curated_posts = curated_posts_get_curated_posts( $post->ID );

	// Bail if no curated posts
	if ( ! $curated_posts ) {
		return;
	}

	// Counter
	$count = 0;

	// Open the curated posts section tag
	echo '<section class="curated-posts clearfix">';

	// Loop through each term
	foreach ( $curated_posts as $term_id => $term_curated_posts ) {

		// Break apart curated posts from each term by resetting counter
		if ( apply_filters( 'curated_posts_output_separate_terms', true ) === true ) {
			$count = 0;
		}

		// Loop through the term's curated posts
		foreach ( $term_curated_posts as $curated_post_data ) {

			// Skip if over the max limit
			if ( $max !== 0 && $count >= $max ) {
				continue;
			}

			$curated_post_id = isset( $curated_post_data['post_id'] ) ? intval( $curated_post_data['post_id'] ) : '';

			// Skip if curated_post_data not set
			if ( ! $curated_post_id ) {
				continue;
			}

			// Curated post we're working with
			$curated_post = get_post( $curated_post_id );

			// Skip if the current post is in the list of curated posts
			if ( $curated_post->ID == $post->ID ) {
				continue;
			}

			$title = ! empty( $curated_post_data['custom_title'] ) ? sanitize_text_field( $curated_post_data['custom_title'] ) : $curated_post->post_title;
			$link = ! empty( $curated_post_data['custom_link'] ) ? sanitize_text_field( $curated_post_data['custom_link'] ) : get_permalink( $curated_post->ID );

			$classes = array(
				'curated-post',
				$term_id,
				'post_id_' . $curated_post->ID
			);

			if ( $columns !== 0 ) { // Columns 

				if ( $count === 0 || 0 === $count % $columns ) {

					$classes[] = $column_class;
					$classes[] = $first_class;

				} else {

					$classes[] = $column_class;
				}

			} else { // No columns

				//
			}

			$classes = implode( ' ', $classes );

			ob_start();

			printf( '<article class="%s" data-curated-posts-term-id="%s" data-curated-posts-post-id="%s">', $classes, str_replace( 'term_id_', '', $term_id ), $curated_post->ID );

				printf( '<h2><a href="%s">%s</a></h2>', $link, $title );

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

		// Bail after the first iteration if displaying curated posts from only one term
		if ( ! $all ) {
			echo '</section>';
			return;
		}
	}

	echo '</section>';
}
<?php
/**
 *	Shortcodes
 *
 *	@package Curated Posts
 */

// Ensure shortcodes can be run in text widgets
add_filter( 'widget_text', 'do_shortcode' );

// Curated Posts output
add_shortcode( 'curated_posts', 'curated_posts_shortcode_callback' );
function curated_posts_shortcode_callback( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'max' => '3',
			'columns' => '3',
			'column_class' => 'one-third',
			'first_class' => 'first'
		), $atts )
	);

	// Do the output
	ob_start();
	curated_posts_output( $max, $columns, $column_class, $first_class );
	return ob_get_clean();
}
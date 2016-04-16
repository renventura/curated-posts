<?php
/**
 *	Meta boxes template
 *	Adds a repeatable table row to each meta box
 */
?>

<table class="form-table js curated-posts-settings-table" style="display: none;">

	<tbody>

		<tr valign="top">

			<td>
				
				<p>
					<?php echo $term_args->description; // Show the category's description and edit link ?>
					<a href="<?php echo get_edit_term_link( $term_args->term_id, 'category', 'post' ); ?>"><?php _e( 'Edit Category', 'curated-posts' ); ?></a>
				</p>

				<p class="no-js-msg"><?php _e( 'To use the Curated Posts settings, you must enable JavaScript in your browser.', 'curated-posts' ); ?></p>

			</td>

		</tr>

		<?php

		// Saved values
		$term_meta = get_term_meta( $term_args->term_id, 'curated_posts', true );

		if ( $term_meta ) :

			foreach ( $term_meta as $term_key => $term_data ): ?>
				
				<tr valign="top" id="" class="curated-posts-repeater-group" data-row-post-number="<?php echo $term_key; ?>">
					
					<td>

						<span class="sort"><span class="dashicons dashicons-sort"></span></span>

						<table>
							
							<tbody>

								<tr valign="top" class="curated-posts-post">
									<th scope="row"><label for="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][post_id]"><strong><?php _e( 'Select a Post', 'curated-posts' ); ?></strong></label></th>
									<td>
										<select name="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][post_id]" id="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][post_id]" class="" style="width: 100%;">
											<option value=""></option>
											<?php foreach ( $posts as $post ) : ?>
												<option value="<?php esc_attr_e( $post->ID ); ?>" <?php if ( $term_data['post_id'] == $post->ID ) echo 'selected="selected"'; ?>><?php echo $post->post_title; ?> (ID: <?php echo $post->ID; ?>)</option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
								
								<tr valign="top" class="curated-posts-title">
									<th scope="row"><label for="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][custom_title]"><strong><?php _e( 'Custom Title', 'curated-posts' ); ?></strong></label></th>
									<td>
										<p><input type="text" name="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][custom_title]" id="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][custom_title]" class="regular-text" size="36" value="<?php esc_attr_e( $term_data['custom_title'] ); ?>" /></p>
									</td>
								</tr>

								<tr valign="top" class="curated-posts-link">
									<th scope="row"><label for="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][custom_link]"><strong><?php _e( 'Custom Link', 'curated-posts' ); ?></strong></label></th>
									<td>
										<p><input type="url" name="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][custom_link]" id="curated_posts[terms][<?php echo $term_args->term_id; ?>][<?php echo $term_key; ?>][custom_link]" class="regular-text" size="36" value="<?php esc_attr_e( $term_data['custom_link'] ); ?>" /></p>
									</td>
								</tr>

							</tbody>

							<tfoot>

								<tr valign="top">
									<th scope="row"></th>
									<td>
										<p><button class="button button-secondary curated-posts-remove-row"><?php _e( 'Remove Post', 'curated-posts' ); ?></button></p>
									</td>
								</tr>

							</tfoot>

						</table>

					</td>

				</tr>

			<?php endforeach;

		endif; ?>

		<tr valign="top">

			<td>
				<button class="curated-posts-add-row button button-primary" data-category-id="<?php echo $term_args->term_id; ?>">
					<?php _e( 'Add Post', 'curated-posts' ); ?>
				</button>
			</td>

		</tr>

		<?php // Hidden repeater group to clone ?>
		<tr valign="top" id="" class="curated-posts-repeater-group hidden-repeater" data-row-post-number="0" style="display: none;">
			
			<td>

				<span class="sort"><span class="dashicons dashicons-sort"></span></span>

				<table>
					
					<tbody>

						<tr valign="top" class="curated-posts-post">
							<th scope="row"><label for="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][post_id]"><strong><?php _e( 'Select a Post', 'curated-posts' ); ?></strong></label></th>
							<td>
								<select name="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][post_id]" id="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][post_id]" class="" style="width: 100%;">
									<option value=""></option>
									<?php foreach ( $posts as $post ) : ?>
										<option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?> (ID: <?php echo $post->ID; ?>)</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						
						<tr valign="top" class="curated-posts-title">
							<th scope="row"><label for="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][custom_title]"><strong><?php _e( 'Custom Title', 'curated-posts' ); ?></strong></label></th>
							<td>
								<p><input type="text" name="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][custom_title]" id="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][custom_title]" class="regular-text" size="36" value="" /></p>
							</td>
						</tr>

						<tr valign="top" class="curated-posts-link">
							<th scope="row"><label for="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][custom_link]"><strong><?php _e( 'Custom Link', 'curated-posts' ); ?></strong></label></th>
							<td>
								<p><input type="url" name="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][custom_link]" id="curated_posts[terms][<?php echo $term_args->term_id; ?>][new_post_number][custom_link]" class="regular-text" size="36" value="" /></p>
							</td>
						</tr>

					</tbody>

					<tfoot>

						<tr valign="top">
							<th scope="row"></th>
							<td>
								<p><button class="button button-secondary curated-posts-remove-row"><?php _e( 'Remove Post', 'curated-posts' ); ?></button></p>
							</td>
						</tr>

					</tfoot>

				</table>

			</td>

		</tr><?php // End repeater group ?>

	</tbody>
	
</table>
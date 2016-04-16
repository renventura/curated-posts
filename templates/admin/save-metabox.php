<?php
/**
 *	Meta boxes template
 *	Adds the save-settings meta box
 */
?>

<table class="form-table curated-posts-settings-table">

	<tbody>

		<tr>

			<p class="no-js-msg"><?php _e( 'To use the Curated Posts settings, you must enable JavaScript in your browser.', 'curated-posts' ); ?></p>

			<div class="js" style="display: none;">
				<p><?php _e( 'When finished, save your changes.', 'curated-posts' ); ?></p>
				<input type="submit" id="curated_posts_categories_submit" class="button button-primary button-hero" value="<?php _e( 'Save Changes', 'curated-posts' ); ?>" style="width: 100%;">
			</div>

		</tr>

	</tbody>

</table>
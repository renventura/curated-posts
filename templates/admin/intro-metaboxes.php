<?php
/**
 *	Meta boxes template
 *	Adds the intro to each page/tab
 */
?>

<div class="postbox curated-posts-settings-intro">

	<div class="inside">

		<table class="form-table curated-posts-settings-table">

			<tbody>

				<tr>

					<?php if ( $_GET['tab'] == 'welcome' ) : ?>

						<td>
							
							<h3 class="intro-headline"><?php _e( 'Curated Posts', 'curated-posts' ); ?></h3 class="intro-headline">

							<p><?php _e( 'Curated Posts allows you to manually select a chosen number of Posts as "featured posts" for each of your categories. To output a category\'s curated posts, you can insert a simple function (below) in your theme. Some great areas for curated posts are after post content, and in a sidebar.', 'curated-posts' ); ?></p>

							<p><pre><code>&lt;?php curated_posts_output(); ?&gt;</code></pre></p>

						</td>

					<?php endif; ?>

					<?php if ( $_GET['tab'] == 'categories' ) : ?>

						<td>
							
							<h3 class="intro-headline"><?php _e( 'Post Categories', 'curated-posts' ); ?></h3 class="intro-headline">

							<p style="margin-bottom: 20px;"><?php _e( 'Below are all of your registered post categories. For each category, you can select however many posts to be shown as "featured posts." You may enter customized titles and links (i.e. tracking links) for each post.', 'curated-posts' ); ?></p>

							<p><?php _e( 'When selecting posts for output, it is recommended that you add the number of posts you want to display, plus one, which will acts as a fallback for the current post. For example, if you want to show three posts, add four to the category (3+1). By default, the current post will not be shown in the curated posts output.', 'curated-posts' ); ?></p>

						</td>

					<?php endif; ?>

				</tr>

				<?php do_action( 'curated_posts_tab_intros' ); ?>

			</tbody>

		</table>

	</div><!-- .inside -->

</div><!-- .postbox -->
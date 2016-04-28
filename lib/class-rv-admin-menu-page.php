<?php
/**
 *	Add a menu or submenu page with meta boxes and settings
 *
 *	Example uses:
 *
 *		$top_menu_page = new RV_Admin_Menu_Page( '', 'My Admin Page', 'My Admin Page', 'manage_options', 'my-admin-page', true, true ) 		
 *		$sub_menu_page = new RV_Admin_Menu_Page( 'parent_hook', 'My Admin Page', 'My Admin Page', 'manage_options', 'my-admin-page', true, true ) 
 *
		$settings_page = new RV_Admin_Menu_Page(
			'edit.php', // hook of the 'parent' if subpage
			__( 'Curated Posts', 'curated-posts' ), // browser window title
			__( 'Curated Posts', 'curated-posts' ), // menu title
			'', // URL to an icon, or name of a Dashicons helper class to use a font icon
			'manage_options', // capability a user must have to see the page
			'curated-posts', // slug identifier for this page
			99, // priority for menu positioning
			'curated_posts_admin_settings_page_top', // callback that prints to the page, above the metaboxes
			true, // whether the meta boxes should be sortable
			true, // whether the meta boxes should be collapsable
			true, // whether the page utilizes the media uploader
			apply_filters( 'curated_posts_settings_page_tabs', array( // settings tabs
				'overview' => __( 'Overview', 'curated-posts' ),
				'agents' => __( 'Agents', 'curated-posts' ),
				'settings' => __( 'Settings', 'curated-posts' ),
		)));
 *
 *	Credit to Stephen Harris (stephenharris.info) for providing the base of this skeleton; https://gist.github.com/stephenh1988/3676396
 *
 *	@version 1.0.0
 */

if ( ! class_exists( 'RV_Admin_Menu_Page' ) ) :

class RV_Admin_Menu_Page {

	private $hook, $title, $menu, $icon, $permissions, $slug, $priority, $page, $sortable, $collapsable, $contains_media, $tabs;

	/**
	 *	@param $hook (string; optional) - hook of the 'parent' if subpage
	 *	@param $title (string) - browser window title
	 *	@param $menu (string) - menu title
	 *	@param $icon (string) - URL to an icon, or name of a Dashicons helper class to use a font icon
	 *	@param $permissions (string) - capability a user must have to see the page
	 *	@param $slug (string) - slug identifier for this page
	 *	@param $priority (int) - priority for menu positioning
	 *	@param $body_content (string; optional) - callback that prints to the page, above the metaboxes
	 *	@param $sortable (boolean; optional; default = true) - whether the meta boxes should be sortable
	 *	@param $collapsable (boolean; optional; default = true) - whether the meta boxes should be collapsable
	 *	@param $contains_media (boolean; optional; default = true) - whether the page utilizes the media uploader
	 *	@param $tabs (array; optional; default = empty) - settings tabs
	 */
	function __construct( $hook = '', $title, $menu, $icon = '', $permissions, $slug, $priority, $body_content = '__return_true', $sortable = true, $collapsable = true, $contains_media = true, $tabs = array() ) {

		// Setup
		$this->hook = $hook;
		$this->title = $title;
		$this->menu = $menu;
		$this->icon = $icon;
		$this->permissions = $permissions;
		$this->slug = $slug;
		$this->priority = $priority;
		$this->body_content = $body_content;
		$this->sortable = $sortable;
		$this->collapsable = $collapsable;
		$this->contains_media = $contains_media;
		$this->tabs = $tabs;

		// Activate first tab when tabs are enabled
		add_action( 'admin_init', array( $this, 'activate_default_tab' ) );

		// Add the page
		add_action( 'admin_menu', array( $this, 'add_page' ) );

		// Add JavaScript
		if ( $this->contains_media === true ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );
		}
	}

	/**
	 *	Rertieve settings page slug for use elsewhere
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 *	If tabs are enabled, and no tab is clicked, automatically activate the first tab
	 */
	public function activate_default_tab() {

		// Bail if no tabs are set
		if ( ! $this->tabs ) {
			return;
		}

		// Bail if not on the proper page
		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== $this->slug ) {
			return;
		}

		// Bail if tab is set
		if ( isset( $_GET['tab'] ) ) {
			return;
		}

		$keys = array_keys( $this->tabs );

		// Redirect to activate the first tab
		wp_redirect( add_query_arg( 'tab', $keys[0] ) );
		exit;
	}

	/**
	 *	Adds the custom page.
	 *	Adds callbacks to the load-* and admin_footer-* hooks
	 */
	function add_page(){

		// Add the page
		if ( ! $this->hook ) {
			$this->page = add_menu_page( $this->title, $this->menu, $this->permissions, $this->slug, array( $this, 'render_page' ), $this->icon, $this->priority );
		} else {
			$this->page = add_submenu_page( $this->hook, $this->title, $this->menu, $this->permissions, $this->slug, array( $this, 'render_page'), $this->priority );
		}

		// Add callbacks for this screen only
		add_action( 'load-' . $this->page, array( $this, 'page_actions' ), 9 );
		add_action( 'admin_footer-' . $this->page, array( $this, 'footer_scripts' ) );
	}

	/**
	 *	Add JS files for media uploads (if enabled)
	 */
	function enqueue_admin_js() {
		wp_enqueue_media();
		wp_enqueue_script( 'media-upload' ); // Provides all the functions needed to upload, validate and give format to files.
		wp_enqueue_script( 'thickbox' ); // Responsible for managing the modal window.
		wp_enqueue_style( 'thickbox' ); // Provides the styles needed for this window.
		// wp_enqueue_script( 'script', plugins_url( 'upload.js', __FILE__), array( 'jquery' ), '', true ); //It will initialize the parameters needed to show the window properly.
	}

	/**
	 *	jQuery to initialize meta boxes and media uploads (if enabled)
	 *		call on admin_footer-*
	 */
	function footer_scripts(){ ?>
		<script>
			jQuery(document).ready( function($) {

				<?php if ( $this->sortable === false ) : // Sortable disabled ?>

				$('.meta-box-sortables').sortable({
					disabled: true
				});

				$('.postbox .hndle').css('cursor', 'pointer');

				<?php endif; ?>

				<?php if ( $this->collapsable === true ) : // Collapsing enabled (default) ?>

				postboxes.add_postbox_toggles(pagenow);

				<?php else : ?>

				$('.postbox .hndle').css('cursor', 'default');
				$('.handlediv.button-link').css({
					cursor: 'default',
					display: 'none'
				});

				<?php endif; ?>
			});
		</script>

		<?php if ( $this->contains_media === true ) : // Add media uploader (default) ?>

		<script>
			jQuery(document).ready(function($) {

				// Uploading files
				var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
				var set_to_post_id = 10; // Set this

				jQuery('.rv-media-uploader').live('click', function(event){

					event.preventDefault();

					var file_frame;
					var button = $(this);
					var id = button.attr('id').replace('_button', '');

					// If the media frame already exists, reopen it.
					if (file_frame) {

						// Set the post ID to what we want
						file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
						
						// Open frame
						file_frame.open();

						alert(id);
						
						return;

					} else {
						// Set the wp.media post id so the uploader grabs the ID we want when initialised
						wp.media.model.settings.post.id = set_to_post_id;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({

						title: button.data('uploader-title'),
						button: {
							text: button.data('uploader-button-text'),
						},
						multiple: false  // Set to true to allow multiple files to be selected
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {

						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();

						// Do something with attachment.id and/or attachment.url here
						$("#" + id).val(attachment.url);

						// Restore the main post ID
						wp.media.model.settings.post.id = wp_media_post_id;
					});

					// Finally, open the modal
					file_frame.open();
				});

				// Restore the main ID when the add media button is pressed
				$('a.add_media').on('click', function() {
					wp.media.model.settings.post.id = wp_media_post_id;
				});
			});
		</script>

		<?php endif;		
	}


	/*
	 *	Actions to be taken prior to page loading. This is after headers have been set.
     * 		call on load-$hook
	 *	This calls the add_meta_boxes hooks, adds screen options and enqueues the postbox.js script.   
	 */
	function page_actions(){

		do_action( 'add_meta_boxes_' . $this->page, null );
		do_action( 'add_meta_boxes', $this->page, null );

		// User can choose between 1 or 2 columns (default 2)
		add_screen_option( 'layout_columns', array( 'max' => 2, 'default' => 2 ) );

		// Enqueue WordPress' script for handling the metaboxes
		wp_enqueue_script( 'postbox' ) ; 
	}


	/**
	 *	Renders the settings page
	 */
	function render_page(){ ?>

		 <div class="wrap">

			<?php screen_icon(); ?>

			<h2><?php echo esc_html( $this->title );?></h2>

			<?php if ( isset( $_GET['settings-saved'] ) && $_GET['settings-saved'] == 'success' ) : ?>

				<div class="notice notice-success"><p><?php _e( 'Settings saved successfully!', 'curated-posts' ) ?></p></div>

			<?php endif; ?>

			<?php $this->render_tabs( isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '' ); ?>

			<form name="<?php echo $this->slug; ?>_settings_form" id="<?php echo $this->slug; ?>_settings_form" action="?<?php echo $this->slug; ?>_settings<?php if ( isset( $_GET['tab'] ) ) echo '&tab=' . $_GET['tab']; ?>" method="post">
				
				<?php wp_nonce_field( $this->slug . '_settings_nonce', $this->slug . '_settings_nonce' );

				// Used to save closed metaboxes and their order
				wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>

				<div id="poststuff">
		
					 <div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>"> 

						  <div id="post-body-content" class="postbox-container">
							<?php call_user_func( $this->body_content ); ?>
						  </div>    

						  <div id="postbox-container-1" class="postbox-container">
						        <?php do_meta_boxes( '', 'side', null ); ?>
						  </div>    

						  <div id="postbox-container-2" class="postbox-container">
						        <?php do_meta_boxes( '', 'normal', null );  ?>
						        <?php do_meta_boxes( '', 'advanced', null ); ?>
						  </div>	     					

					 </div> <!-- #post-body -->
				
				</div> <!-- #poststuff -->

	      	</form>			

		 </div><!-- .wrap -->

		<?php
	}

	/**
	 *	Generate the tabs
	 */
	public function render_tabs( $current = '' ) {

		if ( ! empty( $this->tabs ) ) : ?>

			<h2 class="nav-tab-wrapper">

				<?php foreach ( $this->tabs as $key => $tab ) : ?>

					<?php $active = ( $key == $current ) ? ' nav-tab-active' : ''; ?>

					<a href="<?php echo add_query_arg( 'tab', $key, CURATED_POSTS_PLUGIN_SETTINGS_PAGE_URL ); ?>" class="nav-tab<?php echo $active; ?>"><?php echo $tab; ?></a>

				<?php endforeach; ?>

			</h2>

		<?php endif;
	}
}

endif;
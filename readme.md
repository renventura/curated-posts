# Curated Posts

Manually curate a list of featured posts for each post category.

## Installation ##

__Manually__

1. Download the zip file, unzip it and upload plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Frequently Asked Questions ##

*How do I use this plugin?*

This plugin adds a settings page ("Curated Posts") under the "Posts" menu. Under the Categories tab, you can add any number of posts to any of the post categories registered on your site.

To output the curated posts on your website, you need to run a simple function in your theme. If you want to add the posts to a sidebar, for example, you would add them to your theme's sidebar.php file, or via a hook, if available.

Genesis Framework example:

```php
/**
 *	Output a maximum of 3 curated posts after entry content, with no columns (Genesis)
 *
 *	@uses curated_posts_output( $max = 3, $columns = 3, $column_class = 'one-third', $first_class = 'first' )
 */
add_action( 'genesis_after_entry_content', 'curated_posts_genesis_after_entry_content' );
function curated_posts_genesis_after_entry_content() {
	curated_posts_output( 3, 0 );
}
```

You can also use the following shortcode in a post's content editor, or a text widget:

```
[curated_posts max="3" columns="3" column_class="one-third" first_class="first"]
```

## Bugs ##
If you find an issue, let me know!

## Changelog ##

__1.0__
* Initial version
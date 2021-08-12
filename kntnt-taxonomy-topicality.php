<?php


/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Topicality Taxonomy
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Provides the `topicality` taxonomy whose terms indicate the topicality of a post (e.g. `evergreen`, `up to date`, `outdated`, and `obsolete`).
 * Version:           1.0.2
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


namespace Kntnt\Topicality;


defined( 'ABSPATH' ) && new Taxonomy;


class Taxonomy {

	public function __construct() {
		add_action( 'init', [ $this, 'run' ] );
	}

	public function run() {

		$slug = apply_filters( 'kntnt-taxonomy-topicality-slug', 'topicality' );
		$post_types = apply_filters( 'kntnt-taxonomy-topicality-objects', [ 'post' ] );

		register_taxonomy( $slug, null, $this->taxonomy( $slug ) );

		foreach ( $post_types as $post_type ) {
			register_taxonomy_for_object_type( $slug, $post_type );
		}

		add_filter( 'term_updated_messages', [ $this, 'term_updated_messages' ] );

	}

	private function taxonomy( $slug ) {
		return [

			// A short descriptive summary of what the taxonomy is for.
			'description' => _x( 'Topicality is a taxonomy used as post metadata. Its terms denote the topicality of the content. For example, Evergreen, Up to date, Outdated, and Obsolete.', 'Description', 'kntnt-taxonomy-topicality' ),

			// Whether the taxonomy is hierarchical.
			'hierarchical' => false,

			// Whether a taxonomy is intended for use publicly either via
			// the admin interface or by front-end users.
			'public' => true,

			// Whether the taxonomy is publicly queryable.
			'publicly_queryable' => true,

			// Whether to generate and allow a UI for managing terms in this
			// taxonomy in the admin.
			'show_ui' => true,

			// Whether to show the taxonomy in the admin menu.
			'show_in_menu' => true,

			// Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => true,

			// Whether to list the taxonomy in the Tag Cloud Widget controls.
			'show_tagcloud' => false,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => true,

			// Whether to display a column for the taxonomy on its post
			// type listing screens.
			'show_admin_column' => true,

			// Metabox to show on edit. If a callable, it is called to render
			// the metabox. If `null` the default metabox is used. If `false`,
			// no metabox is shown.
			'meta_box_cb' => false,

			// Array of capabilities for this taxonomy.
			'capabilities' => [
				'manage_terms' => 'edit_posts',
				'edit_terms' => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			],

			// Sets the query var key for this taxonomy. Default $taxonomy key.
			// If false, a taxonomy cannot be loaded
			// at ?{query_var}={term_slug}. If a string,
			// the query ?{query_var}={term_slug} will be valid.
			'query_var' => true,

			// Triggers the handling of rewrites for this taxonomy.
			// Replace the array with false to prevent handling of rewrites.
			'rewrite' => [

				// Customize the permastruct slug.
				'slug' => $slug,

				// Whether the permastruct should be prepended
				// with WP_Rewrite::$front.
				'with_front' => true,

				// Either hierarchical rewrite tag or not.
				'hierarchical' => false,

				// Endpoint mask to assign. If null and permalink_epmask
				// is set inherits from $permalink_epmask. If null and
				// permalink_epmask is not set, defaults to EP_PERMALINK.
				'ep_mask' => null,

			],

			// Default term to be used for the taxonomy.
			'default_term' => null,

			// An array of labels for this taxonomy.
			'labels' => [
				'name' => _x( 'Topicality', 'Plural name', 'kntnt-taxonomy-topicality' ),
				'singular_name' => _x( 'Topicality', 'Singular name', 'kntnt-taxonomy-topicality' ),
				'search_items' => _x( 'Search topicality', 'Search items', 'kntnt-taxonomy-topicality' ),
				'popular_items' => _x( 'Search topicality', 'Search items', 'kntnt-taxonomy-topicality' ),
				'all_items' => _x( 'All topicality', 'All items', 'kntnt-taxonomy-topicality' ),
				'parent_item' => _x( 'Parent topicality', 'Parent item', 'kntnt-taxonomy-topicality' ),
				'parent_item_colon' => _x( 'Parent topicality colon', 'Parent item colon', 'kntnt-taxonomy-topicality' ),
				'edit_item' => _x( 'Edit topicality', 'Edit item', 'kntnt-taxonomy-topicality' ),
				'view_item' => _x( 'View topicality', 'View item', 'kntnt-taxonomy-topicality' ),
				'update_item' => _x( 'Update topicality', 'Update item', 'kntnt-taxonomy-topicality' ),
				'add_new_item' => _x( 'Add new topicality', 'Add new item', 'kntnt-taxonomy-topicality' ),
				'new_item_name' => _x( 'New topicality name', 'New item name', 'kntnt-taxonomy-topicality' ),
				'separate_items_with_commas' => _x( 'Separate topicality with commas', 'Separate items with commas', 'kntnt-taxonomy-topicality' ),
				'add_or_remove_items' => _x( 'Add or remove topicality', 'Add or remove items', 'kntnt-taxonomy-topicality' ),
				'choose_from_most_used' => _x( 'Choose from most used', 'Choose from most used', 'kntnt-taxonomy-topicality' ),
				'not_found' => _x( 'Not found', 'Not found', 'kntnt-taxonomy-topicality' ),
				'no_terms' => _x( 'No terms', 'No terms', 'kntnt-taxonomy-topicality' ),
				'items_list_navigation' => _x( 'Topicality list navigation', 'Items list navigation', 'kntnt-taxonomy-topicality' ),
				'items_list' => _x( 'Items list', 'Topicality list', 'kntnt-taxonomy-topicality' ),
				'most_used' => _x( 'Most used', 'Most used', 'kntnt-taxonomy-topicality' ),
				'back_to_items' => _x( 'Back to topicality', 'Back to items', 'kntnt-taxonomy-topicality' ),
			],

		];
	}

	public function term_updated_messages( $messages ) {
		$messages['topicality'] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Topicality added.', 'kntnt-taxonomy-topicality' ),
			2 => __( 'Topicality deleted.', 'kntnt-taxonomy-topicality' ),
			3 => __( 'Topicality updated.', 'kntnt-taxonomy-topicality' ),
			4 => __( 'Topicality not added.', 'kntnt-taxonomy-topicality' ),
			5 => __( 'Topicality not updated.', 'kntnt-taxonomy-topicality' ),
			6 => __( 'Topicality deleted.', 'kntnt-taxonomy-topicality' ),
		];
		return $messages;
	}

}
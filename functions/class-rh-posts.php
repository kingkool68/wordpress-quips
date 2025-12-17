<?php
use voku\helper\HtmlDomParser;
/**
 * Handles blog posts
 */
class RH_Posts {

	/**
	 * The post type
	 *
	 * @var string
	 */
	public static $post_type = 'post';

	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
			$instance->setup_actions();
			$instance->setup_filters();
		}
		return $instance;
	}

	/**
	 * Hook in to WordPress via actions
	 */
	public function setup_actions() {
		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
		add_action( 'admin_bar_menu', array( $this, 'action_admin_bar_menu' ), 999 );
	}

	/**
	 * Hook in to WordPress via filters
	 */
	public function setup_filters() {}

	/**
	 * Hide the post post type
	 */
	public function action_init() {
		unregister_taxonomy_for_object_type( 'category', 'post' );
		unregister_taxonomy_for_object_type( 'post_tag', 'post' );

		$caps = array(
			'create_posts',
			'edit_posts',
			'edit_others_posts',
			'publish_posts',
			'delete_posts',
			'delete_others_posts',
		);

		foreach ( $caps as $cap ) {
			$role = get_role( 'administrator' );
			if ( $role ) {
				$role->remove_cap( $cap );
			}
		}
	}

	/**
	 * Remove the Posts menu
	 */
	public function action_admin_menu() {
		remove_menu_page( 'edit.php' );
	}

	/**
	 * Remove "New Post" menu from admin bar
	 *
	 * @param  WP_Admin_Bar $wp_admin_bar The admin bar object to modify
	 */
	public function action_admin_bar_menu( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'new-post' );
	}
}
RH_Posts::get_instance();

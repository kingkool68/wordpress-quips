<?php
/**
 * All things Quips
 */
class RH_Quips {

	/**
	 * The post type
	 *
	 * @var string
	 */
	public static $post_type = 'quip';

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
	}

	/**
	 * Hook in to WordPress via filters
	 */
	public function setup_filters() {}

	/**
	 * Register things
	 */
	public function action_init() {
		wp_register_style(
			'rh-page-post',
			get_template_directory_uri() . '/assets/css/rh-page-post.min.css',
			$deps  = array(),
			$ver   = null,
			$media = 'all'
		);

		wp_register_script(
			'rh-page-post',
			get_template_directory_uri() . '/assets/js/rh-page-post.js',
			$deps      = array(),
			$ver       = null,
			$in_footer = true
		);

		$args = array(
			'labels'              => RH_Helpers::generate_post_type_labels( 'quip', 'quips' ),
			'supports'            => array( 'editor', 'revisions', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-comments',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => array(
				'slug'       => 'quip',
				'with_front' => false,
			),
		);
		register_post_type( static::$post_type, $args );
	}

	/**
	 * Disable the block editor for the post type but keep the REST API available
	 *
	 * @param  boolean $use_block_editor Whether to use the block editor or not
	 * @param  string  $post_type The post type to check for block editor support
	 */
	public function filter_use_block_editor_for_post_type( $use_block_editor = true, $post_type = '' ) {
		if ( $post_type === static::$post_type ) {
			return false;
		}
		return $use_block_editor;
	}
}
RH_Quips::get_instance();

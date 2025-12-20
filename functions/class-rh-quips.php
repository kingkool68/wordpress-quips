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
	public function setup_filters() {
		add_filter( 'use_block_editor_for_post_type', array( $this, 'filter_use_block_editor_for_post_type' ), 10, 2 );
		add_filter( 'wp_insert_post_data', array( $this, 'filter_wp_insert_post_data' ), 11, 2 );
	}

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

	/**
	 * Set the post title and post_name to the next incremental number when new quip posts are inserted
	 *
	 * @param  array $data Post data about to be saved to the database
	 * @param  array $postarr Submitted $_POST data
	 */
	public function filter_wp_insert_post_data( $data = array(), $postarr = array() ) {
		if ( $data['post_type'] === static::$post_type && empty( $postarr['ID'] ) ) {
			$sequential_id      = static::get_next_sequential_id();
			$data['post_title'] = $sequential_id;
			$data['post_name']  = $sequential_id;
		}
		return $data;
	}

	/**
	 * Get the stored sequential ID option and increments the value by one
	 */
	public static function get_next_sequential_id() {
		$option_name   = 'quip_sequential_id';
		$sequential_id = get_option( $option_name );

		// If false (aka not found), recalculate it from the existing quips
		if ( ! $sequential_id ) {
			$sequential_id = static::recalculate_sequential_id();
		}

		$next_sequential_id = absint( $sequential_id ) + 1;
		update_option( $option_name, $next_sequential_id, $autoload = false );
		return $next_sequential_id;
	}

	/**
	 * Recalculate the sequential ID by querying published quips
	 */
	public static function recalculate_sequential_id() {
		$latest_quip = get_posts(
			array(
				'post_type'              => static::$post_type,
				'post_status'            => 'publish',
				'posts_per_page'         => 1,
				'orderby'                => 'name',
				'order'                  => 'DESC',

				// For performance
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
		if ( empty( $latest_quip[0]->post_name ) ) {
			return 0;
		}
		return absint( $latest_quip[0]->post_name );
	}
}
RH_Quips::get_instance();

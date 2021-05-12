<?php
namespace JSF_SBID;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main file
 */
class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	private $search_query = null;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	public function maybe_hook_id_clause( $query ) {
		$data = isset( $_REQUEST['query'] ) ? $_REQUEST['query'] : array();

		foreach ( $data as $key => $value ) {
			if ( false !== strpos( $key, '|search' ) ) {
				$this->search_query = absint( $value );
				add_filter( 'posts_where', array( $this, 'add_id_clause' ), 10, 2 );
			}
		}

		return $query;
	}

	public function add_id_clause( $where, $query ) {

		if ( empty( $query->query_vars['jet_smart_filters'] ) && empty( $query->query_vars['jsf'] ) ) {
			return $where;
		}

		if ( $this->search_query ) {

			global $wpdb;

			$where .= " OR ( ";

			if ( $exact_match = apply_filters( 'jet-smart-filters-search-by-id/exact-match', true ) ) {
				$where .= "{$wpdb->posts}.ID = {$this->search_query}";
			} else {
				$where .= "{$wpdb->posts}.ID LIKE '%{$this->search_query}%'";
			}

			if ( ! empty( $query->query['post_type'] ) ) {
				if ( is_array( $query->query['post_type'] ) ) {

					$post_types = array_map( function( $item ) {
						$item = esc_sql( $item );
						return "'{$item}'";
					}, $query->query['post_type'] );

					$post_types = implode( ', ', $post_types );
					$where .= " AND {$wpdb->posts}.post_type IN ( {$post_types} )";

				} else {

					$post_type = esc_sql( $query->query['post_type'] );
					$where .= " AND {$wpdb->posts}.post_type IN ( '{$post_type}' )";

				}
			}

			$where .= " AND {$wpdb->posts}.post_status = 'publish' )";

			$this->search_query = null;
			remove_filter( 'posts_where', array( $this, 'add_id_clause' ), 10, 2 );

		}

		return $where;

	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		add_action( 'jet-smart-filters/query/final-query', array( $this, 'maybe_hook_id_clause' ) );
	}

}

Plugin::instance();

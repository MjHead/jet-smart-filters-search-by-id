<?php
/**
 * Plugin Name: JetSmartFilters - search posts by ID
 * Plugin URI:  https://crocoblock.com/
 * Description: Allow to search by post ID in the JetSmartFilter's search filter
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'plugins_loaded', function() {

	define( 'JSF_SBID_VERSION', '1.0.0' );

	define( 'JSF_SBID__FILE__', __FILE__ );
	define( 'JSF_SBID_PATH', plugin_dir_path( JSF_SBID__FILE__ ) );

	require JSF_SBID_PATH . 'includes/plugin.php';

} );

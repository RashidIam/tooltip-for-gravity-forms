<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.facebook.com/disismehbub
 * @since             1.0.0
 * @package           Gravity_Forms_Tooltip
 *
 * @wordpress-plugin
 * Plugin Name:       Tooltip for Gravity Forms
 * Description:       Add Tooltips next to field labels of Gravity Forms.
 * Version:           1.1
 * Author:            Mehbub Rashid
 * Author URI:        https://www.facebook.com/disismehbub
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tooltip-for-gravity-forms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GRAVITY_FORMS_TOOLTIP_VERSION', '1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gravity-forms-tooltip-activator.php
 */
function activate_gravity_forms_tooltip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gravity-forms-tooltip-activator.php';

	/* Set transient if gravity forms plugin is not active*/
	if ( !is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
		set_transient( 'gravitychecker', true, 5 );
	}
	else {
		delete_transient( 'tooltip_update_checker' );
		if ( ! get_option('tooltip_plugin_version') ) {
			add_option('tooltip_plugin_version', GRAVITY_FORMS_TOOLTIP_VERSION);
		}
		else {
			update_option('tooltip_plugin_version', GRAVITY_FORMS_TOOLTIP_VERSION);
		}
		Gravity_Forms_Tooltip_Activator::activate();
	}
}

add_action( 'admin_notices', 'gravitychecker' );
/* If not gravity form active,show a message */
function gravitychecker(){

    /* Check transient, if available display notice */
    if( get_transient( 'gravitychecker' ) ){
        ?>
        <div class="error is-dismissible"><p><?php echo esc_html__( 'Gravity Forms plugin is required to activate Tooltip for Gravity Forms plugin.', 'tooltip-for-gravity-forms' ); ?></p></div>
		<?php
		deactivate_plugins( plugin_basename( __FILE__ ) );
        /* Delete transient, only display this notice once. */
        delete_transient( 'gravitychecker' );
    }
}



/* Deactivate this plugin when admin deactivates gravity forms plugin */
function detect_plugin_deactivation( $plugin, $network_activation ) {
    if ($plugin=="gravityforms/gravityforms.php")
    {
        set_transient( 'gravitychecker', true, 5 );
    }
}
add_action( 'deactivated_plugin', 'detect_plugin_deactivation', 10, 2 );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gravity-forms-tooltip-deactivator.php
 */
function deactivate_gravity_forms_tooltip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gravity-forms-tooltip-deactivator.php';
	Gravity_Forms_Tooltip_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gravity_forms_tooltip' );
register_deactivation_hook( __FILE__, 'deactivate_gravity_forms_tooltip' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gravity-forms-tooltip.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gravity_forms_tooltip() {

	$plugin = new Gravity_Forms_Tooltip();
	$plugin->run();

}
run_gravity_forms_tooltip();

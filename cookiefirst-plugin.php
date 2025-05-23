<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cookiefirst.com
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       CookieFirst Cookie Consent Banner
 * Plugin URI:        https://cookiefirst.com/wp-plugin
 * Description:       This Plugin enables the cookiefirst.com cookie banner on your website. Create a free account at cookiefirst.com to get started.
 * Version:           2.0.0
 * Author:            CookieFirst
 * Author URI:        https://www.cookiefirst.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cookiefirst-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('COOKIEFIRST_PLUGIN_VERSION', '2.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cookiefirst-plugin-activator.php
 */
function activate_cookiefirst_plugin()
{
    require_once plugin_dir_path(__FILE__).'includes/class-cookiefirst-plugin-activator.php';
    Cookiefirst_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cookiefirst-plugin-deactivator.php
 */
function deactivate_cookiefirst_plugin()
{
    require_once plugin_dir_path(__FILE__).'includes/class-cookiefirst-plugin-deactivator.php';
    Cookiefirst_Plugin_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_cookiefirst_plugin');
register_deactivation_hook(__FILE__, 'deactivate_cookiefirst_plugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-cookiefirst-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cookiefirst_plugin()
{

    $plugin = new Cookiefirst_Plugin;
    $plugin->run();
}

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://cookiefirst.com/plugins/cookiefirst-wp-plugin.json',
    __FILE__, // Full path to the main plugin file or functions.php.
    'cookiefirst-plugin'
);

run_cookiefirst_plugin();

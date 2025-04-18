<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://cookiefirst.com
 * @since      1.0.0
 *
 * @package    Cookiefirst_Plugin
 * @subpackage Cookiefirst_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cookiefirst_Plugin
 * @subpackage Cookiefirst_Plugin/includes
 * @author     CookieFirst <rafal@cookiefirst.com>
 */
class Cookiefirst_Plugin_i18n
{

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'cookiefirst-plugin',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }

}

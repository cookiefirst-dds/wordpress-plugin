<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cookiefirst.com
 * @since      1.0.0
 *
 * @package    Cookiefirst_Plugin
 * @subpackage Cookiefirst_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cookiefirst_Plugin
 * @subpackage Cookiefirst_Plugin/includes
 * @author     CookieFirst <rafal@cookiefirst.com>
 */
class Cookiefirst_Plugin
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Cookiefirst_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('COOKIEFIRST_PLUGIN_VERSION')) {
            $this->version = COOKIEFIRST_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'cookiefirst-plugin';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Cookiefirst_Plugin_Loader. Orchestrates the hooks of the plugin.
     * - Cookiefirst_Plugin_i18n. Defines internationalization functionality.
     * - Cookiefirst_Plugin_Admin. Defines all hooks for the admin area.
     * - Cookiefirst_Plugin_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cookiefirst-plugin-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cookiefirst-plugin-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cookiefirst-plugin-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cookiefirst-plugin-public.php';

        $this->loader = new Cookiefirst_Plugin_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Cookiefirst_Plugin_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Cookiefirst_Plugin_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Cookiefirst_Plugin_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_action('admin_menu', $this, 'cf_add_admin_menu');
        $this->loader->add_action('admin_init', $this, 'cf_settings_init');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Cookiefirst_Plugin_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        add_shortcode('cf_declaration', [$this, 'cf_declaration']);
        $this->loader->add_action('wp_enqueue_scripts', $this, 'cf_footer_script');
        if (function_exists('register_block_type')) {
            $this->loader->add_action('init', $this, 'cf_policy_block');
        }
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Cookiefirst_Plugin_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * CookieFirst Policy Gutenberg Block function.
     *
     * @since     1.0.0
     * @return    void    Echoes CookieFirst Policy container
     */
    public function cf_policy_block()
    {
        wp_register_script(
            'cookiefirst-policy',
            plugins_url('block.js', __FILE__),
            array('wp-blocks', 'wp-element')
        );

        register_block_type('cookiefirst/policy', array(
            'editor_script' => 'cookiefirst-policy',
        ));
    }

    /**
     * CookieFirst Policy shortcode function.
     *
     * @since     1.0.0
     * @return    void    Echoes CookieFirst Policy container
     */
    public function cf_declaration($atts = [])
    {
        echo '<div id="cookiefirst-policy-page"></div>';
    }

    /**
     * CookieFirst WP-Admin Setting menu item.
     *
     * @since     1.0.0
     * @return    void    Adds a menu item to wp-admin
     */
    public function cf_add_admin_menu()
    {

        add_submenu_page('options-general.php', 'CookieFirst', 'CookieFirst', 'manage_options', 'cookiefirst', [$this, 'cf_options_page']);
    }

    /**
     * CookieFirst WP-Admin Setting page.
     *
     * @since     1.0.0
     * @return    void    Initializes the CookieFirst Plugin setting page.
     */
    public function cf_settings_init()
    {
        register_setting('pluginPage', 'cf_settings', [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_cf_settings']
        ]);

        add_settings_section(
            'cf_pluginPage_section',
            __('', 'cf'),
            [$this, 'cf_settings_section_callback'],
            'cookiefirst'
        );

        add_settings_field(
            'cf_api_key_field',
            __('Your api key', 'cookiefirst'),
            [$this, 'cf_render_api_key_field'],
            'cookiefirst',
            'cf_pluginPage_section'
        );

        add_settings_field(
            'cf_script_loading',
            __('Load cookie banner asynchronous or deferred?', 'cookiefirst'),
            [$this, 'cf_render_script_loading_field'],
            'cookiefirst',
            'cf_pluginPage_section'
        );

        add_settings_field(
            'cf_should_show_in_preview',
            __('Should render banner in preview?', 'cookiefirst'),
            [$this, 'cf_render_should_show_in_preview_field'],
            'cookiefirst',
            'cf_pluginPage_section'
        );

        add_settings_field(
            'cf_debug_mode',
            __('Enable debug mode', 'cookiefirst'),
            [$this, 'cf_render_debug_mode_field'],
            'cookiefirst',
            'cf_pluginPage_section'
        );

        // Always add the WordPress Consent API section
        add_settings_section(
            'cf_wp_consent_section',
            __('WordPress Consent API Settings', 'cookiefirst'),
            [$this, 'cf_wp_consent_section_callback'],
            'cookiefirst'
        );

        // Only add fields if WP Consent API is active
        if ($this->is_wp_consent_api_active()) {
            add_settings_field(
                'cf_consent_type',
                __('Consent type', 'cookiefirst'),
                [$this, 'cf_render_consent_type_field'],
                'cookiefirst',
                'cf_wp_consent_section'
            );

            // Add category mapping fields
            add_settings_field(
                'cf_marketing_mapping',
                __('Advertising Category', 'cookiefirst'),
                [$this, 'cf_render_marketing_mapping_field'],
                'cookiefirst',
                'cf_wp_consent_section'
            );

            add_settings_field(
                'cf_functional_mapping',
                __('Functional Category', 'cookiefirst'),
                [$this, 'cf_render_functional_mapping_field'],
                'cookiefirst',
                'cf_wp_consent_section'
            );

            add_settings_field(
                'cf_statistics_mapping',
                __('Performance Category', 'cookiefirst'),
                [$this, 'cf_render_statistics_mapping_field'],
                'cookiefirst',
                'cf_wp_consent_section'
            );

            add_settings_field(
                'cf_preferences_mapping',
                __('Necessary Category', 'cookiefirst'),
                [$this, 'cf_render_preferences_mapping_field'],
                'cookiefirst',
                'cf_wp_consent_section'
            );
        }
    }

    /**
     * Check if the WordPress Consent API plugin is active
     *
     * @return bool
     */
    private function is_wp_consent_api_active()
    {
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        return is_plugin_active('wp-consent-api/wp-consent-api.php');
    }

    /**
     * Sanitize the settings before saving
     *
     * @param array $input The input settings
     * @return array The sanitized settings
     */
    public function sanitize_cf_settings($input)
    {
        $sanitized = [];
        
        // Sanitize API key
        if (isset($input['api_key'])) {
            $sanitized['api_key'] = sanitize_text_field($input['api_key']);
        }
        
        // Sanitize consent type
        if (isset($input['consent_type'])) {
            $sanitized['consent_type'] = in_array($input['consent_type'], ['opt-in', 'opt-out']) 
                ? $input['consent_type'] 
                : 'opt-in';
        }
        
        // Sanitize script loading
        if (isset($input['script_loading'])) {
            $sanitized['script_loading'] = in_array($input['script_loading'], ['do-nothing', 'async', 'defer', 'async-defer']) 
                ? $input['script_loading'] 
                : 'do-nothing';
        }
        
        // Sanitize checkboxes
        $sanitized['should_show_in_preview'] = isset($input['should_show_in_preview']) ? 'true' : '';
        $sanitized['debug_mode'] = isset($input['debug_mode']) ? 'true' : '';
        
        // Sanitize category mappings
        $valid_categories = ['marketing', 'functional', 'statistics', 'statistics-anonymous', 'preferences'];
        
        if (isset($input['marketing_mapping']) && is_array($input['marketing_mapping'])) {
            $sanitized['marketing_mapping'] = array_intersect($input['marketing_mapping'], $valid_categories);
        }
        
        if (isset($input['functional_mapping']) && is_array($input['functional_mapping'])) {
            $sanitized['functional_mapping'] = array_intersect($input['functional_mapping'], $valid_categories);
        }
        
        if (isset($input['statistics_mapping']) && is_array($input['statistics_mapping'])) {
            $sanitized['statistics_mapping'] = array_intersect($input['statistics_mapping'], $valid_categories);
        }
        
        if (isset($input['preferences_mapping']) && is_array($input['preferences_mapping'])) {
            $sanitized['preferences_mapping'] = array_intersect($input['preferences_mapping'], $valid_categories);
        }
        
        return $sanitized;
    }

    /**
     * CookieFirst WP-Admin Setting field.
     *
     * @since     1.0.0
     * @return    void    Renders CookieFirst Plugin ApiKey field.
     */
    public function cf_render_api_key_field()
    {
        $options = get_option('cf_settings');

        $value = $options && array_key_exists('api_key', $options) ? $options['api_key'] : '';
        echo "<input type='text' name='cf_settings[api_key]' value='" . $value . "'>";
        echo '<p class="description">' . __('To get your api key, login to your account at <a href="https://app.cookiefirst.com" target="_blank">https://app.cookiefirst.com</a> and go to the domain settings and then in the left menu "Your embed script". Copy the key and put it in this field to enable the cookie banner on your website.', 'cookiefirst') . '</p>';
    }

    /**
     * CookieFirst WP-Admin Setting field for consent type.
     *
     * @since     1.0.0
     * @return    void    Renders CookieFirst Plugin consent type field.
     */
    public function cf_render_consent_type_field()
    {
        $options = get_option('cf_settings');
        $value = $options && array_key_exists('consent_type', $options) ? $options['consent_type'] : 'opt-in';
        
        echo '<select name="cf_settings[consent_type]">';
        echo '<option value="opt-in" ' . selected($value, 'opt-in', false) . '>' . __('Opt-in (GDPR)', 'cookiefirst') . '</option>';
        echo '<option value="opt-out" ' . selected($value, 'opt-out', false) . '>' . __('Opt-out', 'cookiefirst') . '</option>';
        echo '</select>';
        echo '<p class="description">' . __('Choose whether your users should opt-in for cookies (GDPR) or Opt-out.', 'cookiefirst') . '</p>';
    }

    /**
     * CookieFirst WP-Admin Setting field for script loading method.
     *
     * @since     1.0.0
     * @return    void    Renders CookieFirst Plugin script loading field.
     */
    public function cf_render_script_loading_field()
    {
        $options = get_option('cf_settings');
        $value = $options && array_key_exists('script_loading', $options) ? $options['script_loading'] : 'do-nothing';
        
        echo '<select name="cf_settings[script_loading]">';
        echo '<option value="do-nothing" ' . selected($value, 'do-nothing', false) . '>' . __('Do nothing', 'cookiefirst') . '</option>';
        echo '<option value="async" ' . selected($value, 'async', false) . '>' . __('Async', 'cookiefirst') . '</option>';
        echo '<option value="defer" ' . selected($value, 'defer', false) . '>' . __('Defer', 'cookiefirst') . '</option>';
        echo '<option value="async-defer" ' . selected($value, 'async-defer', false) . '>' . __('Async + Defer', 'cookiefirst') . '</option>';
        echo '</select>';
        echo '<p class="description">' . __('Loading the cookie banner async or deferred might speed up load times but features like autoblocking and Google consent mode might not work properly if not set before Google tags are running.', 'cookiefirst') . '</p>';
    }

    /**
     * CookieFirst WP-Admin Setting field.
     *
     * @since     1.0.0
     * @return    void    Renders CookieFirst Plugin shuold show in preview field.
     */
    public function cf_render_should_show_in_preview_field()
    {
        $options = get_option('cf_settings');
        $value = $options && array_key_exists('should_show_in_preview', $options) ? $options['should_show_in_preview'] : '';
        echo "<input type='checkbox' name='cf_settings[should_show_in_preview]' value='true'" . checked($value, 'true', false) . ">";
    }

    /**
     * CookieFirst WP-Admin Setting field for debug mode.
     *
     * @since     1.0.0
     * @return    void    Renders CookieFirst Plugin debug mode field.
     */
    public function cf_render_debug_mode_field()
    {
        $options = get_option('cf_settings');
        $value = $options && array_key_exists('debug_mode', $options) ? $options['debug_mode'] : '';
        echo "<input type='checkbox' name='cf_settings[debug_mode]' value='true'" . checked($value, 'true', false) . ">";
        echo '<p class="description">' . __('You can check whether the consent is set correctly.', 'cookiefirst') . '</p>';
    }

    /**
     * CookieFirst WP-Admin Settings text.
     *
     * @since     1.0.0
     * @return    void    Renders CookieFirst Plugin intro text.
     */
    public function cf_settings_section_callback()
    {
        ?>
        <div class="cookiefirst-section-intro">
            <p><?php echo __('Connect your website with CookieFirst by entering your API key below. This will enable the cookie consent banner and management system on your website.', 'cookiefirst'); ?></p>
            <p class="cookiefirst-help-text"><?php echo __('Need help? Visit our <a href="https://support.cookiefirst.com" target="_blank">support center</a> or contact our support team.', 'cookiefirst'); ?></p>
        </div>
        <style>
            .cookiefirst-section-intro {
                background: #f0f6fc;
                border-left: 4px solid #2271b1;
                padding: 16px;
                margin-bottom: 24px;
                border-radius: 0 4px 4px 0;
            }

            .cookiefirst-section-intro p {
                margin: 0 0 8px;
                font-size: 14px;
            }

            .cookiefirst-section-intro p:last-child {
                margin-bottom: 0;
            }

            .cookiefirst-help-text {
                color: #757575;
            }

            .cookiefirst-help-text a {
                color: #2271b1;
                text-decoration: none;
            }

            .cookiefirst-help-text a:hover {
                text-decoration: underline;
            }
        </style>
        <?php
    }

    /**
     * CookieFirst WP-Admin Setting form.
     *
     * @since     1.0.0
     * @return    void    Renders CookieFirst Plugin Settings Form.
     */
    public function cf_options_page()
    {
        ?>
        <div class="wrap">
            <div class="cookiefirst-container">
                <div class="cookiefirst-header">
                    <h1><?php echo __('CookieFirst Integration', 'cookiefirst'); ?></h1>
                    <p class="cookiefirst-subtitle"><?php echo __('Configure your CookieFirst cookie banner and consent management settings.', 'cookiefirst'); ?></p>
                </div>

                <div class="cookiefirst-main-settings">
                    <form action='options.php' method='post'>
                        <?php
                        settings_fields('pluginPage');
                        ?>
                        <div class="cookiefirst-settings-grid">
                            <?php do_settings_sections('cookiefirst'); ?>
                        </div>
                        <?php submit_button(__('Save Settings', 'cookiefirst'), 'primary', 'submit', true, ['id' => 'cookiefirst-save-settings']); ?>
                    </form>
                </div>
            </div>
        </div>

        <style>
            .cookiefirst-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }

            .cookiefirst-header {
                margin-bottom: 30px;
                border-bottom: 1px solid #e2e4e7;
                padding-bottom: 20px;
            }

            .cookiefirst-subtitle {
                font-size: 14px;
                color: #757575;
                margin-top: 0;
            }

            .cookiefirst-main-settings {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                padding: 24px;
            }

            .cookiefirst-settings-grid {
                display: grid;
                gap: 24px;
            }

            /* Form element styling */
            .form-table {
                margin: 0;
            }

            .form-table th {
                padding: 20px 10px 20px 0;
                width: 200px;
                font-weight: 600;
            }

            .form-table td {
                padding: 20px 10px;
            }

            /* Tag input styling */
            .cf-tag-input-container {
                width: 100%;
                max-width: 500px;
            }

            .cf-tag-input {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                background: #fff;
                min-height: 40px;
                position: relative;
            }

            .cf-tag {
                display: inline-flex;
                align-items: center;
                padding: 4px 8px;
                background: #f0f0f1;
                border: 1px solid #c3c4c7;
                border-radius: 2px;
                font-size: 13px;
                line-height: 1.4;
            }

            .cf-tag-remove {
                background: none;
                border: none;
                color: #d63638;
                cursor: pointer;
                font-size: 16px;
                line-height: 1;
                margin-left: 4px;
                padding: 0;
            }

            .cf-tag-remove:hover {
                color: #dc3232;
            }

            .cf-tag-input-field {
                flex: 1;
                min-width: 120px;
                border: none;
                outline: none;
                padding: 4px;
                font-size: 13px;
            }

            .cf-tag-input-field:focus {
                box-shadow: none;
            }

            .cf-tag-input-field::placeholder {
                color: #757575;
            }

            .cf-suggestions {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                border: 1px solid #8c8f94;
                border-top: none;
                border-radius: 0 0 4px 4px;
                max-height: 200px;
                overflow-y: auto;
                z-index: 100;
                display: none;
            }

            .cf-suggestion {
                padding: 8px 12px;
                cursor: pointer;
                font-size: 13px;
            }

            .cf-suggestion:hover {
                background: #f0f0f1;
            }

            #cookiefirst-save-settings {
                margin-top: 24px;
                padding: 8px 24px;
                height: auto;
            }
        </style>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.cf-tag-input').each(function() {
                    const $container = $(this);
                    const $input = $container.find('.cf-tag-input-field');
                    const $suggestions = $container.find('.cf-suggestions');
                    const categories = JSON.parse($container.attr('data-categories'));
                    const fieldName = $container.closest('td').find('input[type="hidden"]').attr('name').replace('[]', '');

                    function showSuggestions() {
                        const availableCategories = categories.filter(cat => 
                            !$container.find(`input[value="${cat}"]`).length
                        );
                        
                        if (availableCategories.length > 0) {
                            $suggestions.empty();
                            availableCategories.forEach(cat => {
                                const displayName = cat === 'statistics-anonymous' ? 'Statistics Anonymous' : cat.replace('-', ' ');
                                $suggestions.append(
                                    `<div class="cf-suggestion" data-category="${cat}">${displayName}</div>`
                                );
                            });
                            $suggestions.show();
                        } else {
                            $suggestions.hide();
                        }
                    }

                    // Show suggestions on focus
                    $input.on('focus', showSuggestions);

                    // Hide suggestions when clicking outside
                    $(document).on('click', function(e) {
                        if (!$(e.target).closest('.cf-tag-input').length) {
                            $suggestions.hide();
                        }
                    });

                    // Handle input
                    $input.on('input', function() {
                        const value = $(this).val().toLowerCase();
                        if (value) {
                            const matches = categories.filter(cat => 
                                cat.toLowerCase().includes(value) && 
                                !$container.find(`input[value="${cat}"]`).length
                            );
                            
                            if (matches.length > 0) {
                                $suggestions.empty();
                                matches.forEach(cat => {
                                    const displayName = cat === 'statistics-anonymous' ? 'Statistics Anonymous' : cat.replace('-', ' ');
                                    $suggestions.append(
                                        `<div class="cf-suggestion" data-category="${cat}">${displayName}</div>`
                                    );
                                });
                                $suggestions.show();
                            } else {
                                $suggestions.hide();
                            }
                        } else {
                            showSuggestions();
                        }
                    });

                    // Handle suggestion click
                    $container.on('click', '.cf-suggestion', function() {
                        const category = $(this).data('category');
                        addTag(category);
                        $input.val('').focus();
                        showSuggestions();
                    });

                    // Handle tag removal
                    $container.on('click', '.cf-tag-remove', function() {
                        $(this).closest('.cf-tag').remove();
                        showSuggestions();
                    });

                    // Handle enter key
                    $input.on('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const value = $(this).val().toLowerCase();
                            const match = categories.find(cat => 
                                cat.toLowerCase() === value && 
                                !$container.find(`input[value="${cat}"]`).length
                            );
                            
                            if (match) {
                                addTag(match);
                                $(this).val('');
                                showSuggestions();
                            }
                        }
                    });

                    function addTag(category) {
                        if (!$container.find(`input[value="${category}"]`).length) {
                            const displayName = category === 'statistics-anonymous' ? 'Statistics Anonymous' : category.replace('-', ' ');
                            const $tag = $('<span class="cf-tag">' + 
                                displayName + 
                                '<input type="hidden" name="' + fieldName + '[]" value="' + category + '">' +
                                '<button type="button" class="cf-tag-remove">&times;</button></span>');
                            $tag.insertBefore($input);
                        }
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * CookieFirst WP-Admin footer script.
     *
     * @since     1.0.0
     * @return    void    Inserts CookieFirst script into footer.
     */
    public function cf_footer_script()
    {
        $options = get_option('cf_settings');

        if ($options && array_key_exists('api_key', $options) && !empty($options['api_key'])) {
            $should_enqueue_script = true;
            if (!array_key_exists('should_show_in_preview', $options)) {
                if (is_preview() || is_customize_preview()) {
                    $should_enqueue_script = false;
                }
            }

            if ($should_enqueue_script) {
                // Get the consent type from settings, default to opt-in
                $consent_type = isset($options['consent_type']) ? $options['consent_type'] : 'opt-in';
                
                // Output the consent type variable in head
                echo '<script type="text/javascript">';
                echo 'window.wp_consent_type = "' . ($consent_type === 'opt-in' ? 'optin' : 'optout') . '";';
                echo '</script>';

                // Get script loading method
                $script_loading = isset($options['script_loading']) ? $options['script_loading'] : 'do-nothing';
                
                // Prepare script attributes based on loading method
                $script_attrs = [];
                if ($script_loading === 'async') {
                    $script_attrs[] = 'async';
                } elseif ($script_loading === 'defer') {
                    $script_attrs[] = 'defer';
                } elseif ($script_loading === 'async-defer') {
                    $script_attrs[] = 'async';
                    $script_attrs[] = 'defer';
                }

                // Enqueue CookieFirst script in head with appropriate attributes
                wp_enqueue_script(
                    'cookiefirst-script',
                    sprintf('https://consent.cookiefirst.com/sites/%s-%s/consent.js', preg_replace('/^www\./', '', parse_url(home_url(), PHP_URL_HOST)), $options['api_key']),
                    [],
                    null,
                    false
                );

                // Add script attributes
                add_filter('script_loader_tag', function($tag, $handle) use ($script_attrs) {
                    if ($handle === 'cookiefirst-script' && !empty($script_attrs)) {
                        foreach ($script_attrs as $attr) {
                            $tag = str_replace('></script>', ' ' . $attr . '></script>', $tag);
                        }
                    }
                    return $tag;
                }, 10, 2);

                // Add the consent management script before </body>
                add_action('wp_footer', function() use ($options) {
                    $debug_mode = isset($options['debug_mode']) && $options['debug_mode'] === 'true';
                    
                    // Only proceed with mapping if WP Consent API is active
                    if (!$this->is_wp_consent_api_active()) {
                        if ($debug_mode) {
                            echo '<script type="text/javascript">';
                            echo 'console.log("[CookieFirst] WP Consent API not enabled");';
                            echo '</script>';
                        }
                        return;
                    }
                    
                    // Get category mappings with defaults
                    $marketing_mapping = isset($options['marketing_mapping']) ? $options['marketing_mapping'] : ['marketing'];
                    $functional_mapping = isset($options['functional_mapping']) ? $options['functional_mapping'] : ['functional'];
                    $statistics_mapping = isset($options['statistics_mapping']) ? $options['statistics_mapping'] : ['statistics'];
                    $preferences_mapping = isset($options['preferences_mapping']) ? $options['preferences_mapping'] : ['preferences', 'statistics-anonymous'];
                    
                    // Create a map of WordPress categories to CookieFirst categories
                    $wp_to_cf_map = [];
                    
                    // Map each WordPress category to its CookieFirst categories
                    foreach ($marketing_mapping as $wp_category) {
                        if (!isset($wp_to_cf_map[$wp_category])) {
                            $wp_to_cf_map[$wp_category] = [];
                        }
                        $wp_to_cf_map[$wp_category][] = 'advertising';
                    }
                    
                    foreach ($functional_mapping as $wp_category) {
                        if (!isset($wp_to_cf_map[$wp_category])) {
                            $wp_to_cf_map[$wp_category] = [];
                        }
                        $wp_to_cf_map[$wp_category][] = 'functional';
                    }
                    
                    foreach ($statistics_mapping as $wp_category) {
                        if (!isset($wp_to_cf_map[$wp_category])) {
                            $wp_to_cf_map[$wp_category] = [];
                        }
                        $wp_to_cf_map[$wp_category][] = 'performance';
                    }
                    
                    foreach ($preferences_mapping as $wp_category) {
                        if (!isset($wp_to_cf_map[$wp_category])) {
                            $wp_to_cf_map[$wp_category] = [];
                        }
                        $wp_to_cf_map[$wp_category][] = 'necessary';
                    }
                    
                    ?>
                    <script type="text/javascript">
                    function updateWPConsent(source) {
                        <?php if ($debug_mode) { ?>
                        console.log("[CookieFirst] updateWPConsent called from:", source || 'initial load');
                        <?php } ?>
                        
                        // Check if wp_set_consent is available
                        if (typeof wp_set_consent !== 'function') {
                            <?php if ($debug_mode) { ?>
                            console.log("[CookieFirst] wp_set_consent not available");
                            <?php } ?>
                            return;
                        }
                        
                        var cookie = document.cookie.split("; ").find(row => row.startsWith("cookiefirst-consent="));
                        if (!cookie) {
                            <?php if ($debug_mode) { ?>
                            console.log("[CookieFirst] No consent cookie found");
                            <?php } ?>
                            // Remove any existing WordPress consent cookies
                            var cookies = document.cookie.split("; ");
                            for (var i = 0; i < cookies.length; i++) {
                                var cookie = cookies[i];
                                if (cookie.startsWith("wp_consent_")) {
                                    var cookieName = cookie.split("=")[0];
                                    document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                                    <?php if ($debug_mode) { ?>
                                    console.log("[CookieFirst] Removed WordPress consent cookie:", cookieName);
                                    <?php } ?>
                                }
                            }
                            return;
                        }
                        
                        try {
                            var cookieValue = decodeURIComponent(cookie.split("=")[1]);
                            var consentData = JSON.parse(cookieValue);
                            <?php if ($debug_mode) { ?>
                            console.log("[CookieFirst] Raw consent data:", consentData);
                            <?php } ?>
                            
                            // Map of WordPress categories to their required CookieFirst categories
                            var wpToCfMap = <?php echo json_encode($wp_to_cf_map); ?>;
                            
                            // Update each WordPress category based on all its mapped CookieFirst categories
                            for (var wpCategory in wpToCfMap) {
                                var shouldAllow = true;
                                var cfCategories = wpToCfMap[wpCategory];
                                
                                // Check if all mapped CookieFirst categories are accepted
                                for (var i = 0; i < cfCategories.length; i++) {
                                    var cfCategory = cfCategories[i];
                                    if (cfCategory === 'advertising' && !consentData.advertising) {
                                        shouldAllow = false;
                                        break;
                                    } else if (cfCategory === 'functional' && !consentData.functional) {
                                        shouldAllow = false;
                                        break;
                                    } else if (cfCategory === 'performance' && !consentData.performance) {
                                        shouldAllow = false;
                                        break;
                                    } else if (cfCategory === 'necessary' && !consentData.necessary) {
                                        shouldAllow = false;
                                        break;
                                    }
                                }
                                
                                // Set WordPress consent based on all mapped categories
                                wp_set_consent(wpCategory, shouldAllow ? 'allow' : 'deny');
                                
                                <?php if ($debug_mode) { ?>
                                console.log("[CookieFirst] " + wpCategory + " consent set to " + (shouldAllow ? 'allow' : 'deny') + 
                                    " based on CookieFirst categories:", cfCategories, "(" + (source || 'initial load') + ")");
                                <?php } ?>
                            }
                        } catch (e) {
                            console.error("[CookieFirst] Error parsing consent cookie:", e);
                        }
                    }
                    
                    // Initial check
                    updateWPConsent();
                    
                    // Listen for CookieFirst events with a small delay to ensure data is available
                    function handleConsentEvent(event) {
                        <?php if ($debug_mode) { ?>
                        console.log("[CookieFirst] Event received:", event.type);
                        <?php } ?>
                        // Add a small delay to ensure the cookie is updated
                        setTimeout(function() {
                            updateWPConsent(event.type);
                        }, 100);
                    }
                    
                    window.addEventListener("cf_consent", handleConsentEvent);
                    window.addEventListener("cf_consent_loaded", handleConsentEvent);
                    </script>
                    <?php
                }, 9999);
            }
        }
    }

    /**
     * WordPress Consent API section callback.
     *
     * @since     1.0.0
     * @return    void    Renders WordPress Consent API section description.
     */
    public function cf_wp_consent_section_callback()
    {
        if (!$this->is_wp_consent_api_active()) {
            echo '<div class="notice notice-warning inline">';
            echo '<p>' . __('You are currently not using the WordPress Consent API. If you use Google Site Kit we would recommend installing the WP consent api plugin.', 'cookiefirst') . '</p>';
            
            // Check if user has permission to install plugins
            if (current_user_can('install_plugins')) {
                $plugin_slug = 'wp-consent-api';
                $install_url = wp_nonce_url(
                    self_admin_url("update.php?action=install-plugin&plugin={$plugin_slug}"),
                    'install-plugin_' . $plugin_slug
                );
                echo '<p><a href="' . esc_url($install_url) . '" class="button button-primary">' . __('Install WP Consent API', 'cookiefirst') . '</a></p>';
            } else {
                echo '<p><em>' . __('You do not have permission to install plugins. Please contact your administrator to install the WP Consent API plugin.', 'cookiefirst') . '</em></p>';
                echo '<p><a href="https://wordpress.org/plugins/wp-consent-api/" target="_blank" class="button button-secondary">' . __('View Plugin Details', 'cookiefirst') . '</a></p>';
            }
            
            echo '</div>';
            return;
        }
        
        echo '<p>' . __('Configure how CookieFirst categories map to WordPress Consent API categories.', 'cookiefirst') . '</p>';
    }

    /**
     * Render marketing category mapping field.
     *
     * @since     1.0.0
     * @return    void    Renders marketing category mapping field.
     */
    public function cf_render_marketing_mapping_field()
    {
        $options = get_option('cf_settings');
        $wp_categories = ['marketing', 'functional', 'statistics', 'statistics-anonymous', 'preferences'];
        $selected = isset($options['marketing_mapping']) ? $options['marketing_mapping'] : ['marketing'];
        
        echo '<div class="cf-tag-input-container">';
        echo '<div class="cf-tag-input" data-categories=\'' . wp_json_encode($wp_categories) . '\'>';
        foreach ($selected as $category) {
            echo '<span class="cf-tag">' . ucfirst(str_replace('-', ' ', $category)) . 
                 '<input type="hidden" name="cf_settings[marketing_mapping][]" value="' . esc_attr($category) . '">' .
                 '<button type="button" class="cf-tag-remove">&times;</button></span>';
        }
        echo '<input type="text" class="cf-tag-input-field" placeholder="' . esc_attr__('Type to add categories...', 'cookiefirst') . '">';
        echo '<div class="cf-suggestions"></div>';
        echo '</div>';
        echo '</div>';
        echo '<p class="description">' . __('Select which WordPress consent categories should be controlled by the CookieFirst Advertising category.', 'cookiefirst') . '</p>';
    }

    /**
     * Render functional category mapping field.
     *
     * @since     1.0.0
     * @return    void    Renders functional category mapping field.
     */
    public function cf_render_functional_mapping_field()
    {
        $options = get_option('cf_settings');
        $wp_categories = ['marketing', 'functional', 'statistics', 'statistics-anonymous', 'preferences'];
        $selected = isset($options['functional_mapping']) ? $options['functional_mapping'] : ['functional'];
        
        echo '<div class="cf-tag-input-container">';
        echo '<div class="cf-tag-input" data-categories=\'' . wp_json_encode($wp_categories) . '\'>';
        foreach ($selected as $category) {
            echo '<span class="cf-tag">' . ucfirst(str_replace('-', ' ', $category)) . 
                 '<input type="hidden" name="cf_settings[functional_mapping][]" value="' . esc_attr($category) . '">' .
                 '<button type="button" class="cf-tag-remove">&times;</button></span>';
        }
        echo '<input type="text" class="cf-tag-input-field" placeholder="' . esc_attr__('Type to add categories...', 'cookiefirst') . '">';
        echo '<div class="cf-suggestions"></div>';
        echo '</div>';
        echo '</div>';
        echo '<p class="description">' . __('Select which WordPress consent categories should be controlled by the CookieFirst Functional category.', 'cookiefirst') . '</p>';
    }

    /**
     * Render statistics category mapping field.
     *
     * @since     1.0.0
     * @return    void    Renders statistics category mapping field.
     */
    public function cf_render_statistics_mapping_field()
    {
        $options = get_option('cf_settings');
        $wp_categories = ['marketing', 'functional', 'statistics', 'statistics-anonymous', 'preferences'];
        $selected = isset($options['statistics_mapping']) ? $options['statistics_mapping'] : ['statistics'];
        
        echo '<div class="cf-tag-input-container">';
        echo '<div class="cf-tag-input" data-categories=\'' . wp_json_encode($wp_categories) . '\'>';
        foreach ($selected as $category) {
            echo '<span class="cf-tag">' . ucfirst(str_replace('-', ' ', $category)) . 
                 '<input type="hidden" name="cf_settings[statistics_mapping][]" value="' . esc_attr($category) . '">' .
                 '<button type="button" class="cf-tag-remove">&times;</button></span>';
        }
        echo '<input type="text" class="cf-tag-input-field" placeholder="' . esc_attr__('Type to add categories...', 'cookiefirst') . '">';
        echo '<div class="cf-suggestions"></div>';
        echo '</div>';
        echo '</div>';
        echo '<p class="description">' . __('Select which WordPress consent categories should be controlled by the CookieFirst Performance category.', 'cookiefirst') . '</p>';
    }

    /**
     * Render preferences category mapping field.
     *
     * @since     1.0.0
     * @return    void    Renders preferences category mapping field.
     */
    public function cf_render_preferences_mapping_field()
    {
        $options = get_option('cf_settings');
        $wp_categories = ['marketing', 'functional', 'statistics', 'statistics-anonymous', 'preferences'];
        $selected = isset($options['preferences_mapping']) ? $options['preferences_mapping'] : ['preferences', 'statistics-anonymous'];
        
        echo '<div class="cf-tag-input-container">';
        echo '<div class="cf-tag-input" data-categories=\'' . wp_json_encode($wp_categories) . '\'>';
        foreach ($selected as $category) {
            $display_name = $category === 'statistics-anonymous' ? 'Statistics Anonymous' : ucfirst(str_replace('-', ' ', $category));
            echo '<span class="cf-tag">' . $display_name . 
                 '<input type="hidden" name="cf_settings[preferences_mapping][]" value="' . esc_attr($category) . '">' .
                 '<button type="button" class="cf-tag-remove">&times;</button></span>';
        }
        echo '<input type="text" class="cf-tag-input-field" placeholder="' . esc_attr__('Type to add categories...', 'cookiefirst') . '">';
        echo '<div class="cf-suggestions"></div>';
        echo '</div>';
        echo '</div>';
        echo '<p class="description">' . __('Select which WordPress consent categories should be controlled by the CookieFirst Necessary category.', 'cookiefirst') . '</p>';
    }
}

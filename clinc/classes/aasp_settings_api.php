<?php
/**
 * WEDEV settings API demo class - 
 *
 * @Author        Tuba
 */

if (!defined('ABSPATH')) {
    exit;
}  // If direct access

if (!class_exists('AASP_SettingAPI')):

    class AASP_SettingAPI {
        private $settings_api;

        function __construct() {
            $this->aasp_settingapi = new WeDevs_Settings_API;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function admin_init() {
            // Set settings sections and fields
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            $this->settings_api->admin_init();
        }

        function admin_menu() {
            add_submenu_page(
                'woocommerce',                              // Parent slug
                'Product Search',                           // Page title
                'Product Search',                           // Menu title
                'manage_options',                           // Capability
                'product-search-for-woo',                   // Menu slug
                array($this, 'plugin_setting_page')         // Callback function
            );
        }

        function get_settings_sections() {
            $sections = array(
                array(
                    'id'    => 'aasp_search_form',
                    'title' => esc_html__('Search Form', 'aasp-in')
                ),
                array(
                    'id'    => 'aasp_search_result',
                    'title' => esc_html__('Search Results', 'aasp-in')
                ),
                array(
                    'id'    => 'aasp_color',
                    'title' => esc_html__('Styling Options', 'aasp-in')
                ),
            );
            return $sections;
        }

        function get_settings_fields() {
            
            $settings_fields = array(
                'aasp_search_form' => array(
                    array('name' => 'search_value'),
                    array('name' => 'search_btn'),
                    array('name' => 'search_bar_width'),
                    array('name' => 'search_bar_height'),
                    array('name' => 'action_charaters'),
                    array('name' => 'show_loader'),
                    array('name' => 'search_action'),
                    array('name' => 'search_form_style'),
                    array('name' => 'how_to_use'),
                ),
                'aasp_search_result' => array(
                    array('name' => 'how_to_use'),
                ),
                'aasp_color' => array(
                    array('name' => 'how_to_use'),
                ),
            );
            return $settings_fields;
        }

        function plugin_page() {
            echo '<div class="wrap">';

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();

            echo '</div>';
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages() {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }

            return $pages_options;
        }
    }

    new AASP_SettingAPI();
endif;

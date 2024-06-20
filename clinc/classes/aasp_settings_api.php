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
            $this->settings_api = new WeDevs_Settings_API;

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
                    'id'    => 'aasp_search_results',
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
                array(
                'name' => 'text',
                'label' => __('Search Bar', 'aasp-in'),
                'desc' => __('Search Placeholder', 'aasp-in'),
                'type' => 'text',
                'default' => 'Search Product'
                ),
                array(
                'name' => 'textarea',
                'label' => __('Search Button', 'aasp-in'),
                'desc' => __('Button Placeholder', 'aasp-in'),
                'type' => 'text'
                ),
                array(
                'name' => 'checkbox',
                'label' => __('Search Form Styles', 'aasp-in'),
                'desc' => __('Checkbox Label', 'aasp-in'),
                'type' => 'checkbox'
                ),
                array(
                'name' => 'radio',
                'label' => __('Search Bar Width', 'aasp-in'),
                'desc' => __('A radio button', 'aasp-in'),
                'type' => 'text'
                ),
                array(
                'name' => 'multicheck',
                'label' => __('Search Bar Height', 'aasp-in'),
                'desc' => __('Multi checkbox description', 'aasp-in'),
                'type' => 'text'
                ),
                array(
                'name' => 'selectbox',
                'label' => __('Button Width', 'aasp-in'),
                'desc' => __('Dropdown description', 'aasp-in'),
                'type' => 'text'
                ),
                array(
                    'name' => 'selectbox',
                    'label' => __('Button Height', 'aasp-in'),
                    'desc' => __('Dropdown description', 'aasp-in'),
                    'type' => 'text'
                ),
                array(
                    'name' => 'selectbox2',
                    'label' => __('Button Width', 'aasp-in'),
                    'desc' => __('Dropdown description', 'aasp-in'),
                    'type' => 'text'
                    )
                ),
                'aasp_search_results' => array(
                array(
                'name' => 'text',
                'label' => __('Button Height', 'aasp-in'),
                'desc' => __('Text input description', 'aasp-in'),
                'type' => 'text',
                'default' => 'Title'
                ),
                array(
                'name' => 'textarea',
                'label' => __('Textarea Input', 'aasp-in'),
                'desc' => __('Textarea description', 'aasp-in'),
                'type' => 'textarea'
                ),
                array(
                'name' => 'checkbox',
                'label' => __('Checkbox', 'aasp-in'),
                'desc' => __('Checkbox Label', 'aasp-in'),
                'type' => 'checkbox'
                ),
                array(
                'name' => 'radio',
                'label' => __('Radio Button', 'aasp-in'),
                'desc' => __('A radio button', 'aasp-in'),
                'type' => 'radio',
                'default' => 'no',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                ),
                array(
                'name' => 'multicheck',
                'label' => __('Multile checkbox', 'aasp-in'),
                'desc' => __('Multi checkbox description', 'aasp-in'),
                'type' => 'multicheck',
                'default' => array('one' => 'one', 'four' => 'four'),
                'options' => array(
                'one' => 'One',
                'two' => 'Two',
                'three' => 'Three',
                'four' => 'Four'
                )
                ),
                array(
                'name' => 'selectbox',
                'label' => __('A Dropdown', 'aasp-in'),
                'desc' => __('Dropdown description', 'aasp-in'),
                'type' => 'select',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                )
                ),
                'aasp_color' => array(
                array(
                'name' => 'text',
                'label' => __('Text Input', 'aasp-in'),
                'desc' => __('Text input description', 'aasp-in'),
                'type' => 'text',
                'default' => 'Title'
                ),
                array(
                'name' => 'textarea',
                'label' => __('Textarea Input', 'aasp-in'),
                'desc' => __('Textarea description', 'aasp-in'),
                'type' => 'textarea'
                ),
                array(
                'name' => 'checkbox',
                'label' => __('Checkbox', 'aasp-in'),
                'desc' => __('Checkbox Label', 'aasp-in'),
                'type' => 'checkbox'
                ),
                array(
                'name' => 'radio',
                'label' => __('Radio Button', 'aasp-in'),
                'desc' => __('A radio button', 'aasp-in'),
                'type' => 'radio',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                ),
                array(
                'name' => 'multicheck',
                'label' => __('Multile checkbox', 'aasp-in'),
                'desc' => __('Multi checkbox description', 'aasp-in'),
                'type' => 'multicheck',
                'options' => array(
                'one' => 'One',
                'two' => 'Two',
                'three' => 'Three',
                'four' => 'Four'
                )
                ),
                array(
                'name' => 'selectbox',
                'label' => __('A Dropdown', 'aasp-in'),
                'desc' => __('Dropdown description', 'aasp-in'),
                'type' => 'select',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                )
                )
                );
            return $settings_fields;
        }

        function plugin_setting_page() {
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

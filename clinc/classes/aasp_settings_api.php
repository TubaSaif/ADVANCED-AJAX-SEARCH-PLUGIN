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
                array(
                'name' => 'text',
                'label' => __('Text Input', 'wedevs'),
                'desc' => __('Text input description', 'wedevs'),
                'type' => 'text',
                'default' => 'Title'
                ),
                array(
                'name' => 'textarea',
                'label' => __('Textarea Input', 'wedevs'),
                'desc' => __('Textarea description', 'wedevs'),
                'type' => 'textarea'
                ),
                array(
                'name' => 'checkbox',
                'label' => __('Checkbox', 'wedevs'),
                'desc' => __('Checkbox Label', 'wedevs'),
                'type' => 'checkbox'
                ),
                array(
                'name' => 'radio',
                'label' => __('Radio Button', 'wedevs'),
                'desc' => __('A radio button', 'wedevs'),
                'type' => 'radio',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                ),
                array(
                'name' => 'multicheck',
                'label' => __('Multile checkbox', 'wedevs'),
                'desc' => __('Multi checkbox description', 'wedevs'),
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
                'label' => __('A Dropdown', 'wedevs'),
                'desc' => __('Dropdown description', 'wedevs'),
                'type' => 'select',
                'default' => 'no',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                )
                ),
                'aasp_search_result' => array(
                array(
                'name' => 'text',
                'label' => __('Text Input', 'wedevs'),
                'desc' => __('Text input description', 'wedevs'),
                'type' => 'text',
                'default' => 'Title'
                ),
                array(
                'name' => 'textarea',
                'label' => __('Textarea Input', 'wedevs'),
                'desc' => __('Textarea description', 'wedevs'),
                'type' => 'textarea'
                ),
                array(
                'name' => 'checkbox',
                'label' => __('Checkbox', 'wedevs'),
                'desc' => __('Checkbox Label', 'wedevs'),
                'type' => 'checkbox'
                ),
                array(
                'name' => 'radio',
                'label' => __('Radio Button', 'wedevs'),
                'desc' => __('A radio button', 'wedevs'),
                'type' => 'radio',
                'default' => 'no',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                ),
                array(
                'name' => 'multicheck',
                'label' => __('Multile checkbox', 'wedevs'),
                'desc' => __('Multi checkbox description', 'wedevs'),
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
                'label' => __('A Dropdown', 'wedevs'),
                'desc' => __('Dropdown description', 'wedevs'),
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
                'label' => __('Text Input', 'wedevs'),
                'desc' => __('Text input description', 'wedevs'),
                'type' => 'text',
                'default' => 'Title'
                ),
                array(
                'name' => 'textarea',
                'label' => __('Textarea Input', 'wedevs'),
                'desc' => __('Textarea description', 'wedevs'),
                'type' => 'textarea'
                ),
                array(
                'name' => 'checkbox',
                'label' => __('Checkbox', 'wedevs'),
                'desc' => __('Checkbox Label', 'wedevs'),
                'type' => 'checkbox'
                ),
                array(
                'name' => 'radio',
                'label' => __('Radio Button', 'wedevs'),
                'desc' => __('A radio button', 'wedevs'),
                'type' => 'radio',
                'options' => array(
                'yes' => 'Yes',
                'no' => 'No'
                )
                ),
                array(
                'name' => 'multicheck',
                'label' => __('Multile checkbox', 'wedevs'),
                'desc' => __('Multi checkbox description', 'wedevs'),
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
                'label' => __('A Dropdown', 'wedevs'),
                'desc' => __('Dropdown description', 'wedevs'),
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

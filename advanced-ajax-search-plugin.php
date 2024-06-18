<?php
/*
Plugin Name: Advanced Ajax Search for WooCommerce
Plugin URI: https://github.com/TubaSaif
Description: Advanced Ajax Product Search for WooCommerce
Version: 1.0
Author: Tuba Saif----------
Author URI: https://tubasaif.github.io/
Domain Path: /languages/
*/

if (!defined('ABSPATH')) exit;  // Restrict direct access

if (!class_exists('AASP_Woo_Product_Search_Class')) {

    class AASP_Woo_Product_Search_Class {
        
         /**
	 * The option_search_results
	 *
	 */
	public $option_search_from;
    /**
    * The option_search_results
    *
    */
   public $option_search_results;
   
    /**
    * The option_search_results
    *
    */
   public $option_color;
        /**
         * The single instance of the class
         *
         * @var AASP_Woo_Product_Search_Class
         */
        private static $_instance;

        /**
         * Class constructor.
         */
        public function __construct() {
            $this->aasp_load_classes();
            $this->apsw_load_functions();
            $this->option_search_from 		= wp_parse_args ( aasp_get_option('apsw_search_form') );
            $this->option_search_results 	= wp_parse_args ( aasp_get_option('apsw_search_results') );
            $this->option_color 			= wp_parse_args ( aasp_get_option('apsw_color_scheme') );
        }

        /**
         * Main instance
         *
         * @return AASP_Woo_Product_Search_Class
         */
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        	/**
	 *
	 * @return plugins related function 
	 */
    public function apsw_load_functions() {
        
        $this->aasp_load_module( '<clinc>
        <helper-functions>aasp-functions' );
         
      }

        public function aasp_load_classes() {
            $this->aasp_load_module('libraries/class.settings-api');
            $this->aasp_load_module('clinc/classes/aasp_settings_api');
            $this->apsw_load_module( 'libraries/class.settings-api' );
            $this->apsw_load_module( 'clinc/classes/aasp_search' );
        }

        protected function aasp_load_module($mod) {
            $dir = defined('aasp_PLUGIN_DIR') ? aasp_PLUGIN_DIR : __DIR__; // Ensure aasp_PLUGIN_DIR is defined
            if (empty($dir) || !is_dir($dir)) {
                return false;
            }
            $file = path_join($dir, $mod . '.php');

            if (file_exists($file)) {
                require_once $file;
            } 
        }
    }

    global $aasp_product_search_final_class;
    if (!$aasp_product_search_final_class) {
        $aasp_product_search_final_class = AASP_Woo_Product_Search_Class::getInstance();
    }
}

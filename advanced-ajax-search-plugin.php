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
            $this->aasp_load_defines();
            $this->aasp_load_scripts();
            $this->aasp_load_classes();
            $this->aasp_load_functions();
            $this->option_search_from 		= wp_parse_args(aasp_get_option('aasp_search_form') );
            $this->option_search_results 	= wp_parse_args(aasp_get_option('aasp_search_results') );
            $this->option_color 			= wp_parse_args(aasp_get_option('aasp_color') );
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
    public function aasp_load_functions() {
        $this->aasp_load_module( 'clinc/helper-functions/aasp-functions' );
         
      }

    public function aasp_load_classes() {
           $this->aasp_load_module('libraries/class.settings-api');
            $this->aasp_load_module('clinc/classes/aasp_settings_api');
            $this->aasp_load_module('clinc/classes/aasp_search');
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
    public function aasp_admin_scripts() {

            wp_enqueue_style('aasp-stylesheet',  plugins_url( 'assets/admin/css/style.css' , __FILE__ ), array(), time());
             
            
            wp_enqueue_script('jquery');
          
            wp_enqueue_script('aasp-plugins-scripts', plugins_url( 'assets/admin/js/wpadminscripts.js' , __FILE__ ) , array( 'jquery' ));
            
            $aasp_notify = array(
                'ajaxurl' => admin_url( 'admin-ajax.php'),
            );
            
            wp_localize_script( 'aasp-plugins-scripts', 'aasp_loc', $aasp_notify );
           
        }
    
    public function aasp_front_scripts() {
    
            wp_enqueue_style('aasp-stylesheet', plugins_url( 'assets/ajaxfront/css/style.css' , __FILE__ ), array(), time());
            
             
           
            $custom_css = "  .aasp-search-wrap {max-width:".absint( $this->option_search_from['search_bar_width'] ) ."px;}
            .aasp-search-wrap .aasp-search-form input[type='search'],.aasp-search-wrap.aasp_search_form_style_4 button.aasp-search-btn,.aasp-search-wrap.aasp_search_form_style_5 button.aasp-search-btn,.aasp-search-wrap.aasp_search_form_style_6 button.aasp-search-btn,.aasp-search-wrap .aasp-search-btn{ height:".absint( $this->option_search_from['search_bar_height'] ) ."px; line-height: ".absint( $this->option_search_from['search_bar_height'] )."px }
            .aasp-search-wrap .aasp-select-box-wrap{height:".absint( $this->option_search_from['search_bar_height'] ) ."px;}
            .aasp-search-wrap .aasp-category-items{ line-height: ".absint( $this->option_search_from['search_bar_height'] )."px; }
            .aasp_ajax_result{ top:".absint( $this->option_search_from['search_bar_height'] + 1)."px; }
            ";
                
            $custom_css .=".aasp-search-wrap .aasp-search-form{ background:".esc_attr( $this->option_color['search_bar_bg'] )."; border-color:".esc_attr( $this->option_color['search_bar_border'] )."; }";
            
            $custom_css .=".aasp-search-wrap .aasp-category-items,.aasp-search-wrap .aasp-search-form input[type='search']{color:".esc_attr( $this->option_color['search_bar_text'] )."; }";
            
            $custom_css .=".aasp-search-wrap.aasp_search_form_style_4 button.aasp-search-btn, .aasp-search-wrap.aasp_search_form_style_5 button.aasp-search-btn, .aasp-search-wrap.aasp_search_form_style_6 button.aasp-search-btn{ color:".esc_attr( $this->option_color['search_btn_text'] )."; background:".esc_attr( $this->option_color['search_btn_bg'] )."; }";
            
            $custom_css .=".aasp-search-wrap .aasp-search-btn svg{ fill:".esc_attr( $this->option_color['search_btn_bg'] )."; }";
            
            $custom_css .=".aasp-search-wrap.aasp_search_form_style_4 button.aasp-search-btn::before, .aasp-search-wrap.aasp_search_form_style_5 button.aasp-search-btn::before, .aasp-search-wrap.aasp_search_form_style_6 button.aasp-search-btn::before { border-color: transparent ".esc_attr( $this->option_color['search_btn_bg'] )."  transparent;; }";
            
            $custom_css .=".aasp_ajax_result .aasp_result_wrap{ background:".esc_attr( $this->option_color['results_con_bg'] )."; border-color:".esc_attr( $this->option_color['results_con_bor'] )."; } ";
            
            $custom_css .="ul.aasp_data_container li:hover{ background:".esc_attr( $this->option_color['results_row_hover'] )."; border-color:".esc_attr( $this->option_color['results_con_bor'] )."; } ";
            $custom_css .="ul.aasp_data_container li .aasp-name{ color:".esc_attr( $this->option_color['results_heading_color'] ).";} ";
            $custom_css .="ul.aasp_data_container li .aasp-price{ color:".esc_attr( $this->option_color['price_color'] ).";} ";
            
            $custom_css .="ul.aasp_data_container li .aasp_result_excerpt{ color:".esc_attr( $this->option_color['results_text_color'] ).";} ";
            $custom_css .="ul.aasp_data_container li .aasp_result_category{ color:".esc_attr( $this->option_color['category_color'] ).";} ";
            $custom_css .="ul.aasp_data_container li.aasp_featured{ background:".esc_attr( $this->option_color['featured_product_bg'] ).";} ";
            $custom_css .="ul.aasp_data_container li .aasp_result_on_sale{ background:".esc_attr( $this->option_color['on_sale_bg'] ).";} ";
            $custom_css .="ul.aasp_data_container li .aasp_result_stock{ color:".esc_attr( $this->option_color['results_stock_color'] ).";} ";
            
            //$this->option_color	
            wp_add_inline_style( 'aasp-stylesheet', $custom_css );
    
            wp_enqueue_script('jquery');
            wp_enqueue_script('aasp-plugins-scripts', plugins_url( 'assets/ajaxfront/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
            wp_localize_script('aasp-plugins-scripts', 'aasp_localize', $this->aasp_get_localize_script() );
        }
    
    function aasp_load_scripts() {
    
            
            add_action( 'admin_enqueue_scripts', array( $this, 'aasp_admin_scripts' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'aasp_front_scripts' ) );
        }
    
    function aasp_load_defines(){
    
            $this->define('aasp_PLUGIN_URL',WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
            $this->define('aasp_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            $this->define('aasp_PLUGIN_FILE', __FILE__ );
            $this->define('aasp_PLUGIN_VERSION', '1.0.0' );
            $this->define('aasp_PLUGIN_FILE', plugin_basename( __FILE__ ) );
            $this->define('aasp', 'advanced-ajax-search-plugin' );
        }
    
    private function define( $name, $value ){
            if( ! defined( $name ) ) define( $name, $value );
        }
    
    private function aasp_get_localize_script(){
    
            return apply_filters( 'aasp_localize_filters_', array(
                'ajaxurl' => admin_url( 'admin-ajax.php'),
                'view_text'	=> esc_html( $this->option_search_results['view_all_text'] ),
                'text' => array(
                    'working' => esc_html__('Working...', 'aasp-lang'),
                ),
            ) );
            
            
        }
   
    }

}



    global $aasp_product_search_final_class;
if (!$aasp_product_search_final_class) {
    $aasp_product_search_final_class = AASP_Woo_Product_Search_Class::getInstance();
}


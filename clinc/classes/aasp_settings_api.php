<?php
/**
 * WEDEV settings API demo class - 

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

            $default = aasp_default_theme_options();
		
            $form 		= ( isset( $default['aasp_search_form'] ) ) ? wp_parse_args(  $default['aasp_search_form'] ) : array();
            $result 	= ( isset( $default['aasp_search_results'] ) ) ? wp_parse_args( $default['aasp_search_results'] ) : array();
            $color	 	= ( isset( $default['aasp_color_scheme'] ) ) ? wp_parse_args(  $default['aasp_color_scheme'] ) : array();

            $settings_fields = array(
                'aasp_search_form' => array(
                    array(
                        'name' => 'search_placeholder',
                        'label' => esc_attr__( 'Text for search field', 'aasp-lang' ),
                        'desc' => esc_html__( 'Text for search field placeholder.', 'aasp-lang' ),
                        'type' => 'text',
                        'default' => esc_attr( $form['search_placeholder'] ),
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    array(
                        'name' => 'btn_placeholder',
                        'label'             => esc_attr__( 'Text for search button', 'aasp-lang' ),
                        'desc'              => esc_html__( 'Text for search button text.', 'aasp-lang' ),
                        'placeholder'       => esc_attr( $form['search_btn'] ),
                        'type'              => 'text',
                        'default'           => esc_attr( $form['search_btn'] ),
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    array(
                        'name' => 'search_form_style',
                        'label' => esc_attr__('Search Form Styles', 'aasp-in'),
                        'type' => 'select',
                        'options' => array(
                            'aasp_search_form_style_1'  => 1,
                            'aasp_search_form_style_2'  => 2,
                            'aasp_search_form_style_3'  => 3,
                            'aasp_search_form_style_4'  => 4,
                            'aasp_search_form_style_5'  => 5,
                            'aasp_search_form_style_6'  => 6
                        ),
                        'default'       => esc_attr( $form['search_form_style'] ),
					    'sanitize_callback' => 'sanitize_text_field'
                    ),

                    array(
                        'name'              => 'search_bar_width',
                        'label'             => esc_attr__( 'Search bar width', 'aasp-lang' ),
                        'desc'              => esc_html__( 'maximum width of search bar.', 'aasp-lang' ),
                        'placeholder'       => esc_attr( $form['search_bar_width'] ),
                        'min'               => 1,
                        'step'              => '1',
                        'type'              => 'number',
                        'sanitize_callback' => 'floatval',
                        'default'           => esc_attr( $form['search_bar_width'] ),
                    ),
				array(
                    'name'              => 'search_bar_height',
                    'label'             => esc_attr__( 'Search bar height', 'aasp-lang' ),
                    'desc'              => esc_html__( 'maximum height of search bar.', 'aasp-lang' ),
                    'placeholder'       => esc_attr( $form['search_bar_height'] ),
					'default'           => esc_attr( $form['search_bar_height'] ),
                    'min'               => 20,
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'              => 'action_charaters',
                    'label'             => esc_attr__( 'Minimum number of characters', 'aasp-lang' ),
                    'desc'              => esc_html__( 'Minimum number of characters required to run ajax search.', 'aasp-lang' ),
                    'placeholder'       => esc_attr( $form['action_charaters'] ),
					'default'           => esc_attr( $form['action_charaters'] ),
                    'min'               => 1,
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'    => 'show_loader',
                    'label'   => esc_attr__( 'Show loader', 'aasp-lang' ),
                    'desc'    => esc_html__( 'Show loader animation while searching. ', 'aasp-lang' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    ),
					'default' => esc_attr( $form['show_loader'] ),
					 'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'    => 'search_action',
                    'label'   => esc_attr__( "Search Actions", 'aasp-lang' ),
                    'desc'    => esc_html__( 'Show link to search results page at the bottom of search results block. ', 'aasp-lang' ),
                    'type'    => 'radio',
                    'options' => array(
                        'both' 		=> esc_html__( "Both ajax search results and search results page", 'aasp-lang' ),
                        'ajax'  	=> esc_html__( "Only ajax search results ( no search results page )", 'aasp-lang' ),
						'simple'  	=> esc_html__( "Only search results page ( no ajax search results )", 'aasp-lang' ),
                    ),
					'default'       => esc_attr( $form['search_action'] ),
					'sanitize_callback' => 'sanitize_text_field'
                ),
                ),
                'aasp_search_results' => array(
                    array(
           'name'    => 'content_source',
           'label'   => esc_attr__( "Description source", 'aasp-lang' ),
           'desc'    => esc_html__( 'From where to take product description.If first source is empty data will be taken from other sources. ', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'content' => 'Content',
               'excerpt'  => 'Excerpt'
           ),
           'default'       => esc_attr( $result['content_source'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       array(
           'name'              => 'length',
           'label'             => esc_attr__( 'Content length', 'aasp-lang' ),
           'desc'              => esc_html__( 'Maximal allowed number of words for product description.', 'aasp-lang' ),
              'default'       => esc_attr( $result['length'] ),
           'type'              => 'number',
           'sanitize_callback' => 'floatval'
       ),
       
       array(
           'name'              => 'number_of_product',
           'label'             => esc_attr__( 'Number of product', 'aasp-lang' ),
           'desc'              => esc_html__( 'Maximum number of displayed search results. ', 'aasp-lang' ),
            'default'      	=> esc_attr( $result['number_of_product'] ),
           'type'              => 'numberdisable',
           'sanitize_callback' => 'floatval'
       ),
       
       array(
           'name'              => 'nothing_found',
           'label'             => esc_attr__( 'Nothing found text', 'aasp-lang' ),
           'desc'              => esc_html__( 'Text when there is no product found search results. .', 'aasp-lang' ),
           'default'     	 	=> esc_attr( $result['nothing_found'] ),
           'type'              => 'text',
           'sanitize_callback' => 'sanitize_text_field'
       ),
       array(
           'name'              => 'nothing_found_cat',
           'label'             => esc_attr__( 'Nothing found text with category search', 'aasp-lang' ),
           'desc'              => esc_html__( 'Text when there is no product found search results. .', 'aasp-lang' ),
           'type'              => 'text',
           'default'     	 	=> esc_attr( $result['nothing_found_cat'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'              => 'view_all_text',
           'label'             => esc_attr__( 'View All Text', 'aasp-lang' ),
           'desc'              => esc_html__( 'leave empty to hide the button.', 'aasp-lang' ),
           'default'     	 	=> esc_attr( $result['nothing_found_cat'] ),
           'type'              => 'text',
           
           'sanitize_callback' => 'view_all_text'
       ),
       
       array(
           'name'              => 'divider',
           'type'              => 'divider',
           'desc'              => esc_html__( 'More Settings for search results. ', 'aasp-lang' ),
       ),
       
       
      array(
           'name'    => 'show_image',
           'label'   => esc_attr__( "Show Product image", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product image for each search result.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_image'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'    => 'show_description',
           'label'   => esc_attr__( "Show Product Description", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product description text. ', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_description'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'    => 'show_price',
           'label'   => esc_attr__( "Show price", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product price for each search result.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_price'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'    => 'show_rating',
           'label'   => esc_attr__( "Show Rating", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product Rating for each search result.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_rating'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'    => 'show_category',
           'label'   => esc_attr__( "Show product category", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product category for each search result.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_category'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'    => 'show_sku',
           'label'   => esc_attr__( "Show product sku", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product sku for each search result.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_sku'] ),
       ),
       
       array(
           'name'    => 'stock_status',
           'label'   => esc_attr__( "Show stock status", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product price for stock status products.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['stock_status'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'    => 'show_feature',
           'label'   => esc_attr__( "Active Feature ", 'aasp-lang' ),
           'desc'    => esc_html__( 'will active green color each Feature product.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_feature'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       array(
           'name'    => 'show_on_sale',
           'label'   => esc_attr__( "Show On Sale", 'aasp-lang' ),
           'desc'    => esc_html__( 'Show product On Sale status products.', 'aasp-lang' ),
           'type'    => 'radio',
           'options' => array(
               'yes' => 'Yes',
               'no'  => 'No'
           ),
           'default'     	 	=> esc_attr( $result['show_on_sale'] ),
           'sanitize_callback' => 'sanitize_text_field'
       ),
       
       
       
   ),
   'aasp_color' => array(
				
    array(
        'name'    => 'search_bar_bg',
        'label'   => esc_attr__( 'Search Bar background', 'wedevs' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['search_bar_bg'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'search_bar_border',
        'label'   => esc_attr__( 'Search Bar border', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['search_bar_border'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    array(
        'name'    => 'search_bar_text',
        'label'   => esc_attr__( 'Search Bar Text', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['search_bar_text'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    array(
        'name'    => 'search_btn_bg',
        'label'   => esc_attr__( 'Search button background', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['search_btn_bg'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'search_btn_text',
        'label'   => esc_attr__( 'Search button color', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['search_btn_text'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'              => 'divider',
        'type'              => 'divider',
        'desc'              => esc_html__( 'Settings for search search results. ', 'aasp-lang' ),
    ),
    
    array(
        'name'    => 'results_con_bg',
        'label'   => esc_attr__( 'Results Container background', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['results_con_bg'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'results_con_bor',
        'label'   => esc_attr__( 'Results Container border', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['results_con_bor'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'results_row_hover',
        'label'   => esc_attr__( 'Results each row hover background', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['results_row_hover'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'results_heading_color',
        'label'   => esc_attr__( 'Results Title Color', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['results_heading_color'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    array(
        'name'    => 'price_color',
        'label'   => esc_attr__( 'Price Color', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['results_text_color'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    array(
        'name'    => 'results_text_color',
        'label'   => esc_attr__( 'Results Text Color', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['results_text_color'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'category_color',
        'label'   => esc_attr__( 'Results Category Color', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['category_color'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'results_stock_color',
        'label'   => esc_attr__( 'Results Stock Color', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['results_stock_color'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    array(
        'name'    => 'on_sale_bg',
        'label'   => esc_attr__( 'On Sale background', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['on_sale_bg'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    array(
        'name'    => 'featured_product_bg',
        'label'   => esc_attr__( 'Featured Product background', 'aasp-lang' ),
        'desc'    => esc_html__( 'The plugins comes with unlimited color schemes for your theme\'s styling.', 'aasp-lang' ),
        'type'    => 'color',
        'default' => esc_attr( $color['featured_product_bg'] ),
        'sanitize_callback' => 'sanitize_text_field'
    ),
    
    
    
    
),
            );
        
            return $settings_fields;
        }
        

        function plugin_setting_page() {
            echo '<div class="wrap aasp_settings_wrap">';

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

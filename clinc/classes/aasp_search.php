<?php
/**
 * Search Template Class - 
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access



class aasp_Search_Template {
	
	public $option_search_from;
	public $option_search_results;
	
	
	public function __construct() {
		
		$this->option_search_from =  apply_filters( 'aasp_search_form', wp_parse_args( aasp_get_option('aasp_search_form') ) );
		$this->option_search_results =  apply_filters( 'aasp_search_results', wp_parse_args ( aasp_get_option('aasp_search_results') ) );
		
		
		
		add_action( 'aasp_search_bar_preview', array( $this, 'aasp_search_style_common' ) );
		add_shortcode( 'aasp_search_bar_preview', array( $this, 'aasp_search_shortcode' ) );
		
		
		
		add_action( 'wp_ajax_nopriv_aasp_get_woo_search_result', array( $this, 'aasp_get_woo_search_result' ) );
		add_action( 'wp_ajax_aasp_get_woo_search_result', array( $this, 'aasp_get_woo_search_result' ) );
		
		add_action('pre_get_posts', array( $this,'aasp_replace_woocomerce_search' ), 999);
		
		
		
	}
	/**
     * replace woocomerce search
     * 
     */
	public function  aasp_replace_woocomerce_search($query) {
	
		if($query->is_search()) {
			
			if (isset($_GET['category']) && !empty($_GET['category'])) {
				
				$query->set('tax_query', array(array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => array( sanitize_text_field ( $_GET['category'] ) ) )
				));
			}    
		}
		return $query;
	}
	/**
     * send ajax result
     * 
     */
	public function aasp_get_woo_search_result(){
		global $woocommerce;
		$search_keyword =  sanitize_text_field ( $_POST['keyword'] );
		$search_results   = array();
		
		
		$args = array(
                's'                   => esc_html( $search_keyword ),
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => absint( $this->option_search_results['number_of_product'] ),
              
            );
		// The Query
		
		 if ( isset($_POST['category']) && !empty($_REQUEST['category']) ){
					
				 
				$args['tax_query'][] = array(
					array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => array( sanitize_text_field ( $_POST['category'] ) )
					)
		 		);
		 }
			   
		$the_query = new WP_Query( $args );
		
		// The Loop
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) : $the_query->the_post();
			
					$product = wc_get_product( get_the_ID() );
					
					$rating_count = $product->get_rating_count();
					$average      = $product->get_average_rating();
					

					
					$search_results[] = array(
						'id'    	=> absint ( $product->id ),
						'title' 	=> esc_html( $product->get_title() ),
						'url'   	=> $product->get_permalink(),
						
						'img_url' 	=> ( apply_filters( 'aasp_show_image', $this->option_search_results['show_image'] ) == 'yes' )? esc_url_raw( get_the_post_thumbnail_url( $product->id,'thumbnail') )  : '' ,
					
						'price'		=> ( apply_filters( 'aasp_show_price', $this->option_search_results['show_price'] ) == 'yes' )? $product->get_price_html() : '' ,
						
						'rating'	=> ( apply_filters( 'aasp_show_rating', $this->option_search_results['show_rating'] ) == 'yes' )? wc_get_rating_html( $average, $rating_count ) : '' ,
						
						'category' 	=> ( apply_filters( 'aasp_show_category', $this->option_search_results['show_category'] ) == 'yes' )? esc_html( wc_get_product_category_list( $product->get_id(), ', ') ) : '' ,
						
						'stock'		=> ( apply_filters( 'aasp_show_stock', $this->option_search_results['stock_status'] ) == 'yes' )? $product->get_stock_status() : '' ,
						
						'content'	=> $this->aasp_ajax_data_content( $product->id ),
						
						'featured'	=> ( $product->is_on_sale() && $this->option_search_results['show_feature'] == 'yes' ) ? true : '',
						
						'on_sale'	=>( $product->is_on_sale() && $this->option_search_results['show_on_sale'] == 'yes' ) ?  esc_html__( 'Sale!', 'aasp-in' ) : '',
						'sku'	=>( $this->option_search_results['show_sku'] == 'yes' ) ?  $product->get_sku() : '',	
					);
					
					
					
				
			endwhile;
		} else {
			 if ( isset($_POST['category'])  && !empty($_REQUEST['category']) ){
				 $search_results[] = array(
                    'id'    => 0,
                    'title' => esc_html( $this->option_search_results['nothing_found_cat'] ),
                    'url'   => '#',
                );
			 }else{
				 
				$search_results[] = array(
                    'id'    => 0,
                    'title' => esc_html( $this->option_search_results['nothing_found'] ),
                    'url'   => '#',
                );
				
			 }
		}
		
		/* Restore original Post Data */
		wp_reset_postdata();
		
		echo json_encode( $search_results );
		
		die();
	}

	/**
     * ajax content type
     * 
     */
	public function aasp_ajax_data_content( $id ){
		$content = '';
		if( apply_filters( 'aasp_show_description', $this->option_search_results['show_description'] )  == 'yes' ){
			
			if( $this->option_search_results['content_source'] == 'content' ){
				$content = mb_strimwidth( esc_html( strip_tags(get_the_content( absint( $id )) ) ) ,0, absint( $this->option_search_results['length'] )  );
			}else{
				$scontent = mb_strimwidth( esc_html( strip_tags(get_the_excerpt( absint( $id )) ) ) ,0, absint( $this->option_search_results['length'] )  );
			}
		}
		return $content;
		
	}
	
	/**
     * Preview the from via shortcode
     * $this->option_search_from['search_form_style']
     */
	function aasp_search_shortcode($atts) {
		// Extract shortcode attributes with a default style value of 6
		extract(shortcode_atts(array(
			'style' => '1',
		), $atts));
	
		// Determine the form style dynamically
		if( isset( $style ) && !empty( $style ) ){
			// Apply filter to customize the form style dynamically
			$style  = esc_html( apply_filters( 'aasp_search_form_style', $this->option_search_from['search_form_style'] ));
		} else {
			// Fallback to a default style from your options if not provided
			$style  = esc_html( apply_filters( 'aasp_search_form_style', 'aasp_search_form_style_'.absint($style) ));

		}
	
		// Return the form style or use it in further processing
		return $this->aasp_search_style_common($style);
	}
	
	/**
     * search form render
     * 
     */
	public function aasp_search_style_common( $style ){
		
		
		echo '<div class="aasp-search-wrap '.esc_attr( $style ).'">';
			
			echo wp_kses( $this->aasp_search_from_start(), aspw_alowed_tags() );
			
			switch ( $style ) {
				
				case "aasp_search_form_style_6":
					$this->aasp_search_style_6();
					break;
					
				case "aasp_search_form_style_5":
					$this->aasp_search_style_5();
					break;
					
				case "aasp_search_form_style_4":
					$this->aasp_search_style_4();
					break;
					
				case "aasp_search_form_style_3":
					$this->aasp_search_style_3();
					break;
				case "aasp_search_form_style_2":
					$this->aasp_search_style_1();
					break;
				default:
				$this->aasp_search_style_1();
			}
			if( !empty($this->aasp_ajax_data()) ){
			echo wp_kses( $this->aasp_ajax_data(), aspw_alowed_tags() );
			}
			
			echo wp_kses( $this->aasp_search_from_end(), aspw_alowed_tags() );
		echo '</div>';
	}
	/**
     * search style 6
     * 
     */
	public function aasp_search_style_6(){
		echo wp_kses( $this->aasp_search_element(  esc_attr( $this->option_search_from['search_btn']  ) ),aspw_alowed_tags() );
	}
	/**
     * search style 5 
     * 
     */
	public function aasp_search_style_5(){
		echo wp_kses( $this->aasp_search_element( esc_attr( $this->option_search_from['search_btn']  ) ) , aspw_alowed_tags() );
		echo wp_kses( $this->aasp_search_element_category(), aspw_alowed_tags() );
	}
	/**
     * search style4 
     * 
     */
	public function aasp_search_style_4(){
		echo wp_kses( $this->aasp_search_element( esc_attr( $this->option_search_from['search_btn']  ) ) , aspw_alowed_tags() );
		echo wp_kses( $this->aasp_search_element_category(), aspw_alowed_tags() );
		
	}
	/**
     * search style 3 
     * 
     */
	public function aasp_search_style_3(){
		echo wp_kses( $this->aasp_search_element( $this->aasp_svg_icon_btn() ), aspw_alowed_tags() );
	}
	 /**
     * search style 2 
     * 
     */
	public function aasp_search_style_2(){
		echo wp_kses( $this->aasp_search_element( $this->aasp_svg_icon_btn() ), aspw_alowed_tags() );
		echo wp_kses( $this->aasp_search_element_category(), aspw_alowed_tags() );
		
		
	}
	 /**
     * search style 1 
     * 
     */
	public function aasp_search_style_1(){
		echo wp_kses( $this->aasp_search_element( $this->aasp_svg_icon_btn() ), aspw_alowed_tags() );
		echo wp_kses( $this->aasp_search_element_category(), aspw_alowed_tags() );
		
		
	}
	 /**
     * create svg icon 
     * 
     */
	public function aasp_svg_icon_btn(){
		return  '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="485.213px" height="485.213px" viewBox="0 0 485.213 485.213" style="enable-background:new 0 0 485.213 485.213;"
	 xml:space="preserve">
  <g>
    <g>
      <path d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324
			c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z"/>
      <path d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951
			C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46
			c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465
			C318.424,257.208,257.206,318.416,181.956,318.416z"/>
      <path d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z"/>
    </g>
  </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
</svg>
';
	}
	public function aasp_ajax_data(){
			
		?>
    	<div class="aasp_ajax_result">
      	
        </div>    
        <?php
	}
	 /**
     * Search from start
     * 
     */
	public function aasp_search_from_start( ) {
		
	
	 	$html = '<form role="search" class="aasp-search-form '.esc_attr( $this->option_search_from['search_action']  ).'" autocomplete="off" action="'.esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ).'" method="get">';
          
		return apply_filters( 'aasp_search_from_start', $html);
	}
	
	 /**
     * Search from end
     * 
     */
	public function aasp_search_from_end() {
		
	 	$html = '</form>';
          
		return apply_filters( 'aasp_search_from_end', $html);
	}
	
	 /**
     * Render Product Search element
     * 
     * @param $post_type = string .
	 *
	 * @return html input type = search ,submit button ,post_type = product
     */
	public function aasp_search_element( $button = '' ) {
		
	 	$html = '<input type="search" name="s" class="aasp-search-input" value="'.esc_attr( get_search_query() ).'" placeholder="'.esc_attr( $this->option_search_from['search_placeholder']  ).'" data-charaters="'.esc_attr( $this->option_search_from['action_charaters']  ).'" data-functiontype="'.esc_attr( $this->option_search_from['search_action']  ).'" />';
          
         $html .= '<button class="aasp-search-btn" type="submit">'. $button .'</button>';
          
         $html .= '<input type="hidden" name="post_type" value="product" />';
		 
		 if( apply_filters( 'aasp_show_loader', $this->option_search_from['show_loader'] ) == 'yes' ){
			 
		 	$html .='<img class="aasp_loader" src="'.esc_url_raw( aasp_PLUGIN_URL ).'assets/images/loader.gif"/>';
			
		 }
		 
		return apply_filters( 'aasp_aasp_search_element', $html);
	}
	 /**
     * Render woocommerce category select box view
     * 
     * @param array .
	 *
	 * @return woocommerce category selectbox html
     */
	public function aasp_search_element_category() {
		
		$cat_args = array(
				'taxonomy' => 'product_cat',
				'orderby' => 'name',
				'show_count' => '0',
				'pad_counts' => '0',
				'hierarchical' => '1',
				'title_li' => '',
				'hide_empty' => '0',
            
            );
			
			
		$all_categories = apply_filters( 'aasp_get_categories_list',get_categories( $cat_args ));
		
		$current_cat = ( isset( $_GET['category'] ) && $_GET['category'] != "" ) ? sanitize_text_field( $_GET['category'] ) : '';
			
			$html ='<div class="aasp-select-box-wrap"><select class="aasp-category-items" name="category">
			
			<option value="0">'.esc_html__('All Categories','aasp-in').'</option>';
			
			
		if( is_array( $all_categories ) && count( $all_categories ) > 0 ):
		
			foreach( $all_categories as $category ) :
			
			
				$selected  = ( $category->slug == $current_cat ) ? 'selected="selected"':'';
				
				$html .= '<option  value="'.esc_attr( $category->slug ).'" data-value="'.esc_attr( $category->slug ).'" '.esc_attr( $selected ).'>'.esc_html( $category->cat_name ).'</option>';
			
			
			
			endforeach;
			
		endif;
		
		
			$html .= '</select></div>';
		
        return apply_filters( 'aasp_woo_categories_select_box', $html); 
	}
	
}

new aasp_Search_Template();
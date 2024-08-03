<?php

class AASPSEARCH extends WP_Widget {

    function __construct() {
        parent::__construct(
            // Base ID of widget
            'aasp-widgets-wrap', 

            // Widget name will appear in UI
            esc_html__('Advanced Product Search', 'aasp-in'),

            // Widget description
            array(
                'classname' => 'aasp-widgets-wrap-class',
                'description' => esc_html__('Advanced Product Search – powerful live search plugin for WooCommerce', 'aasp-in'),
                'customize_selective_refresh' => true,
            )
            );
    }

    // The widget() function - Outputs the content of the widget
    public function widget( $args, $instance ) {
        // Set widget title
        $widget_title = isset( $instance['title'] ) ? $instance['title'] : '';
		$style		  = isset( $instance['search_bar_style'] ) ? $instance['search_bar_style'] : '';
        echo wp_kses( $args['before_widget'] ,aasp_alowed_tags() );
        // if title is present
        if ( ! empty( $widget_title ) ) {
			echo wp_kses( $args['before_title'] ,aasp_alowed_tags() );
			echo  esc_html( $widget_title );
			echo wp_kses( $args['after_title'] ,aasp_alowed_tags() );
          
        }
        // output
        do_action('aasp_search_bar_preview', absint( $style ) );
        echo wp_kses( $args['after_widget'] ,aasp_alowed_tags() );      
    }

    // The form() function - Outputs the options form in the admin
    public function form( $instance ) {
        // Title
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__( 'Product Search', 'AASPSEARCH_domain' );
    
        // Search Bar Style
        $search_bar_style = !empty($instance['search_bar_style']) ? $instance['search_bar_style'] : '1';
    
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'aasp-in' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'search_bar_style' ); ?>"><?php _e( 'Search Bar Style:', 'aasp-in' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'search_bar_style' ); ?>" name="<?php echo $this->get_field_name( 'search_bar_style' ); ?>">
                <?php
                $options = array(
                    '1' => esc_html__( 'Style 1', 'aasp-in' ),
                    '2.0' => esc_html__( 'Style 2', 'aasp-in' ),
                    '3.0' => esc_html__( 'Style 3', 'aasp-in' ),
                    '4.0' => esc_html__( 'Style 4', 'aasp-in' ),
                    '5.0' => esc_html__( 'Style 5', 'aasp-in' ),
                    '6.0' => esc_html__( 'Style 6', 'aasp-in' )
                );
    
                foreach ($options as $key => $label) {
                    echo '<option value="' . esc_attr( $key ) . '" ' . selected( $search_bar_style, $key, false ) . '>' . esc_html( $label ) . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }
    

    // The update() function - Processes widget options to be saved
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['search_bar_style'] = ( ! empty( $new_instance['search_bar_style'] ) ) ? strip_tags( $new_instance['search_bar_style'] ) : '1';
        return $instance;
    }
}

// Register and load the widget
function aasp_register_widget() {
    register_widget( 'AASPSEARCH' );
}
add_action( 'widgets_init', 'aasp_register_widget' );
?>

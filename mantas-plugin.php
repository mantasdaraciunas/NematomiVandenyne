<?php
/*
 * Plugin Name: Nematomi Vandenyne Plugin
 * Plugin URI: https://vandenyne.lt
 * Description: This plugin creates a Gutenberg block
 * Version: 1.0.1
 * Author: Mantas Daraciunas
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: NematomiVandenyne
 * Domain Path: /languages
 *
*/

if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}

if ( ! function_exists( 'mantas_categories_view' ) ) {
    function mantas_categories_view( ) {
        global $wp_query, $nm_theme_options;
        /* Categories */
        $categories_view_output = "";
        if ( $nm_theme_options['shop_categories'] ) {
            $current_cat_id = ( is_tax( 'product_cat' ) ) ? $wp_query->queried_object->term_id : '';
            $is_category = ( strlen( $current_cat_id ) > 0 ) ? true : false;
            $page_id = wc_get_page_id( 'shop' );
            $page_url = get_permalink( $page_id );
            
            echo '<span id="current_cat" style="display:none">'.$current_cat_id.
            get_term_by( 'id', $current_cat_id, 'product_cat' )->name.
            '</span>';

            $currentCategoryName = $is_category ? get_term_by( 'id', $current_cat_id, 'product_cat' )->name : __("Viskas");
            
            $args = array(
                'taxonomy'		=> 'product_cat',
                'type'			=> 'post',
                'hide_empty'	=> true,
                'hierarchical'	=> 0
            );
                
            $categories = get_categories( $args );

            $categories_view_output .= '<div class="nm-row" style="width:80%">
                <div class="shop-categories">
                    <label class="shop-categories__current" for="selected-cat">';
                    $categories_view_output .= $currentCategoryName;
                    $categories_view_output .= '</label>
                    <input type="checkbox" id="selected-cat" class="shop-categories__input">
                    <div class="shop-categories__full-list">
                        <div class="row">
                            <div class="col col-xs-6 col-sm-4 col-md-2 shop-categories__cat"></div> ';
                                if($is_category) {
                                    $categories_view_output .= '<div class="col col-xs-6 col-sm-4 col-md-2 shop-categories__cat">';
                                    $categories_view_output .= '<a href="'. $page_url . '" >';
                                    $categories_view_output .= __('Viskas');
                                    $categories_view_output .= '</a></div>';
                                }
                                foreach($categories as $cat):
                                    if(in_array($cat->slug, ['kaledos'])) continue;

                                    $categories_view_output .= '<div class="col col-xs-6 col-sm-4 col-md-2 shop-categories__cat">';
                                    $categories_view_output .= '<a href="'. get_term_link( (int) $cat->term_id, 'product_cat' ) . '" >';
                                    $categories_view_output .= $cat->name;
                                    $categories_view_output .= '</a></div>';

                                endforeach;

            $categories_view_output .= '</div></div></div></div>';
        } // if $nm_theme_options['shop_categories']
        echo $categories_view_output;
    }
}

// Filter 'kaledos' cat from shop
add_action( 'woocommerce_product_query', 'prefix_custom_pre_get_posts_query' );
function prefix_custom_pre_get_posts_query( $q ) {

	if( is_shop() || is_page('shop') ) { // set conditions here
	    $tax_query = (array) $q->get( 'tax_query' );
	
	    $tax_query[] = array(
	           'taxonomy' => 'product_cat',
	           'field'    => 'slug',
	           'terms'    => array( 'kaledos' ),
	           'operator' => 'NOT IN'
	    );
	
	
	    $q->set( 'tax_query', $tax_query );
	}
}

/**
 * Remove the generated product schema markup from Product Category and Shop pages.
 */
function wc_remove_product_schema_product_archive() {
	remove_action( 'woocommerce_shop_loop', array( WC()->structured_data, 'generate_product_data' ), 10, 0 );
}
add_action( 'woocommerce_init', 'wc_remove_product_schema_product_archive' );

// add_filter( 'page_template', 'specialCategotry' );
// function specialCategotry( $page_template )
// {
//     // if ( is_page( 'kunui' ) ) {
//     //     $page_template = plugin_dir_path( __FILE__ ) . 'special-category.php';
//     // }
//     return $page_template;
// }


// require_once "./FrontPageRedesign2.php";

// add_action("woocommerce_checkout_update_order_review", function() {
//     write_log("hellwlow trigger");
// });

// add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// function custom_override_checkout_fields($fields) {
//     do_action( 'woocommerce_shipping_method_chosen', 'check_if_local_pickup', 10, 1 );
//     // var_dump((WP_DEBUG === true ? 'true' : 'false') . " > " . (WP_DEBUG_LOG !== false ? 'true' : 'false'));
//     try {
//         write_log("fields trigger");
//     } catch ( \Throwable $e) {

//     }
//     return $fields;
// }

// add_action( 'woocommerce_shipping_method_chosen', 'check_if_local_pickup', 10, 1 );
// function check_if_local_pickup( $chosen_method ) {
//     $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
//     $chosen_shipping = $chosen_methods[0]; 
//     try {
//         write_log("------------ Start -------------");
//         write_log($chosen_methods);
//         write_log($chosen_shipping);
//         write_log("------------ END -------------");
//     } catch ( \Throwable $e) {

//     }
// }

// add_action(
//     "woocommerce_after_shipping_rate",
//     'mantofunction',
//     10,
//     2
// );
// function mantofunction( $method, $index ) {
//     $chosenMethods = WC()->session->get( 'chosen_shipping_methods' );
//     try {
//         write_log("------ manto function trigger");
//         write_log($chosenMethods);
//         write_log("------ manto function trigger end ");
//     } catch ( \Throwable $e) {

//     }
// }

// wp_enqueue_script('mantas-plugin-js', plugins_url('/js/mantas-plugin-js.js', __FILE__),
//                 ['jquery'], "1", true);

wp_enqueue_style('custom-mantas-css', plugins_url('/css/mantas-style.min.css', __FILE__));

add_action( 'enqueue_block_editor_assets', 'gb_block_01_basic_editor_assets' );

function gb_block_01_basic_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'gb-block-01-basic',
		plugins_url( 'products-block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'products-block.js' )
	);

	// Styles.
	wp_enqueue_style(
		'gb-block-01-basic-editor',
		plugins_url( 'editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
	);
}
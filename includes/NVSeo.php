<?php


class NVSeo
{
    function __construct()
    {

    }

    function register_hooks() {
        $post_types = get_post_types();

        write_log($post_types);

        foreach ( $post_types as $post_type ) {
            add_filter( 'rest_prepare_' . $post_type->name, [ $this, 'rest_add_seo' ], 11, 3 );
        }


        add_filter( 'woocommerce_rest_prepare_product_cat', [ $this, 'rest_add_seo' ], 11, 3 );
    }

    function rest_add_seo( $response, $post, $request ) {

        $response->data['nv_seo'] = "jega";

        return $response;
    }
}

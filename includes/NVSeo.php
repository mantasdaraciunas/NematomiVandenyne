<?php


class NVSeo
{
    function __construct()
    {
        $this->register_hooks();
    }

    function register_hooks()
    {
        add_filter('rest_prepare_post', [$this, 'rest_add_seo'], 11, 3);
        add_filter('rest_prepare_page', [$this, 'rest_add_seo'], 11, 3);
        add_filter('woocommerce_rest_prepare_product_cat',
            [$this, 'rest_add_seo_taxonomy'], 11, 3);
    }

    function rest_add_seo($response, $post, $request)
    {
        $meta = get_post_meta($post->ID);

        $response->data['nv_seo'] = $this->mapMetaData($meta);

        return $response;
    }

    function rest_add_seo_taxonomy($response, $post, $request)
    {
        $meta = get_term_meta($post->term_id);

        $response->data['nv_seo'] = $this->mapMetaData($meta);

        return $response;
    }

    private function mapMetaData($meta)
    {
        if (!is_array($meta)) {
            return [];
        }

        $seo = [
            "title"       => "",
            "keywords"    => "",
            "description" => ""
        ];

        if (array_key_exists("seo-title", $meta)) {
            $seo['title'] = is_array($meta["seo-title"])
                ? array_shift($meta["seo-title"]) : $meta["seo-title"];
        }

        if (array_key_exists("seo-keywords", $meta)) {
            $seo['keywords'] = is_array($meta["seo-keywords"])
                ? array_shift($meta["seo-keywords"]) : $meta["seo-keywords"];
        }

        if (array_key_exists("seo-description", $meta)) {
            $seo['description'] = is_array($meta["seo-description"])
                ? array_shift($meta["seo-description"])
                : $meta["seo-description"];
        }

        return $seo;
    }
}

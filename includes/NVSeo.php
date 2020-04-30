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


        add_filter('wpseo_og_og_title', [$this, "nvseo_og_properties"]);
        add_filter('wpseo_og_og_url', [$this, "nvseo_og_properties"]);
    }

    function rest_add_seo($response, $post, $request)
    {
        $meta = get_post_meta($post->ID);

        $response->data['nv_seo'] = $this->mapMetaData($post, $meta);

        return $response;
    }

    function rest_add_seo_taxonomy($response, $post, $request)
    {
        $meta = get_term_meta($post->term_id);

        $response->data['nv_seo'] = $this->mapMetaData($post, $meta);

        return $response;
    }

    function nvseo_og_properties($content)
    {
        write_log('Some og value');
        write_log($content);
    }

    private function mapMetaData($post, $meta)
    {
        if (!is_array($meta)) {
            return [];
        }

        $seo = [
            "title"       => $this->getTitle($post) . "| " . wp_title(),
            "keywords"    => "",
            "description" => ""
        ];

        if (array_key_exists("seo-title", $meta)) {
            $seo['title'] = (is_array($meta["seo-title"])
                    ? array_shift($meta["seo-title"]) : $meta["seo-title"])
                . "| " . wp_title();
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

        $this->socialMeta($post, $seo);

        return $seo;
    }

    private function socialMeta($post, $seo)
    {
        //Use Yoast
        $wpseo = new \WPSEO_OpenGraph();


        return [
            [
                "property" => "og:locale",
                "content"  => $wpseo->locale() ?: "lt_LT"
            ],
            [
                "property" => "og:type",
                "content"  => $wpseo->type() ?: "article"
            ],
            [
                "property" => "og:title",
                "content"  => $wpseo->og_title()
                    ?: $seo['title'] ?: $this->getTitle($post, true)
            ],
            [
                "property" => "og:description",
                "content"  => $wpseo->description() ?: $seo['description']
            ],
            [
                "property" => "og:url",
                "content"  => ""
            ],
            [
                "property" => "og:site_name",
                "content"  => get_bloginfo('name')
            ],
            [
                "property" => "og:image",
                "content"  => "https://imgixvandenyne.imgix.net/5-4-2-scaled.jpg?auto=compress%2Cformat&amp;fit=scale&amp;h=594&amp;ixlib=php-1.2.1&amp;w=1024&amp;wpsize=large"
            ],
            [
                "property" => "og:image:secure_url",
                "content"  => "https://imgixvandenyne.imgix.net/5-4-2-scaled.jpg?auto=compress%2Cformat&amp;fit=scale&amp;h=594&amp;ixlib=php-1.2.1&amp;w=1024&amp;wpsize=large"
            ],
            [
                "property" => "og:image:width",
                "content"  => "1024"
            ],
            [
                "property" => "og:image:height",
                "content"  => "594"
            ]
        ];
    }

    private function getTitle($post, $full = false)
    {
        if (array_key_exists("title", $post)) {
            $title = $post['title'];
            if (is_array($title) && array_key_exists("rendered", $title)) {
                return $title["rendered"] . ($full ? "| " . wp_title() : "");
            }

            return $title . ($full ? "| " . wp_title() : "");
        }

        if (array_key_exists("name", $post)) {
            $title = $post['name'];
            if (is_array($title) && array_key_exists("rendered", $title)) {
                return $title["rendered"] . ($full ? "| " . wp_title() : "");
            }

            return $title . ($full ? "| " . wp_title() : "");
        }

        return $full ? wp_title() : "";
    }
}

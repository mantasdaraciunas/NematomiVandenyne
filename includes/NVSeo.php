<?php


class NVSeo
{
    protected $post_seo;
    protected $current_nvseo_post;
    protected $wpSeo;

    function __construct()
    {
        $GLOBALS['current_nvseo_post'] = null;
        $this->post_seo                = [];
        $this->register_hooks();
    }

    function register_hooks()
    {
        add_filter('rest_prepare_post', [$this, 'rest_add_seo'], 10, 3);
        add_filter('rest_prepare_page', [$this, 'rest_add_seo'], 10, 3);
        add_filter('woocommerce_rest_prepare_product_cat',
            [$this, 'rest_add_seo_taxonomy'], 10, 3);
        add_filter('woocommerce_rest_prepare_product_object',
            [$this, 'rest_add_seo_product'], 10, 3);


        add_filter("wpseo_title", [$this, "nvseo_title"]);
        add_filter("wpseo_metadesc", [$this, "nvseo_metadesc"]);
        add_filter("wpseo_robots", [$this, "nvseo_robots"]);


        add_filter('wpseo_og_og_title', [$this, "nvseo_og_properties_title"]);
        add_filter('wpseo_og_og_description',
            [$this, "nvseo_og_properties_description"]);
        add_filter('wpseo_og_og_url', [$this, "nvseo_og_properties_url"]);
        add_filter('wpseo_og_og_locale', [$this, "nvseo_og_properties_locale"]);
        add_filter('wpseo_og_og_type', [$this, "nvseo_og_properties_type"]);
        add_filter('wpseo_og_og_image',
            [$this, "nvseo_og_properties_og_image"]);
        add_filter('wpseo_og_og_image_secure_url',
            [$this, "nvseo_og_properties_og_image_secure_url"]);
        add_filter('wpseo_og_og_image_width',
            [$this, "nvseo_og_properties_og_image_width"]);
        add_filter('wpseo_og_og_image_height',
            [$this, "nvseo_og_properties_og_image_height"]);
    }

    function rest_add_seo($response, $post, $request)
    {
        global $wp_query;
        global $current_nvseo_post;

        $current_nvseo_post              = $post;
        $this->post_seo[$this->postId()] = [];

        ob_start();

        $this->wpSeo = \WPSEO_Frontend::get_instance();

        $wp_query = new WP_Query(
            [
                'p' => $post->ID
            ]
        );

        if ($wp_query->have_posts()) {
            $wp_query->the_post();
        }

        $seo_meta = $this->mapMetaData($post);

        ob_end_clean();

        $this->post_seo[$this->postId()] = $seo_meta;

        $response->data['nv_seo'] = $seo_meta;

        return $response;
    }

    function rest_add_seo_product($response, \WC_Product $post)
    {
        global $wp_query;
        global $current_nvseo_post;

        $current_nvseo_post              = $post;
        $this->post_seo[$this->postId()] = [];

        ob_start();

        $this->wpSeo = \WPSEO_Frontend::get_instance();

        $wp_query = new WP_Query(
            [
                'page'      => "",
                'product'   => $post->get_slug(),
                'post_type' => 'product',
                'name'      => $post->get_slug()
            ]
        );


        if ($wp_query->have_posts()) {
            $wp_query->the_post();
        }

        $seo_meta = $this->mapMetaData($post);

        ob_end_clean();

        $this->post_seo[$this->postId()] = $seo_meta;

        $response->data['nv_seo'] = $seo_meta;

        return $response;
    }

    function rest_add_seo_taxonomy($response, $post, $request)
    {
        global $wp_query;
        global $current_nvseo_post;

        $current_nvseo_post              = $post;
        $this->post_seo[$this->postId()] = [];

        ob_start();

        $this->wpSeo = \WPSEO_Frontend::get_instance();

        $wp_query = new WP_Query(
            [
                'tax_query' => array(
                    array(
                        'taxonomy' => $post->taxonomy,
                        'field'    => 'term_id',
                        'terms'    => $post->term_id,
                    ),
                ),
            ]
        );


        $seo_meta = $this->mapMetaData($post);

        ob_end_clean();

        $this->post_seo[$this->postId()] = $seo_meta;

        $response->data['nv_seo'] = $seo_meta;

        return $response;
    }


    function nvseo_title($content)
    {
        $this->post_seo[$this->postId()]['main_title'] = $content;

        return $content;
    }


    function nvseo_metadesc($content)
    {
        $this->post_seo[$this->postId()]['metadesc'] = $content;

        return $content;
    }


    function nvseo_robots($content)
    {
        $this->post_seo[$this->postId()]['robots'] = $content;

        return $content;
    }

    function nvseo_og_properties_description($content)
    {
        $this->post_seo[$this->postId()]['og_description'] = $content;

        return $content;
    }

    function nvseo_og_properties_url($content)
    {
        $this->post_seo[$this->postId()]['og_url'] = $content;

        return $content;
    }

    function nvseo_og_properties_title($content)
    {
        $this->post_seo[$this->postId()]['og_title'] = $content;

        return $content;
    }

    function nvseo_og_properties_og_image($content)
    {
        $this->post_seo[$this->postId()]['og_image'] = $content;

        return $content;
    }

    function nvseo_og_properties_og_image_secure_url($content)
    {
        $this->post_seo[$this->postId()]['og_image_secure_url'] = $content;

        return $content;
    }

    function nvseo_og_properties_og_image_width($content)
    {
        $this->post_seo[$this->postId()]['og_image_width'] = $content;

        return $content;
    }

    function nvseo_og_properties_og_image_height($content)
    {
        $this->post_seo[$this->postId()]['og_image_height'] = $content;

        return $content;
    }

    function nvseo_og_properties_locale($content)
    {
        $this->post_seo[$this->postId()]['og_locale'] = $content;

        return $content;
    }

    function nvseo_og_properties_type($content)
    {
        $this->post_seo[$this->postId()]['og_type'] = $content;

        return $content;
    }

    private function postId()
    {
        global $current_nvseo_post;

        if (is_null($current_nvseo_post)
            || get_class($current_nvseo_post) === false
        ) {
            return "";
        }

        if ($current_nvseo_post instanceof \WC_Product) {
            return "product_" . $current_nvseo_post->get_slug();
        }

        if ($current_nvseo_post instanceof \WP_Term) {
            return "term_" . $current_nvseo_post->term_id;
        }

        return $current_nvseo_post->ID;

    }

    private function mapMetaData($post)
    {

        $seo = [
            "title"  => $this->wpSeo->title("")
                ?: $this->getTitle($post) . " | "
                . get_bloginfo('name'),
            "robots" => $this->wpSeo->robots()
        ];

        //Set Description
        $this->wpSeo->metadesc();

        $seo["description"]
            = isset($this->post_seo[$this->postId()]['metadesc'])
            ? $this->post_seo[$this->postId()]['metadesc'] : "not set";


        $seo["meta"] = $this->socialMeta($post, $seo);

        return $seo;
    }

    private function socialMeta($post, $seo)
    {
        //Use Yoast
        new \WPSEO_OpenGraph();
        do_action('wpseo_opengraph');

        $meta = [
            [
                "property" => "og:locale",
                "content"  => (isset($this->post_seo[$this->postId()]['og_locale'])
                    ? $this->post_seo[$this->postId()]['og_locale'] : null)
                    ?: "lt_LT"
            ],
            [
                "property" => "og:type",
                "content"  => (isset($this->post_seo[$this->postId()]['og_type'])
                    ? $this->post_seo[$this->postId()]['og_type'] : "article")
            ],
            [
                "property" => "og:title",
                "content"  => (isset($this->post_seo[$this->postId()]['og_title'])
                    ? $this->post_seo[$this->postId()]['og_title'] : null)
                    ?: ($seo['title'] ?: $this->getTitle($post, true))
            ],
            [
                "property" => "og:description",
                "content"  => (isset($this->post_seo[$this->postId()]['og_description'])
                    ? $this->post_seo[$this->postId()]['og_description'] : null)
                    ?: $seo['description']
            ],
            [
                "property" => "og:url",
                "content"  => (isset($this->post_seo[$this->postId()]['og_url'])
                    ? $this->post_seo[$this->postId()]['og_url'] : null)
            ],
            [
                "property" => "og:site_name",
                "content"  => get_bloginfo('name')
            ],
            [
                "property" => "og:image",
                "content"  => isset($this->post_seo[$this->postId()]['og_image'])
                    ? $this->post_seo[$this->postId()]['og_image'] : ""
            ],
            [
                "property" => "og:image:secure_url",
                "content"  => isset($this->post_seo[$this->postId()]['og_image_secure_url'])
                    ? $this->post_seo[$this->postId()]['og_image_secure_url']
                    : ""
            ],
            [
                "property" => "og:image:width",
                "content"  => isset($this->post_seo[$this->postId()]['og_image_width'])
                    ? $this->post_seo[$this->postId()]['og_image_width'] : ""
            ],
            [
                "property" => "og:image:height",
                "content"  => isset($this->post_seo[$this->postId()]['og_image_height'])
                    ? $this->post_seo[$this->postId()]['og_image_height'] : ""
            ]
        ];

        return $meta;
    }

    private function getTitle($post, $full = false)
    {
        $post = (array)$post;

        if (array_key_exists("title", $post)) {
            $title = $post['title'];
            if (is_array($title) && array_key_exists("rendered", $title)) {
                return $title["rendered"] . ($full ? " | "
                        . get_bloginfo('name') : "");
            }

            return $title . ($full ? " | " . get_bloginfo('name') : "");
        }

        if (array_key_exists("name", $post)) {
            $title = $post['name'];
            if (is_array($title) && array_key_exists("rendered", $title)) {
                return $title["rendered"] . ($full ? " | "
                        . get_bloginfo('name') : "");
            }

            return $title . ($full ? " | " . get_bloginfo('name') : "");
        }

        return $full ? get_bloginfo('name') : "";
    }
}

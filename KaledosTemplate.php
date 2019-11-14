<?php
/* Template Name: Kaledos template */
?>
<?php
global $nm_theme_options, $post;
?>

<?php get_header(); ?>

<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

        <div class="kaledos-page">
            <?php
            if (has_post_thumbnail()): ?>
                <div class="front-page__image"
                     style="background-image: url('<?php echo the_post_thumbnail_url(); ?>')">
                    <h1 class="front-page__title">
                        <?php echo the_title(); ?>
                    </h1>
                    <p class="front-page__subtitle">
                        <?php echo get_field('frontpage_subtitle'); ?>
                    </p>
                </div>
            <?php
            endif;
            ?>    

    <?php endwhile; ?>

    <?php
    wp_reset_postdata();
endif;
?>

</div>

<?php get_footer(); ?>

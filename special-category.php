<?php
/* Template Name: Special Category Name */
?>

<?php get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>


<h2>Labas</h2>

	<?php endwhile; ?>


<?php
    wp_reset_postdata();
endif;
?>

</div>

<?php get_footer(); ?>
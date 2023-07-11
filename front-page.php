<?php get_header(); ?>

<div class="container">
<div class="separator-60"></div>

<?php while ( have_posts() ) : the_post(); ?>

<?php the_content(); ?>

<?php endwhile; // end of the loop. ?>

<div class="separator-60"></div>
</div>

<?php get_footer(); ?>

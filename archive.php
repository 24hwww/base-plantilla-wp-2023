<?php get_header(); ?>

<div class="separator-60"></div>
<div class="container">

<?php while ( have_posts() ) : the_post(); ?>

<?php the_content(); ?>

<?php endwhile; // end of the loop. ?>

</div>
<div class="separator-60"></div>

<?php get_footer(); ?>

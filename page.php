<?php

    /* ======================================================================================

   This is the default 'page' template. It's used when you create a new page in WordPress and
   don't select one of our page templates. It has a sidebar on the right for larger screens.

    ====================================================================================== */

    get_header();

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<h1><?php the_title(); ?></h1>
	<div>
		<?php the_content(); ?>
	</div>

	<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif; ?>

<?php get_footer(); ?>
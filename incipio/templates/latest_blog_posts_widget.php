<?php

	/* ======================================================================================

	This file is the template output for the Latest Blog Posts widget. That widget will use
	this file to output its markup. It is included *after* the if( has_posts() ) call, and
	therefore just a while() loop is required. It's called from within a WP_Query query and,
	as such, needs wp_reset_postdata() at the end to prevent the world from crumbling down.

	The WP_Query is stored as an object in $lbp and the arguments for that query are in
	$query_args

	There are some filters for the containers for this widget

	themeists_widget_lbp_container_open
	themeists_widget_lbp_container_close

	Useful variables:

	$title 				- The *widget* title
	$subtitle			- The widget subtitle
	$num_to_show		- How many posts to show
	$type_to_show		- The post type being shown
	$tax_to_show		- The taxonomy to filter by
	$term_to_show		- And the taxonomy term to filter by
	$show_more_button	- Whether the "Show more" button should be shown
	$button_text		- The show more button text
	$button_link		- The show more button link
	$show_thumbnail		- Whether to show a thumbnail or not
	$show_comments		- Whether to show the number of comments or not
	$show_date			- Whether to show the post date or not
	$show_author		- Whether to show the post author or not
	$show_likes			- Whether to show the amount of likes this post has or not

	You should wrap your markup with

	while( $lbp->have_posts() ) : $lbp->the_post();

		{{ MARKUP HERE USING NORMAL WORDPRESS FUNCTIONS }}

	endwhile; wp_reset_postdata();

	====================================================================================== */

?>
	
	<?php if( $show_more_button == 1 ) : ?>

		<p class="full_link with_icon lbp_type_<?php echo $type_to_show; ?> lbp_tax_<?php echo $tax_to_show; ?> lbp_term_<?php echo $term_to_show; ?>"><a href="<?php echo $button_link; ?>" title="<?php echo $button_text; ?>"><span class="square_icon"></span><?php echo $button_text; ?></a></p>

	<?php endif; ?>


	<ul class="lbp_post_list show_<?php echo $num_to_show; ?>">


		<?php while( $lbp->have_posts() ) : $lbp->the_post(); ?>

			<li class="lbp_widget_post_<?php echo get_the_ID(); ?> columns <?php echo incipio_convert_number_to_words( 12/$num_to_show ); ?>">

				<?php if( $show_thumbnail == 1 ) : ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="lbp_thumb">
						<?php the_post_thumbnail( apply_filters( 'themeists_widget_lbp_thumbnail_size', 'lbp_thumb' ) ); ?>
					</a>
				<?php endif; ?>

				
				<h4 class="<?php echo apply_filters( 'themeists_widget_lbp_post_title_classes', 'widget_post_title' ); ?>">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h4>

				<div class="<?php echo apply_filters( 'themeists_widget_lbp_post_excerpt_classes', 'widget_post_excerpt' ); ?>">
					<?php the_excerpt(); ?>
				</div><!-- .widget_post_excerpt -->


				<?php if( ( $show_comments == 1 ) || ( $show_date == 1 ) || ( $show_author == 1 ) || ( $show_likes == 1 )  ) : ?>

					<ul class="<?php echo apply_filters( 'themeists_widget_lbp_post_meta_classes', 'widget_post_meta' ); ?>">

						<?php if( $show_comments == 1 ) : ?>
						<li class="lbp_comments li_with_icon">
							<a href="<?php the_permalink(); ?>#comments" title="<?php the_title(); ?>">
								<?php comments_number( __( '0 comments', THEMENAME ), __( '1 comment', THEMENAME ), __( '% comments', THEMENAME ) ); ?>
							</a>
						</li>
						<?php endif; ?>

						<?php if( $show_date == 1 ) : ?>
						<li class="lbp_date">
							<?php the_time( 'M j, Y' ); ?>
						</li>
						<?php endif; ?>

						<?php if( $show_author == 1 ) : ?>
						<li class="lbp_author li_with_icon">
							<?php the_author_posts_link(); ?>
						</li>
						<?php endif; ?>

						<?php if( $show_likes == 1 ) : ?>
						<li class="lbp_likes">
							<?php ThemeistsLikeThis::printLikes( get_the_ID() ); ?>
						</li>
						<?php endif; ?>

					</ul><!-- .widget_post_meta -->

				<?php endif; ?>

			</li>

		<?php endwhile; wp_reset_postdata(); ?>


	</ul>
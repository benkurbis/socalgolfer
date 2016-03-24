<?php
/**
 * Template part for displaying blog posts.
 *
 * @package thrive
 */

?>
<div class="col-lg-6 blog-post">
<article id="post-<?php the_ID(); ?>" <?php post_class(array('thrive-card', 'mg-bottom-15', 'thrive-archives')); ?>>

	<?php if (has_post_thumbnail()) { ?>
		<header class="entry-header has-post-thumbnail mg-bottom-25">
			
			<div class="entry-thumbnail">
				<a href="<?php echo esc_url(the_permalink()); ?>" title="<?php echo esc_attr(the_title()); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
			</div>
			<div class="entry-meta">
				<?php thrive_posted_on(); ?>
				<a href="<?php echo esc_url(the_permalink()); ?>" title="<?php echo esc_attr(the_title()); ?>">
					<?php the_title( '<h1 class="entry-title type-light">', '</h1>' ); ?>
				</a>
			</div><!-- .entry-meta -->

			<div class="entry-actions">
				<a href="<?php comments_link(); ?>" title="<?php _e('Comments', 'thrive'); ?>">
					<span class="material-icons md-24 md-light">
						comment
					</span>
					<span class="entry-actions-comment-count">
						<?php comments_number('Add Comment', '1 Comment', '% Comments' ); ?>
					</span>
				</a>
			</div><!--.entry-actions-->
		</header><!-- .entry-header -->

	<?php } else { ?>
		
		<header class="entry-header">
			
			<div class="entry-meta">
				<?php thrive_posted_on(); ?>
			</div><!-- .entry-meta -->

			<a href="<?php echo esc_url(the_permalink()); ?>" title="<?php echo esc_attr(the_title()); ?>">
				<?php the_title('<h1 class="entry-title">', '</h1>' ); ?>
			</a>

		</header><!-- .entry-header -->

	<?php } ?>

	<!--"End Header"-->

	

	
</article><!-- #post-## -->
</div>
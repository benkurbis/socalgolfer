<?php
/**
 * The template for displaying all golf match requests.
 *
 * @package thrive
 */

get_header(); ?>

<?php $layout = thrive_get_layout(); ?>

<div class="<?php echo esc_attr( $layout['layout'] ); ?>">

	<div id="content-left-col" class="col-md-8">

		<div id="primary" class="content-area one">
		
			<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
					<?php if (has_post_thumbnail()) { ?>
					
					<header class="entry-header has-post-thumbnail mg-bottom-25">
				
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
				
						<div class="entry-meta hidden-xs">
							<?php thrive_posted_on(); ?>
							<div class="hidden-xs">
								<?php the_title( '<h1 class="entry-title type-light">', '</h1>' ); ?>
							</div>
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
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->
				
					<?php } ?>
				
					<div class="entry-content">
					
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="visible-xs">
								<?php thrive_posted_on(); ?>
								<?php the_title( '<h2 class="visible-xs h1 entry-title type-light">', '</h1>' ); ?>
							</div>
						<?php } ?>
				
						<p class="tee-time-date"><span class="user-title">Date of Tee Time:</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_when'); ?></span></p>
						<p class="time-day"><span class="user-title">Time of Day:</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_time'); ?></span></p>
						<p class="course-name"><span class="user-title">Course Name:</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_course'); ?></span></p>
						<p class="tee-time"><span class="user-title">Is the Tee Time Booked?</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_booked'); ?></span></p>
						<p class="golfers"><span class="user-title">I need</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_golfers'); ?> golfer(<span style="text-transform: lowercase;">s</span>).</span></p>
						<p></p>
						
						<?php
							wp_link_pages( array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'thrive' ),
								'after'  => '</div>',
							) );
						?>
					</div><!-- .entry-content -->
				
					<footer class="entry-footer">
						<?php thrive_entry_footer(); ?>
					</footer><!-- .entry-footer -->
				</article><!-- #post-## -->


				

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!--col-md-8-->
	
	<div id="content-right-col" class="<?php echo esc_attr( $layout['sidebar'] ); ?>">	
		<?php get_sidebar(); ?>
	</div>
	
</div>
<?php get_footer(); ?>

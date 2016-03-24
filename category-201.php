<?php
/**
 * The template for displaying Golf With Me Posts.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package thrive
 */

get_header(); ?>
<div id="archive-section">
	<div class="col-md-8" id="content-left-col">
		<div id="primary" class="content-area">
			<main id="main" class="site-main testing" role="main">

			<?php if ( have_posts() ) : ?>
				<?php 
					$archive_allowed_tags = array(
					    'a' => array(
					        'href' => array(),
					        'title' => array()
					    ),
					    'span' => array(
					    	'class' => array()
					    )
					);
				?>

				<header class="page-header thrive-card no-mg-top">
					<?php
						$archive_title = "Join A Match";
						$archive_description = get_the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
					<?php if ( empty ( $archive_title ) ) { ?>

						<h1 class="page-title">

							<i class="material-icons md-24 md-dark">archive</i><?php _e( 'Archives', 'thrive' ); ?>

						</h1>

					<?php } else { ?>

						<h1 class="page-title" style="margin-top: 0;"><?php echo wp_kses( $archive_title, $archive_allowed_tags ); ?></h1>

					<?php } ?>

					<?php echo esc_html( $archive_description ); ?>

				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(array('thrive-card', 'mg-bottom-15', 'thrive-archives')); ?>>

	<?php if (has_post_thumbnail()) { ?>
		<header class="entry-header has-post-thumbnail mg-bottom-25">
			<div class="entry-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>

			<div class="entry-meta hidden-xs">
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

	<div class="entry-content">
		
		<?php if ( has_post_thumbnail() ) { ?>

			<header class="entry-header mg-top-10 visible-xs">
			
				<div class="entry-meta">
					<?php thrive_posted_on(); ?>
				</div><!-- .entry-meta -->

				<a href="<?php echo esc_url(the_permalink()); ?>" title="<?php echo esc_attr(the_title()); ?>">
					<?php the_title('<h1 class="entry-title">', '</h1>' ); ?>
				</a>
				
			</header><!-- .entry-header -->

		<?php } ?>
			<p class="tee-time-date"><span class="user-title">Date of Tee Time:</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_when'); ?></span></p>
			<p class="time-day"><span class="user-title">Time of Day:</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_time'); ?></span></p>
			<p class="course-name"><span class="user-title">Course Name:</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_course'); ?></span></p>
			<p class="tee-time"><span class="user-title">Is the Tee Time Booked?</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_booked'); ?></span></p>
			<p class="golfers"><span class="user-title">I need</span> <span class="user-input"><?php echo usp_get_meta(false, 'match_golfers'); ?> golfer(<span style="text-transform: lowercase;">s</span>).</span></p>
						
		<?php
			
			/* translators: %s: Name of current post */
			the_content( sprintf(
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'thrive' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		?>

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

				<?php endwhile; ?>

				<?php the_posts_navigation(); ?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!--col-md-8-->

<div class="col-md-4" id="content-right-col">	
	<?php get_sidebar(); ?>
</div>
</div><!--#archive-section-->
<?php get_footer(); ?>

<?php get_header(); ?>
		<div id="fsn-base-blogroll" class="container">
			<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="row">
					<div class="col-sm-3">
						<?php if (has_post_thumbnail()) {
							if (function_exists('fsn_get_dynamic_image')) {
								$attachment_id = get_post_thumbnail_id($post->ID);
								echo '<a href="'. get_permalink() .'" class="blogroll-post-image">'. fsn_get_dynamic_image($attachment_id, 'img-responsive', 'medium', 'mobile') .'</a>';
							} else {
								echo '<a href="'. get_permalink() .'" class="blogroll-post-image">'. get_the_post_thumbnail($post, 'medium', array( 'class' => 'img-responsive' )) .'</a>';
							}
						} ?>
					</div>
					<div class="col-sm-9">
						<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
						<footer class="post-metadata">
							<?php echo function_exists('fsn_get_post_meta') ? fsn_get_post_meta() : fsn_base_get_post_meta(); ?>
							<?php edit_post_link( __( 'edit', 'fusion-base' ), '', '', 0, 'post-edit-link btn btn-default btn-xs'); ?>
						</footer>
					</div>
				</div>					
			</article>
			<?php endwhile; else: ?>
			<h2><?php _e('No Posts Found', 'fusion-base') ;?></h2>
			<p><?php _e('Sorry, there\'s nothing here.', 'fusion-base'); ?></p>
			<?php endif; ?>
			<?php function_exists('fsn_pagination') ? fsn_pagination() : fsn_base_pagination(); ?>
		</div>
		<?php get_footer(); ?>
    </body>
</html>
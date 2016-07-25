<?php get_header(); ?>
		<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>																							
			<?php //edit_post_link( __( 'edit', 'fusion-base' ), '', '', 0, 'post-edit-link btn btn-default btn-xs'); ?>
		</article>
		<?php endwhile; ?>				
		<?php else: ?>
		<div class="col-sm-12">
			<h2><?php _e('Page Not Found', 'fusion-base') ;?></h2>
			<p><?php _e('Sorry, there\'s nothing here.', 'fusion-base'); ?></p>
		</div>
		<?php endif; ?>				
		<?php get_footer(); ?>
    </body>
</html>
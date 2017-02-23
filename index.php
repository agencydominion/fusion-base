<?php get_header(); ?>
		<div id="fsn-base-blogroll" class="container">
			<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
				<?php get_template_part( 'template-parts/post/content' ); ?>
			<?php endwhile; else: ?>
			<h2><?php _e('No Posts Found', 'fusion-base') ;?></h2>
			<p><?php _e('Sorry, there\'s nothing here.', 'fusion-base'); ?></p>
			<?php endif; ?>
			<?php function_exists('fsn_pagination') ? fsn_pagination() : fsn_base_pagination(); ?>
		</div>
		<?php get_footer(); ?>
    </body>
</html>
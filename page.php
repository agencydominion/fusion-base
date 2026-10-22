<?php get_header(); ?>
		<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
			<?php get_template_part( 'template-parts/page/content', 'page' ); ?>
		<?php endwhile; ?>				
		<?php else: ?>
		<div class="col-sm-12">
			<h2><?php esc_html_e('Page Not Found', 'fusion-base') ;?></h2>
			<p><?php esc_html_e('Sorry, there\'s nothing here.', 'fusion-base'); ?></p>
		</div>
		<?php endif; ?>				
		<?php get_footer(); ?>
    </body>
</html>
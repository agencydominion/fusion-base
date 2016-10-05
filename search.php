<?php get_header(); ?>
		<div id="fsn-base-search-results" class="container">
			<?php 
				$total_results = $wp_query->found_posts;
				$feedback = _n('match', 'matches', $total_results, 'fusion-base');				
				echo '<div class="search-results-summary">'. __('Found', 'fusion-base') .' <span class="search-results-number">'. $total_results .'</span> '. $feedback .' '. __('for', 'fusion-base') .' <span class="search-results-term">'. get_search_query() .'</span></div>';
			?>
		</div>
		<div class="container">
			<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>	
				<?php the_excerpt(); ?>
			</article>
			<?php endwhile; else : ?>
			<h2><?php _e('No Posts Found', 'fusion-base') ;?></h2>
			<p><?php _e('Sorry, there\'s nothing here.', 'fusion-base'); ?></p>
			<?php endif; ?>					
			<?php function_exists('fsn_pagination') ? fsn_pagination() : fsn_base_pagination(); ?>
		</div>
		<?php get_footer(); ?>
    </body>
</html>
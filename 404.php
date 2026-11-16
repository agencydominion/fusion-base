<?php
defined( 'ABSPATH' ) || exit;

if ( ! fsn_base_404_page_enabled() ) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".esc_url(home_url()));
	exit();
}
$fsn_base_404_post = fsn_base_404_page_post();
?>
<?php get_header(); ?>
		<?php if ( $fsn_base_404_post ) : ?>
			<?php
			global $post;
			$post = $fsn_base_404_post;
			setup_postdata( $post );
			get_template_part( 'template-parts/page/content', 'page' );
			wp_reset_postdata();
			?>
		<?php else : ?>
		<div id="fsn-base-404" class="container" style="padding-top:60px;padding-bottom:60px;">
			<h1><?php _e('Page Not Found', 'fusion-base'); ?></h1>
			<p><?php _e('Sorry, the page you\'re looking for isn\'t here.', 'fusion-base'); ?></p>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e('Go to the home page', 'fusion-base'); ?></a></p>
		</div>
		<?php endif; ?>
		<?php get_footer(); ?>
    </body>
</html>

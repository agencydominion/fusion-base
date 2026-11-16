<?php
defined( 'ABSPATH' ) || exit;

if ( ! fsn_base_404_page_enabled() ) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".esc_url(home_url()));
	exit();
}
?>
<?php get_header(); ?>
		<div id="fsn-base-404" class="container" style="padding-top:60px;padding-bottom:60px;">
			<h1><?php _e('Page Not Found', 'fusion-base'); ?></h1>
			<p><?php _e('Sorry, the page you\'re looking for isn\'t here.', 'fusion-base'); ?></p>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e('Go to the home page', 'fusion-base'); ?></a></p>
		</div>
		<?php get_footer(); ?>
    </body>
</html>

<?php

/**
 * Basic Initialization
 */

function fsn_base_setup() {

	// Initialize the language files
	load_theme_textdomain( 'fusion-base', get_template_directory() . '/languages' );

	//add post thumbnails support
	add_theme_support( 'post-thumbnails' );

	//custom background support
	add_theme_support( 'custom-background' );

	//automatic feed links support
	add_theme_support( 'automatic-feed-links' );

	//selective refresh for widgets in the customizer
	add_theme_support( 'customize-selective-refresh-widgets' );

	//add title tag support
	add_theme_support( 'title-tag' );

	//add editor stylesheet
	add_editor_style();

	//define content width
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 2560;
	}

}
add_action( 'after_setup_theme', 'fsn_base_setup' );

//customize excerpt end tag
function fsn_base_excerpt_more() {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		wp_kses_post( sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'fusion-base' ), esc_html( get_the_title( get_the_ID() ) ) ) )
	);
	return ' &hellip; ' . $link;
}
add_filter('excerpt_more', 'fsn_base_excerpt_more');

/**
 * Scripts and Styles
 */

add_action( 'wp_enqueue_scripts', 'fsn_base_script_enqueue' );

function fsn_base_script_enqueue() {
	//enqueue scripts and styles
	wp_enqueue_script('jquery');
	wp_enqueue_style('bootstrap', trailingslashit( get_template_directory_uri() ) .'css/bootstrap.min.css', array(), '3.3.7');
	wp_enqueue_script('bootstrap', trailingslashit( get_template_directory_uri() ) .'js/vendor/bootstrap.min.js', array('jquery'), '3.3.7', true);
	wp_enqueue_script( 'modernizr', trailingslashit( get_template_directory_uri() ) .'js/vendor/modernizr-3.3.1-respond-1.4.2.min.js', array(), '3.3.1');
	wp_enqueue_style('fsn_base_styles', get_stylesheet_uri(), array('bootstrap'), wp_get_theme( get_template() )->get( 'Version' ));

	if ( is_singular() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * Sidebars
 */

add_action( 'widgets_init', 'fsn_base_register_sidebars' );

function fsn_base_register_sidebars() {

	//primary sidebar
	register_sidebar(
		array(
			'name' => __( 'Primary Sidebar', 'fusion-base' ),
			'id' => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		)
	);

}

/**
 * Comments
 */

//filter form fields
add_filter( 'comment_form_default_fields', 'fsn_base_comment_form_default_fields' );
function fsn_base_comment_form_default_fields($fields) {

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$args = array();
	$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html_req = ( $req ? " required='required'" : '' );
	$html5    = 'html5' === $args['format'];

	$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'fusion-base' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> <input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>';
	$fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'fusion-base' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> <input id="email" class="form-control" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>';
	$fields['url'] = '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'fusion-base' ) . '</label> <input id="url" class="form-control" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	return $fields;
}

//filter form defaults
add_filter( 'comment_form_defaults', 'fsn_base_comment_form_defaults' );
function fsn_base_comment_form_defaults($defaults) {
	$defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . esc_html_x( 'Comment', 'noun', 'fusion-base' ) . '</label> <textarea id="comment" class="form-control" name="comment" cols="45" rows="8"  aria-required="true" required="required"></textarea></p>';
	$defaults['class_submit'] = 'btn btn-default';
	return $defaults;
}

/**
 * Pagination
 *
 * Function for outputting pagination
 *
 * @since 1.0.0
 *
 */

//pagination **passing a $query_max_pages fixes pagination for custom WP_Query objects
if (!function_exists('fsn_base_pagination')) {
	function fsn_base_pagination($query_max_pages = false) {
		global $wp_query;
		if (!empty($query_max_pages)) {
			$total_pages = $query_max_pages;
		} else {
			$total_pages = $wp_query->max_num_pages;
		}
		if ( $total_pages > 1 ) {
			$previous_page_label = esc_html__('&laquo; Previous Page', 'fusion-base');
			$next_page_label = esc_html__('Next Page &raquo;', 'fusion-base');
			echo '<ul class="pager">';
		      	echo '<li class="previous">'. get_previous_posts_link($previous_page_label) .'</li>';
		        echo '<li class="next">'. get_next_posts_link($next_page_label, $total_pages) .'</li>';
			echo '</ul>';
		}
	}
}

/**
 * Get Metadata
 *
 * Function for getting and returning post metadata
 *
 * @since 1.0.0
 *
 * @return string
 */

if (!function_exists('fsn_base_get_post_meta')) {
	function fsn_base_get_post_meta($args = false) {
		global $post;

		$defaults = array(
			'author' => true,
			'date' => true,
			'categories' => true,
			'tags' => true,
		);
		$args = wp_parse_args($args, $defaults);
		$author = $args['author'];
		$date = $args['date'];
		$categories = $args['categories'];
		$tags = $args['tags'];

		$output = '';
		$separator = apply_filters('fsn_post_meta_separator', '&bull;');
		if (!empty($author)) {
			$author = get_the_author();
			$output .= sprintf(esc_html__('By %1$s', 'fusion-base'), esc_html($author));
		}
		if (!empty($date)) {
			$date = get_the_date();
			$output .= !empty($author) ? sprintf(esc_html__(' on %1$s', 'fusion-base'), esc_html($date)) : esc_html($date);
		}
		if (!empty($categories)) {
			$post_type = get_post_type();
			$taxonomy = apply_filters('fsn_post_meta_taxonomy', 'category', $post_type);
			if (!empty($taxonomy)) {
				$categories_array = get_the_terms($post->ID, $taxonomy);
				$numcats = is_array($categories_array) ? count($categories_array) : 0;
				$i = 0;
				$categories = '';
				if (!empty($categories_array)) {
					foreach($categories_array as $category) {
						$i++;
						$categories .= '<a href="'. esc_url(get_term_link($category, $taxonomy)) .'">'. esc_html($category->name) .'</a>';
						$categories .= $i < $numcats ? ', ' : '';
					}
					$output .= !empty($author) || !empty($date) ? ' '. $separator .' '. $categories : $categories;
				}
			}
		}
		if (!empty($tags)) {
			//tags
			$tags_array = get_the_tags($post->ID);
			$numtags = is_array($tags_array) ? count($tags_array) : 0;
			$i = 0;
			$tags = '';
			if (!empty($tags_array)) {
				foreach($tags_array as $tag) {
					$i++;
					$tags .= '<a href="'. esc_url(get_tag_link($tag)) .'">'. esc_html($tag->name) .'</a>';
					$tags .= $i < $numtags ? ', ' : '';
				}
				$output .= '<br><span class="post-tags">'. $tags .'</span>';
			}
		}
		return $output;
	}
}

/**
 * WP Body Open backwards compatibility
 *
 * Add fallback function for wp_body_open action, added in WordPress 5.2.0
 *
 * @since 1.4.0
 *
 */

if ( ! function_exists( 'wp_body_open' ) ) {
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support WordPress versions prior to 5.2.0.
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}

/**
 * Fusion Add-ons
 */

if (class_exists('FusionCoreComponents')) {
	//include components
	require_once('functions/components.php');
}

?>

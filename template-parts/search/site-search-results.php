<?php
/**
 * Template part for displaying results of a search query
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Fusion_Base
 * @since 1.2.0
 */
?>

<div id="fsn-base-search-results" class="container">
	<?php 
		$total_results = $wp_query->found_posts;
		$feedback = _n('match', 'matches', $total_results, 'fusion-base');				
		echo '<div class="search-results-summary">'. __('Found', 'fusion-base') .' <span class="search-results-number">'. $total_results .'</span> '. $feedback .' '. __('for', 'fusion-base') .' <span class="search-results-term">'. get_search_query() .'</span></div>';
	?>
</div>
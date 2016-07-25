<form role="search" method="get" class="search-form form-inline" action="<?php echo esc_url(home_url( '/' )); ?>">
	<div class="form-group">
		<span class="screen-reader-text"><?php __( 'Search for:', 'fusion-base' ); ?>'</span>
		<input type="text" name="s" class="search-query form-control" placeholder="<?php __('Search &hellip;', 'fusion-base') ?>">
	</div>
	<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span><span class="screen-reader-text"><?php __( 'Search', 'fusion-base' ); ?>'</span></button>
</form>
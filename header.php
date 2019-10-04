<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php
        $options = get_option( 'fsn_options' );
    		$fsn_meta_viewport = !empty($options['fsn_meta_viewport']) ? $options['fsn_meta_viewport'] : '';
        if (!empty($fsn_meta_viewport)) {
          echo '<meta name="viewport" content="width=device-width">';
        } else {
          if (class_exists('Mobile_Detect')) {
            $detect = new Mobile_Detect();
            if ($detect->isMobile() && !$detect->isTablet()) {
              echo '<meta name="viewport" content="width=device-width">';
            }
          }
        }
        ?>
	    <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?> data-view="mobile">
    <?php wp_body_open(); ?>
	<?php do_action('fsn_base_header_components'); ?>

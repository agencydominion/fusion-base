<?php
/**
 * Components Add-on
 */

//LAYOUT PAGE
add_action('admin_menu', 'fsn_base_layout_add_page');
function fsn_base_layout_add_page() {
	$theme_options = add_theme_page( 'Layout', 'Layout', 'edit_theme_options', 'fsn_base_layout', 'fsn_base_layout_page' );
}

//enqueue scripts
add_action('admin_enqueue_scripts', 'fsn_base_layout_admin_scripts');
function fsn_base_layout_admin_scripts($hook) {
	if ($hook == 'appearance_page_fsn_base_layout') {
		wp_enqueue_script( 'select2' );
		wp_enqueue_style( 'select2' );
		wp_enqueue_script('fsn_base_components_admin', get_template_directory_uri() . '/js/fsn-base-components-admin.js', array( 'jquery','jquery-ui-autocomplete','jquery-ui-sortable'));
		wp_localize_script( 'fsn_base_components_admin', 'fsnBaseJS', array(
				'fsnEditLayoutNonce' => wp_create_nonce('fsn-admin-edit-layout')
			)
		);
	}
}

/* Draw the option page */
function fsn_base_layout_page() {
	?>
	<div class="wrap">
		<h2>Layout</h2>
		<?php settings_errors(); ?>
		<form action="options.php" method="post">
			<?php settings_fields('fsn_base_layout_options'); ?>
			<?php do_settings_sections('fsn_base_layout'); ?>			
			<?php submit_button('Save Changes', 'primary'); ?>
		</form>
	</div>
	<style>
		.appearance_page_fsn_base_layout .form-table th	{
			display:none;	
		}
		.appearance_page_fsn_base_layout .form-table td	{
			padding-left:0;
			padding-right:0;
		}
		#fsn-base-list-items-sort	{
			margin-bottom:25px;
		}
    	#fsn-base-list-items-sort .list-item	{
			padding:10px;
    		position:relative;
    		overflow:hidden;
    		border:1px solid #ddd;
    		background:#f0f0f0;
    		-moz-border-radius:4px;
    		-webkit-border-radius:4px;
    		border-radius:4px;
    		margin-bottom:10px;
    	}
		#fsn-base-list-items-sort .list-item p	{
			margin:10px 0;
		}
    	#fsn-base-list-items-sort .list-item.content-item	{
    		background-color:#d9edf7;
    		border-color:#bce8f1;
    		color:#31708f;
    	}
    	#fsn-base-list-items-sort .list-item:hover	{
    		cursor:move;
    	}       	
		#fsn-base-list-items-sort .list-item .fsn-base-remove-list-item	{
			position:absolute;
			top:5px;
			right:10px;
			font-size:10px;
			text-decoration:none;
			color:#ff0000;
		}
    </style>
	<?php
}

/* Register and define the settings */
add_action('admin_init', 'fsn_base_layout_admin_init');
function fsn_base_layout_admin_init(){
	register_setting(
		'fsn_base_layout_options',
		'fsn_base_layout_options'
	);
	//sections	
	add_settings_section(
		'fsn_base_layout_options',
		'Master Layout Builder',
		'fsn_base_layout_options_section',
		'fsn_base_layout'
	);
	//layout settings fields
	add_settings_field(
		'fsn_base_layout_builder',
		'Layout Builder UI',
		'fsn_base_layout_builder_output',
		'fsn_base_layout',
		'fsn_base_layout_options'
	);	
}

/* Draw the section headers */
function fsn_base_layout_options_section() {
	echo '<p>Choose and place the Components above and below the <strong>Page Content</strong> to control the master layout of the site.</p>';
}

/* Display and fill the form fields */

//example section fields
function fsn_base_layout_builder_output() {
	// get option value from the database
	$options = get_option( 'fsn_base_layout_options' );
	$fsn_base_list_items = $options['layout_builder'];

	//drag and drop interface
	echo '<div id="fsn-base-list-items-sort">';
		//echo existing list items
    	if ( !empty($fsn_base_list_items) ) {
    		$i = 0;
    		foreach($fsn_base_list_items as $fsn_base_list_item) {
    			if ($fsn_base_list_item['item_id'] == 'divider') {
    				$item_class = 'list-item content-item';
    				$item_title = 'Page Content';
    				$item_value = 'divider';
    			} else {
	    			$list_item = get_post($fsn_base_list_item['item_id']);
	    			$item_class = 'list-item';
	    			$item_title = $list_item->post_title;
    				$item_value = $list_item->ID;
    			}
    			
    			echo '<div class="'. $item_class .'">';					
					echo '<div class="list-item-details">';
						//title
						echo '<p><strong>'. $item_title .'</strong></p>';						
						//id input
				    	echo '<input class="list-item-id" type="hidden" name="fsn_base_layout_options[layout_builder]['. $i .'][item_id]" value="'. $item_value .'">';
			    	echo '</div>';
			    	if ($item_value != 'divider') {
			    		echo '<a href="#" class="fsn-base-remove-list-item">remove</a>';
			    	}
				echo '</div>';
				$i++;
    		}
    	} else {
	    	//content list item (cannot be removed)
	    	echo '<div class="list-item content-item">';					
				echo '<div class="list-item-details">';
					//title
					echo '<p><strong>Page Content</strong></p>';
					//id input
			    	echo '<input class="list-item-id" type="hidden" name="fsn_base_layout_options[layout_builder][][item_id]" value="divider">';
		    	echo '</div>';
			echo '</div>';
		}
    echo '</div>';
     
    //item select box
    echo '<select class="fsn-base-add-list-layout select2-posts-element" name="fsn-base-add-list-layout" data-placeholder="'. __('Choose Component.', 'fusion-base') .'" data-post-type="component" style="width:100%;">';
    	echo '<option></option>';
    echo '</select>';
}

//add list items via AJAX
add_action('wp_ajax_fsn_base_add_list_item_layout', 'fsn_base_list_builder_add_item');
function fsn_base_list_builder_add_item() {
	//verify nonce
	check_ajax_referer( 'fsn-admin-edit-layout', 'security' );
	
	//verify capabilities
	if ( !current_user_can( 'edit_theme_options' ) )
		die( '-1' );
	
	$list_item_id = intval($_POST['item_id']);
	$list_item = get_post($list_item_id);
	echo '<div class="list-item">';		
		echo '<div class="list-item-details">';
			//title
			echo '<p><strong>'. $list_item->post_title .'</strong></p>';
			//id input
	    	echo '<input class="list-item-id" type="hidden" name="fsn_base_layout_options[layout_builder][][item_id]" value="'. $list_item->ID .'">';
    	echo '</div>';
    	echo '<a href="#" class="fsn-base-remove-list-item">remove</a>';
	echo '</div>';
	exit;
}

/**
 * HEADER COMPONENTS
 *
 */

function fsn_base_output_header_components() {
	global $post;
	if (is_singular()) {
        $layout_override = get_post_meta($post->ID, '_fsn_base_layout_override', true);
    }
    if (empty($layout_override)) {
    	$layout_options = get_option('fsn_base_layout_options');
        $layout_components = $layout_options['layout_builder'];
        if (!empty($layout_components)) {        	
        	//create header components array        	
        	$header_components = array();	
        	foreach($layout_components as $layout_component) {
        		if ($layout_component['item_id'] == 'divider') {
        			break;
        		} else {        			
        			$header_components[] = (object) array(
        				'id' => $layout_component['item_id']
        			);
        		}
        	}
        	//output header components
        	fsn_base_output_components($header_components);
        }
        //content component open
        echo '<div id="content-component" class="component clearfix">';
    }
}
add_action('fsn_base_header_components', 'fsn_base_output_header_components');
 
/**
 * FOOTER COMPONENTS
 *
 */
 
function fsn_base_output_footer_components() {
	global $post;
	if (is_singular()) {
        $layout_override = get_post_meta($post->ID, '_fsn_base_layout_override', true);
    }
	if (empty($layout_override)) {
		//content component close
		echo '</div>';
		        
        $layout_options = get_option('fsn_base_layout_options');
        $layout_components = $layout_options['layout_builder'];	        
        if (!empty($layout_components)) {
        	//create footer components array
        	$footer_components = array();
        	$trigger_footer = false;
        	foreach($layout_components as $layout_component) {	        		
        		if ($trigger_footer == true) {
        			$footer_components[] = (object) array(
        				'id' => $layout_component['item_id']
        			);
        		}
        		if ($layout_component['item_id'] == 'divider') {
        			$trigger_footer = true;
        		}
        	}
        	//output footer components
        	fsn_base_output_components($footer_components);
        }
    }
}
add_action('fsn_base_footer_components', 'fsn_base_output_footer_components');

//OUTPUT COMPONENTS
function fsn_base_output_components($components = false) {
	if ( !empty($components) && is_array($components) ) {
		foreach($components as $component) {
			$component_object = get_post($component->id);
			echo '<div id="component-'. $component->id .'" class="component clearfix">';
	    		echo apply_filters('the_content', $component_object->post_content);
			echo '</div>';
		}
	}
}

?>
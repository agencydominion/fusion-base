/**
 * WP Admin scripts for Fusion Components List builder
 */

//add list item
jQuery(document).ready(function() {
	//get items array
	var listItemsContainer = jQuery('#fsn-base-list-items-sort');
	var itemSelectElement = jQuery('.fsn-base-add-list-layout');
	
	itemSelectElement.on('change', function(e) {
		e.preventDefault();
		var selectedID = jQuery(this).val();		
		var data = {
			action: 'fsn_base_add_list_item_layout',
			item_id: selectedID,
			security: fsnBaseJS.fsnEditLayoutNonce
		};	
		jQuery.post(ajaxurl, data, function(response) {
			if (response == '-1') {
				alert('Oops, something went wrong. Please reload the page and try again.');
				return false;
			}
			listItemsContainer.append(response);
			itemSelectElement.val('');
			adupdateListNumbers();
		});	
	});
});

//drag and drop sorting
jQuery(document).ready(function() {
	var sortableList = jQuery('#fsn-base-list-items-sort');
	sortableList.sortable({
		stop: function( event, ui ) {
			adupdateListNumbers();
		}
	});
});

//remove list item
jQuery(document).ready(function() {
	jQuery('#fsn-base-list-items-sort').on('click', '.fsn-base-remove-list-item', function(e) {
		e.preventDefault();
		var targetListItem = jQuery(this).parents('.list-item');
		targetListItem.fadeOut(500, function() {
			jQuery(this).remove();
			adupdateListNumbers();
		});
	});
});

//list numbering function
function adupdateListNumbers() {
	var sortableList = jQuery('#fsn-base-list-items-sort');
	var items = sortableList.find('.list-item');
	//assign array keys
	for (var i=0; i < items.length; i++) {
		items.eq(i).find('.list-item-id').attr('name','fsn_base_layout_options[layout_builder]['+ i +'][item_id]');
	}
}
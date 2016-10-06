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
				alert(fsnBaseL10n.error);
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

//init select2 fields
jQuery(document).ready(function() {
	fsnBaseInitPostSelect();
});

function fsnBaseInitPostSelect() {
	var select2Elements = jQuery('.select2-posts-element');
	select2Elements.each(function() {
		var select2Element = jQuery(this);
		var postsPerPage = 30;
		var postType  = select2Element.data('postType');
		select2Element.select2({
			ajax: {
				url: ajaxurl,
				dataType: 'json',
				method: 'POST',
			    delay: 250,
			    data: function (params) {
					return {
						q: params.term, // search term
						page: params.page,
						action: 'fsn_posts_search',
						posts_per_page: postsPerPage,
						postType: postType,
						security: fsnBaseJS.fsnEditNonce
					};
			    },
			    processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.items,
						pagination: {
							more: (params.page * postsPerPage) < data.total_count
						}
					};
				},
			},
			minimumInputLength: 1,
			language: {
				inputTooShort: function(args) {
					return fsnBaseL10n.search;
				}
			},
			escapeMarkup: function (text) {
				return text;
			}
		});
	});	
}
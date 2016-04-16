/**
 *	Handle repeater groups
 */
jQuery(document).ready(function($) {

	$('.no-js-msg').remove();
	$('.js').show();

	// Initialize select2
	$('.select2').select2( curated_posts_repeater );

	$('.curated-posts-add-row').click(function(e) {

		e.preventDefault();

		var $this, prev_el, hidden_el, cloned_el, post_number;

		$this = $(this);
		
		// Last visible group
		prev_el = $this.closest('tr').prev('.curated-posts-repeater-group');

		// Clone the hidden group
		hidden_el = $this.closest('.form-table').find('.curated-posts-repeater-group.hidden-repeater');
		cloned_el = hidden_el.clone().removeAttr('style').removeClass('hidden-repeater');

		// Increment the post number
		if ( typeof prev_el.data('row-post-number') == 'undefined' ) {
			post_number = 0;
		} else {
			post_number = Number( prev_el.data('row-post-number') ) + 1;
		}

		console.log($this.closest('.form-table').find('.curated-posts-repeater-group.hidden-repeater'));

		cloned_el.data( 'row-post-number', post_number );

		// Update attributes
		$.each( cloned_el.find('input, select, textarea'), function(index, el) {
			el = $(el);
			el.attr( 'name', el.attr('name').replace( 'new_post_number', post_number ) );
			el.attr( 'id', el.attr('id').replace( 'new_post_number', post_number ) );
		});

		// Add new group before the "Add Post" button
		$this.closest('tr').before(cloned_el);

		// Initialize select2 on new group
		cloned_el.find('select').addClass('select2').select2( curated_posts_repeater );

	});

	// Remove a row/group
	$('body').on( 'click', '.curated-posts-remove-row', function(e) {
		e.preventDefault();
		var closest = $(this).closest('.curated-posts-repeater-group');
		closest.fadeOut('500', function() {
			$.each( closest.find( 'input, select, textarea' ), function( index, el ) {
				$(el).val( '' );
			});
		});;
	});

	// Get rid of hidden groups before posting
	$('#curated_posts_categories_submit').click(function(e) {
		$.each( $.find( '.curated-posts-repeater-group.hidden-repeater' ), function(index, el) {
			el = $(el);
			el.remove();
		});
	});

	// Sort rows
	$('.curated-posts-settings-table tbody').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.sort',
		axis: 'y',
		opacity: 0.7,
		items: '.curated-posts-repeater-group'
	});

});

/**
 *	Stick sidebar meta box for easy saving
 */
jQuery(document).ready(function($) {

	var sidebar = $('#curated_posts_save_metabox');
	var adminBar = $('#wpadminbar').height();
	var offset = 0;

	if ( adminBar ) {
		offset = adminBar;
	}

	sidebar.before('<div id="sticky-anchor"></div>');

	function stick() {

		if ( $(window).width() < 768 ) {
			return false;
		}

		var window_top = $(window).scrollTop();
		var div_top = $('#sticky-anchor').offset().top;
		if ( window_top > div_top - offset ) {
			sidebar.css({
				'position': 'fixed',
				'top': offset,
				'width': sidebar.closest('.meta-box-sortables').css('width'),
				'z-index': '99',
			});
		} else {
			sidebar.css('position','static');
		}
	}

	$(window).scroll(stick);
	stick();
});
jQuery(function($){
	// update posts list when post_type changes
	$('select.contentwidget_post_type').live( 'change', function(){
		thiz = $(this);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'content_widget_get_posts',
				post_type: thiz.val()
			},
			success: function(result) {
				thiz.closest('form').find('select.contentwidget_posts_list').html(result);
				// control the display of category option
				if( thiz.val() == 'post' ) {}
			}
		});
	} );

	// update the widget title field with current post's title
	$('select.contentwidget_posts_list').live( 'change', function(){
		$(this).next().val( $(this).find(":selected").text() );
	} );
});
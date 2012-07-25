jQuery(function($){
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
			}
		});
	} );
});
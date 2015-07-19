jQuery(function($){
	// update posts list when post_type changes
	$( 'body' ).on( 'change', 'select.contentwidget_post_type', function(){
		var $this = $(this);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'content_widget_get_posts',
				post_type: $this.val()
			},
			success: function(result) {
				$this.closest( 'form' ).find( 'select.contentwidget_posts_list' ).html( result );
				// control the display of category option
				if( $this.val() == 'post' ) {}
			}
		});
	} )

	// update the widget title field with current post's title
	.on( 'change', 'select.contentwidget_posts_list', function(){
		$(this).next().val( $(this).find( ":selected" ).text() );
	} );
});
<?php

// Block direct requests
defined( 'ABSPATH' ) or die( '-1' );
?>
<p>
	<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post Type'); ?>:</label>
	<select class="contentwidget_post_type widefat" id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
		<?php
		$post_types = get_post_types( array( 'public' => true ), 'object' );
		// Do not list any attachements
		unset( $post_types['attachment'] );
		foreach( $post_types as $key => $post_type ) {
			echo '<option value="' . $key . '" ' . selected( $instance['post_type'], $key, false ) . '>' . $post_type->labels->name . '</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id('post'); ?>"><?php _e('Post'); ?>:</label>
	<select class="contentwidget_posts_list widefat" id="<?php echo $this->get_field_id('post'); ?>" name="<?php echo $this->get_field_name('post'); ?>">
		<?php $this->get_posts( $instance['post_type'], $instance['post'] ); ?>
	</select>
	<input type="hidden" value="<?php echo esc_attr( strip_tags( $instance['title'] ) ); ?>" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>">
</p>

<p class="contentwidget_category_option" <?php if( !( 'post' == $instance['post_type'] && ( 'latest' == $instance['post'] || 'random' == $instance['post'] ) ) ) echo 'style="display: none;"'; ?>>
	<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category'); ?>:</label>
	<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
		<option value=""><?php _e( 'All' ) ?></option>
		<?php
		$categories = get_categories( 'hide_empty=0' );
		foreach( $categories as $cat ) {
			echo '<option value="' . $cat->term_id . '" ' . selected( $instance['category'], $cat->term_id, false ) . '>' . $cat->name . '</option>';
		}
		?>
	</select>
</p>

<p <?php if( 'latest' != $instance['post'] ) echo 'style="display: none;"'; ?>>
	<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Offset'); ?>:</label>
	<input type="text" class="small-text" id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" value="<?php echo $instance['offset'] ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('charlimit'); ?>"><?php _e('Words limit:'); ?></label>
	<input type="text" class="small-text" id="<?php echo $this->get_field_id('charlimit'); ?>" name="<?php echo $this->get_field_name('charlimit'); ?>" value="<?php echo $instance['charlimit'] ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('delimiter'); ?>"><?php _e('Delimiter:'); ?></label>
	<input type="text" class="medium-text" id="<?php echo $this->get_field_id('delimiter'); ?>" name="<?php echo $this->get_field_name('delimiter'); ?>" value="<?php echo $instance['delimiter'] ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('readmore'); ?>"><?php _e('Read More link:'); ?></label>
	<input type="text" class="medium-text" id="<?php echo $this->get_field_id('readmore'); ?>" name="<?php echo $this->get_field_name('readmore'); ?>" value="<?php echo $instance['readmore'] ?>" />
</p>

<p>
	<input id="<?php echo $this->get_field_id('hidetitle'); ?>" name="<?php echo $this->get_field_name('hidetitle'); ?>" type="checkbox" <?php checked( $instance['hidetitle'], 1 ); ?> value="1" />&nbsp;<label for="<?php echo $this->get_field_id('hidetitle'); ?>"><?php _e('Hide the title?'); ?></label>
</p>

<p>
	<input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked( $instance['filter'], 1 ); ?> value="1" />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label>
</p>
<?php

// Block direct requests
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );
?>
<p>
	<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post Type:'); ?></label>
	<select class="contentwidget_post_type widefat" id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
		<?php
		$post_types = get_post_types( array( 'public' => true ), 'object' );
		foreach( $post_types as $key => $post_type ) {
			echo '<option value="' . $key . '" ' . selected( $instance['post_type'], $key, false ) . '>' . $post_type->labels->name . '</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id('post'); ?>"><?php _e('Post:'); ?></label>
	<select class="contentwidget_posts_list widefat" id="<?php echo $this->get_field_id('post'); ?>" name="<?php echo $this->get_field_name('post'); ?>">
		<?php $this->get_posts( $instance['post_type'], $instance['post'] ); ?>
	</select>
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
<?php
/*
Plugin Name:	Content Widget
Description:	Allows you to display posts inside widget areas.
Author:			Hassan Derakhshandeh
Version:		0.1.1
Author URI:		http://tween.ir/


		* 	Copyright (C) 2011  Hassan Derakhshandeh
		*	http://tween.ir/
		*	hassan.derakhshandeh@gmail.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Content_Widget extends WP_Widget {

	function __construct() {
		global $pagenow;

		$widget_ops = array( 'classname' => 'widget_content', 'description' => __('Displays the contents of a post.') );
		parent::__construct( 'content', __('Content'), $widget_ops, null );
		if( is_admin() && $pagenow == 'widgets.php' ) {
			wp_enqueue_script( 'content-widget', plugins_url( '/js/admin.js', __FILE__ ), array( 'jquery' ) );
		}
	}

	function widget( $args, $instance ) {
		extract( $args );
		if( ! isset( $instance['post'] ) ) return;
		$query = new WP_Query( array( 'p' => $instance['post'] ) );
		while( $query->have_posts() ) : $query->the_post();
		$title = apply_filters( 'widget_title', get_the_title() );
		$text = apply_filters( 'widget_text', get_the_content() );

		/* limit words */
		if( $instance['charlimit'] ) {
			$text = $this->limit_words( $text, $instance['charlimit'] ) . $instance['delimiter'];
			if( ! empty( $instance['readmore'] ) ) {
				$text .= ' <a class="read-more" href="' . get_permalink() . '">' . $instance['readmore'] . '</a>';
			}
		}

		/* redner output */
		echo $before_widget;
		if ( !( isset( $instance['hidetitle'] ) && $instance['hidetitle'] == 1 ) && ! empty( $title ) ) { echo $before_title . $title . $after_title; }
			echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text;
		echo $after_widget;

		endwhile;
		wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_defaults() );
		require( dirname( __FILE__ ) . '/views/form.php' );
	}

	/**
	 * Get a list of all posts based on post_type
	 *
	 * @todo: cache the results
	 */
	function get_posts( $post_type, $selected = null ) {
		query_posts( array( 'posts_per_page' => '-1', 'post_type' => $post_type ) );
		if( have_posts() ) : while( have_posts() ) : the_post();
			echo '<option value="'. get_the_ID() .'" '. selected( $selected, get_the_ID(), false ) .'>' . get_the_title() . '</option>';
		endwhile; endif;
		wp_reset_postdata();
	}

	/**
	 * Ajax callback to return a list of all posts based on the chosen post_type
	 *
	 * @return void
	 */
	function ajax_get_posts() {
		Content_Widget::get_posts( $_POST['post_type'] );
		exit;
	}

	/**
	 * Default widget options
	 *
	 * @return array
	 */
	function get_defaults() {
		return array(
			'post_type' => 'page',
			'charlimit' => 0,
			'delimiter' => '...',
			'readmore'  => __( 'More' ),
			'hidetitle' => 0
		);
	}

	function register() {
		register_widget( 'Content_Widget' );
	}

	/**
	 * A utlity function to limit the number of words on a given string.
	 *
	 * @link http://wp-snippets.com/limit-excerpt-words/
	 * @return string
	 */
	function limit_words( $string, $word_limit ) {
		$words = explode( ' ', $string );
		return implode( ' ', array_slice( $words, 0, $word_limit ) );
	}
}
add_action( 'widgets_init', array( 'Content_Widget', 'register' ) );
add_action( 'wp_ajax_content_widget_get_posts', array( 'Content_Widget', 'ajax_get_posts' ) );
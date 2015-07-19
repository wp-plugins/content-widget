<?php
/*
Plugin Name:    Content Widget
Description:    A handy widget to display the contents of a post inside a widget area.
Author:         Hassan Derakhshandeh
Version:        0.4
Author URI:     http://shazdeh.me/
Text Domain:    builder-bar-chart
Domain Path:    /languages

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

defined( 'ABSPATH' ) or die( '-1' );

class Content_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_content', 'description' => __( 'Displays the contents of a post.', 'content-widget' ) );
		parent::__construct( 'content', __( 'Content', 'content-widget' ), $widget_ops, null );

		add_action( 'admin_print_scripts', array( $this, 'admin_queue' ) );
		add_action( 'wp_ajax_content_widget_get_posts', array( $this, 'ajax_get_posts' ) );
		add_action( 'init', array( $this, 'i18n' ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'content-widget', false, '/languages' );
	}

	function widget( $args, $instance ) {
		extract( $args );
		if( ! isset( $instance['post'] ) ) return;

		// build query
		$query = array( 'posts_per_page' => 1, 'post_type' => $instance['post_type'] );
		if( 'latest' == $instance['post'] ) {
			// hmmm... I think we are set for this situation
		} elseif( 'random' == $instance['post'] ) {
			$query['orderby'] = 'rand';
		} else {
			$query['p'] = $instance['post'];
		}
		if( 'post' == $instance['post_type'] && isset( $instance['category'] ) ) {
			$query['cat'] = $instance['category'];
		}
		if( 'latest' == $instance['post'] && isset( $instance['offset'] ) ) {
			$query['offset'] = $instance['offset'];
		}

		$query = new WP_Query( $query );
		while( $query->have_posts() ) : $query->the_post();
		$title = apply_filters( 'widget_title', get_the_title() );
		$text = apply_filters( 'widget_text', get_the_content() );

		/* redner output */
		include( $this->get_emplate( 'widget' ) );

		endwhile;
		wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_defaults() );
		require( $this->get_emplate( 'form' ) );
	}

	/**
	 * Queue script file for admin
	 *
	 * @since 0.2
	 */
	function admin_queue() {
		wp_register_script( 'content-widget', plugins_url( '/js/admin.js', __FILE__ ), array( 'jquery' ), '0.3', true );
		wp_enqueue_script( 'content-widget' );
	}

	/**
	 * Get a list of all posts based on post_type
	 *
	 * @todo: cache the results
	 */
	function get_posts( $post_type, $selected = null ) {
		echo '<optgroup label="'. __( 'Random', 'content-widget' ) .'">';
			echo '<option value="random" '. selected( $selected, 'random', false ) .'>' . __( 'Choose one at random', 'content-widget' ) . '</option>';
		echo '</optgroup>';
		echo '<optgroup label="'. __( 'Latest', 'content-widget' ) .'">';
			echo '<option value="latest" '. selected( $selected, 'latest', false ) .'>' . __( 'Show latest item', 'content-widget' ) . '</option>';
		echo '</optgroup>';
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
			'readmore'  => __( 'More', 'content-widget' ),
			'hidetitle' => 0,
			'category' => '',
			'offset' => '',
		);
	}

	function register() {
		register_widget( 'Content_Widget' );
	}

	/**
	 * Loads theme files in appropriate hierarchy: 1) child theme,
	 * 2) parent template, 3) plugin resources. will look in the image-widget/
	 * directory in a theme and the views/ directory in the plugin
	 *
	 * @param string $template template file to search for
	 * @return template path
	 * @author Modern Tribe, Inc. (Matt Wiebe)
	 *
	 * @since 0.2
	 **/
	function get_emplate( $template ) {
		// whether or not .php was added
		$template_slug = rtrim( $template, '.php' );
		$template = $template_slug . '.php';

		if ( $theme_file = locate_template( array( 'html/content-widget/' . $template ) ) ) {
			$file = $theme_file;
		} else {
			$file = 'views/' . $template;
		}

		return apply_filters( 'content_widget_template_' . $template, $file );
	}
}
add_action( 'widgets_init', array( 'Content_Widget', 'register' ) );
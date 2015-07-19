<?php

// Block direct requests
defined( 'ABSPATH' ) or die( '-1' );

echo $before_widget;

if ( ! ( isset( $instance['hidetitle'] ) && $instance['hidetitle'] == 1 ) && ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}

if( $instance['charlimit'] ) {
	$text = wp_trim_words( $text, $instance['charlimit'], $instance['delimiter'] );
}

echo ! empty( $instance['filter'] ) ? wpautop( $text ) : $text;

if( $instance['charlimit'] && ! empty( $instance['readmore'] ) ) {
	printf( ' <a class="read-more" href="%s">%s</a>', get_permalink(), $instance['readmore'] );
}

echo $after_widget;
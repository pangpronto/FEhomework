<?php

function new_loop_shop_per_page( $cols ) {
	$cols = 20;
	return $cols;
}
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function tours_shortcode( $atts ) {
	$atts = shortcode_atts( 
		array(
			'travel_types' => '',
			'destination'  => '',
			'partners'     => '',
			'columns'      => 'col-md-4 col-sm-4',
			'limit'        => 999,
			'order_by'     => date,
		), 
		$atts, 'tours' 
	);

	if ( $atts['destination'] ) {
		$destination = array(
			'taxonomy' => 'tour_destination',
			'field'    => 'slug',
			'terms'    => $atts['destination'],
		);
	} else {
		$destination = '';
	}

	if ( $atts['travel_types'] ) {
		$travel_types = array(
			'taxonomy' => 'travel_types',
			'field'    => 'slug',
			'terms'    => $atts['travel_types'],
		);
	} else {
		$travel_types = '';
	}

	if ( $atts['partners'] ) {
		$partners = array(
			'taxonomy' => 'partners',
			'field'    => 'slug',
			'terms'    => $atts['partners'],
		);
	} else {
		$partners = '';
	}
	
	$args = array( 
		'post_type'      => 'tours',
		'posts_per_page' => $atts['limit'],
		'order_by'       => $atts['order_by'],
		'order'          => 'ASC',
		'tax_query'      => array(
			'relation' => 'AND',
			$destination,
			$travel_types,
			$partners,
		),
	);

	$the_query  = new WP_Query( $args );
	$html       = '';

	if ( $the_query->have_posts() ):
		$html = '<div class="row">';

		while ( $the_query->have_posts() ):
			$the_query->the_post();
			$header_image = get_field( 'header_image', $terms );

			if ( have_rows( 'brief_detail' ) ):
			  	while ( have_rows( 'brief_detail' ) ): the_row();
					$tour_price = get_sub_field( 'tour_price' );
				endwhile;
			endif;
			$term_name  = wp_get_post_terms( get_the_ID(), 'travel_types', array( 'fields' => 'all' ) );
			$class_name = '';
			foreach ( $term_name as $name ) {
				$class_name .= ' ' . $name->slug;
			}

			$html .= '<div class="' . $atts['columns'] . '">';
			$html .= '<well class="single-tour ' . $class_name . '">';
			$html .= '<div class="tour-image" style="background-image: url( \'' . $header_image['url'] . '\' );"></div>';
			$html .= '<h2 class="font-27 title"><a href="' . get_the_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></h2>';
			$html .= '<p class="font-16">Starting From</p>';
			$html .= '<p class="price"><strong>CAD<span>' . $tour_price . '</span></strong></p>';
			$html .= '<a href="' . get_the_permalink( get_the_ID() ) . '" class="btn btn-primary btn-custom" rel="nofollow">view trip</a>';
			$html .= '</well>';
			$html .= '</div>';
		endwhile;
		$html .= '</div>';

	else:
		$html = '';
	endif;
	return $html;
}
add_shortcode( 'tours', 'tours_shortcode' );

function glp_display_product_category() {
	global $post;
	echo '<div class="cat-name">';
	$terms = wp_get_post_terms( $post->ID, 'product_cat' );
	$terms_counter = 1;
	foreach ( $terms as $term ) {
		if ( $terms_counter <= 3 ) {
				// do not display "Featured Category" which has ID 47
			if ( $term->term_id != '47' ) { // <------- Change to 107, 112 in production site
				$product_categories[] = $term->name;
				// $product_categories[] = '<a href="' . esc_url( get_term_link( $term->term_id ) ) . '" rel="tag">' . $term->name . '</a>';
			}
		}
		$terms_counter++;
	}
	echo wpautop( wptexturize( implode( ', ', $product_categories ) ) );
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'glp_display_product_category', 15 );


function glp_display_product_attributes_days() {
	global $post;
	$product = wc_get_product();
	$product_attribute_days = $product->get_attribute( 'days' );
	if ( $product_attribute_days ) {
		echo '<div class="product_attribute">';
		echo $product_attribute_days;
		echo '</div>';
	}
}
add_action( 'woocommerce_shop_loop_item_title', 'glp_display_product_attributes_days', 11 );


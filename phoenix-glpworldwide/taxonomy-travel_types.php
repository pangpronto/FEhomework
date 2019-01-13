<?php
get_header();

if ( is_page() ) {
	$id = get_the_ID();
}
elseif ( is_home() ) {
	$id = get_option( 'page_for_posts', true );
}

$terms_id    = get_queried_object()->term_id;
$term        = get_term( $terms_id, $taxonomy );
$slug        = $term->slug;
$description = get_field( 'short_description', get_queried_object() );

echo masthead( $slug, $description );
echo description( $term );
echo destination_tabs( $slug );

get_footer();

function masthead( $slug, $description ) {
	$html  = '<div class="segment3 space-huge shop-masthead">';
	$html .= '<div class="container"><div class="row">';
	$html .= '<div class="col-md-12 text-center"><h1 class="font-60 uppercase font-white margin-bottom-40">' . $slug . '</h1>';
	$html .= '<p class="font-18 font-white">' . $description . '</p></div>';
	$html .= '</div></div></div>';
	return $html;
}

function description( $term ) {
	$html  = '<div class="col-md-10 col-md-offset-1 text-center description">';
	$html .= '<p class="margin-top-20">' . $term->description . '</p>';
	$html .= '</div>';
	return $html;
}

function destination_tabs( $slug ) {
	$html  = '<div class="col-md-10 col-md-offset-1 text-center"><h2 class="font-38 uppercase">Choose your destination</h2></div>';
	$html .= '<div class="col-md-10 col-md-offset-1 text-center tour-tabs">';

	$tour_destination = get_terms( 'tour_destination', 'orderby=count&hide_empty=0' );

	if ( ! empty( $tour_destination ) && ! is_wp_error( $tour_destination ) ){
		$first_tab = true;
		$html     .= '<ul class="nav nav-tabs">';
		foreach ( $tour_destination as $term ) {
			if ( do_shortcode( '[tours travel_types="' . $slug . '" destination="' . $term->slug . '"  limit="3"]' ) ) {
				if ( $first_tab ) {
					$html      .= '<li class="active"><a href="#' . $term->slug . '" data-toggle="tab">' . $term->slug . '</a></li>';
					$first_tab  = false;
				} else {
					$html .= '<li><a href="#' . $term->slug . '" data-toggle="tab">' . $term->slug . '</a></li>';
				}
			} 
		}
		$html .= '</ul>';

		$first_content = true;
		$html         .= '<div class="tab-content">';
		foreach ( $tour_destination as $term ) {
			if ( do_shortcode( '[tours travel_types="' . $slug . '" destination="' . $term->slug . '"  limit="3"]' ) ) {
				if ( $first_content ) {
					$html .= '<div id="' . $term->slug . '" class="tab-pane fade in active">';
					$first_content  = false;
				} else {
					$html .= '<div id="' . $term->slug . '" class="tab-pane fade">';
				}
				$html .= do_shortcode( '[tours travel_types="' . $slug . '" destination="' . $term->slug . '"  limit="999"]' );
				$html .= '</div>';
			}
		}
		$html .= '</div>';
	}
	$html .= '</div>';

	return $html;
}
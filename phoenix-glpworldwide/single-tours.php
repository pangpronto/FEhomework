<?php
get_header();
if ( is_page() ) {
	$id = get_the_ID();
}
elseif ( is_home() ) {
	$id = get_option( 'page_for_posts', true );
}

echo '<div class="col-md-12">';
echo render_masthead();
echo render_intro();
echo render_video();
echo render_infomation_tabs();
echo render_featured();
echo render_gallery();
echo '</div>';
get_footer();

function render_masthead() {
	$header_image = get_field( 'header_image' );
	$masthead     = '<div class="segment space-huge tours-masthead" ';
	$masthead    .= 'style="background-image: url(\'' . $header_image['url'] . '\'); background-repeat: no-repeat; background-position: center center; background-attachment: scroll; background-size: cover;">';
	$masthead    .= '<div class="container"></div></div>';
	return $masthead;
}

function render_intro() {
	$header_image = get_field( 'header_image' );
	if ( have_rows( 'brief_detail' ) ):
	  	while ( have_rows( 'brief_detail' ) ): the_row();
			$number_of_days   = get_sub_field( 'number_of_days' );
			$number_of_nights = get_sub_field( 'number_of_nights' );
			$tour_style       = get_sub_field( 'tour_style' );
			$tour_price       = get_sub_field( 'tour_price' );
			$destination      = get_sub_field( 'destination' );
			$from_date        = get_sub_field( 'from_date' );
			$to_date          = get_sub_field( 'to_date' );
			$agents           = get_sub_field( 'agent' );
			$brochure         = get_sub_field( 'pdf' );

			if ( empty( $brochure ) ) {
				$term_name  = wp_get_post_terms( get_the_ID(), 'travel_types', array( 'fields' => 'all' ) );	
				foreach ( $term_name as $name ) {
					$name = $name->name;
					if ( 'River' == $name ) {
						$brochure = '/river-travel-brochure/';
					}
					elseif ( 'Rail' == $name ) {
						$brochure = '/rail-travel-brochure/';
					}
					elseif ( 'Ocean' == $name ) {
						$brochure = '/ocean-travel-brochure/';
					}
					elseif ( 'Discover' == $name ) {
						$brochure = '/discover-travel-brochure/';
					}
					elseif ( 'Expedition' == $name ) {
						$brochure = '/expedition-travel-brochure/';
					}
				}
			}
			
		endwhile;
	endif;
	$title                  = get_the_title();
	$tour_title             = get_field( 'tour_title' );
	$tour_short_description = get_field( 'tour_short_description' );

	$html  = '<div class="well well-intro text-center">';
	$html .= '<div class="row"><div class="col-md-12">';
	$html .= '<h2 class="font-52 font-black">' . $title . '</h2>';
	if ( $destination ) {
		$html .= '<p class="font-20 margin-0">' . $destination . '<p>';
	}
	if ( $from_date && $to_date ) {
		$html .= '<p class="font-20">' . $from_date . ' to ' . $to_date .  '<p>';
	}

	$html .= '</div>';
	$html .= '<div class="col-md-4 col-sm-12 travel-info"><ul>';

	if ( $number_of_days ) {
		$html .= '<li><img src="/wp-content/uploads/sites/3/2018/08/ico-calendar.png"> <span class="amountDays">' . $number_of_days . ' days / ' . $number_of_nights . ' nights </span></li>';
	}
	if ( $tour_style ) {
		$html .= '<li><img src="/wp-content/uploads/sites/3/2018/08/ico-casual.png"> <span class="casual">' . $tour_style . '</span></li>';
	}
	if ( $tour_price ) {
		$html .= '<li><img src="/wp-content/uploads/sites/3/2018/08/ico-price.png"> <span class="price">' . $tour_price . '</span></li>';
	}
	$html .= '</ul></div>';

	if ( $agents ) {
		foreach ( $agents as $agent ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $agent->ID ), 'single-post-thumbnail' );
			$html .= '<div class="col-md-4 col-sm-12 travel-consultant"><img src="' . $image[0] . '"><h2 class="font-black">' . get_the_title( $agent->ID ) . '</h2></div>';
		}
	}
	else {
		$html .= '<div class="col-md-4 col-sm-12 travel-consultant"><img src="/wp-content/uploads/sites/3/2018/08/logo-expedia-cruise-ship.png"></div>';
	}
	
	$html .= '<div class="col-md-4 col-sm-12">';
	$html .= '<a class="btn btn-primary font-14 btn-brochure" href="' . $brochure . '" target="_blank">Download Brochure</a><a class="btn btn-primary font-14 btn-call" href="/agent-kelly-steele/">Contact now</a>';
	$html .= '</div></div></div>';

	$html .= '<div class="well well-intro-transparent text-center">';
	$html .= '<h2>' . $tour_title . '</h2><p>' . $tour_short_description . '</p>';
	$html .= '</div>';
	return $html;
}

function render_video() {
	$video_itinerary = get_field( 'video_itinerary' );
	if ( $video_itinerary ) {
		$html  = '<div class="video-tour text-center">';
		$html .= '<h2 class="font-36 font-black text-center">Video Itinerary</h2>';
		$html .= $video_itinerary;
		$html .= '</div>';
	}
	return $html;
}

function render_infomation_tabs() {
	$overview      = get_post_meta( get_the_ID() , 'overview', true );
	$ship_detail   = get_post_meta( get_the_ID() , 'ship_detail', true );
	$travel_tips   = get_post_meta( get_the_ID() , 'travel_tips', true );
	$itinerary     = get_field( 'detailed_itinerary' );
	$price_details = get_field( 'price_details' );
	$note          = get_field( 'note' );

	$html  = '<div class="tour-tabs"><ul class="nav nav-tabs">';
	if ( $overview ) {
		$html .= '<li class="active"><a data-toggle="tab" href="#tab-overview"> Overview </a></li>';
	}
	if ( have_rows( 'detailed_itinerary' ) ) {
		$html .= '<li><a data-toggle="tab" href="#detailed-itinerary"> Detailed Itinerary </a></li>';
	}
	if ( $price_details ) {
		$html .= '<li><a data-toggle="tab" href="#dates-prices"> Dates &amp; Prices </a></li>';
	}
	if ( $ship_detail ) {
		$html .= '<li><a data-toggle="tab" href="#ship-details"> Ship Details </a></li>';
	}
	if ( $travel_tips ) {
		$html .= '<li><a data-toggle="tab" href="#travel-tips"> Travel Tips </a></li>';
	}
	$html .= '</ul>';

	$html .= '<div class="tab-content">';
	$html .= '<div id="tab-overview" class="tab-pane fade in active">';
	$html .=  do_shortcode( $overview ); 
	$html .= '</div>';

	$html .= '<div id="detailed-itinerary" class="tab-pane fade">';
	if ( have_rows( 'detailed_itinerary' ) ):
		$html .= '<div><ul class="nav nav-tabs">';
		$first_tab = 1;
	  	while ( have_rows( 'detailed_itinerary' ) ): the_row();
			$itinerary = get_sub_field( 'itinerary' );
			if ( 1 == $first_tab ) {
				$html .= '<li class="active"><a href="#' . strtolower( str_replace( ' ', '-', $itinerary ) ) . '" data-toggle="tab">' . $itinerary . '</a></li>';
				$first_tab = 0;
			}
			else {
				$html .= '<li><a href="#' . strtolower( str_replace( ' ', '-', $itinerary ) ) . '" data-toggle="tab">' . $itinerary . '</a></li>';
			}
		endwhile;
		$html .= '</ul>';
		$html .= '<div class="tab-content">';

		$first_tab = 1;
		while ( have_rows( 'detailed_itinerary' ) ): the_row();
			$itinerary       = get_sub_field( 'itinerary' );
			$itinerary_image = get_sub_field( 'itinerary_image' );
			$details         = get_sub_field( 'details' );

			if ( 1 == $first_tab ) {
				$html .= '<div id="' . strtolower( str_replace( ' ', '-', $itinerary ) ) . '" class="tab-pane fade in active">';
				$first_tab = 0;
			}
			else {
				$html .= '<div id="' . strtolower( str_replace( ' ', '-', $itinerary ) ) . '" class="tab-pane fade">';
			}
			$html .= '<div class="row"><div class="col-md-8">';
			$html .= '<img src="' . $itinerary_image['url'] . '">';
			$html .= '</div>';
			$html .= '<div class="col-md-4">';
			$html .= $details;
			$html .= '</div></div></div>';
		endwhile;
		$html .= '</div><p>' . $note . '</p>';
	endif;
	$html .= '</div></div>';

	$html .= '<div id="dates-prices" class="tab-pane fade">';
	$html .= $price_details;
	$html .= '</div>';

	$html .= '<div id="ship-details" class="tab-pane fade">';
	$html .= do_shortcode( $ship_detail );
	$html .= '</div>';

	$html .= '<div id="travel-tips" class="tab-pane fade">';
	$html .= do_shortcode( $travel_tips );
	$html .= '</div>';
	$html .= '</div></div>';

	return $html;
}

function render_featured() {
	if ( have_rows( 'feature_inclusions' ) ):
		$html  = '<h2 class="font-36 uppercase font-black text-center">Featured Inclusions</h2>';
		$html .= '<div class="featured">';
		$html .= '<input id="post-2" class="read-more-state" type="checkbox">';
		$html .= '<div class="row read-more-wrap text-center">';
		$item     = 0;
		$max_item = 4;
		$count    = count( get_field( 'feature_inclusions' ) );

	  	while ( have_rows( 'feature_inclusions' ) ): the_row();
			$icon_field      = get_sub_field( 'icon_field' );
			$feature_title   = get_sub_field( 'feature_title' );
			$feature_caption = get_sub_field( 'feature_caption' );
			if ( $item < $max_item ) {
				$html .= '<div class="col-md-3 col-sm-6">';
			}
			else {
				$html .= '<div class="col-md-3 col-sm-6 read-more-target">';
			}
			$html .= '<img src="' . $icon_field['url'] . '">';
			$html .= '<p class="font-16 font-black"><strong>' . $feature_title . '</strong></p>';
			$html .= '<p class="font-14 font-black">' . $feature_caption . '</p>';
			$html .= '</div>';
			$item++;
		endwhile;
		
		$html .= '</div>';

		if ( $count > $max_item ) {
			$html .= '<p><label class="read-more-trigger" for="post-2"></label></p>';
		}
		$html .= '</div>';
		$html .= '<div class="section-join"><a href="/agent-kelly-steele/" class="btn btn-primary font-14 btn-join">Contact Now!</a></div>';
		return $html;
	endif;
}

function render_gallery() {
	$tour_gallerys = get_field( 'tour_gallery' );
	$map_image     = get_field( 'map_image' );

	$gallery  = '<div class="gallery">';
	if ( $map_image ) { 
		$gallery .= '<div class="col-md-8 gallery-side">';
	}
	else {
		$gallery .= '<div class="col-md-12 gallery-side">';
	}
	$gallery .= '<div class="one-item-carousel"><ul class="owl-carousel  owl-theme">';

	if ( $tour_gallerys ):
		foreach ( $tour_gallerys as $tour_gallery ):
			$gallery .= '<li class="item"><div style="background-image: url(\'' . $tour_gallery['url'] . '\');"></div></li>';
		endforeach;
	endif;

	$gallery .= '</ul></div>';
	$gallery .= '</div>';

	if ( $map_image ) { 
		$gallery .= '<div class="col-md-4 map-side"><div class="map" style="background-image: url(\'' . $map_image['url'] . '\');"></div></div>';
	}

	$gallery .= '<div class="clearfix"></div></div>';
	return $gallery;
}

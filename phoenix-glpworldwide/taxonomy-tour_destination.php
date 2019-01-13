<?php
get_header();

if ( is_page() ) {
	$id = get_the_ID();
}
elseif ( is_home() ) {
	$id = get_option( 'page_for_posts', true );
}

$terms_id = get_queried_object()->term_id;
$term     = get_term( $terms_id, $taxonomy );
$slug     = $term->slug;

echo '<div class="col-md-12">';
echo '<div class="row">';
echo masthead( $slug );
echo tour_destination( $terms_id );
echo '</div>';
echo '</div>';

get_footer();

function masthead( $slug ) {
	$html  = '<div class="segment3 space-huge shop-masthead">';
	$html .= '<div class="container"><div class="row">';
	$html .= '<div class="col-md-12 text-center"><h1 class="font-60 uppercase font-white margin-bottom-40">' . $slug . '</h1></div>';
	$html .= '</div></div></div>';
	return $html;
}

function tour_destination( $terms_id ) {
	$the_query = new WP_Query( array(
		'post_type' => 'tours',
		'tax_query' => array(
			array (
				'taxonomy' => 'tour_destination',
				'field'    => 'id',
				'terms'    => array( $terms_id ),
			),
		),
	) );

	$count_posts  = $the_query->post_count; 
	$html         = '<div class="col-md-12"><p class="post-count">Showing all ' . $count_posts . ' results</p></div>';

	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		
		$header_image = get_field( 'header_image', $terms );

		if ( have_rows( 'brief_detail' ) ):
		  	while ( have_rows( 'brief_detail' ) ): the_row();
				$tour_price = get_sub_field( 'tour_price' );
			endwhile;
		endif;
		$term_name  = wp_get_post_terms( get_the_ID(), 'travel_types', array( 'fields' => 'all' ));
		$class_name = '';
		foreach ($term_name as $name ) {
			$class_name .= ' ' . $name->slug;
		}

		$html .= '<div class="col-md-3 col-sm-4">';
		$html .= '<well class="single-tour ' . $class_name . '">';
		$html .= '<div class="tour-image" style="background-image: url(\'' . $header_image['url'] . '\');"></div>';
		$html .= '<h2 class="font-27 title"><a href="' . get_the_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></h2>';
		$html .= '<p class="font-16">Starting From</p>';
		$html .= '<p class="price"><strong>CAD<span>' . $tour_price . '</span></strong></p>';
		$html .= '<a href="' . get_the_permalink( get_the_ID() ) . '" class="btn btn-primary btn-custom" rel="nofollow">view trip</a>';
		$html .= '</well>';
		$html .= '</div>';
		
	endwhile;
	return $html;
}

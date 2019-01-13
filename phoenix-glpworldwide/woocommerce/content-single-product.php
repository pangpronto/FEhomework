<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook Woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

$tabs = apply_filters( 'woocommerce_product_tabs', array() );
global $product;


if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

?>

<?php if ( has_post_thumbnail() ) { ?>
<div class="segment space-huge product-masthead" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>'); background-repeat: no-repeat; background-position: center center; background-attachment: scroll; background-size: cover;"></div>
<?php } ?>

<div class="container">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="well well-intro">
				<div class="row">
					<div class="col-md-6">
						<?php
							if ( isset( $tab['callback'] ) ) { echo call_user_func( $tabs['description']['callback'], 'description', $tabs['description'] ); }
							/**
							 * Hook: woocommerce_before_single_product_summary.
							 *
							 * @hooked woocommerce_show_product_sale_flash - 10
							 * @hooked woocommerce_show_product_images - 20
							 */
							do_action( 'woocommerce_before_single_product_summary' );
						?>
					</div>
					<div class="col-md-6 travel_info_wrapper">
						<h2 class="font-52 font-black"><?php echo $product->get_title(); ?> </h2>
						<p class="font-20 margin-0"><?php echo $product->get_attribute( 'places' ); ?></p><p class="font-20"><?php echo $product->get_attribute( 'date' ); ?></p>
						<div class="travel-info">
							<ul>
								<li class="travel_info-days"><img src="/33/wp-content/uploads/2016/07/ico-calendar.png"> <span class="amountDays"> <?php echo $product->get_attribute( 'days' ); ?> </span></li>
								<li class="travel_info-price"><img src="/33/wp-content/uploads/2016/07/ico-price.png"> <span class="price"> <?php echo number_format( $product->get_regular_price() ); ?></span></li>
							</ul>
						</div>
						<div class="buttons-group">
							<div class="row-condensed">
								<div class="col-md-6">
									<a class="btn btn-primary font-14 btn-brochure" href="<?php $key="pdf"; echo get_post_meta( $post->ID, $key, true ); ?>">Download Brochure</a>
								</div>
								<div class="col-md-6">
									<a class="btn btn-primary font-14 btn-call" href="">Contact now</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="well well-intro-transparent text-center"> 
				<?php echo $product->get_short_description() ?>
			</div>

			<div class="summary entry-summary">
				<?php
					/**
					 * Hook: Woocommerce_single_product_summary.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action( 'woocommerce_single_product_summary' );
				?>
			</div>

			<?php
				/**
				 * Hook: woocommerce_after_single_product_summary.
				 *
				 * @hooked woocommerce_output_product_data_tabs - 10
				 * @hooked woocommerce_upsell_display - 15
				 * @hooked woocommerce_output_related_products - 20
				 */
				do_action( 'woocommerce_after_single_product_summary' );
			?>
			<h2 class="font-36 uppercase font-black text-center">Featured Inclusions</h2>
			<?php echo call_user_func( $tabs['featured-inclusions']['callback'], 'featured-inclusions', $tabs['featured-inclusions'] ); ?>
		</div>

		<?php do_action( 'woocommerce_after_single_product' ); ?>
	</div>
</div>

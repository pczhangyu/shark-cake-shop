<?php
/**
 * The Shop Section on the frontpage
 *
 * @package azera-shop-luxury
 */

if ( class_exists( 'WooCommerce' ) ) {

	$azera_shop_shop_section_title    = get_theme_mod( 'azera_shop_shop_section_title', esc_html__( 'Shop', 'azera-shop-luxury' ) );
	$azera_shop_shop_section_subtitle = get_theme_mod( 'azera_shop_shop_section_subtitle', esc_html__( 'Showcase your work effectively and in an attractive form that your prospective clients will love.', 'azera-shop-luxury' ) );
	$nb_of_products                   = get_theme_mod( 'azera_shop_number_of_products', 3 );
	$cat                              = get_theme_mod( 'azera_shop_woocomerce_categories', 'all' );
	?>
	<section class="shop" id="shop" role="region" aria-label="<?php esc_html_e( 'Shop', 'azera-shop-luxury' ); ?>">
		<div class="section-overlay-layer">
			<div class="container">

				<?php
				if ( ! empty( $azera_shop_shop_section_title ) || ! empty( $azera_shop_shop_section_subtitle ) ) {
					?>

					<div class="section-header">
						<?php
						if ( ! empty( $azera_shop_shop_section_title ) ) {
							echo '<h2 class="dark-text">' . esc_attr( $azera_shop_shop_section_title ) . '</h2>';
						} elseif ( isset( $wp_customize ) ) {
							echo '<h2 class="dark-text azera_shop_only_customizer"></h2>';
						}

						if ( ! empty( $azera_shop_shop_section_subtitle ) ) {
							echo '<div class="sub-heading">' . esc_attr( $azera_shop_shop_section_subtitle ) . '</div>';
						} elseif ( isset( $wp_customize ) ) {
							echo '<div class="sub-heading azera_shop_only_customizer"></div>';
						}
						?>
					</div>
					<?php
				}
				?>
				<div class="home-shop-product">
					<div class="azera_shop_products_container">
						<?php
						if ( $cat == 'all' ) {
							$args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => esc_attr( $nb_of_products ), 'orderby' => 'date', 'order' => 'DESC' );
						} else {
							$args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => esc_attr( $nb_of_products ), 'orderby' => 'date', 'order' => 'DESC', 'product_cat' => esc_attr( $cat ) );
						}
						$loop = new WP_Query( $args );
						while ( $loop->have_posts() ) : $loop->the_post();
							global $product; ?>

							<div class="col-md-4 col-sm-6 home-shop-product-wrap-all">
								<div class="home-azera-shop-luxury-product-wrap">
									<div class="home-shop-product-img">
										<?php
										if ( has_post_thumbnail( $loop->post->ID ) ) {
											echo get_the_post_thumbnail( $loop->post->ID, 'azera_shop_home_prod' );
										} else {
											echo '<img src="' . esc_url( woocommerce_placeholder_img_src() ) . '" alt="Placeholder" />';
										}
										?>
									</div>
									<div class="home-azera-shop-luxury-product-info">
										<div class="home-azera-shop-luxury-product-title">
											<h4><?php the_title(); ?></h4>
										</div>
										<div class="home-azera-shop-luxury-product-content">
											<?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
										</div>
										<div class="home-azera-shop-luxury-add-to-cart-wrap">
											<?php woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
										</div>
									</div>
								</div>
								<a id="id-<?php the_id(); ?>" href="<?php the_permalink(); ?>"
								   title="<?php the_title(); ?>">
									<h3><?php the_title(); ?></h3>
								</a>
								<p class="home-azera-shop-luxury-product-price">
									<?php echo $product->get_price_html(); ?>
								</p>
							</div>

						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
} ?>

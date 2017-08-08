<?php
/**
 * Main functions file
 *
 * @package azera-shop-luxury
 */

/**
 * Register Fonts
 */
function azera_shop_luxury_fonts_url() {
	$fonts_url = '';

	/*
     Translators: If there are characters in your language that are not
     * supported by Lato, Questrial or Rozha One, translate this to 'off'. Do not translate
     * into your own language.
	 */
	$questrial = _x( 'on', 'Questrial font: on or off', 'azera-shop-luxury' );
	$rozha_one = _x( 'on', 'Rozha One font: on or off', 'azera-shop-luxury' );
	$lato = _x( 'on', 'Lato font: on or off', 'azera-shop-luxury' );

	if ( 'off' != $questrial || 'off' != $rozha_one || 'off' != $lato ) {
		$font_families = array();
		if ( 'off' != $questrial ) {
			$font_families[] = 'Questrial';
		}
		if ( 'off' != $rozha_one ) {
			$font_families[] = 'Rozha One';
		}

		if ( 'off' != $lato ) {
			$font_families[] = 'Lato';
		}
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}
	return $fonts_url;
}


/**
 * Function to enqueue the fonts, scripts and styles
 */
function azera_shop_luxury_enqueue_styles() {

	wp_enqueue_style( 'azera-shop-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'azera-shop-luxury-style', get_stylesheet_directory_uri() . '/style.css', array( 'azera-shop-parent-style', 'dashicons' ) );
	wp_enqueue_style( 'azera-shop-luxury-font', azera_shop_luxury_fonts_url(), array(), null );
	wp_enqueue_script( 'azera-shop-luxury-js', get_stylesheet_directory_uri() . '/js/functions.js', array( 'jquery' ), false, true );

}
add_action( 'wp_enqueue_scripts', 'azera_shop_luxury_enqueue_styles', 12 );

/**
 * Remove the underline after the widgets titles
 */
function azera_shop_luxury_widgets_before_title() {
	return '<h2 class="widget-title">';
}
add_filter( 'azera_shop_widgets_before_title','azera_shop_luxury_widgets_before_title' );

/**
 * Remove the underline after the widgets titles
 */
function azera_shop_luxury_widgets_after_title() {
	return '</h2>';
}
add_filter( 'azera_shop_widgets_after_title','azera_shop_luxury_widgets_after_title' );

/**
 * Filter the navigation to make a custom one
 */
function azera_shop_luxury_numeric_posts_nav() {

	if ( is_singular() ) {
		return;
	}

	global $wp_query;

	/**
	 * Stop execution if there`s only 1 page
	 */
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/**
	 * Add current page to the array
	 */
	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	/**
	 * Add the pages around the current page to the array
	 */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="clearfix"></div><div class="row text-center"><ul class="azera-luxury-pager">' . "\n";

	/**
	 * Previous Post Link
	 */
	if ( get_previous_posts_link() ) {
		printf( '<li>%s</li>' . "\n", get_previous_posts_link( '<span class="fa fa-angle-left"></span>' ) );
	}

	/**
	 * Link to first page, plus ellipses if necessary
	 */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) ) {
			echo '<li>…</li>';
		}
	}

	/**
	 * Link to current page, plus 2 pages in either direction if necessary
	 */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**
	 * Link to last page, plus ellipses if necessary
	 */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) ) {
			echo '<li>…</li>' . "\n";
		}

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**
	 * Next Post Link
	 */
	if ( get_next_posts_link() ) {
		printf( '<li>%s</li>' . "\n", get_next_posts_link( '<span class="fa fa-angle-right"></span>' ) );
	}

	echo '</ul></div>' . "\n";

}

add_filter( 'azera_shop_post_navigation_filter', 'azera_shop_luxury_numeric_posts_nav' );

/**
 * Change the top of single post page to add the featured image
 */
function azera_shop_luxury_top_single_post_function() {
	?>
		</div>
	</header>
	<div class="content-wrap">
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="azera-featured-image" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url() ); ?>')">
	<?php } else { ?>
		<div class="azera-featured-image" style="background-image:url('<?php echo esc_url( get_header_image() ); ?>')">
	<?php } ?>
	<h2><?php echo get_the_title(); ?></h2>
	<?php if ( has_post_thumbnail() || has_header_image() ) { ?>
		</div>
	<?php } ?>
	<div class="container">
	<?php
}

add_action( 'azera_shop_top_single_post','azera_shop_luxury_top_single_post_function' );

/**
 * Change the bottom of single post page to add the featured image (closing the opened divs)
 */
function azera_shop_luxury_bottom_single_post_function() {
	?>
		</div>
	</div><!-- .content-wrap -->
	<?php
}

add_action( 'azera_shop_bottom_single_post','azera_shop_luxury_bottom_single_post_function' );

if ( ! function_exists( 'azera_shop_blog_header' ) ) {

	/**
	 * Override the function for blog header from the parent theme
	 */
	function azera_shop_blog_header() {

		$azera_shop_blog_header_image = get_theme_mod( 'azera_shop_blog_header_image', azera_shop_get_file( '/images/background-images/background.jpg' ) );
		$azera_shop_blog_header_title = get_theme_mod( 'azera_shop_blog_header_title', esc_html__( 'BLOG','azera-shop-luxury' ) );
		$azera_shop_blog_header_subtitle = get_theme_mod( 'azera_shop_blog_header_subtitle' );

		if ( ! empty( $azera_shop_blog_header_image ) || ! empty( $azera_shop_blog_header_title ) || ! empty( $azera_shop_blog_header_subtitle ) ) {

			if ( ! empty( $azera_shop_blog_header_image ) ) {
				echo '<div class="archive-top" style="background-image: url(' . esc_url( $azera_shop_blog_header_image ) . ');" role="banner">';
			} else {
				echo '<div class="archive-top" role="banner">';
			}
				echo '<div class="section-overlay-layer">';
			if ( ! empty( $azera_shop_blog_header_title ) ) {
				echo '<p class="archive-top-big-title">' . esc_html( $azera_shop_blog_header_title ) . '</p>';
			}

			if ( ! empty( $azera_shop_blog_header_subtitle ) ) {
				echo '<p class="archive-top-text">' . esc_html( $azera_shop_blog_header_subtitle ) . '</p>';
			}
				echo '</div>';
			echo '</div>';

		}

	}
}

/**
 * Change the css classes for the footer element
 */
function azera_shop_luxury_change_footer_classes() {
	return 'footer';
}

add_filter( 'azera_shop_footer_class_filter','azera_shop_luxury_change_footer_classes' );

/**
 * Remove actions from parent theme
 */
function azera_shop_luxury_remove_from_parent_theme() {

	/**
	 * Remove the powered by section from the parent theme
	 */
	remove_action( 'azera_shop_bottom_footer','azera_shop_footer_powered_by' );

	/**
	 * Remove the posts entry meta from the parent theme
	 */
	remove_action( 'azera_shop_content_single_top','azera_shop_post_entry_meta' );

	/**
	 * Remove the post date box on search page
	 */
	remove_action( 'azera_shop_post_date_box','azera_shop_post_date_box_function' );
}

add_action( 'after_setup_theme', 'azera_shop_luxury_remove_from_parent_theme', 0 );

/**
 * Add a new powered by section in footer
 */
function azera_shop_luxury_powered_by() {
	?>
	<div class="container-fluid">
		<div class="row">
			<div class="powered-by gray-bg">
				<?php printf(
					__( '%1$s powered by %2$s', 'azera-shop-luxury' ),
					sprintf( '<a href="https://themeisle.com/themes/azera-shop-luxury/" rel="nofollow">%s</a>', esc_html__( 'Azera Shop Luxury', 'azera-shop-luxury' ) ),
					sprintf( '<a href="http://wordpress.org/" rel="nofollow">%s</a>', esc_html__( 'WordPress', 'azera-shop-luxury' ) )
				); ?>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'azera_shop_after_footer','azera_shop_luxury_powered_by' );

/**
 * Remove the underline from headers
 */
function azera_shop_luxury_remove_header_underline() {
	return '';
}

add_filter( 'azera_shop_header_underline','azera_shop_luxury_remove_header_underline' );

/**
 * New post entry meta
 */
function azera_shop_luxury_post_entry_meta() {
	?>
	<div class="entry-meta single-entry-meta">
		<time class="post-time posted-on published" datetime="<?php the_time( 'c' ); ?>" itemprop="datePublished">
			<i class="fa fa-clock-o" aria-hidden="true"></i><?php the_time( get_option( 'date_format' ) ); ?>
		</time>

		<span class="author-link" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
			<span itemprop="name" class="post-author author vcard">
				<i class="fa fa-user" aria-hidden="true"></i><a
					href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" itemprop="url"
					rel="author"><?php the_author(); ?></a>
			</span>
		</span>

		<a href="<?php comments_link(); ?>" class="post-comments">
			<i class="fa fa-comment-o"
			   aria-hidden="true"></i><?php comments_number( esc_html__( 'No comments', 'azera-shop-luxury' ), esc_html__( 'One comment', 'azera-shop-luxury' ), esc_html__( '% comments', 'azera-shop-luxury' ) ); ?>
		</a>
	</div><!-- .entry-meta -->
	<?php
}

add_action( 'azera_shop_content_single_top','azera_shop_luxury_post_entry_meta' );

/**
 * Filter the article class on content-search and content
 */
function azera_shop_luxury_content_post_class_function() {
	return 'azera-shop-luxury-post';
}

add_filter( 'azera_shop_content_post_class_filter', 'azera_shop_luxury_content_post_class_function' );

/**
 * Add the post date on the blog listing
 */
function azera_shop_luxury_add_post_date() {
	?>
	<span class="post-date" itemprop="datePublished" datetime="<?php the_time( 'Y-m-d\TH:i:sP' ); ?>" title="<?php the_time( _x( 'l, F j, Y, g:i a', 'post time format', 'azera-shop-luxury' ) ); ?>">
		<i class="fa fa-clock-o" aria-hidden="true"></i>
		<?php the_time( get_option( 'date_format' ) ); ?>
	</span>
	<?php
}

add_action( 'azera_shop_content_entry_meta_top','azera_shop_luxury_add_post_date' );



function azera_shop_luxury_themeisle_sdk(){
	require dirname(__FILE__).'/vendor/themeisle/load.php';
	themeisle_sdk_register (
		array(
			'product_slug'=>'azera-shop-luxury',
			'store_url'=>'https://themeisle.com',
			'store_name'=>'Themeisle',
			'product_type'=>'theme',
			'wordpress_available'=>false,
			'paid'=>false,
		)
	);
}

azera_shop_luxury_themeisle_sdk(); 

 

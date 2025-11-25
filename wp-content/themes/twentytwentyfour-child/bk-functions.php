<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array(  ) );
        wp_enqueue_style('swiper-css', get_stylesheet_directory_uri() . '/assist/css/swiper-bundle.min.css');
		 wp_enqueue_style('select-css', get_stylesheet_directory_uri() . '/assist/css/select2.min.css');
        wp_enqueue_script('swiper-js', get_stylesheet_directory_uri() . '/assist/js/swiper-bundle.min.js', array('jquery'), null, true);
        wp_enqueue_script('matchheight', get_stylesheet_directory_uri() . '/assist/js/jquery.matchHeight-min.js', array('jquery'), null, true);
        wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assist/js/custom.js', array('jquery'), null, true);
        wp_localize_script('custom-js', 'custom_ajax_obj', array( // Match 'custom-js' with the script handle
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('custom_ajax_nonce')
        ));
        
        wp_enqueue_script('progressbar', get_stylesheet_directory_uri() . '/assist/js/chart.min.js', array('jquery'), null, true);
		wp_enqueue_script('select-js', get_stylesheet_directory_uri() . '/assist/js/select2.min.js', array('jquery'), null, true);
         // Enqueue WooCommerce styles if not already enqueued
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('woocommerce-general');
        wp_enqueue_style('woocommerce-layout');
        wp_enqueue_style('woocommerce-smallscreen');
    }
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );




function mytheme_register_widget_areas() {
    register_sidebar(array(
        'name'          => __('Primary Sidebar', 'mytheme'),
        'id'            => 'primary-sidebar',
        'description'   => __('Widgets in this area will be shown on all posts and pages.', 'mytheme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'mytheme_register_widget_areas');

// Shortcode to display the primary sidebar
function mytheme_display_primary_sidebar($atts) {
    ob_start();
    if (is_active_sidebar('primary-sidebar')) {
        dynamic_sidebar('primary-sidebar');
    }
    return ob_get_clean();
}
add_shortcode('primary_sidebar', 'mytheme_display_primary_sidebar');


add_action( 'add_meta_boxes', 'product_meta_box_add' );
function product_meta_box_add() {
   add_meta_box( 'my-meta-box-id', 'All Custom Field', 'product_field', 'product', 'normal', 'high' );
}

function product_field( $post ) {
   $difficulty = get_post_meta(get_the_ID(),'difficulty',true);
   $THC = get_post_meta(get_the_ID(),'THC_live',true);
   $Flower_Time = get_post_meta(get_the_ID(),'Flower_Time_live',true);
   $type
    = get_post_meta(get_the_ID(),'type',true);
    $CBD  = get_post_meta(get_the_ID(),'CBD_live',true);
    $planttype  = get_post_meta(get_the_ID(),'planttype',true);
    $Effects  = get_post_meta(get_the_ID(),'Effects',true);
    $relieves  = get_post_meta(get_the_ID(),'relieves',true);
   ?>
<style type="text/css">
.custom-data input[type=text] {
    width: 400px;
}

.custom-data textarea {
    width: 80%;
}

.custom-data label {
    width: 110px;
    float: left;
}
</style>
<div class="custom-data">
    <p>
        <label for="planttype"> Difficulty :</label>
        <input type="text" name="difficulty" id="difficulty" value="<?php echo $difficulty; ?>" />
    </p>
    <p>
        <label for="planttype"> Plant Type :</label>
        <input type="text" name="planttype" id="planttype" value="<?php echo $planttype; ?>" />
    </p>
    <p>
        <label for="difficulty"> THC :</label>
        <input type="text" name="THC_live" id="THC_live" value="<?php echo $THC; ?>" />
    </p>
    <p>
        <label for="difficulty"> CBD :</label>
        <input type="text" name="CBD_live" id="CBD_live" value="<?php echo $CBD; ?>" />
    </p>
    <p>
        <label for="difficulty"> Flower Time :</label>
        <input type="text" name="Flower_Time_live" id="Flower_Time_live" value="<?php echo $Flower_Time; ?>" />
    </p>
    <p>
        <label for="Effects"> Effects :</label>
        <input type="text" name="Effects" id="Effects" value="<?php echo $Effects; ?>" />
    </p>
    <p>
        <label for="relieves"> Relieves :</label>
        <input type="text" name="relieves" id="relieves" value="<?php echo $relieves; ?>" />
    </p>
</div>
<?php   
}

add_action( 'save_post', 'addproduct_meta_box_save' );
function addproduct_meta_box_save( $post_id ) {
  if( isset( $_POST[ 'difficulty' ] ) ) {
     update_post_meta($post_id, 'difficulty',$_POST[ 'difficulty' ]);
   }
   if( isset( $_POST[ 'THC_live' ] ) ) {
     update_post_meta($post_id, 'THC_live',$_POST[ 'THC_live' ]);
   }
   if( isset( $_POST[ 'Flower_Time_live' ] ) ) {
     update_post_meta($post_id, 'Flower_Time_live',$_POST[ 'Flower_Time_live' ]);
   }
   if( isset( $_POST[ 'type' ] ) ) {
     update_post_meta($post_id, 'type',$_POST[ 'type' ]);
   }
   if( isset( $_POST[ 'type' ] ) ) {
    update_post_meta($post_id, 'type',$_POST[ 'type' ]);
  }
  if( isset( $_POST[ 'CBD_live' ] ) ) {
    update_post_meta($post_id, 'CBD_live',$_POST[ 'CBD_live' ]);
  }
  if( isset( $_POST[ 'planttype' ] ) ) {
    update_post_meta($post_id, 'planttype',$_POST[ 'planttype' ]);
  }
  if( isset( $_POST[ 'Effects' ] ) ) {
    update_post_meta($post_id, 'Effects',$_POST[ 'Effects' ]);
  }
  if( isset( $_POST[ 'relieves' ] ) ) {
    update_post_meta($post_id, 'relieves',$_POST[ 'relieves' ]);
  }
}

function display_city_name_from_url() {
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $url_parts = parse_url($current_url);
    parse_str($url_parts['query'], $query_params);

    $city_name = isset($query_params['city']) ? sanitize_text_field($query_params['city']) : '';

    return !empty($city_name) ? '<h2 class="city-title">' . esc_html($city_name) . '</h2>' : '<h2>No city specified</h2>';
}

add_shortcode('show_city_name', 'display_city_name_from_url');

function wc_category_tabs_shortcode($atts) {
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ));

    ob_start(); ?>

    <div class="tabs-wrapper">
        <div class="tabs">
            <ul class="tab-links">
                <li><a href="#latest">Top Picks</a></li>
               <?php 
				// Sort categories in descending order by name
				usort($product_categories, function($a, $b) {
					return strcmp($b->name, $a->name); // Sort in descending order
				});

				foreach($product_categories as $category) :
					$checkbox_value = get_field('categories_checkbox', 'product_cat_' . $category->term_id);
					if (is_array($checkbox_value) && in_array('yes', $checkbox_value)) : ?>
						<li><a href="#<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></a></li>
					<?php 
					endif;
				endforeach; 
				?>

            </ul>
        </div>

        <div class="tab-content-wrapper">
            <div id="latest" class="tab-content">
                <div class="category-details match-height">
                    <h2>Top Picks</h2>
                    <div class="category-image">
                        <img src="/wp-content/themes/twentytwentyfour-child/img/top-rate.jpg" alt="Top Pick">
                    </div>
                    <a href="/shop/" class="shop-link">Shop All</a>
                </div>
                <div class="products-container swiper-container match-height">
                    <div class="swiper-wrapper">
                        <?php
                            $latest_query = new WP_Query(array(
                                'post_type' => 'product',
                                'posts_per_page' => 10, 
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'post__not_in'   => array(36455)
                            ));
                            if($latest_query->have_posts()) : while($latest_query->have_posts()) : $latest_query->the_post(); 
                                global $product;
                            ?>
                       <div class="product-item swiper-slide">
						   <a href="<?php echo get_permalink(); ?>">
							   <img 
									src="<?php echo get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : get_stylesheet_directory_uri() . '/img/unnamed.jpg'; ?>" 
									alt="<?php echo esc_attr(get_the_title()); ?>"
									>
						   </a>                    
						   <div class="cat-match-height">
                                <h2><?php the_title(); ?></h2>
                                <?php 
                                    global $product;
                                    $average = $product->get_average_rating();
                                    $review_count = $product->get_review_count();
                                    $regular_price = $product->get_regular_price();
                                    
                                    // For variable products, get the first variation's regular price
                                    if ($product->is_type('variable')) {
                                        $available_variations = $product->get_available_variations();
                                        if (!empty($available_variations)) {
                                            $regular_price = $available_variations[0]['display_regular_price'];
                                        }
                                    }
                                ?>
                                <ul class="woocommerce">
                                    <?php if ($average) : ?>
                                        <li>
                                            <div class="star-rating" title="<?php echo sprintf(__('Rated %s out of 5', 'woocommerce'), $average); ?>">
                                                <span style="width:<?php echo (($average / 5) * 100); ?>%">
                                                    <strong itemprop="ratingValue" class="rating"><?php echo esc_html($average); ?></strong> <?php _e('out of 5', 'woocommerce'); ?>
                                                </span>
                                            </div>
                                            <span class="comment-count">(<?php echo esc_html($review_count); ?>)</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="price"><?php echo wc_price($regular_price); ?></div> <!-- Display only the regular price -->
                        </div>

                        <?php endwhile; wp_reset_postdata(); endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            <?php 
            foreach($product_categories as $category) :
                $checkbox_value = get_field('categories_checkbox', 'product_cat_' . $category->term_id);
                if (is_array($checkbox_value) && in_array('yes', $checkbox_value)) : ?>
                <div id="<?php echo esc_attr($category->slug); ?>" class="tab-content">
                    <div class="category-details match-height">
                        <h2><?php echo esc_html($category->name); ?></h2>
                        <div class="category-image">
                            <?php woocommerce_subcategory_thumbnail($category); ?>
                        </div>
                        <a href="<?php echo get_term_link($category); ?>" class="shop-link">Shop All</a>
                    </div>
                    <div class="products-container swiper-container match-height">
                        <div class="swiper-wrapper">
                            <?php
                                $query = new WP_Query(array(
                                    'post_type' => 'product',
                                    'posts_per_page' => 10,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'field'    => 'slug',
                                            'terms'    => $category->slug,
                                        ),
                                    ),
                                ));
                                if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); 
                                    global $product;
                                ?>
                          <div class="product-item swiper-slide">
							  <a href="<?php echo get_permalink(); ?>">
								  <img 
									   src="<?php echo get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : get_stylesheet_directory_uri() . '/img/unnamed.jpg'; ?>" 
									   alt="<?php echo esc_attr(get_the_title()); ?>"
									   >
							  </a>      
							  <div class="cat-match-height">
                                    <h2><?php the_title(); ?></h2>
                                    <?php 
                                        global $product;
                                        $average = $product->get_average_rating();
                                        $review_count = $product->get_review_count();
                                        $regular_price = $product->get_regular_price();
                                        
                                        // For variable products, get the first variation's regular price
                                        if ($product->is_type('variable')) {
                                            $available_variations = $product->get_available_variations();
                                            if (!empty($available_variations)) {
                                                $regular_price = $available_variations[0]['display_regular_price'];
                                            }
                                        }
                                    ?>
                                    <ul class="woocommerce">
                                        <?php if ($average) : ?>
                                            <li>
                                                <div class="star-rating" title="<?php echo sprintf(__('Rated %s out of 5', 'woocommerce'), $average); ?>">
                                                    <span style="width:<?php echo (($average / 5) * 100); ?>%">
                                                        <strong itemprop="ratingValue" class="rating"><?php echo esc_html($average); ?></strong> <?php _e('out of 5', 'woocommerce'); ?>
                                                    </span>
                                                </div>
                                                <span class="comment-count">(<?php echo esc_html($review_count); ?>)</span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="price"><?php echo wc_price($regular_price); ?></div> <!-- Display only the regular price -->
                            </div>

                            <?php endwhile; wp_reset_postdata(); endif; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <?php 
                endif;
            endforeach; 
            ?>
        </div>
    </div>

<script>
jQuery(document).ready(function($) {
    function initializeSwiper() {
        $('.tab-content').each(function() {
            var swiperContainer = $(this).find('.swiper-container');
            if (swiperContainer.length) {
                new Swiper(swiperContainer.get(0), {
                    slidesPerView: 1,
                    spaceBetween: 24,
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        type: 'fraction',
                    },
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        500: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        640: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 4,
                            spaceBetween: 24,
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 24,
                        },
                    },
                });
            }
        });

        $('.tab-content .match-height').matchHeight();
        $('.product-item img').matchHeight();
    }

    $('.tab-links a').on('click', function(e) {
        e.preventDefault();
        var currentAttrValue = $(this).attr('href');

        $('.tab-content-wrapper ' + currentAttrValue).css('display', 'flex').siblings('.tab-content').hide();

        $(this).parent('li').addClass('active').siblings().removeClass('active');

        initializeSwiper();
    });

    $('.tab-links li:first-child').addClass('active');
    $('#latest').css('display', 'flex').siblings('.tab-content').hide();

    initializeSwiper();
});
</script>
<?php
    return ob_get_clean();
}
add_shortcode('wc_category_tabs', 'wc_category_tabs_shortcode');

add_action('template_redirect', 'custom_track_product_view');
function custom_track_product_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }

    global $post;
    $product_id = $post->ID;

    $viewed_products = get_option( 'custom_recently_viewed_products', array() );

    if ( ( $key = array_search( $product_id, $viewed_products ) ) !== false ) {
        unset( $viewed_products[$key] );
    }

    array_unshift( $viewed_products, $product_id );

    $viewed_products = array_slice( $viewed_products, 0, 10 );

    update_option( 'custom_recently_viewed_products', $viewed_products );
}

function custom_wc_recently_viewed_products_shortcode() {
    $recently_viewed = isset($_COOKIE['recently_viewed_products']) ? explode(',', $_COOKIE['recently_viewed_products']) : array();

    if (empty($recently_viewed)) {
        return '<div class="no-recently-viewed-products">
                    <h2>No Items Recently Viewed</h2>
                    <p>Get back to shopping - check out our <a href="/weekly-ad">weekly ad</a> for the latest sales.</p>
                </div>';
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        'post__in' => $recently_viewed,
        'orderby' => 'post__in' // Preserve the product order
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<div class="no-recently-viewed-products">
                    <h2>No Items Recently Viewed</h2>
                    <p>Get back to shopping - check out our <a href="/weekly-ad">weekly ad</a> for the latest sales.</p>
                </div>';
    }

    ob_start();

    echo '<div class="recentview-container swiper-container">';
    echo '<div class="swiper-wrapper">';

    while ($query->have_posts()) : $query->the_post();
        global $product;

        echo '<div class="swiper-slide">';
        echo '<a href="' . get_permalink() . '">';
        echo get_the_post_thumbnail($product->get_id(), 'woocommerce_thumbnail'); // Product image
        echo '</a>';
        echo '<h2 class="woocommerce-loop-product__title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
        echo '<span class="price">' . $product->get_price_html() . '</span>'; // Product price
        echo wc_get_rating_html($product->get_average_rating()); // Product rating
        echo '</div>';

    endwhile;

    echo '</div>'; // .swiper-wrapper
    echo '<div class="swiper-pagination"></div>';
    echo '<div class="swiper-button-next"></div>';
    echo '<div class="swiper-button-prev"></div>';
    echo '</div>'; // .swiper-container

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('custom_recently_viewed_products', 'custom_wc_recently_viewed_products_shortcode');


function custom_post_grid_shortcode($atts) {
    $atts = shortcode_atts(array(
        'posts_per_page' => 4,  
    ), $atts, 'post_grid');

    $args = array(
        'post_type' => 'post',  
        'posts_per_page' => $atts['posts_per_page'], 
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        echo '<div class="post-grid-container">';
        while ($query->have_posts()) {
            $query->the_post();
            ?>
<div class="post-item">
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium'); ?>
        </a>
    </div>
    <div class="post-content">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
        <a class="learn-more" href="<?php the_permalink(); ?>">Learn More</a>
    </div>
</div>
<?php
        }
        echo '</div>';
        wp_reset_postdata();  
    } else {
        echo '<p>No posts found</p>';
    }

    return ob_get_clean();
}
add_shortcode('post_grid', 'custom_post_grid_shortcode');

function todays_recommendations_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'limit' => 4, 
        ), $atts, 'todays_recommendations'
    );
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'meta_query' => array(
            array(
                'key' => 'recommendations_product', 
                'value' => 'yes',
                'compare' => 'LIKE'
            ),
        ),
    );

    $loop = new WP_Query($args);
    ob_start();

    if ($loop->have_posts()) {
        echo '<ul class="products todays-recommendations">';
        while ($loop->have_posts()) : $loop->the_post();
            global $product;
            $product_id = get_the_ID();
            $product_title = get_the_title();
            $product_link = get_permalink();
            $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail')[0];
            
            $regular_price = $product->get_regular_price();
        
            if ($product->is_type('variable')) {
                $available_variations = $product->get_available_variations();
                if (!empty($available_variations)) {
                    $regular_price = $available_variations[0]['display_regular_price']; 
                }
            }
        
            $average = $product->get_average_rating();
            $review_count = $product->get_review_count();
        
            echo '<li class="product">';
            echo '<div class="product-top-right">';
            echo '<a href="?add-to-cart=' . $product_id . '&redirect_to_cart=1" class="add-to-cart"><img src="' . esc_url(get_stylesheet_directory_uri() . '/img/heard.svg') . '" alt="Add to Cart"></a>';
            echo '</div>';
            echo '<a href="' . esc_url($product_link) . '">';
            echo '<img class="recommendations_product_img" src="' . esc_url($product_image) . '" alt="' . esc_attr($product_title) . '">';
            echo '<h2>' . esc_html($product_title) . '</h2>';
            echo '</a>';
            echo '<div class="product-wrap">';
            echo '<div class="cat-match-height">';
                echo '<ul class="woocommerce">';
                if ($average) {
                    echo '<li>';
                    echo '<div class="star-rating" title="' . sprintf(__('Rated %s out of 5', 'woocommerce'), $average) . '">';
                    echo '<span style="width:' . (($average / 5) * 100) . '%">';
                    echo '<strong itemprop="ratingValue" class="rating">' . esc_html($average) . '</strong> ' . __('out of 5', 'woocommerce');
                    echo '</span>';
                    echo '</div>';
                    echo '<span class="comment-count">(' . esc_html($review_count) . ')</span>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '<span class="price">' . wc_price($regular_price) . '</span>';
            echo '</div>';
            echo '<div class="add-to-cart-button">';
            woocommerce_template_loop_add_to_cart(); 
            echo '</div>';
        
            echo '</div>';
            echo '</li>';
        endwhile;
        echo '</ul>';
    }

     else {
        echo __('No recommendations found');
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('todays_recommendations', 'todays_recommendations_shortcode');


function sale_products_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'limit' => '4', 
        ), $atts, 'sale_products' );

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'meta_query' => array(
            array(
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC'
            )
        )
    );

    $sale_products = new WP_Query($args);

    ob_start();
    
    if ($sale_products->have_posts()) {
        echo '<ul class="sale-products">';
        while ($sale_products->have_posts()) {
            $sale_products->the_post();
            global $product;
            ?>
<li>
    <a href="<?php the_permalink(); ?>">
        <?php echo woocommerce_get_product_thumbnail(); ?>
        <h2><?php the_title(); ?></h2>
    </a>
</li>
<?php
        }
        echo '</ul>';
    } else {
        echo 'No sale products found.';
    }
    
    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('sale_products_short', 'sale_products_shortcode');



function sale_products_shortcode_home($atts) {
    $atts = shortcode_atts(
        array(
            'limit' => '4', 
        ), $atts, 'sale_products' );

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'meta_query' => array(
            array(
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC'
            )
        )
    );

    $sale_products = new WP_Query($args);
    ob_start();

    if ($sale_products->have_posts()) {
        ?>
        <div class="swiper-container sale-products-swiper">
            <div class="swiper-wrapper">
        <?php
        while ($sale_products->have_posts()) {
            $sale_products->the_post();
            global $product;
            ?>
            <div class="swiper-slide">
                <a href="<?php the_permalink(); ?>">
                    <?php echo woocommerce_get_product_thumbnail(); ?>
                    <h2><?php the_title(); ?></h2>
                </a>
            </div>
            <?php
        }
        ?>
            </div>
        </div>
   
        <?php
    } else {
        echo 'No sale products found.';
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('sale_products_home', 'sale_products_shortcode_home');

function display_sponsored_products($atts) {
    // Define default attributes
    $atts = shortcode_atts(
        array(
            'limit' => -1, // Number of products to display
        ), $atts, 'products_sponsored'
    );

    // WP_Query arguments to fetch products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'meta_query' => array(
            array(
                'key' => 'sponsored_products', // ACF field key
                'value' => 'yes',
                'compare' => 'LIKE'
            ),
        ),
    );

    // Fetch products
    $loop = new WP_Query($args);

    // Start output buffer
    ob_start();
    if ($loop->have_posts()) {
        echo '<div class="sponsored-products-grid">';
        echo '<div class="swiper-container">';
        echo '<div class="swiper-wrapper">';
    
        while ($loop->have_posts()) {
            $loop->the_post();
            global $product;
    
            $comment_count = get_comments_number($product->get_id());
            $product_link = get_permalink();
            $product_thumbnail = woocommerce_get_product_thumbnail();
            $product_title = get_the_title();
            $rating_html = $product->get_rating_count() ? '<div class="star-rating-container">' . wc_get_rating_html($product->get_average_rating()) . '</div>' : '';
            $comment_html = $comment_count > 0 ? '<div class="comment-count">(' . esc_html($comment_count) . ')</div>' : '';
            $price_html = $product->get_price_html();
            $add_to_cart_url = esc_url($product->add_to_cart_url());
            $add_to_cart_text = esc_html($product->add_to_cart_text());
    
            echo '<div class="swiper-slide">';
            echo '<div class="product">';
    
            echo '<a href="' . $product_link . '">' . $product_thumbnail . '</a>';
            echo '<h2 class="woocommerce-loop-product__title"><a href="' . $product_link . '">' . $product_title . '</a></h2>';
            echo '<div class="rating-wrap">';
            echo '<div class="rating-section woocommerce">';
            echo $rating_html;
            echo $comment_html;
            echo '</div>';
            echo '<span class="price">' . $price_html . '</span>';
            echo '</div>';
            echo '<a href="' . $add_to_cart_url . '" class="button add_to_cart_button">' . $add_to_cart_text . '</a>';
            echo '</div>';
            echo '</div>';
        }
    
        echo '</div>';
        echo '<div class="swiper-pagination"></div>';
        echo '<div class="swiper-button-next"></div>';
        echo '<div class="swiper-button-prev"></div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<p>' . __('No sponsored products found', 'woocommerce') . '</p>';
    }
    
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('sponsored_products', 'display_sponsored_products');

function track_recently_viewed_products() {
    if (is_singular('product')) {
        global $post;

        if (empty($_COOKIE['recently_viewed_products'])) {
            $recently_viewed = array();
        } else {
            $recently_viewed = explode(',', $_COOKIE['recently_viewed_products']);
        }

        if (($key = array_search($post->ID, $recently_viewed)) !== false) {
            unset($recently_viewed[$key]);
        }
        array_unshift($recently_viewed, $post->ID);

        $recently_viewed = array_slice($recently_viewed, 0, 5);

        setcookie('recently_viewed_products', implode(',', $recently_viewed), time() + 3600, '/');
    }
}
add_action('template_redirect', 'track_recently_viewed_products');


function display_recently_viewed_products() {
    if (empty($_COOKIE['recently_viewed_products'])) {
        return '<p>No products viewed recently.</p>';
    } else {
        $recently_viewed = explode(',', $_COOKIE['recently_viewed_products']);

        $args = array(
            'post_type' => 'product',
            'post__in' => $recently_viewed,
            'orderby' => 'post__in',
        );

        $recent_products = new WP_Query($args);

        if ($recent_products->have_posts()) {
            ob_start();
            ?>
<div class="recent-container">
    <div class="swiper-wrapper">
        <?php while ($recent_products->have_posts()) : $recent_products->the_post(); 
            global $product;  // Get the global product object
            $average = $product->get_average_rating();
            $review_count = $product->get_review_count();
        ?>
        <div class="swiper-slide">
            <a href="<?php echo get_permalink(); ?>">
                <?php echo get_the_post_thumbnail(get_the_ID(), 'shop_catalog'); ?>
            </a>
            <h2><?php the_title(); ?></h2>

            <ul class="woocommerce">
                <?php if ($average) : ?>
                    <li>
                        <div class="star-rating" title="<?php echo sprintf(__('Rated %s out of 5', 'woocommerce'), $average); ?>">
                            <span style="width:<?php echo (($average / 5) * 100); ?>%">
                                <strong itemprop="ratingValue" class="rating"><?php echo $average; ?></strong> <?php _e('out of 5', 'woocommerce'); ?>
                            </span>
                        </div>
                        <span class="comment-count">(<?php echo esc_html($review_count); ?>)</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endwhile; ?>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
    <!-- Add Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
<?php
            wp_reset_postdata();
            return ob_get_clean();
        } else {
            return '<p>No products viewed recently.</p>';
        }
    }
}
add_shortcode('recently_viewed_products', 'display_recently_viewed_products');

// Shortcode for Typical Effects, May Relieve, Flavors, and Aromas
function custom_effects_shortcode() {
    ob_start();
    ?>
<div class="effects-grid">
    <div class="typical-effects-field-image">
        <div class="typical-image">
            <!-- Effect Profile -->
            <div class="effects-typical_heading typical--heading">
                <h3>Effect Profile</h3>
                <div class="effects-typical-image side-effects-section effects-section">
                    <?php
                    $typical_effects = get_field('typical_effects', get_the_ID());
                    if (empty($typical_effects)) {
                        $typical_effects = get_field('typical_effects', 'option');
                    }
                    if (is_array($typical_effects)) {
                        foreach ($typical_effects as $index => $term) :
                            $image = get_field('product_taxonomy_img', 'term_' . $term->term_id);
                            ?>
                            <div class="taxonomy-img-heading-section<?php echo $index >= 4 ? ' hidden' : ''; ?>">
                                <div class="taxonomy-img-heading">
                                    <div class="img"><img src="<?php echo esc_url($image); ?>"></div>
                                    <h4><?php echo esc_html($term->name); ?></h4>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    }
                    ?>
                </div>
                <?php if (is_array($typical_effects) && count($typical_effects) > 4) : ?>
                    <p class="more-effects side-effects-more">See More</p>
                <?php endif; ?>
            </div>

            <!-- Relief Profile -->
            <div class="effects-typical_heading typical--heading">
                <h3>Relief Profile</h3>
                <div class="effects-typical-image may-relive-section effects-section">
                    <?php
                    $commonusage = get_field('common_usage', get_the_ID());
                    if (empty($commonusage)) {
                        $commonusage = get_field('common_usage', 'option');
                    }
                    if (is_array($commonusage)) {
                        foreach ($commonusage as $index => $term) :
                            $image = get_field('product_taxonomy_img', 'term_' . $term->term_id);
                            ?>
                            <div class="taxonomy-img-heading-section<?php echo $index >= 4 ? ' hidden' : ''; ?>">
                                <div class="taxonomy-img-heading">
                                    <div class="img"><img src="<?php echo esc_url($image); ?>"></div>
                                    <h4><?php echo esc_html($term->name); ?></h4>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    }
                    ?>
                </div>
                <?php if (is_array($commonusage) && count($commonusage) > 4) : ?>
                    <p class="more-effects may-relive-more">See More</p>
                <?php endif; ?>
            </div>

            <!-- Flavors -->
            <div class="effects-typical_heading typical--heading">
                <h3>Flavors</h3>
                <div class="effects-typical-image flavors-section effects-section">
                    <?php
                    $flavors = get_field('flavors', get_the_ID());
                    if (empty($flavors)) {
                        $flavors = get_field('flavors', 'option');
                    }
                    if (is_array($flavors)) {
                        foreach ($flavors as $index => $term) :
                            $image = get_field('product_taxonomy_img', 'term_' . $term->term_id);
                            ?>
                            <div class="taxonomy-img-heading-section<?php echo $index >= 4 ? ' hidden' : ''; ?>">
                                <div class="taxonomy-img-heading">
                                    <div class="img"><img src="<?php echo esc_url($image); ?>"></div>
                                    <h4><?php echo esc_html($term->name); ?></h4>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    }
                    ?>
                </div>
                <?php if (is_array($flavors) && count($flavors) > 4) : ?>
                    <p class="more-effects flavors-more">See More</p>
                <?php endif; ?>
            </div>

            <!-- Aromas -->
            <div class="effects-typical_heading typical--heading">
                <h3>Aromas</h3>
                <div class="effects-typical-image aromas-section effects-section">
                    <?php
                    $aromas = get_field('aromas', get_the_ID());
                    if (empty($aromas)) {
                        $aromas = get_field('aromas', 'option');
                    }
                    if (is_array($aromas)) {
                        foreach ($aromas as $index => $term) :
                            $image = get_field('product_taxonomy_img', 'term_' . $term->term_id);
                            ?>
                            <div class="taxonomy-img-heading-section<?php echo $index >= 4 ? ' hidden' : ''; ?>">
                                <div class="taxonomy-img-heading">
                                    <div class="img"><img src="<?php echo esc_url($image); ?>"></div>
                                    <h4><?php echo esc_html($term->name); ?></h4>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    }
                    ?>
                </div>
                <?php if (is_array($aromas) && count($aromas) > 4) : ?>
                    <p class="more-effects aromas-more">See More</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php
    return ob_get_clean();
}

add_shortcode('custom_effects', 'custom_effects_shortcode');





function allow_svg_upload( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'allow_svg_upload' );
function generate_progress_bar_shortcode() {
    ob_start(); 
    ?>
    <div class="progress-bar-grid">
        <?php 
        // Retrieve THC progress bar value
        $thc_progress_bar = get_field('htc_progress_bar', get_the_ID());
        if (!$thc_progress_bar) {
            $thc_progress_bar = get_field('htc_progress_bar', 'option');
        }
        $thc_progress_bar = $thc_progress_bar ? $thc_progress_bar : 0; // Set default if not found
        ?>
        <section class="progress-bar-section" data-thc-progress-bar="<?php echo esc_attr($thc_progress_bar); ?>">
            <span>THC</span>
            <input type="range" value="<?php echo esc_attr($thc_progress_bar); ?>" min="0" max="100" hidden step="1">
            <canvas id="thcChart" width="150" height="150"></canvas>
        </section>

        <?php 
        // Retrieve CBD progress bar value
        $cbd_progress_bar = get_field('cbd_progress_bar', get_the_ID());
        if (!$cbd_progress_bar) {
            $cbd_progress_bar = get_field('cbd_progress_bar', 'option');
        }
        $cbd_progress_bar = $cbd_progress_bar ? $cbd_progress_bar : 0; // Set default if not found
        ?>
        <section class="progress-bar-section" data-cbd-progress-bar="<?php echo esc_attr($cbd_progress_bar); ?>">
            <span>CBD</span>
            <input type="range" value="<?php echo esc_attr($cbd_progress_bar); ?>" min="0" max="100" hidden step="1">
            <canvas id="cbdChart" width="150" height="150"></canvas>
        </section>
		
        <div class="progress-bar-section">
            <div class="typical-image">
                <div class="effects-typical_heading typical--heading">
                    <h3>Effect</h3>
                    
                </div>
				 </div>
        </div>
        <div class="progress-bar-section">
            <div class="typical-image">
                <div class="effects-typical_heading typical--heading">
                    <h3>Relief</h3>
                    
                </div>
            </div>
        </div>
    </div>

  
    <?php
    return ob_get_clean(); // Return the buffered content
}

add_shortcode('progress_bar', 'generate_progress_bar_shortcode');


add_filter ( 'woocommerce_product_thumbnails_columns', 'bbloomer_change_gallery_columns' );

function bbloomer_change_gallery_columns() {
return 1;
}

add_action('woocommerce_single_product_summary', 'display_specific_shipping_class', 15);
function display_specific_shipping_class() {
global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
$product = wc_get_product();
}

if ( ! is_a( $product, 'WC_Product' ) ) {
return; 
}

$defined_shipping_class = "Estimated Delivery in 7-15 days";

$product_shipping_class = $product->get_shipping_class();

$term = get_term_by('slug', $product_shipping_class, 'product_shipping_class');

if ($term && $term->name == $defined_shipping_class) {
echo '<p class="product-shipping-class">' . esc_html($term->name) . '</p>';
}
}


add_action( 'woocommerce_before_quantity_input_field', 'bbloomer_display_quantity_minus' );
 
function bbloomer_display_quantity_minus() {
   if ( ! is_product() ) return;
   echo '<button type="button" class="minus" >-</button>';
}
 
add_action( 'woocommerce_after_quantity_input_field', 'bbloomer_display_quantity_plus' );
 
function bbloomer_display_quantity_plus() {
   if ( ! is_product() ) return;
   echo '<button type="button" class="plus" >+</button>';
}
 
add_action( 'woocommerce_before_single_product', 'bbloomer_add_cart_quantity_plus_minus' );
 
function bbloomer_add_cart_quantity_plus_minus() {
   wc_enqueue_js( "
      $('form.cart').on( 'click', 'button.plus, button.minus', function() {
            var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
            var val   = parseFloat(qty.val());
            var max = parseFloat(qty.attr( 'max' ));
            var min = parseFloat(qty.attr( 'min' ));
            var step = parseFloat(qty.attr( 'step' ));
            if ( $( this ).is( '.plus' ) ) {
               if ( max && ( max <= val ) ) {
                  qty.val( max );
               } else {
                  qty.val( val + step );
               }
            } else {
               if ( min && ( min >= val ) ) {
                  qty.val( min );
               } else if ( val > 1 ) {
                  qty.val( val - step );
               }
            }
         });
   " );
}


// Add the shortcode function
function display_related_products($atts) {
    // Ensure WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    global $product;

    // Set default attributes and override with user attributes
    $atts = shortcode_atts(array(
        'limit' => 4, // Number of related products to display
    ), $atts, 'related_products');

    // Get related products
    $related_ids = wc_get_related_products($product->get_id(), $atts['limit']);
    
    if (empty($related_ids)) {
        return '<p>No related products found.</p>';
    }

    // Set up the query for related products
    $args = array(
        'post_type' => 'product',
        'post__in' => $related_ids,
        'posts_per_page' => $atts['limit'],
    );

    $related_query = new WP_Query($args);

    ob_start();

    if ($related_query->have_posts()) {
        echo '<ul class="related-products">';
        while ($related_query->have_posts()) : $related_query->the_post();
            global $product;
            ?>
<li class="product">
    <a href="<?php the_permalink(); ?>">
        <?php echo woocommerce_get_product_thumbnail(); ?>
        <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
    </a>
    <?php
                // Display the review stars
                if (wc_get_rating_html($product->get_average_rating())) {
                    echo wc_get_rating_html($product->get_average_rating());
                }

                // Display the price
                echo '<span class="price">' . $product->get_price_html() . '</span>';

                // Display the add to cart button
                woocommerce_template_loop_add_to_cart();
                ?>
</li>
<?php
        endwhile;
        echo '</ul>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('related_products', 'display_related_products');



function get_woocommerce_product($product_id) {
    $args = array(
        'include' => array( 144 ),
    );
    
    $products = wc_get_products( $args );
    
    foreach ($products as $product) {
        $saleproduct_id = $product->get_id(); 
        $sale_price = $product->get_sale_price();
        $regular_price = $product->get_regular_price();
        $sales_price_from = $product->get_date_on_sale_from();
        $sales_price_to = $product->get_date_on_sale_to();
        $new_price_from = $sales_price_from ? date('Y-m-d H:i:s', strtotime($sales_price_from)) : '';
        $new_price_to = $sales_price_to ? date('Y-m-d H:i:s', strtotime($sales_price_to)) : '';
        $sale_free = $sale_price > 0 ? $sale_price : 'Free';
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($saleproduct_id), 'full');
    
        $html .= '<div class="sale-image-contant">';
        $html .= '<div class="sale-iamge">';
        $html .= '<div class="pro-image"><img src="' . $image[0] . '"></div></div>';
        $html .= '<div class="sale-contant"><div class="sale">Sale</div><div class="pro-title"><a href="' . get_permalink($saleproduct_id) . '">' . $product->get_name() . '</a></div>';
        $html .= '<div class="pro-short-des">' . $product->get_short_description() . '</div>';
        $html .= '<div class="pro-price"><div class="pro-sale-price">' . $sale_free . '</div><div class="pro-regular"><del>' . get_woocommerce_currency_symbol() . '' . $regular_price . '.00</del></div></div>';
        $html .= '<div id="countdown" data-end-date="' . $new_price_to . '"><div id="tiles"></div>';
        $html .= '</div>';
        $html .= '<div class="pro-info-btn"><div class="btn"><a href="' . get_permalink($saleproduct_id) . '" class="info-btn">MORE INFO</a></div></div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<input type="hidden" id="new_price_from" name="new_price_from" value="' . $new_price_from . '">';
        $html .= '<input type="hidden" id="new_price_to" name="new_price_to" value="' . $new_price_to . '">';
    }
    
    return $html; 
    
    }
    add_shortcode( 'single_product', 'get_woocommerce_product' );

  /* store locator customize code */
add_filter( 'wpsl_meta_box_fields', 'custom_meta_box_fields' );
function custom_meta_box_fields( $meta_fields ) {
    
    $meta_fields[__( 'Information', 'wpsl' )] = array(
        'mobile_number' => array(
            'label' => __( 'Mobile Numnber', 'wpsl' )
        ),
        'product_list' => array(
            'label' => __( 'Product List', 'wpsl' )
        ),
        'price_rate' => array(
            'label' => __( 'Price Rate', 'wpsl' )
        ),
        'coverage' => array(
            'label' => __( 'Coverage', 'wpsl' )
        ),
        'extended_coverage' => array(
            'label' => __( 'Extended Coverage', 'wpsl' )
        ),
        'minimum_order' => array(
            'label' => __( 'Minimum Order', 'wpsl' )
        ),
         'nursrey_pickup_address' => array(
            'label' => __( 'Nursrey Pickup Address', 'wpsl' )
        ),
          'nurserie_quantity_one' => array(
            'label' => __( 'Pricing 5 - 49 ', 'wpsl' )
        ),
          'nurserie_quantity_second' => array(
            'label' => __( 'Pricing 50 - 99 ', 'wpsl' )
        ),
          'nurserie_quantity_third' => array(
            'label' => __( 'Pricing 100 - 499 ', 'wpsl' )
        ),
          'nurserie_quantity_fourth' => array(
            'label' => __( 'Pricing 500 - 999 ', 'wpsl' )
        ),
         'nurserie_quantity_fifth' => array(
            'label' => __( 'Pricing 1000 + ', 'wpsl' )
        ),  
         'delivery_range' => array(
         'label' => __( 'Delivery Range', 'wpsl' )
        ),
         'nurserie_grower_notes' => array(
         'label' => __( 'Grower Notes', 'wpsl' )
        ),
         'nurserie_updated_inventory' => array(
         'label' => __( 'Updated Inventory', 'wpsl' )
        ), 
        'nurserie_order_confirmation' => array(
            'label' => __( 'Order Confirmation Text', 'wpsl' ),
            'type'  => 'wp_editor'
        ),
        'product_strain_listing' => array(
            'label' => __( 'Product Strain listing', 'wpsl' ),
            'type'  => 'wp_editor'
        ),
    );
    return $meta_fields;
}

add_filter( 'wpsl_templates', 'custom_templates' );

add_filter( 'wpsl_frontend_meta_fields', 'custom_frontend_meta_fields' );
function custom_frontend_meta_fields( $store_fields ) {

    $store_fields['wpsl_product_strain_listing'] = array( 
        'name' => 'product_strain_listing' 
    );
    $store_fields['wpsl_strain_menu'] = array( 
        'name' => 'strain_menu',
        'type' => 'url'
    );
    $store_fields['wpsl_price_list'] = array( 
        'name' => 'price_list',
        'type' => 'text'
    );
    $store_fields['wpsl_mobile_number'] = array( 
        'name' => 'mobile_number',
        'type' => 'number'
    );
    $store_fields['wpsl_product_list'] = array( 
        'name' => 'product_list',
        'type' => 'textarea'
    );
    $store_fields['wpsl_price_rate'] = array( 
        'name' => 'price_rate',
        'type' => 'textarea'
    );
    $store_fields['wpsl_coverage'] = array( 
        'name' => 'coverage',
        'type' => 'text'
    );
    $store_fields['wpsl_extended_coverage'] = array( 
        'name' => 'extended_coverage',
        'type' => 'text'
    ); 
     $store_fields['wpsl_minimum_order'] = array( 
        'name' => 'minimum_order',
        'type' => 'text'
    );
     $store_fields['wpsl_nursrey_pickup_address'] = array( 
        'name' => 'nursrey_pickup_address',
        'type' => 'text'
    );
     $store_fields['wpsl_nurserie_quantity_one'] = array( 
        'name' => 'nurserie_quantity_one',
        'type' => 'text'
    );
    $store_fields['wpsl_nurserie_quantity_second'] = array( 
        'name' => 'nurserie_quantity_second',
        'type' => 'text'
    );   
    $store_fields['wpsl_nurserie_quantity_third'] = array( 
        'name' => 'nurserie_quantity_third',
        'type' => 'text'
    );
    $store_fields['wpsl_nurserie_quantity_fourth'] = array( 
        'name' => 'nurserie_quantity_fourth',
        'type' => 'text'
    );
    $store_fields['wpsl_nurserie_quantity_fifth'] = array( 
        'name' => 'nurserie_quantity_fifth',
        'type' => 'text'
    );
    $store_fields['wpsl_nurserie_delivery_range'] = array( 
        'name' => 'delivery_range',
        'type' => 'text'
    );
    $store_fields['wpsl_nurserie_grower_notes'] = array( 
        'name' => 'nurserie_grower_notes',
        'type' => 'text'
    ); 
    $store_fields['wpsl_nurserie_updated_inventory'] = array( 
        'name' => 'nurserie_updated_inventory',
        'type' => 'text'
    );      
    $store_fields['wpsl_nurserie_order_confirmation'] = array( 
        'name' => 'nurserie_order_confirmation',
    );
    return $store_fields;
}

function wpsl_address_data() {

    global $wpsl_settings;

    $address_format = 'test';
  
    return $address_format;
}

function custom_templates( $templates ) {
    $templates[] = array (
        'id'   => 'custom',
        'name' => 'nurserie',
        'path' => get_stylesheet_directory() . '/public_html/wordpress/eighthsounces_new/wp-content/themes/twentytwentyfour-child/wpsl-templates/custom.php',
    );
    return $templates;
}
add_filter( 'wpsl_store_meta', 'custom_store_meta', 10, 2 );
function custom_store_meta( $store_meta, $store_id ) {

  $data = get_field_object('select_strain_products',$store_id);
  $product_lists = array();
  if ( count( $data['value'] ) > 1 ) {
    foreach($data['value'] as $term ) {     
      $permalink = get_permalink( $term->ID );
      $product_lists[] = '<p>' .$term->post_title. '</p>';
    }
    $store_meta['terms'] = implode( ' ', $product_lists );
  }else{
    $store_meta['terms'] = $terms[0]->name;
  }
  return $store_meta;
}

add_filter( 'wpsl_store_meta', 'sample_callback', 10, 2 );
function sample_callback($store_meta, $store_id) {
    
    $save_bookmark = array();
    $store_meta['save_book_mark']='';
    if(isset($_COOKIE['bookmarks'])){
       foreach($_COOKIE['bookmarks'] as $name => $value){
                    
            if($store_id == $value){
                $save_bookmark[]='true';
            }
        } 
    }
    $store_meta['save_book_mark'] = $save_bookmark; 
    return $store_meta;
}

add_filter( 'wpsl_store_meta', 'custom_store_meta_text', 10, 2 );
function custom_store_meta_text( $store_meta, $store_id ) {
    
    $confirm_text = get_field_object('order_confirmation_text', $store_id);
    $nursery_confirm_text = $confirm_text["value"];

    $img = get_field_object('nurseries_store_image', $store_id);
    $nursery_images = $img["value"];

    $store_meta['order_confirmation_text'] = $nursery_confirm_text;
    $store_meta['nurseries_store_image'] = $nursery_images;

    return $store_meta;
}

add_filter('wpsl_listing_template', 'custom_listing_template');
function custom_listing_template() {
    global $wpsl, $wpsl_settings;

    $more_info_url = '#';
    $listing_template = '<div id="overlay"><div class="cv-spinner"><span class="spinner"></span></div></div>';

    if ($wpsl_settings['template_id'] == 'default' && $wpsl_settings['more_info_location'] == 'info window') {
        $more_info_url = '#wpsl-search-wrap';
    }

    $listing_template .= '<li class="strain-store" data-store-id="<%= id %>">';
    $listing_template .= '
        <div id="wpsl-id-<%= _.escape(id) %>" class="wpsl-more-info-listings">
            <p class="coverage-details">
                <% if (typeof coverage !== "undefined") { %>
                    <span><strong>Coverage</strong>: <%= _.escape(coverage) %></span>
                <% } %>
                <% if (typeof extended_coverage !== "undefined") { %>
                    <span><strong>Extended Coverage</strong>: <%= _.escape(extended_coverage) %></span>
                <% } %>
                <% if (typeof minimum_order !== "undefined") { %>
                    <span><strong>Minimum Order</strong>: <%= _.escape(minimum_order) %></span>
                <% } %>
                <% if (typeof nurserie_updated_inventory !== "undefined") { %>
                    <span><strong>Updated Inventory</strong>: <a target="_blank" href="<%= _.escape(nurserie_updated_inventory) %>"><%= _.escape(nurserie_updated_inventory) %></a></span>
                <% } %>
                <% if (typeof delivery_range !== "undefined") { %>
                    <span><strong>Delivery Range</strong>: <%= _.escape(delivery_range) %></span>
                <% } %>
                <% if (typeof nurserie_grower_notes !== "undefined") { %>
                    <span><strong>Grower Notes</strong>: <%= _.escape(nurserie_grower_notes) %></span>
                <% } %>
                <% if (typeof order_confirmation_text !== "undefined") { %>
                    <span><strong>Order Confirmation Text</strong>: <%= _.escape(order_confirmation_text) %></span>
                <% } %>
                <% if (typeof terms !== "undefined") { %>
                    <div class="moreinfo-store-product-list"><%= _.escape(terms) %></div>
                <% } %>
                <% if (typeof nurseries_store_image !== "undefined") { %>
                    <div class="store-img"><%= nurseries_store_image %></div>
                <% } %>
            </p>
        </div>';

    $listing_template .= '
        <div class="wpsl-store-location">
            <div class="back-to-result"><i class="fas fa-arrow-left"></i> Back</div>
            <div class="nurseries-address">
                <span class="store-list #wpsl-id-<%= id %>" href="#wpsl-id-<%= id %>">
                    <div class="copy-nurseries-address">' . wpsl_store_header_template('listing') . '
                        <div class="city">
                            <h4><%= city %></h4> <h6><%= state %></h6>
                        </div>
                        <div class="wpsl-direction-wrap">';
    if (!$wpsl_settings['hide_distance']) {
        $listing_template .= '<%= distance %> ' . esc_html($wpsl_settings['distance_unit']) . '';
    }
    $listing_template .= '
                        </div>
                        <div class="clones_available">
                            <h6><strong>Clones Available :</strong> Yes</h6>
                        </div>
                        <div class="minimum_order">
                            <h6><strong>Minimum Clone Order :</strong> 5 Clones</h6>
                        </div>
                        <div class="teens_available">
                            <h6><strong>Teens Available :</strong> Yes</h6>
                        </div>
                        <div class="grow_medium">
                            <h6><strong>Minimum Teen Order :</strong> 3 Teens</h6>
                        </div>
                        <div class="delivery_available">
                            <h6><strong>Delivery Available :</strong> 100+ Clones</h6>
                        </div>
                    </div>
                </span>
            </div>
        </div>';

    $listing_template .= '
        <div class="right-button">
            <div class="btn-group">
                <div class="more-info btn-store green-btn-line">
                    <a class="btn-more-info" target="_blank" href="<%= permalink %>"> Available Strains</a>
                </div>
                <% if (save_book_mark == "true") { %>
                    <a class="btn-bookmark btn-save" href="javascript:void(0)" data-id="<%= id %>">Saved</a>
                <% } else { %>
                    <div class="more-info btn-store-line green-btn">
                        <button class="btn-more-info popmake-46468" data-name="<%= city %>">Contact Nursery</button>
                    </div>
                <% } %>
            </div>
             <div class="store-strain-details">
            <ul>
                <li>
                    <a data-fancybox class="starin_list" data-id="<%= id %>" href="#starin_list-<%= id %>">
                       <img src="/wp-content/uploads/2024/07/menu.svg">   Strains
                    </a>
                   <a data-fancybox class="starin_list" data-id="<%= id %>" href="#starin_list-<%= id %>">
                       <img src="/wp-content/uploads/2024/08/cash-app.svg">   Price 
                    </a>
                </li>';

    $listing_template .= '
                <% if (city == "Nationwide Shipping") { %>
                    <li class="test">
                        <a data-fancybox class="pricelist" data-id="<%= id %>" href="#pricelist-<%= id %>">
                            <i class="fa fa-dollar-sign" aria-hidden="true"></i>Price List
                        </a>
                        <div class="tabelview-fancybox" id="pricelist-<%= id %>" style="display:none">
                            <h3>Price List
                                <a class="copy-btn" href="#" onclick="CopyToClipboardcostprice(<%= id %>);return false;">
                                    <i class="far fa-copy"></i>
                                </a>
                            </h3>
                            <div class="price-list-box">
                                <div class="columnbox clearfix clones">
                                    <h4>For Clones</h4>
                                    <div class="inner-column four-column">
                                        <h5>Clones</h5>
                                        <ul>
                                            <li>3</li>
                                            <li class="price-list-genral">4-6</li>
                                            <li>7-11</li>
                                            <li>12-17</li>
                                            <li>18-23</li>
                                            <li>24-40</li>
                                            <li>41-50</li>
                                            <li>51-99</li>
                                            <li>100+</li>
                                        </ul>
                                    </div>
                                    <div class="inner-column four-column">
                                        <h5>Donation</h5>
                                        <ul>
                                            <li>$60/Ea</li>
                                            <li class="price-list-genral">$50/Ea</li>
                                            <li>$40/Ea</li>
                                            <li>$35/Ea</li>
                                            <li>$34/Ea</li>
                                            <li>$31/Ea</li>
                                            <li>$28/Ea</li>
                                            <li>$26/Ea</li>
                                            <li><a class="clone-link" href="../online-wholesale-order/">Get Quote</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="columnbox clearfix teens">
                                    <h4>For Teens</h4>
                                    <div class="inner-column four-column">
                                        <h5>Teens</h5>
                                        <ul>
                                            <li>1</li>
                                            <li>2</li>
                                            <li>3</li>
                                            <li>4-6</li>
                                            <li>7-11</li>
                                            <li>12+</li>
                                        </ul>
                                    </div>
                                    <div class="inner-column four-column">
                                        <h5>Donation</h5>
                                        <ul>
                                            <li>--</li>
                                            <li>--</li>
                                            <li>$100/Ea</li>
                                            <li>$95/Ea</li>
                                            <li>$85/Ea</li>
                                            <li>$75/Ea</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <% } %>
            </ul>
        </div>
        </div>';

    $listing_template .= '
       
    </li>';

    return $listing_template;
}

function custom_product_filters_shortcode() {
    ob_start();      
    $categories = get_terms('product_cat', array('hide_empty' => true));
    
    // Fetch featured strains using ACF custom field
// Fetch categories
$categories = get_terms('product_cat', array('hide_empty' => true));

// Fetch featured strains using ACF custom field
$featured_strains_query = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => -1, // Adjust the number of posts as needed
    'meta_query' => array(
        array(
            'key' => 'featured_strains',
            'value' => 'yes',
            'compare' => 'LIKE'
        )
    )
));

$featured_strains = $featured_strains_query->posts;

    $types = get_terms('pa_type', array('hide_empty' => true));
    $child_categories = get_terms('product_cat', array('parent' => 0, 'hide_empty' => true));
    $seed_types = get_terms('pa_seeds', array('hide_empty' => true));
    $flavors = get_terms('pa_flavors', array('hide_empty' => true));
    $aromas = get_terms('pa_aromas', array('hide_empty' => true));
    $flower_time = get_terms('pa_flower_time', array('hide_empty' => true));
    $health_benefits = get_terms('pa_health_benefits', array('hide_empty' => true));
    $deals = get_posts(array('post_type' => 'product', 'meta_query' => array(array('key' => '_sale_price', 'value' => 0, 'compare' => '>'))));
    ?>
<div class="product-filters">
    <form method="GET" action="<?php echo esc_url(get_permalink(wc_get_page_id('seed'))); ?>">

        <!-- All Strains -->
        <div class="filter-category">
            <h4 class="filter-title" onclick="toggleFilter('all-strains-filters')">All Strains </h4>
            <div id="all-strains-filters" style="display:none;">
                <!-- Categories Subtitle -->
                <div class="filter-subtitle">
                    <div class="filter-subtitle-title" onclick="toggleFilter('category-filters')">Categories</div>
                    <ul id="category-filters" style="display:none;">
                        <?php foreach ($categories as $category): ?>
                        <li> <label><input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($category->slug); ?>" /> <?php echo esc_html($category->name); ?>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Featured Strains Subtitle -->
                <div class="filter-subtitle">
                    <div class="filter-subtitle-title" onclick="toggleFilter('featured-strains-filters')">Featured Strains </div>
                    <ul id="featured-strains-filters" style="display:none;">
                        <?php foreach ($featured_strains as $strain): ?>
                        <li><label><input type="checkbox" name="featured_strains[]" value="<?php echo esc_attr($strain->ID); ?>" /><?php echo esc_html($strain->post_title); ?>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Type -->
        <div class="filter-attribute">
            <h4 onclick="toggleFilter('type-filters')">Type</h4>
            <ul id="type-filters" style="display:none;">
                <?php foreach ($types as $type): ?>
                <li><label><input type="checkbox" name="pa_type[]" value="<?php echo esc_attr($type->slug); ?>" /><?php echo esc_html($type->name); ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Seeds -->
        <div class="filter-attribute">
            <h4 onclick="toggleFilter('seeds-filters')">Seeds</h4>
            <ul id="seeds-filters" style="display:none;">
                <?php foreach ($child_categories as $child_category): ?>
                <li><label> <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($child_category->slug); ?>" /><?php echo esc_html($child_category->name); ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
            <ul id="seeds-type-filters" style="display:none;">
                <?php foreach ($seed_types as $seed_type): ?> <li> <label> <input type="checkbox" name="pa_seeds[]" value="<?php echo esc_attr($seed_type->slug); ?>" /> <?php echo esc_html($seed_type->name); ?></label></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Flavors -->
        <div class="filter-attribute">
            <h4 onclick="toggleFilter('flavors-filters')">Flavors</h4>
            <ul id="flavors-filters" style="display:none;">
                <?php foreach ($flavors as $flavor): ?>
                <li><label><input type="checkbox" name="pa_flavors[]" value="<?php echo esc_attr($flavor->slug); ?>" /> <?php echo esc_html($flavor->name); ?></label></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Aromas -->
        <div class="filter-attribute">
            <h4 onclick="toggleFilter('aromas-filters')">Aromas</h4>
            <ul id="aromas-filters" style="display:none;">
                <?php foreach ($aromas as $aroma): ?>
                <li><label><input type="checkbox" name="pa_aromas[]" value="<?php echo esc_attr($aroma->slug); ?>" /> <?php echo esc_html($aroma->name); ?></label></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Flower Time -->
        <div class="filter-attribute">
            <h4 onclick="toggleFilter('flower-time-filters')">Flower Time</h4>
            <ul id="flower-time-filters" style="display:none;">
                <?php foreach ($flower_time as $time): ?>
                <li><label><input type="checkbox" name="pa_flower_time[]" value="<?php echo esc_attr($time->slug); ?>" /> <?php echo esc_html($time->name); ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Health Benefits -->
        <div class="filter-attribute">
            <h4 onclick="toggleFilter('health-benefits-filters')">Health Benefits</h4>
            <ul id="health-benefits-filters" style="display:none;">
                <?php foreach ($health_benefits as $benefit): ?>
                <li><label><input type="checkbox" name="pa_health_benefits[]" value="<?php echo esc_attr($benefit->slug); ?>" /> <?php echo esc_html($benefit->name); ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Deals -->
        <div class="filter-attribute">
            <h4 onclick="toggleFilter('deals-filters')">Deals</h4>
            <ul id="deals-filters" style="display:none;">
                <?php foreach ($deals as $deal): ?>
                <li><label> <input type="checkbox" name="deals[]" value="<?php echo esc_attr($deal->ID); ?>" /> <?php echo esc_html($deal->post_title); ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Price -->
        <div class="filter-price">
            <h4 onclick="toggleFilter('price-filters')">Price</h4>
            <ul id="price-filters" style="display:none;">
                <li>
                    <label><input type="checkbox" name="price_range" value="under_20" /> Under $20</label>
                </li>
                <li>
                    <label><input type="checkbox" name="price_range" value="20_40" /> $20 - $40</label>
                </li>
                <li>
                    <label><input type="checkbox" name="price_range" value="40_60" /> $40 - $60</label>
                </li>
                <li>
                    <label><input type="checkbox" name="price_range" value="60_80" /> $60 - $80</label>
                </li>
                <li>
                    <label><input type="checkbox" name="price_range" value="80_above" /> $80 & above</label>
                </li>
            </ul>
        </div>

        <button type="submit">Apply</button>
    </form>
</div>

<script>
function toggleFilter(id) {
    var element = document.getElementById(id);
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}
</script>

<?php
    return ob_get_clean();  
}

add_shortcode('custom_product_filters', 'custom_product_filters_shortcode');


function filter_products_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('product')) {
        // Filter by category
        if (!empty($_GET['product_cat'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $_GET['product_cat']
                )
            ));
        }
        
        
        if (!empty($_GET['featured_strains'])) {
            $meta_query = $query->get('meta_query');
            $meta_query[] = array(
                'key' => 'featured_strains',
                'value' => 'yes',
                'compare' => 'LIKE'
            );
            $query->set('meta_query', $meta_query);
        }

        // Add other filters as needed...
    }
}
add_action('pre_get_posts', 'filter_products_query');






function logo_slider_shortcode() {
    // Define the image URLs here - adjust the path as per your setup
    $images = array(
        get_stylesheet_directory_uri() . '/img/image118.svg',
        get_stylesheet_directory_uri() . '/img/image119.svg',
        get_stylesheet_directory_uri() . '/img/image120.svg',
        get_stylesheet_directory_uri() . '/img/image121.svg',
        get_stylesheet_directory_uri() . '/img/image122.svg'
    );

    ob_start();
    ?>
<div class="logo-img-slider">
    <div class="swiper-wrapper">
        <?php foreach ($images as $image): ?>
        <div class="swiper-slide"><img src="<?php echo esc_url($image); ?>" alt="Slide Image"></div>
        <?php endforeach; ?>
    </div>
</div>
<?php
    return ob_get_clean();
}

add_shortcode('logo_swiper_slider', 'logo_slider_shortcode');



 function display_shipping_methods_location() {
     ob_start();
     ?>
     <div class="shipping-method-selection">
         <ul id="shipping-method" class="shipping-method-list">
            <li data-method="ship_my_order" class="selected">
                <h4>Ship My Order</h4>
                <p class="green-text">SHIPPING INCLUDED</p>
            </li> </ul></div><script>
         jQuery(document).ready(function($) {
             // Set the default selected shipping method
             var selectedMethod = "ship_my_order";
             $("#shipping-method li").each(function() {
                 if ($(this).data("method") == selectedMethod) {
                     $(this).addClass("selected");
                 } else {
                    $(this).removeClass("selected");
                 }
             });
        });
    </script><?php return ob_get_clean();
 }
 add_shortcode('shipping_methods_location', 'display_shipping_methods_location');



 function add_shipping_method_to_cart_item_data($cart_item_data, $product_id, $variation_id) {
     if (isset($_POST['shipping_method'])) {
         $cart_item_data['shipping_method'] = sanitize_text_field($_POST['shipping_method']);
     }
     return $cart_item_data;
 }
 add_filter('woocommerce_add_cart_item_data', 'add_shipping_method_to_cart_item_data', 10, 3);

 // Display the shipping method in the cart
 function display_shipping_method_in_cart($item_data, $cart_item) {
     if (isset($cart_item['shipping_method'])) {
         $item_data[] = array(
             'name' => 'Shipping Method',
             'value' => $cart_item['shipping_method']
        );
     }
     return $item_data;
 }
 add_filter('woocommerce_get_item_data', 'display_shipping_method_in_cart', 10, 2);

 // Save the shipping method to the order
 function save_shipping_method_to_order($item, $cart_item_key, $values, $order) {
     if (isset($values['shipping_method'])) {
         $item->add_meta_data('Shipping Method', $values['shipping_method'], true);
     }
 }
 add_action('woocommerce_checkout_create_order_line_item', 'save_shipping_method_to_order', 10, 4);

// Modify URL construction to remove commas
function show_state_city_list($atts) {
    $output = '';

    // Get the current post ID
    $post_id = get_the_ID();

    // Retrieve the state meta value for the current post
    $state = get_post_meta($post_id, 'wpsl_state', true);

    if (!empty($state)) {
        // Query to get all store IDs in the specified state
        $args = array(
            'post_type'      => 'wpsl_stores',
            'posts_per_page' => -1, // Retrieve all posts
            'meta_query'     => array(
                array(
                    'key'   => 'wpsl_state',
                    'value' => $state,
                ),
            ),
        );

        $stores_query = new WP_Query($args);

        if ($stores_query->have_posts()) {
            // Start output with grid container
            $output .= '<div class="store-grid-container">';
        
            while ($stores_query->have_posts()) {
                $stores_query->the_post();
        
                // Get city name for each store
                $city = get_post_meta(get_the_ID(), 'wpsl_city', true);
        
                // Get map URL for each store
                $map_url = get_post_meta(get_the_ID(), 'city_map', true);
        
                // Start grid item
                $output .= '<div class="store-grid-item">';
                if (!empty($map_url)) {
                    $output .= '<iframe src="' . esc_url($map_url) . '" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
                }
                $output .= '<h4>Clones for sale in ' . esc_html($city) . '</h4>';
                $output .= '<p>Available: Clones, Teens, & Shipping</p>';
                $output .= '<div class="button-wrap green-btn-line btn-store btn-store-line green-btn">';
                
                // Add city URL to the View Strains button, removing commas
                $city_sanitized = str_replace(',', '', $city); // Remove commas from the city name
                $city_url = site_url('city') . '?city=' . urlencode($city_sanitized); 
                $output .= '<a href="' . esc_url($city_url) . '" class="button">View Strains</a> ';
                $output .= '<a href="#" class="button">Contact Us</a>';
                $output .= '</div>';
                $output .= '</div>'; // End grid item
            }
        
            // End output
            $output .= '</div>'; // End grid container
        
            // Restore original post data
            wp_reset_postdata();
        } else {
            $output = __('No stores found in the specified state.', 'wpsl');
        }
        
    } else {
        $output = __('State not specified.', 'wpsl');
    }

    return $output;
}

// Register the shortcode
add_shortcode('wpsl_state_city_list', 'show_state_city_list');


function show_city_products($atts) {
    $output = '';

    // Set up the query arguments
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 9,
    );

    if (isset($_GET['city']) && !empty($_GET['city'])) {
        $city = sanitize_text_field($_GET['city']);
        $term_city = get_term_by('name', $city, 'city');
        if ($term_city) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'city',
                    'field'    => 'term_id',
                    'terms'    => $term_city->term_id,
                ),
            );
        }
    }

    $products_query = new WP_Query($args);

    if ($products_query->have_posts()) {
        // Start output
        $output .= '<ul class="products todays-recommendations city-product">';

        while ($products_query->have_posts()) {
            $products_query->the_post();
            global $product;
            $product_id = get_the_ID();
            $product_title = get_the_title();
            $product_link = get_permalink();
            $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail')[0];
            $thc = get_field('thc', $product_id);
            $cbd = get_field('cbd', $product_id);
            $type = get_field('type', $product_id);
            $flower_time = get_field('flower_time', $product_id);

            $output .= '<li class="product">';
            $output .= '<div class="product-top-right">';
            $output .= '<a href="?add-to-cart=' . $product_id . '&redirect_to_cart=1" class="add-to-cart"><img src="' . esc_url(get_stylesheet_directory_uri() . '/img/heard.svg') . '" alt="Add to Cart"></a>';
            $output .= '</div>';
            $output .= '<a href="' . esc_url($product_link) . '">';
            $output .= '<img class="city-product-img" src="' . esc_url($product_image) . '" alt="' . esc_attr($product_title) . '">';
            $output .= '<h2>' . esc_html($product_title) . '</h2>';
            $output .= '</a>';
            $output .= '<div class="product-wrap">';
            $output .= '<div class="product-details">';
            $output .= '<div class="product-thc-cbd">';
            $output .= '<p><strong>THC:</strong> ' . esc_html($thc) . '</p>';
            $output .= '<p><strong>CBD:</strong> ' . esc_html($cbd) . '</p>';
            $output .= '</div>';
            $output .= '<div class="product-type-flower">';
            $output .= '<p><strong>Type:</strong> ' . esc_html($type) . '</p>';
            $output .= '<p><strong>Flower Time:</strong> ' . esc_html($flower_time) . '</p>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<a href="' . esc_url($product_link) . '" class="button">View Product</a>';
            $output .= '</div>';
            $output .= '</li>';
        }
        $output .= '</ul>';

        // Restore original post data
        wp_reset_postdata();
    } else {
        $output = __('No products found for the specified city.', 'wpsl');
    }

    return $output;
}

add_shortcode('city_products', 'show_city_products');

add_action('template_redirect', 'redirect_to_cart_after_adding');

function redirect_to_cart_after_adding() {
    if (isset($_GET['add-to-cart']) && isset($_GET['redirect_to_cart']) && $_GET['redirect_to_cart'] == 1) {
        wp_redirect(wc_get_cart_url());
        exit;
    }
}


// Register the shortcode
function display_city_info_shortcode() {
    // Get the repeater field values
    if (isset($_GET['city']) && !empty($_GET['city'])) {
        $city = sanitize_text_field($_GET['city']);
        $term_city = get_term_by('name', $city, 'city');
        
        $city_info = get_field('table_content', 'term_'. $term_city->term_id);
        
        ob_start();

        if( $city_info ) {
            echo '<div class="tab_list_wrap">';
            echo '<ul class="tab_lists">';
            foreach( $city_info as $key => $row ) {
                $city_name = $row['table_title'];
                echo '<li class="tab_list" data-id="'. $key .'">' . esc_html( $city_name ) . '</li>';
            }
            echo '</ul>';
            
            foreach( $city_info as $key => $row ) {
                echo '<div class="tab_content" id="tab-content-'. $key .'">';
                echo $row['table_details'];
                echo '</div>';
            }
            echo '</div>';
        } else {
            
        }
        
    }else{
       
    }
    return ob_get_clean();
}

// Register the shortcode with WordPress
add_shortcode('city_info', 'display_city_info_shortcode');


// Shortcode to display ACF repeater field with Swiper.js based on category term or page
function acf_slider_shortcode() {
    if (is_product_category()) {
        $term = get_queried_object(); // Get the current category term
        $slider_repeater = get_field('slider', $term->taxonomy . '_' . $term->term_id); // Get the repeater field for this term

        if ($slider_repeater) : // Check if the repeater field exists
            ob_start();
            ?>
            <div class="home-slider swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    // Loop through the rows of data
                    foreach ($slider_repeater as $row) {
                        $slider_img = $row['slider_img'];
                        $slider_img_2 = $row['slider_img_2'];
                        $slider_img_3 = $row['slider_img_3'];
                        $slider_img_4 = $row['slider_img_4'];
                        $slider_img_5 = $row['slider_img_5'];
						$slider_img_6 = $row['slider_img_6'];
                        $slider_title = $row['slider_title'];
                        $slider_subtitle = $row['slider_subtitle'];
                        $shop_button = $row['shop_button'];
                        $note = $row['note'];
                        ?>
                        <div class="swiper-slide">
                            <div class="slider-wrap">
                              <div class="slider-content-group" style="background-image: url('<?php echo esc_url($slider_img); ?>'); background-size: cover; background-position: center;">
									<?php if ($slider_title) : ?>
										<h2><?php echo esc_html($slider_title); ?></h2>
									<?php endif; ?>
									<?php if ($slider_subtitle) : ?>
										<p><?php echo esc_html($slider_subtitle); ?></p>
									<?php endif; ?>
									<?php if ($shop_button) : ?>
										<a href="<?php echo esc_url($shop_button); ?>" class="shop-button">Shop Now</a>
									<?php endif; ?>
									<?php if ($note) : ?>
										<p class="note"><?php echo esc_html($note); ?></p>
									<?php endif; ?>
								</div>
                              
                                <div class="slider-img-2-group slider-flex">
                                    <?php if ($slider_img_2) : ?>
                                        <a href="https://eighthsandounces.com/product-category/cannabis-edibles/"> <img src="<?php echo esc_url($slider_img_2); ?>" /></a>
                                    <?php endif; ?>
                                    <?php if ($slider_img_3) : ?>
                                        <a href="https://eighthsandounces.com/product-category/pre-rolls/"><img src="<?php echo esc_url($slider_img_3); ?>" /></a>
                                    <?php endif; ?>
                                    <?php if ($slider_img_4) : ?>
                                        <a href="#"> <img src="<?php echo esc_url($slider_img_4); ?>" /></a>
                                    <?php endif; ?>
                                    <?php if ($slider_img_5) : ?>
                                        <a href="https://eighthsandounces.com/product-category/cannabis-cartridges/"> <img src="<?php echo esc_url($slider_img_5); ?>" /></a>
                                    <?php endif; ?>
									<div class="img-strain-slider">
										<?php if ($slider_img_6) : ?>
                                         <img src="<?php echo esc_url($slider_img_6); ?>" /></a>
                                        <?php endif; ?>
									</div> 
									
                                </div></div></div> <?php }?></div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <?php
            return ob_get_clean();
        endif;
    } else {
        // Default code for regular pages
        if (have_rows('slider')) :
            ob_start();
            ?>
            <div class="home-slider swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    // Loop through the rows of data
                    while (have_rows('slider')) : the_row();
                        $slider_img = get_sub_field('slider_img');
                        $slider_img_2 = get_sub_field('slider_img_2');
                        $slider_img_3 = get_sub_field('slider_img_3');
                        $slider_img_4 = get_sub_field('slider_img_4');
                        $slider_img_5 = get_sub_field('slider_img_5');
						 $slider_img_6 = get_sub_field('slider_img_6');
                        $slider_title = get_sub_field('slider_title');
                        $slider_subtitle = get_sub_field('slider_subtitle');
                        $shop_button = get_sub_field('shop_button');
                        $note = get_sub_field('note');
                        ?>
                        <div class="swiper-slide">
                            <div class="slider-wrap">
                              <div class="slider-content-group" style="background-image: url('<?php echo esc_url($slider_img); ?>'); background-size: cover; background-position: center;">
									<?php if ($slider_title) : ?>
										<h2><?php echo esc_html($slider_title); ?></h2>
									<?php endif; ?>
									<?php if ($slider_subtitle) : ?>
										<p><?php echo esc_html($slider_subtitle); ?></p>
									<?php endif; ?>
									<?php if ($shop_button) : ?>
										<a href="<?php echo esc_url($shop_button); ?>" class="shop-button">Shop Now</a>
									<?php endif; ?>
									<?php if ($note) : ?>
										<p class="note"><?php echo esc_html($note); ?></p>
									<?php endif; ?>
								</div>

                           
                                <div class="slider-img-2-group slider-flex">
                                    <?php if ($slider_img_2) : ?>
                                        <a href="https://eighthsandounces.com/product-category/cannabis-edibles/"> <img src="<?php echo esc_url($slider_img_2); ?>" /></a>
                                    <?php endif; ?>
                                    <?php if ($slider_img_3) : ?>
                                        <a href="https://eighthsandounces.com/product-category/pre-rolls/"><img src="<?php echo esc_url($slider_img_3); ?>" /></a>
                                    <?php endif; ?>
                                    <?php if ($slider_img_4) : ?>
                                        <a href="#"> <img src="<?php echo esc_url($slider_img_4); ?>" /></a>
                                    <?php endif; ?>
                                    <?php if ($slider_img_5) : ?>
                                        <a href="https://eighthsandounces.com/product-category/cannabis-cartridges/"> <img src="<?php echo esc_url($slider_img_5); ?>" /></a>
                                    <?php endif; ?>
									<div class="img-strain-slider">
										<?php if ($slider_img_6) : ?>
                                         <img src="<?php echo esc_url($slider_img_6); ?>" /></a>
                                        <?php endif; ?>
									</div> 
                                </div></div> </div> <?php endwhile;?></div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <?php
            return ob_get_clean();
        endif;
    }
}
add_shortcode('acf_slider', 'acf_slider_shortcode');

// Create the shortcode to display recently viewed products with Swiper slider
if (!function_exists('custom_recently_viewed_products_shortcode')) {
    add_shortcode('custom_recently_viewed_products_home', 'custom_recently_viewed_products_shortcode');
    function custom_recently_viewed_products_shortcode($atts) {
        if (empty($_SESSION['custom_recently_viewed_products'])) {
            return '<div class="no-recently-viewed-products">
                        <img src="/wp-content/uploads/2024/09/Layer-43.png">
                        <h2>No Items Recently Viewed</h2>
                        <p>Get back to shopping - check out our weekly ad for the latest sales.</p>
                    </div>';
        }

        $recently_viewed = $_SESSION['custom_recently_viewed_products'];

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 10,
            'post__in' => $recently_viewed,
            'orderby' => 'post__in'
        );

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return '<div class="no-recently-viewed-products">
                        <h2>No recently viewed products</h2>
                        <p>Get back to shopping - check out our <a href="/weekly-ad">weekly ad</a> for the latest sales.</p>
                    </div>';
        }

        ob_start();

        echo '<div class="recentview-container-home swiper-container">';
        echo '<div class="swiper-wrapper">';

        while ($query->have_posts()) : $query->the_post();
            global $product;

            echo '<div class="swiper-slide">';
            echo '<li>';
            echo '<a href="' . get_permalink() . '">';
            echo woocommerce_get_product_thumbnail(); // Product image
            echo '</a>';
            echo '<h2 class="woocommerce-loop-product__title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            echo '</li>';
            echo '</div>';

        endwhile;

        echo '</div>'; // .swiper-wrapper
        echo '</div>'; // .swiper-container

        wp_reset_postdata();

        return ob_get_clean();
    }
}



// Shortcode to display the review count and average rating of the current product
function display_current_product_review_info() {
    if ( ! is_product() ) {
        return 'This shortcode can only be used on product pages.';
    }

    global $product;

    if ( ! $product ) {
        return 'Product not found.';
    }

    // Get the review count and average rating
    $review_count = $product->get_review_count();
    $average_rating = $product->get_average_rating();

    // Generate the star icons
    $star_rating_html = wc_get_rating_html( $average_rating );

    // Format the output
    $output = sprintf(
        '<div class="product-review-info">
            <div class="star-rating">%s</div>
            <div class="review-text"> %.1f out of 5 (%d reviews)</div>
        </div>',
        $star_rating_html,
        $average_rating,
        $review_count
    );

    return $output;
}
add_shortcode( 'current_product_review_info', 'display_current_product_review_info' );

function display_faqs_shortcode($atts) {
    $product_id = get_the_ID();
    $counter = 0;
    // Get the product categories based on the product ID
    $product_categories = get_the_terms($product_id, 'product_cat');
    $product_categories_ids = array();

    if ($product_categories && !is_wp_error($product_categories)) {
        foreach ($product_categories as $category) {
            $product_categories_ids[] = $category->term_id;
        }
    }

    // Query FAQ posts
   /* $args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'faq_categories', // ACF field key for taxonomy
                'value' => $product_categories_ids,
                'compare' => 'IN',
            ),
        ),
    );*/
    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'OR',  // Use 'OR' to search for any of the category values
        ),
       
    );
    foreach ( $product_categories_ids as $category_id ) {
        $args['meta_query'][] = array(
            'key'     => 'faq_categories',
            'value'   => '"' . $category_id . '"',  // Wrap each ID in quotes to match serialized data
            'compare' => 'LIKE',
        );
    }

    $faq_query = new WP_Query($args);
    ob_start();
   
    if ($faq_query->have_posts()) {
        
        echo '<div class="faqs">';

        while ($faq_query->have_posts()) {
            $faq_query->the_post();

            if ($counter % 2 == 0) {
                if ($counter > 0) {
                    echo '</div>'; // Close the previous main div
                }
                echo '<div class="faq-pair">';
            }

            // Use the provided image URL inside the <h2> tag
            $image_html = '<img src="/wp-content/uploads/2024/07/Question.png" alt="Question Icon" style="vertical-align: middle; margin-right: 5px;">';

            echo '<div class="faq">';
            echo '<h2>' . $image_html . get_the_title() . '</h2>';
            echo '<div class="faq-description">' . get_the_content() . '</div>';
            echo '</div>';

            $counter++;
        }

        echo '</div>'; // Close the last main div
        echo '</div>'; // Close the faqs wrapper

        // Only show "See More" button if there are more than 8 FAQs
        if ($counter > 8) {
            echo '<button id="see-more-faqs">See More</button>';
        }
    } else {
        echo '<p class="not-found-p">No FAQs found.</p>';
    }

    wp_reset_postdata();

    ?>
    <script type="text/javascript">
        (function($) {
            if( $('.faqs').length ){
                var totalFaqs = <?php echo $counter; ?>;
                var visibleFaqs = 8;
                $('#see-more-faqs').click(function() {
                    $('.faq-pair').slice(visibleFaqs / 2, visibleFaqs / 2 + 2).slideDown();
                    visibleFaqs += 8;
                    if (visibleFaqs >= totalFaqs) {
                        $(this).hide();
                    }
                });
                $(document).ready(function() {
                    $('.faq-pair').slice(8 / 2).hide();
                });
        }
        })(jQuery);
    </script>
    <?php 
    return ob_get_clean();
}

add_shortcode('display_faqs', 'display_faqs_shortcode');

function count_faqs_by_category() {
    if (!is_singular('product')) {
        return '';
    }

    $product_categories = get_the_terms(get_the_ID(), 'product_cat');
    if (empty($product_categories) || is_wp_error($product_categories)) {
        return '';
    }

    // Extract category IDs
    $product_category_ids = wp_list_pluck($product_categories, 'term_id');
    // print_r($product_category_ids);
    // Prepare the query to fetch FAQs
    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'OR',  // Use 'OR' to search for any of the category values
        ),
       
    );
    foreach ( $product_category_ids as $category_id ) {
        $args['meta_query'][] = array(
            'key'     => 'faq_categories',
            'value'   => '"' . $category_id . '"',  // Wrap each ID in quotes to match serialized data
            'compare' => 'LIKE',
        );
    }

    $faq_query = new WP_Query($args);
    $faq_count = $faq_query->found_posts;

    // Return the count output
    return '<div class="faq-count">(' . $faq_count . ') Questions & Answers</div>';
}


add_shortcode('faq_count', 'count_faqs_by_category');


function register_sale_product_widget() {
    register_widget('Sale_Product_Widget');
}
add_action('widgets_init', 'register_sale_product_widget');

class Sale_Product_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'sale_product_widget',
            __('Sale Product Widget', 'text_domain'),
            array('description' => __('Displays products on sale with a countdown timer', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];

        // Countdown timer and sale products logic here
        $this->display_sale_products();

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Sale Products', 'text_domain');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

    private function display_sale_products() {
        // Sale product fetching logic here
        $current_time = current_time('timestamp');
        
        $args = array(
            'post_type' => 'product',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'NUMERIC'
                ),
                array(
                    'key' => '_sale_price_dates_to',
                    'value' => $current_time,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                )
            )
        );
        
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                global $product;
                $end_date = get_post_meta(get_the_ID(), '_sale_price_dates_to', true);

                echo '<li>' . get_the_title();
                echo ' <span class="sale-countdown" data-end-date="' . date('Y-m-d H:i:s', $end_date) . '"></span>';
                echo '</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>No sale products found.</p>';
        }
    }

}


function sale_products_with_countdown_shortcode($atts) {
    ob_start();

    $current_time = current_time('timestamp');
    
    $args = array(
        'post_type' => 'product',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC'
            ),
            array(
                'key' => '_sale_price_dates_to',
                'value' => $current_time,
                'compare' => '>=',
                'type' => 'NUMERIC'
            )
        )
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        echo '<div class="sale-countdown-container">';
        echo '<div class="swiper-wrapper">'; // Swiper wrapper start
        while ($query->have_posts()) {
            $query->the_post();
            global $product;
            $end_date = get_post_meta(get_the_ID(), '_sale_price_dates_to', true);
            $product_image = get_the_post_thumbnail(get_the_ID(), 'medium'); // Get the product image
            $product_title = get_the_title(); // Get the product title

            echo '<div class="swiper-slide">'; // Swiper slide start
            echo '<span class="sale-countdown" data-end-date="' . date('Y-m-d H:i:s', $end_date) . '"></span>'; // Countdown timer
            echo $product_image; // Product image
            echo '<h3>' . $product_title . '</h3>'; // Product title
            echo '</div>'; // Swiper slide end
        }
        echo '</div>'; // Swiper wrapper end

        // Add navigation buttons if needed
        echo '<div class="swiper-button-next"></div>';
        echo '<div class="swiper-button-prev"></div>';
        echo '</div>'; // Swiper container end
        
        wp_reset_postdata();
    } else {
        echo '<p>No sale products found.</p>';
    }

    // Include JavaScript for countdown and swiper initialization
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Swiper
            var swiper = new Swiper('.sale-countdown-container', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                loop: true,
                slidesPerView: 2,
                spaceBetween: 10,
                // Add more swiper options as needed
            });

            // Countdown timer script
            document.querySelectorAll('.sale-countdown').forEach(function (element) {
                var endDate = new Date(element.dataset.endDate).getTime();
                var countdown = setInterval(function () {
                    var now = new Date().getTime();
                    var distance = endDate - now;
                    
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    element.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                    
                    if (distance < 0) {
                        clearInterval(countdown);
                        element.innerHTML = "Sale ended";
                    }
                }, 1000);
            });
        });
    </script>
    <?php

    return ob_get_clean();
}
add_shortcode('sale_products_with_countdown', 'sale_products_with_countdown_shortcode');


function custom_express_checkout_redirect() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.express-checkout-button').on('click', function(e) {
                e.preventDefault();
                
                // Get the form closest to the button, assuming each button is associated with a product
                var form = $(this).closest('form.cart');
                var formData = form.serialize();

                $.post(form.attr('action'), formData, function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }
                    window.location = '<?php echo wc_get_checkout_url(); ?>';
                });
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_express_checkout_redirect');



function custom_product_filter() {
    ob_start();
    $current_cat_id = get_queried_object_id(); ?>
    <div class="product-filter">
        <div class="filtered-options-list">
            <ul class="filtered-items"></ul>
        </div>
				
        <div class="filter-checkbox-group">
            <h4 class="toggle-header">Strain Type	 <span class="toggle-icon">+</span></h4>
            <div class="checkbox-content">
                <label for="Hybrid"><input type="checkbox" name="cat_type[]" value="Hybrid"> Hybrid</label>
                <label for="Sativa"><input type="checkbox" name="cat_type[]" value="Sativa"> Sativa</label>
                <label for="Indica"><input type="checkbox" name="cat_type[]" value="Indica"> Indica</label>
            </div>
        </div>
		
        <div class="filter-checkbox-group">
            <h4 class="toggle-header">By Effect <span class="toggle-icon">+</span></h4>
            <div class="checkbox-content">
                <?php
                $effects = get_terms(array(
                    'taxonomy'   => 'typical-effects',
                    'hide_empty' => false,
                ));
                if ($effects && !is_wp_error($effects)) {
                    foreach ($effects as $effect) {
                        echo '<label for="'. esc_html($effect->name) .'"><input type="checkbox" name="typical_effects[]" value="' . esc_attr($effect->term_id) . '">' . esc_html($effect->name) . '</label>';
                    }
                }
                ?>
            </div>
        </div>
        
        <div class="filter-checkbox-group">
            <input type="hidden" name="current_cat" value="<?php echo esc_attr($current_cat_id); ?>" />
            <div class="checkbox-group" data-title="Category">
                <h4 class="toggle-header">Product type <span class="toggle-icon">+</span></h4>
                <div class="checkbox-content">
                    <?php 
                    $current_category = get_queried_object();
                    if ($current_category && is_a($current_category, 'WP_Term')) { 
                        $subcategories = get_terms('product_cat', array(
                            'hide_empty' => false,
                            'parent'     => $current_category->term_id  
                        ));
                        if (!empty($subcategories)) {
                            foreach ($subcategories as $subcat) {
                                echo '<label for="' . esc_html($subcat->name) . '"><input type="checkbox" name="cat_name[]" value="' . esc_attr($subcat->term_id) . '">' . esc_html($subcat->name) . '</label>';
                            }
                        } else {
                            echo '<p>No subcategories available</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-checkbox-group">
            <h4 class="toggle-header">Psychoactive Level <span class="toggle-icon">+</span></h4>
            <div class="checkbox-content">
                <?php echo '<label for="Low THC (0% - 10%)"><input type="checkbox" name="thc[]" value="low">Low THC 0% - 10%</label>';
				echo '<label for="Medium THC (10% - 20%)"><input type="checkbox" name="thc[]" value="medium">Medium THC 10% - 20%</label>';
				echo '<label for="High THC (20% - 30%)"><input type="checkbox" name="thc[]" value="high">High THC 20% - 30%</label>'; ?>
            </div>
        </div>

        <button id="apply-filters">Apply Filters</button>
        <!-- Area to display selected filters -->
        <div id="selected-filters"></div>

        <!-- Area to show the filtered products -->
        <div id="filtered-products"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleHeaders = document.querySelectorAll('.toggle-header');
            toggleHeaders.forEach(header => {
                header.addEventListener('click', function () {
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.toggle-icon');
                    if (content) {
                        const isVisible = content.style.display === 'block';
                        content.style.display = isVisible ? 'none' : 'block';
                        icon.textContent = isVisible ? '+' : 'x';
                    }
                });
            });
            const allContents = document.querySelectorAll('.checkbox-content');
            allContents.forEach(content => content.style.display = 'none');
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_product_filter', 'custom_product_filter');

// AJAX action hook for logged-in and guest users
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

function filter_products() {
    // Validate nonce
    check_ajax_referer('custom_ajax_nonce', 'nonce');

    $cat_name = isset($_POST['cat_name']) ? array_map('intval', $_POST['cat_name']) : array();
	$cat_type = isset($_POST['cat_type']) ? array_map('sanitize_text_field', $_POST['cat_type']) : array();
	$typical_effects = isset($_POST['typical_effects']) ? array_map('intval', $_POST['typical_effects']) : array();
	$common_usage = isset($_POST['common_usage']) ? array_map('intval', $_POST['common_usage']) : array();
	$flavors = isset($_POST['flavors']) ? array_map('sanitize_text_field', $_POST['flavors']) : array();
	$thc = isset($_POST['thc']) ? array_map('sanitize_text_field', $_POST['thc']) : array();
	$current_cat_id = isset($_POST['current_cat']) ? intval($_POST['current_cat']) : 0;

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'tax_query'      => array(
			'relation' => 'AND',
		),
		'meta_query'     => array(
			'relation' => 'AND',
		),
	);

	if (!empty($current_cat_id)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $current_cat_id,
		);
	}

	if (!empty($cat_name)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $cat_name,
			'operator' => 'IN',
		);
	}

	$or_tax_queries = array('relation' => 'OR');

	if (!empty($typical_effects)) {
		$or_tax_queries[] = array(
			'taxonomy' => 'typical-effects',
			'field'    => 'term_id',
			'terms'    => $typical_effects,
			'operator' => 'IN',
		);
	}

	if (!empty($common_usage)) {
		$or_tax_queries[] = array(
			'taxonomy' => 'common-usage',
			'field'    => 'term_id',
			'terms'    => $common_usage,
			'operator' => 'IN',
		);
	}

	if (!empty($flavors)) {
		$or_tax_queries[] = array(
			'taxonomy' => 'flavors',
			'field'    => 'slug',
			'terms'    => $flavors,
			'operator' => 'IN',
		);
	}

	if (count($or_tax_queries) > 1) {
		$args['tax_query'][] = $or_tax_queries;
	}

	if (!empty($cat_type)) {
		$args['meta_query'][] = array(
			'key'     => 'type',
			'value'   => $cat_type,
			'compare' => 'IN',
		);
	}

	if (!empty($thc)) {
		$thc_conditions = array('relation' => 'OR');

		if (in_array('low', $thc)) {
			$thc_conditions[] = array(
				'key'     => 'htc_progress_bar',
				'value'   => 10,
				'compare' => '<',
				'type'    => 'NUMERIC',
			);
		}

		if (in_array('medium', $thc)) {
			$thc_conditions[] = array(
				'key'     => 'htc_progress_bar',
				'value'   => array(11, 20),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC',
			);
		}

		if (in_array('high', $thc)) {
			$thc_conditions[] = array(
				'key'     => 'htc_progress_bar',
				'value'   => array(21, 30),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC',
			);
		}

		$args['meta_query'][] = $thc_conditions;
	}

	// Execute the query
	$query = new WP_Query($args);


	if ($query->have_posts()) {
		ob_start();
		while ($query->have_posts()) {
			$query->the_post(); 
			$product = wc_get_product( get_the_ID() );
			$product_title = get_the_title();
			$product_url = get_permalink();
			$product_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
			$product_price = $product->get_price_html();
			$product_categories = wc_get_product_category_list( $product->get_id() );
			
			?>
            <li class="wp-block-post post-<?php the_ID(); ?> product type-product status-publish has-post-thumbnail filter-product">
                <div class="wp-block-group">
                    <!-- Product Image -->
                    <div data-block-name="woocommerce/product-image">
                        <a href="<?php echo esc_url( $product_url ); ?>">
                            <img src="<?php echo  ('' != $product_image) ? esc_url( $product_image ) : get_stylesheet_directory_uri() . '/img/unnamed.jpg'; ?>" alt="<?php echo esc_attr( $product_title ); ?>" style="max-width:none;height:300px;object-fit:fill;" />
                        </a>
                    </div>
				<div class="fillter-product-details">
                    <div class="wp-block-group">
                        <h2 class="has-link-color wp-block-post-title"><?php echo esc_html( $product_title ); ?></h2>
                    </div>
                    <div class="taxonomy-product_cat wp-block-post-terms">
                        <?php echo $q
                        
                        ; ?>
                    </div>
                    <div class="wp-block-group is-nowrap f-block">
                        <p class="has-contrast-3-color">From</p>
                        <div class="wp-block-woocommerce-product-price">
                            <span class="woocommerce-Price-amount amount">
                                <bdi><?php echo $product_price; ?></bdi>
                            </span>
                        </div>
                    </div>
                    <a class="single-read-more" href="<?php echo esc_url( $product_url ); ?>" target="_self">View Product</a>
					</div>
                </div>
            </li>
<?php
		}
		wp_reset_postdata();
		$response = ob_get_clean();
		wp_send_json(array('html' => $response));
	} else {
		wp_send_json(array('html' => '<p>No Product Found.</p>'));
	}
	wp_die();
}
function get_current_subcategories() {
    if (is_product_category()) {
        $term = get_queried_object();
        $args = array(
            'taxonomy' => 'product_cat',
            'child_of' => $term->term_id,
            'hide_empty' => false,
        );
        $subcategories = get_categories($args);
        return $subcategories;
    }
    return array();
}

// Example code to display subcategories in a dropdown
function display_subcategories_dropdown() {
    $subcategories = get_current_subcategories();
    if ($subcategories) {
        echo '<select id="cat_name">';
        foreach ($subcategories as $subcategory) {
            echo '<option value="' . esc_attr($subcategory->slug) . '">' . esc_html($subcategory->name) . '</option>';
        }
        echo '</select>';
    }
}

// Fix for 'choices' field warning in ACF
function get_field_choices($field_name) {
    $field = get_field_object($field_name);
    if (isset($field['choices']) && is_array($field['choices'])) {
        return $field['choices'];
    }
    return array();
}
add_action('woocommerce_before_calculate_totals', 'custom_discount_after_10th_product', 10, 1);

function custom_discount_after_10th_product($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    // Loop through each item in the cart
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product_quantity = $cart_item['quantity'];
        $original_price = $cart_item['data']->get_regular_price(); // Use regular price to reset correctly

        // Apply discount only if the quantity of this product is greater than 10
        if ($product_quantity > 10) {
            // Apply a 15% discount to this product
            $discounted_price = $original_price * 0.85;
            $cart_item['data']->set_price($discounted_price);

            // Store discount flag in session to show in cart totals
            WC()->session->set('custom_discount_notice', true);
        } else {
            // Remove session notice if no discount is applied
            WC()->session->__unset('custom_discount_notice');
        }
    }
}

// Add custom discount notice in cart totals section (right side)
add_action('woocommerce_cart_totals_after_order_total', 'show_discount_notice_in_cart_totals', 10);

function show_discount_notice_in_cart_totals() {
    if (WC()->session->get('custom_discount_notice')) {
        echo '<tr class="order-discount">
                <th style="color: #000;">Bulk Discount</th>
                <td style="color: #000;"><strong>15% Discount Applied for 11+ Items</strong></td>
              </tr>';
    }
}


add_action('wp_ajax_check_cart_and_apply_discount', 'check_cart_and_apply_discount');
add_action('wp_ajax_nopriv_check_cart_and_apply_discount', 'check_cart_and_apply_discount');
function check_cart_and_apply_discount() {
    // Verify the nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'custom_ajax_nonce')) {
        wp_send_json_error(array('message' => 'Invalid request.'));
    }

    // Define time window (24-hour format)
    $start_time = strtotime('15:20');
    $end_time = strtotime('16:20');
    $current_time = current_time('timestamp');

    // Check if location services are enabled
    $location_enabled = isset($_COOKIE['location_enabled']) && $_COOKIE['location_enabled'] === 'true';

    // Check if cart has items
    if (WC()->cart->is_empty()) {
        wp_send_json_error(array('message' => 'Your cart is empty.'));
    }

    // Apply coupon if conditions are met
    if ($current_time >= $start_time && $current_time <= $end_time && $location_enabled) {
        $coupon_code = '10PERCENTDISCOUNT'; // Replace with your coupon code
        if (!WC()->cart->has_discount($coupon_code)) {
            WC()->cart->add_discount($coupon_code);
        }
    }

    wp_send_json_success(array('message' => 'Coupon applied! Redirecting to cart.'));
}
function remove_image_zoom_support() 
{ remove_theme_support( 'wc-product-gallery-zoom' ); 
} 
add_action( 'wp', 'remove_image_zoom_support', 100 );
// Apply 15% discount for new customers based on their email
add_action('woocommerce_cart_calculate_fees', 'apply_email_based_new_customer_discount', 10, 1);

function apply_email_based_new_customer_discount($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    if ($cart->is_empty()) return;

    // Get the email from session or customer data
    $email = WC()->session->get('customer_email');
    if (empty($email)) {
        $email = WC()->customer->get_billing_email();
        if (empty($email)) {
            $email = WC()->session->get('guest_email'); // Use guest email if available
        }
        if (!empty($email)) {
            WC()->session->set('customer_email', $email);
        }
    }
    
    if (empty($email)) return;
    
    // Check if customer has previous orders
    if (!wc_customer_has_orders($email)) {
        $discount = $cart->get_cart_contents_total() * 0.15;
        $cart->add_fee(__('15% Off for New Customers', 'text-domain'), -$discount, false);
    }
}

function wc_customer_has_orders($email) {
    $customer = get_user_by('email', $email);
    if ($customer) {
        return wc_get_customer_order_count($customer->ID) > 0;
    }
    return !empty(wc_get_orders(['billing_email' => $email, 'limit' => 1]));
}

// Capture billing email during checkout
add_action('woocommerce_checkout_update_order_review', 'capture_email_on_checkout');
function capture_email_on_checkout($posted_data) {
    parse_str($posted_data, $data);
    if (!empty($data['billing_email'])) {
        $email = sanitize_email($data['billing_email']);
        WC()->session->set('customer_email', $email);
        WC()->session->set('guest_email', $email); // Store guest email
    }
}

// Ensure email is stored at checkout
add_action('woocommerce_after_checkout_form', 'ensure_email_is_in_session');
function ensure_email_is_in_session() {
    $email = WC()->customer->get_billing_email();
    if (empty($email)) {
        $email = WC()->session->get('guest_email');
    }
    if (!empty($email)) {
        WC()->session->set('customer_email', $email);
    }
}


// // Apply 10% Happy Hour discount on the cart page only
// add_action('woocommerce_cart_calculate_fees', 'apply_happy_hour_discount');
// function apply_happy_hour_discount() {
//     // Exit if in admin panel and not doing AJAX
//     if (is_admin() && !defined('DOING_AJAX')) return;

//     // Only run on cart page
//     if (!is_cart()) return;

//     // Get current time in site’s timezone (New York)
//     $current_time = current_time('H:i');
//      $happy_hour_start = '15:15';
//     $happy_hour_end = '16:20';

//     // Check if current time is within Happy Hour
//     if ($current_time >= $happy_hour_start && $current_time <= $happy_hour_end) {
//         $discount = WC()->cart->subtotal * 0.10;
//         WC()->cart->add_fee('Happy Hour Discount (10%)', -$discount);
//     }
// }

// add_action('wp_footer', 'happy_hour_banner');
// function happy_hour_banner() {
//     $current_time = current_time('H:i');
//     $happy_hour_start = '15:15';
//     $happy_hour_end = '16:20';

//     // Output HTML only if within Happy Hour
//     if ($current_time >= $happy_hour_start && $current_time <= $happy_hour_end) {
//         echo '<div id="happy-hour-banner" style="display:none; background: #f0c420; color: black; text-align: center; padding: 10px; position: fixed; bottom: 0; width: 100%; z-index: 1000; border-top: 3px solid red;">
//                 🎉 Happy Hour: 10% Off Applied Automatically! 🕒
//               </div>';
//         // Include the script to show it only on the cart page
//         echo '<script>
//             document.addEventListener("DOMContentLoaded", function() {
//                 if (document.body.classList.contains("woocommerce-cart")) {
//                     document.getElementById("happy-hour-banner").style.display = "block";
//                 }
//             });
//         </script>';
//     }
// }

add_action('woocommerce_cart_calculate_fees', 'prevent_combined_discounts');
function prevent_combined_discounts() {
    $applied_fees = WC()->cart->get_fees();

    if (count($applied_fees) > 1) {
        // If both discounts are applied, keep only the larger one
        $largest_discount = max(array_map(function($fee) {
            return abs($fee->amount);
        }, $applied_fees));

        // Remove smaller discounts
        foreach ($applied_fees as $fee) {
            if (abs($fee->amount) < $largest_discount) {
                WC()->cart->remove_fee($fee->id);
            }
        }
    }
}

add_action('woocommerce_before_checkout_form', 'show_new_customer_discount_message');
function show_new_customer_discount_message() {
    echo '<div class="woocommerce-info" style="margin-bottom: 20px;">
            New customers get 15% off their first purchase! Enter your email at checkout to qualify.
          </div>';
}
add_action('woocommerce_removed_coupon', 'recalculate_cart_after_coupon_removed');
function recalculate_cart_after_coupon_removed() {
    WC()->cart->calculate_totals(); // Force cart recalculation
}

add_action('woocommerce_cart_calculate_fees', 'notify_new_customer_discount_reapplied');
function notify_new_customer_discount_reapplied() {
    if (did_action('woocommerce_removed_coupon')) {
        wc_add_notice(__('15% Off for New Customers has been reapplied!', 'text-domain'), 'success');
    }
}

add_action('woocommerce_checkout_process', 'prevent_duplicate_orders_by_name');

function prevent_duplicate_orders_by_name() {
    if (is_admin()) return; // Prevent execution in the admin area

    // Get the customer's first and last name from the checkout form
    $first_name = sanitize_text_field($_POST['billing_first_name']);
    $last_name  = sanitize_text_field($_POST['billing_last_name']);

    if (empty($first_name) || empty($last_name)) return; // Avoid processing if empty

    // Query WooCommerce orders to check for duplicates
    $args = array(
        'status' => array('wc-processing', 'wc-completed', 'wc-on-hold'), // Only check valid orders
        'limit' => -1, // Get all matching orders
        'billing_first_name' => $first_name,
        'billing_last_name'  => $last_name,
    );

    $orders = wc_get_orders($args);

    if (!empty($orders)) {
        wc_add_notice(__('A recent order with this name already exists. Please check your order history before proceeding.'), 'error');
    }
}

function display_product_slider($category) {
    ob_start();

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        'product_cat' => $category,
        'meta_query' => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock'
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <div class="swiper-container-<?php echo esc_attr($category); ?> cat_slider">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    global $product; ?>
                    <div class="swiper-slide">
                        <div class="product-card">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
                                <h3><?php the_title(); ?></h3>
                            </a>
                            <p><?php echo $product->get_price_html(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="view-product">View Product</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

        <script>
           document.addEventListener("DOMContentLoaded", function() {
                new Swiper(".swiper-container-<?php echo esc_attr($category); ?>", {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
						1260: { slidesPerView: 5 },
						1024: { slidesPerView: 3 },
                        768: { slidesPerView: 2 },
                        480: { slidesPerView: 1 }
                    }
                });
            });
        </script>
    <?php endif;

    wp_reset_postdata();
    return ob_get_clean();
}

// Shortcodes for each category
function cannabis_flower_shortcode() { return display_product_slider('cannabis-flower'); }
function cannabis_edibles_shortcode() { return display_product_slider('cannabis-edibles'); }
function cannabis_accessories_shortcode() { return display_product_slider('cannabis-accessories'); }
function pre_rolls_shortcode() { return display_product_slider('pre-rolls'); }
function cannabis_cartridges_shortcode() { return display_product_slider('cannabis-cartridges'); }


add_shortcode('cannabis_flower', 'cannabis_flower_shortcode');
add_shortcode('cannabis_edibles', 'cannabis_edibles_shortcode');
add_shortcode('cannabis_accessories', 'cannabis_accessories_shortcode');
add_shortcode('pre_rolls', 'pre_rolls_shortcode');
add_shortcode('cannabis-cartridges', 'cannabis_cartridges_shortcode');
add_action('woocommerce_applied_coupon', 'check_coupon_and_add_product', 10, 1);

add_action('woocommerce_applied_coupon', 'check_coupon_and_add_product', 10, 1);

function check_coupon_and_add_product($coupon_code) {
    if ($coupon_code === 'myfreebie') { // Check if the applied coupon is "MYFREEBIE"
        $additional_product_id = 36455; // Change this to the ID of the product you want to add

        // Ensure only one promo item is added per order
        $found = false;
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            if ($cart_item['product_id'] == $additional_product_id) {
                $found = true;
                // Ensure quantity remains 1 and disable input field
                WC()->cart->set_quantity($cart_item_key, 1);
                break;
            }
        }

        if (!$found) {
            $cart_item_key = WC()->cart->add_to_cart($additional_product_id);
            if ($cart_item_key) {
                WC()->cart->set_quantity($cart_item_key, 1);
            }
        }

        // Show promo pop-up message
        wc_add_notice(__('Free 1/8th with purchase for new customers.', 'woocommerce'), 'success');
    }
}

add_action('woocommerce_removed_coupon', 'remove_freebie_if_coupon_removed', 10, 1);

function remove_freebie_if_coupon_removed($coupon_code) {
    if ($coupon_code === 'myfreebie') { // If the coupon is removed
        $additional_product_id = 36455;
        
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            if ($cart_item['product_id'] == $additional_product_id) {
                WC()->cart->remove_cart_item($cart_item_key); // Remove the freebie
            }
        }
    }
}

// Backend check for duplicate emails or phone numbers to prevent repeat claims
add_action('woocommerce_checkout_process', 'validate_new_customer_for_promo');

function validate_new_customer_for_promo() {
    if (isset(WC()->cart) && WC()->cart->has_discount('myfreebie')) {
        $billing_email = sanitize_email($_POST['billing_email']);
        $billing_phone = isset($_POST['billing_phone']) ? preg_replace('/\D/', '', $_POST['billing_phone']) : '';

        $query_args = [
            'limit' => -1,
            'status' => ['completed', 'processing'],
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_billing_email',
                    'value' => $billing_email,
                    'compare' => '='
                ],
                [
                    'key' => '_billing_phone',
                    'value' => $billing_phone,
                    'compare' => '='
                ]
            ]
        ];
        
        $existing_orders = wc_get_orders($query_args);

        if (!empty($existing_orders)) {
            wc_add_notice(__('This promo is only available for new customers.', 'woocommerce'), 'error');
        }
    }
}


// Add Shortcode to get WooCommerce Product ID and Full Description with 3 lines preview
function custom_get_product_id_and_full_description_shortcode( $atts ) {
    // Extract the attributes from the shortcode
    $atts = shortcode_atts(
        array(
            'id' => null, // Default is null
        ),
        $atts,
        'get_product_id_and_full_description'
    );

    // If no ID is provided, try to get the current product ID
    if ( ! $atts['id'] ) {
        global $post;
        if ( isset( $post->ID ) && get_post_type( $post->ID ) === 'product' ) {
            $atts['id'] = $post->ID;
        } else {
            return 'Product ID is required or not found.';
        }
    }

    // Get the product object
    $product = wc_get_product( $atts['id'] );

    // Validate if product is found
    if ( !$product ) {
        return 'Product not found.';
    }

    // Get product full description
    $full_description = $product->get_description();

    // Truncate the full description to 3 lines (approx 50 words)
    $max_words = 30;
    $full_description_preview = wp_trim_words( $full_description, $max_words, '...' );
    
    // Check if the full description exceeds the preview limit
    $word_count = str_word_count( strip_tags( $full_description ) );
    
    // Determine whether to show the "View More" link
    $show_view_more = $word_count > $max_words;

    // Prepare HTML content with "View More" toggle (if necessary)
    $html = '<div class="product-description-preview">';
    $html .= '<p class="description-preview">' . esc_html($full_description_preview) . '</p>';

    // Only show the full description and "View More" button if necessary
    $html .= '<p class="description-full" style="display:none;">' . esc_html($full_description) . '</p>';

    // Add "View More" link if the description exceeds the word limit
    if ($show_view_more) {
        $html .= '<span class="read-more-toggle"><a href="javascript:void(0);" class="view-more">View More Details</a></span>';
    }
    
    $html .= '</div>';

    // Add JavaScript for toggle functionality
    $html .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".read-more-toggle .view-more").forEach(function(button) {
                button.addEventListener("click", function() {
                    var parent = this.closest(".product-description-preview");
                    var fullDesc = parent.querySelector(".description-full");
                    var previewDesc = parent.querySelector(".description-preview");
                    if (fullDesc.style.display === "none") {
                        fullDesc.style.display = "block";
                        previewDesc.style.display = "none";
                        this.textContent = "View Less";
                    } else {
                        fullDesc.style.display = "none";
                        previewDesc.style.display = "block";
                        this.textContent = "View More Details";
                    }
                });
            });
        });
    </script>';

    return $html;
}
add_shortcode( 'get_product_id_and_full_description', 'custom_get_product_id_and_full_description_shortcode' );

//add_action( 'template_redirect', 'redirect_to_checkout_if_minimum_met' );

function redirect_to_checkout_if_minimum_met() {
    $minimum = 50; // Set minimum amount
    
    if ( is_cart() ) {
        // If cart total is 50 or more, redirect to checkout
        if ( WC()->cart->total >= $minimum ) {
            wp_safe_redirect( wc_get_checkout_url() );
            exit;
        }
    }
}

//add_action( 'woocommerce_check_cart_items', 'restrict_checkout_if_below_minimum' );

function restrict_checkout_if_below_minimum() {
    $minimum = 50; // Set minimum amount

    if ( WC()->cart->total < $minimum ) {
        wc_add_notice( sprintf( 'You must have a minimum order of $%s before proceeding to checkout.', $minimum ), 'error' );

        // Prevent direct checkout access
        if ( is_checkout() && !is_wc_endpoint_url('order-received') ) {
            wp_safe_redirect( wc_get_cart_url() );
            exit;
        }
    }
}

// Modify product name on checkout based on custom meta
add_action('woocommerce_checkout_create_order_line_item', 'overwrite_product_name_with_quantity', 20, 4);
function overwrite_product_name_with_quantity($item, $cart_item_key, $values, $order)
{
    $custom_meta_value = '';
    if (isset($values['wut_sunit'][0]['wut_lbl'])) {
        $custom_meta_value = $values['wut_sunit'][0]['wut_lbl'];
    }
 
    $product_name = $item->get_name();
    $new_name = $product_name;
 
    if (!empty($custom_meta_value)) {
        $new_name .= ' - ' . $custom_meta_value;
    }
 
    $item->set_name($new_name);
}
 
add_filter('woocommerce_add_cart_item_data', 'add_custom_unit_to_cart_item', 10, 3);
function add_custom_unit_to_cart_item($cart_item_data, $product_id, $variation_id)
{
    // Get the custom unit from your logic, maybe from $_POST or product meta
    // For example, if passed via custom form field on the product page:
    if (!empty($_POST['wut_sunit'])) {
        $unit_data = wc_clean($_POST['wut_sunit']);
        $cart_item_data['wut_sunit'] = json_decode(stripslashes($unit_data), true);
    }
    return $cart_item_data;
}
 
add_filter('woocommerce_cart_item_name', 'custom_product_name_with_unit_label_checkout', 20, 3);
function custom_product_name_with_unit_label_checkout($product_name, $cart_item, $cart_item_key)
{
    if (!empty($cart_item['wut_sunit'][0]['wut_lbl'])) {
        $unit_label = esc_html($cart_item['wut_sunit'][0]['wut_lbl']);
        $product_name .= ' - ' . $unit_label;
    }
    return $product_name;
}
 
add_filter('woocommerce_get_item_data', 'display_custom_unit_under_name', 10, 2);
function display_custom_unit_under_name($item_data, $cart_item)
{
    if (!empty($cart_item['wut_sunit'][0]['wut_lbl'])) {
        $item_data[] = array(
            'key'   => __('Unit'),
            'value' => esc_html($cart_item['wut_sunit'][0]['wut_lbl']),
        );
    }
    return $item_data;
}
 
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_script('photoswipe-ui-default');
    wp_dequeue_script('photoswipe');
    wp_dequeue_style('photoswipe');
}, 100);

add_action('woocommerce_checkout_process', 'log_checkout_data');
function log_checkout_data() {
    error_log('Checkout Data: ' . print_r($_POST, true));
}
add_action('woocommerce_checkout_process', 'before_order_processing');
function before_order_processing() {
    error_log('Checkout process started');
}

add_action('woocommerce_checkout_create_order', 'before_order_creation', 10, 2);
function before_order_creation($order, $data) {
    error_log('Creating order with data: ' . print_r($data, true));
}

add_action('woocommerce_checkout_order_processed', 'after_order_processed', 10, 1);
function after_order_processed($order_id) {
    error_log('Order created: ' . $order_id);
}

add_action('woocommerce_cart_calculate_fees', 'adjust_total_for_pre_rolls', 20, 1);
function adjust_total_for_pre_rolls($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    $target_slug = 'pre-rolls'; // Make sure this matches your category slug
    $total_pre_roll_qty = 0;
    $pre_roll_total = 0;

    // Calculate total quantity and total value of pre-rolls
    foreach ($cart->get_cart() as $item) {
        $product_id = $item['product_id'];

        if (product_in_pre_roll_or_sub($product_id, $target_slug)) {
            $total_pre_roll_qty += $item['quantity'];
            $pre_roll_total += $item['line_total'];
        }
    }

    // Apply discount ONLY if 3 or more pre-rolls are in cart
    if ($total_pre_roll_qty >= 3) {
        $desired_total = 35.00;
        $difference = $pre_roll_total - $desired_total;

        if ($difference > 0) {
            // Add a negative fee to reduce the total to exactly $35
            $cart->add_fee(__('Pre-Roll Deal Adjustment', 'woocommerce'), -$difference, false);
        }
    }
}

function product_in_pre_roll_or_sub($product_id, $target_slug) {
    $terms = get_the_terms($product_id, 'product_cat');
    if (empty($terms) || is_wp_error($terms)) return false;

    foreach ($terms as $term) {
        if ($term->slug === $target_slug) return true;

        $ancestors = get_ancestors($term->term_id, 'product_cat');
        foreach ($ancestors as $ancestor_id) {
            $ancestor = get_term($ancestor_id, 'product_cat');
            if ($ancestor && $ancestor->slug === $target_slug) {
                return true;
            }
        }
    }

    return false;
}


// Add this action to ensure prices update on quantity changes
add_action('woocommerce_before_calculate_totals', 'update_pre_roll_prices', 10, 1);

function update_pre_roll_prices($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    if (did_action('woocommerce_before_calculate_totals') >= 2) {
        return;
    }
	
}


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}






add_filter('woocommerce_coupon_is_valid', function( $valid, $coupon, $discount ){
    if ( $coupon->get_code() === 'FIRSTTIME1/8' ) {
        
        $free_product_id = 35677;
        $min_amount      = 50;
        
        // Get billing email (guest or logged-in)
        $billing_email = '';
        if ( WC()->cart && WC()->cart->get_customer()->get_billing_email() ) {
            $billing_email = WC()->cart->get_customer()->get_billing_email();
        } elseif ( is_user_logged_in() ) {
            $billing_email = wp_get_current_user()->user_email;
        }
        
        // Restrict to first-time customers by email
        if ( $billing_email ) {
            $existing_orders = wc_get_orders([
                'billing_email' => $billing_email,
                'status'        => array('wc-completed', 'wc-processing'),
                'limit'         => 1,
            ]);
            if ( count($existing_orders) > 0 ) {
                wc_add_notice( 'Sorry, this coupon is only available for first-time customers.', 'error' );
                return false;
            }
        }

        // Check cart subtotal excluding free product
        $subtotal = 0;
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            if ( $cart_item['product_id'] != $free_product_id ) {
                $subtotal += $cart_item['line_subtotal'];
            }
        }
        if ( $subtotal < $min_amount ) {
            wc_add_notice( 'You need at least $50 in products (excluding the free gift) to use this coupon.', 'error' );
            return false;
        }
    }
    return $valid;
}, 10, 3);

// Auto-add or remove the free product depending on coupon presence
add_action('woocommerce_before_calculate_totals', function( $cart ){
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    $free_product_id = 35677;
    $coupon_code     = 'FIRSTTIME1/8';
    $has_coupon      = in_array( strtolower($coupon_code), array_map('strtolower', $cart->get_applied_coupons()) );
    $found           = false;

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( $cart_item['product_id'] == $free_product_id ) {
            $found = true;
            if ( $has_coupon ) {
                $cart_item['data']->set_price( 0 ); // Make free
            } else {
                $cart->remove_cart_item( $cart_item_key ); // Remove if no coupon
            }
        }
    }

    // Add free product if coupon is applied and it's not in the cart yet
    if ( $has_coupon && ! $found ) {
        $cart->add_to_cart( $free_product_id, 1 );
    }
});


add_filter('body_class', 'add_woocommerce_account_body_class');
function add_woocommerce_account_body_class($classes) {
    if (is_account_page()) {
        $classes[] = 'woocommerce-account';
    }
    return $classes;
}

// ...existing code...


// Removed old product hiding code - now handled by new free gift system

// --- FREE GIFT SYSTEM - DUAL FLOW ---
// Cart ≥ $150: Auto-add for everyone (no email check)
// Cart < $150: Check email on checkout only (new customers only)

// --- CONFIGURATION ---
define('FREE_GIFT_PRODUCT_ID', 39110); // Free gift product ID
define('FREE_GIFT_THRESHOLD', 150);   // Subtotal threshold

/**
* Check if an email (guest or registered) has completed/processing orders.
 */
function wfg_email_is_new_customer($email) {
    if (!$email) return true;

    $email = strtolower($email); // normalize casing

    $orders = new WP_Query([
        'post_type'      => 'shop_order',
        'post_status'    => ['wc-completed', 'wc-processing'], // treat both as existing
        'posts_per_page' => 1,
        'meta_query'     => [
            [
                'key'     => '_billing_email',
                'value'   => $email,
                'compare' => '=',
            ]
        ],
        'fields' => 'ids',
    ]);

    return ( $orders->found_posts == 0 );
}

/**
* Check if logged in customer is new.
*/
function wfg_logged_in_is_new_customer() {
    if (!is_user_logged_in()) return true;
    $user_id = get_current_user_id();
    $orders  = wc_get_orders([
        'customer_id' => $user_id,
        'status'      => ['wc-completed', 'wc-processing'],
        'return'      => 'ids',
        'limit'       => 1,
    ]);
    return empty($orders);
}

/**
* Add/remove gift in cart.
*/
function wfg_handle_gift($is_new_customer) {
    $cart       = WC()->cart;
    $product_id = FREE_GIFT_PRODUCT_ID;
    $threshold  = FREE_GIFT_THRESHOLD;

    if (!$cart) return;

    $has_gift = false;
    $gift_key = null;
    $subtotal = 0;

    foreach ($cart->get_cart() as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $has_gift = true;
            $gift_key = $key;
        } else {
            $subtotal += $item['line_total'] + $item['line_tax'];
        }
    }

    if ($is_new_customer && $subtotal >= $threshold && !$has_gift) {
        // ✅ Add gift
        $cart->add_to_cart($product_id, 1, 0, [], ['wfg_auto_added' => true]);
    } elseif ((!$is_new_customer || $subtotal < $threshold) && $has_gift) {
        // ❌ Remove gift
        $cart->remove_cart_item($gift_key);
    }
}

/**
* UNIFIED GIFT MANAGEMENT SYSTEM
*/
add_action('woocommerce_before_calculate_totals', function($cart) {
    if (is_admin() && !wp_doing_ajax()) return;
    if (!$cart || $cart->is_empty()) return;
    
    // Prevent infinite loops
    static $running = false;
    if ($running) return;
    $running = true;

    $product_id = FREE_GIFT_PRODUCT_ID;
    $threshold  = FREE_GIFT_THRESHOLD;

    $has_gift = false;
    $gift_key = null;
    $subtotal = 0;

    foreach ($cart->get_cart() as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $has_gift = true;
            $gift_key = $key;
        } else {
            $subtotal += $item['line_total'] + $item['line_tax'];
        }
    }

    $should_have_gift = false;

    // RULE 1: Cart ≥ $150 → Add gift for EVERYONE
    if ($subtotal >= $threshold) {
        $should_have_gift = true;
        }
    // RULE 2: Cart < $150 → Only add for NEW customers (on cart and checkout)
    elseif ($subtotal < $threshold) {
        if (is_user_logged_in()) {
            $should_have_gift = wfg_logged_in_is_new_customer();
    } else {
            // For guests, only add gift if we have email (checkout page or session)
            $email = WC()->session->get('guest_checkout_email');
            if ($email) {
                $should_have_gift = wfg_email_is_new_customer($email);
            }
        }
    }
    
    // Check if user manually removed the gift (and respect their choice)
    $user_removed_gift = WC()->session->get('wfg_user_removed_gift');
    
    // Add or remove gift based on rules
    if ($should_have_gift && !$has_gift && !$user_removed_gift) {
        $cart->add_to_cart($product_id, 1, 0, [], ['wfg_auto_added' => true]);
    } elseif (!$should_have_gift && $has_gift) {
        $cart->remove_cart_item($gift_key);
    }
    
    $running = false;
}, 10);


/**
* Additional cart page hook to ensure gift appears on cart
*/
add_action('woocommerce_cart_loaded_from_session', function($cart) {
    if (is_admin() && !wp_doing_ajax()) return;
    if (!$cart || $cart->is_empty()) return;
    
    // Force cart calculation which will trigger our main gift logic
    $cart->calculate_totals();
}, 5);

/**
* Trigger gift check when cart contents change
*/
add_action('woocommerce_add_to_cart', function() {
    // Reset removal flag when new items are added (cart total might change rules)
    WC()->session->__unset('wfg_user_removed_gift');
    WC()->cart->calculate_totals();
}, 20);

add_action('woocommerce_cart_item_removed', function($cart_item_key, $cart) {
    // Check if the removed item was our free gift
    $removed_item = $cart->removed_cart_contents[$cart_item_key] ?? null;
    if ($removed_item && $removed_item['product_id'] == FREE_GIFT_PRODUCT_ID) {
        // Mark that user manually removed the gift
        WC()->session->set('wfg_user_removed_gift', true);
    }
    WC()->cart->calculate_totals();
}, 20, 2);

/**
* FLOW 3: AJAX - Guest email capture on checkout (cart < $150 only)
 */
add_action('wp_ajax_zoho_capture_billing_email', 'wfg_check_email_ajax');
add_action('wp_ajax_nopriv_zoho_capture_billing_email', 'wfg_check_email_ajax');

function wfg_check_email_ajax() {
    // Skip nonce check for now to avoid 403 errors
    $email = isset($_POST['billing_email']) ? sanitize_email($_POST['billing_email']) : '';
    if (!$email || !WC()->cart) {
        wp_send_json_success(['message' => 'No email or cart']);
        return;
    }

    // Store latest email in session immediately
        WC()->session->set('guest_checkout_email', strtolower($email));
    
    // Reset user removal flag when email changes (new customer check)
    WC()->session->__unset('wfg_user_removed_gift');
    
    // Force cart recalculation to trigger our gift logic
        WC()->cart->calculate_totals();

    wp_send_json_success([
        'refresh_checkout' => true,
        'message' => 'Email captured and cart updated'
    ]);
}

/**
* FLOW 4: Guest checkout page load → check prefilled email
*/
add_action('woocommerce_checkout_init', function() {
    if (is_user_logged_in()) return;

    $email = isset($_POST['billing_email']) ? sanitize_email($_POST['billing_email']) : '';
    if (!$email) {
        $session_email = WC()->session->get('guest_checkout_email');
        if ($session_email) {
            $email = $session_email;
        }
    }

    if ($email) {
        $is_new = wfg_email_is_new_customer($email);
        wfg_handle_gift($is_new);
    }
});

/**
* Store billing email in session during checkout updates.
*/
add_action('woocommerce_checkout_update_order_review', function($post_data) {
    parse_str($post_data, $data);
    if (!empty($data['billing_email'])) {
        $email = strtolower(sanitize_email($data['billing_email']));
        WC()->session->set('guest_checkout_email', $email);
        
        // Reset removal flag when email changes
        WC()->session->__unset('wfg_user_removed_gift');
        
        // Force cart recalculation to trigger our unified gift logic
        WC()->cart->calculate_totals();
    }
});

/**
* Reset gift removal flag when user logs in/out (different rules apply)
*/
add_action( 'wp_login', function( $user_login, $user ) {
    add_action( 'woocommerce_init', function() {
        if ( function_exists( 'WC' ) && WC()->session ) {
            WC()->session->__unset( 'wfg_user_removed_gift' );
        }
    });
}, 10, 2 );

add_action('wp_logout', function() {
    if (WC()->session) {
        WC()->session->__unset('wfg_user_removed_gift');
    }
});

/**
* Make the auto-added free gift price zero.
*/
add_action('woocommerce_before_calculate_totals', function($cart) {
    foreach ($cart->get_cart() as $key => $item) {
        if (isset($item['wfg_auto_added']) && $item['wfg_auto_added']) {
            $item['data']->set_price(0);
        }
    }
}, 30);

/**
* Hide the free gift product from shop/category pages
*/
add_action('pre_get_posts', function($q) {
    if ($q->is_main_query() && (is_shop() || is_product_category() || is_product_tag() || is_search())) {
        $q->set('post__not_in', array(FREE_GIFT_PRODUCT_ID));
    }
});

/**
* Redirect direct access to the free gift product page
*/
add_action('template_redirect', function() {
    if (is_product() && get_the_ID() == FREE_GIFT_PRODUCT_ID) {
        wp_redirect(home_url());
        exit;
    }
});


/**
* Add JavaScript to trigger email capture AJAX on checkout
*/
add_action('wp_footer', 'wfg_checkout_email_capture_js');
function wfg_checkout_email_capture_js() {
    if (!is_checkout() && !is_cart()) return;
        ?>
        <script type="text/javascript">
            jQuery(function($) {
        var emailCaptureTimeout;
        
        // Function to make AJAX call when email is entered (with debouncing)
        function captureEmail(email) {
            if (!email || email.length < 5 || !email.includes('@')) return;
            
            // Clear previous timeout to prevent spam
            if (emailCaptureTimeout) {
                clearTimeout(emailCaptureTimeout);
            }
            
            // Debounce AJAX calls - only send after 1 second of no typing
            emailCaptureTimeout = setTimeout(function() {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'zoho_capture_billing_email',
                        billing_email: email
                    },
                    success: function(response) {
                        if (response.success) {
                    $('body').trigger('update_checkout');
                    $(document.body).trigger('wc_fragment_refresh');
                        }
                    }
                });
            }, 1000);
        }
        
        // Monitor email field changes with debouncing
        $(document).on('input', '#billing_email, input[name="billing_email"], #email, input[id="email"]', function() {
            var email = $(this).val();
            captureEmail(email);
        });
        
        // Immediate trigger on blur (when user leaves field)
        $(document).on('blur', '#billing_email, input[name="billing_email"], #email, input[id="email"]', function() {
            var email = $(this).val();
            if (email && email.includes('@')) {
                // Clear timeout and send immediately on blur
                if (emailCaptureTimeout) {
                    clearTimeout(emailCaptureTimeout);
                }
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'zoho_capture_billing_email',
                        billing_email: email
                    },
                    success: function(response) {
                        if (response.success) {
                            $('body').trigger('update_checkout');
                        }
                    }
                });
            }
        });
        
        // Check existing email on page load
        setTimeout(function() {
            var existingEmail = $('#billing_email, #email').val();
            if (existingEmail && existingEmail.includes('@')) {
                captureEmail(existingEmail);
            }
        }, 1000);
            });
        </script>
        <?php
    }
// Show checkbox on user profile
add_action('show_user_profile', 'oz_add_email_verified_field');
add_action('edit_user_profile', 'oz_add_email_verified_field');
function oz_add_email_verified_field($user){
  $v = get_user_meta($user->ID, 'email_verified', true) ? 1 : 0;
  ?>
  <h2>Verification</h2>
  <table class="form-table">
    <tr>
      <th><label for="email_verified">Email verified</label></th>
      <td><input type="checkbox" name="email_verified" id="email_verified" value="1" <?php checked($v, 1); ?> /></td>
    </tr>
  </table>
  <?php
}

// Save checkbox
add_action('personal_options_update', 'oz_save_email_verified_field');
add_action('edit_user_profile_update', 'oz_save_email_verified_field');
function oz_save_email_verified_field($user_id){
  if (!current_user_can('edit_user', $user_id)) return;
  update_user_meta($user_id, 'email_verified', isset($_POST['email_verified']) ? 1 : 0);
}

// Tell the points plugin when a user is verified
add_filter('wps_wpr_is_user_verified', function($is_verified, $user_id){
  return (bool) get_user_meta($user_id, 'email_verified', true);
}, 10, 2);
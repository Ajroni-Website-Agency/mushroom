<?php
/**
 * OceanWP Child Theme Functions
 *
 * When running a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions will be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function oceanwp_child_enqueue_parent_style() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update the theme).
	$theme   = wp_get_theme( 'OceanWP' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet.
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'oceanwp-style' ), $version );
	
}

add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style' );



function dokan_remove_coupon_menu( $settings_sub ) { 
	unset($settings_sub['scial']);
	unset($settings_sub['seo']);
	return $settings_sub;
} 
add_filter( 'dokan_get_dashboard_nav', 'dokan_remove_coupon_menu' );


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

function output_logout_url(){
	return wp_logout_url( home_url('/') );
}
add_shortcode('wp_clogout', 'output_logout_url');

function get_seller_listing($atts){
    global $post;
    $attr = shortcode_atts(apply_filters('dokan_store_listing_per_page', array('per_page' => 6)), $atts);
    $paged = max(1, get_query_var('paged'));
    $limit = $attr['per_page'];
    $offset = ($paged - 1) * $limit;
    $sellers = dokan_get_sellers($limit, $offset);
    ob_start();
    if ($sellers['users']) {
    ?>
    <div class="dokan-seller-wrap">
        <?php 
        foreach ($sellers['users'] as $seller) {
            $vendor            = dokan()->vendor->get( $seller->ID );
            $store_info = dokan_get_store_info($seller->ID);
            $banner_id = isset($store_info['banner']) ? $store_info['banner'] : 0;
            $store_name = isset($store_info['store_name']) ? esc_html($store_info['store_name']) : __('N/A', 'dokan');
            $store_url = dokan_get_store_url($seller->ID);
            $is_store_featured = $vendor->is_featured();
            $store_rating      = $vendor->get_rating();
            $products = count_user_posts($seller->ID, 'product');
            ?>

            <div class="dokan-single-seller">
                <div class="dokan-middle-seller">
                    <?php if ( $is_store_featured ) : ?>
                    <div class="featured-favourite">
                        <div class="featured-label"><?php esc_html_e( 'Featured', 'dokan-lite' ); ?></div>
                    </div>
                    <?php endif ?>
                    <?php do_action( 'dokan_seller_listing_after_featured', $seller, $store_info ); ?>

                    <div class="dokan-store-thumbnail">
                        <div class="top-banner-vendor">
                            <a href="<?php echo $store_url; ?>">
                                <?php 
                                if ($banner_id) {
                                    $banner_url = wp_get_attachment_image_src($banner_id, 'full'); ?>
                                    <img class="dokan-store-img" src="<?php echo esc_url($banner_url[0]); ?>" alt="<?php echo esc_attr($store_name); ?>">
                                <?php 
                                } else { ?>
                                    <img class="dokan-store-img" src="<?php echo dokan_get_no_seller_image(); ?>" alt="<?php _e('No Image', 'dokan'); ?>">
                                <?php 
                                } ?>
                            </a>
                        </div>
                        <div class="seller-avatar">
                            <a href="<?php echo esc_url( $store_url ); ?>">
                                <img src="<?php echo esc_url( $vendor->get_avatar() ) ?>"
                                alt="<?php echo esc_attr( $vendor->get_shop_name() ) ?>"
                                size="150">
                            </a>
                        </div>
                        <div class="seller-names">
                            <h3><a href="<?php echo $store_url; ?>"><?php echo $store_name; ?></a></h3>
                        </div>
                        <div class="seller-infos">
                            <ul>
                                <li><?php echo $products; ?> Products</li>
                                <li>
                                    <?php if ( !empty( $store_rating['count'] ) ): ?>
                                       <div class="MainStars" style="--rating: <?php echo esc_attr( $store_rating['rating'] ); ?> ;" aria-label="Rating of this product is <?php echo esc_attr( $store_rating['rating'] ); ?> out of 5."></div>
                                    <?php else: ?>
                                           <div class="MainStars" style="--rating: 5.0;" aria-label="Rating of this product is 5.0 out of 5."></div>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                        <div class="seller-locations">
                            <?php 
                            if (isset($store_info['address']) && !empty($store_info['address'])) {
                                echo '<svg height="20" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="20" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M256,32c-74,0-134.2,58.7-134.2,132.7c0,16.4,3.5,34.3,9.8,50.4l-0.1,0l0.6,1.2c0.5,1.1,1,2.2,1.5,3.3L256,480l121.8-259.1   l0.6-1.2c0.5-1.1,1.1-2.2,1.6-3.4l0.4-1.1c6.5-16.1,9.8-33.1,9.8-50.3C390.2,90.7,330,32,256,32z M256,206.9   c-25.9,0-46.9-21-46.9-46.9c0-25.9,21-46.9,46.9-46.9c25.9,0,46.9,21,46.9,46.9C302.9,185.9,281.9,206.9,256,206.9z"/></g></svg><span>'.dokan_get_seller_address($seller->ID).'</span>';
                            }
                            ?>
                        </div>
                    </div> <!-- .thumbnail -->
                </div> <!-- .single-seller -->
            </div>
        <?php 
        }
        ?>
    </div> <!-- .dokan-seller-wrap -->

    <?php 
    } else {
        ?>
        <p class="dokan-error"><?php 
        _e('No seller found!', 'dokan');
        ?>
</p>
    <?php 
    }
    $content = ob_get_clean();
    return apply_filters('dokan_seller_listing', $content, $attr);
}
add_shortcode('seller-list', 'get_seller_listing');

function make_custom_click_shortcode(){
    
    if(isset($_GET['seller'])){
        ?>
        <script>
jQuery(document).ready(function() {
   jQuery(".owp-account-links .login > a").removeClass('current');
   jQuery(".u-column1.col-1").hide();
   jQuery(".u-column1.col-1").css('opacity', '0');
   jQuery(".u-column2.col-2").css('opacity', '1');
   jQuery(".u-column2.col-2").show();
   jQuery(".owp-account-links .register > a").addClass('current');
});
        </script>
        <?php
    }
	?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/58baa3cf5b89e2149e11050b/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<?php
}
add_action('wp_footer', 'make_custom_click_shortcode');
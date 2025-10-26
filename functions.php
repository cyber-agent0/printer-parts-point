<?php
/**
 * Printer Parts Pro Theme Functions
 *
 * @package PrinterPartsPro
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
function printer_parts_theme_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'printer-parts-pro' ),
        'footer'  => __( 'Footer Menu', 'printer-parts-pro' ),
    ) );
    
    // Set content width
    global $content_width;
    if ( ! isset( $content_width ) ) {
        $content_width = 1320;
    }
}
add_action( 'after_setup_theme', 'printer_parts_theme_setup' );

/**
 * Enqueue Scripts and Styles
 */
function printer_parts_enqueue_scripts() {
    // Main stylesheet
    wp_enqueue_style( 'printer-parts-style', get_stylesheet_uri(), array(), '1.0.0' );
    
    // Google Fonts
    wp_enqueue_style( 'printer-parts-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null );
    
    // Font Awesome for icons
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
    
    // Main JavaScript
    wp_enqueue_script( 'printer-parts-script', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.0.0', true );
    
    // Localize script for AJAX
    wp_localize_script( 'printer-parts-script', 'printerPartsAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'printer_parts_nonce' )
    ) );
}
add_action( 'wp_enqueue_scripts', 'printer_parts_enqueue_scripts' );

/**
 * Register Widget Areas
 */
function printer_parts_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Shop Sidebar', 'printer-parts-pro' ),
        'id'            => 'shop-sidebar',
        'description'   => __( 'Widgets in this area will be shown on shop pages.', 'printer-parts-pro' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 3', 'printer-parts-pro' ),
        'id'            => 'footer-3',
        'description'   => __( 'Footer widget area 3', 'printer-parts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 4', 'printer-parts-pro' ),
        'id'            => 'footer-4',
        'description'   => __( 'Footer widget area 4', 'printer-parts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'printer_parts_widgets_init' );

/**
 * Custom Product Categories
 */
function printer_parts_get_product_categories() {
    $categories = array(
        'laser-printer-parts' => array(
            'name' => 'Laser Printer Parts',
            'icon' => 'fa-print',
            'description' => 'Parts for HP, Canon, Brother LaserJet printers'
        ),
        'inkjet-printer-parts' => array(
            'name' => 'Inkjet Printer Parts',
            'icon' => 'fa-fill-drip',
            'description' => 'Parts for Epson, HP, Canon inkjet printers'
        ),
        'dot-matrix-parts' => array(
            'name' => 'Dot Matrix Parts',
            'icon' => 'fa-keyboard',
            'description' => 'Parts for TVS, Epson LX/LQ series'
        ),
        'scanner-parts' => array(
            'name' => 'Scanner Parts',
            'icon' => 'fa-scanner',
            'description' => 'Parts for Canon, HP, Epson scanners'
        ),
        'thermal-pos-parts' => array(
            'name' => 'Thermal/POS Parts',
            'icon' => 'fa-receipt',
            'description' => 'Thermal printer and POS system parts'
        ),
    );
    
    return apply_filters( 'printer_parts_categories', $categories );
}

/**
 * Customize WooCommerce Product Loop
 */
function printer_parts_product_loop_columns() {
    return 4; // Number of products per row
}
add_filter( 'loop_shop_columns', 'printer_parts_product_loop_columns' );

function printer_parts_products_per_page() {
    return 16; // Products per page
}
add_filter( 'loop_shop_per_page', 'printer_parts_products_per_page' );

/**
 * Add Custom Product Badge for New Products
 */
function printer_parts_product_badge() {
    global $product;
    
    $created_date = $product->get_date_created();
    $current_date = new DateTime();
    $days_old = $current_date->diff( $created_date )->days;
    
    if ( $days_old <= 30 ) {
        echo '<span class="product-badge new">New</span>';
    }
    
    if ( $product->is_on_sale() ) {
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        if ( $regular_price && $sale_price ) {
            $savings = $regular_price - $sale_price;
            echo '<span class="product-badge">Save ₹' . number_format( $savings, 0 ) . '</span>';
        }
    }
}

/**
 * Custom Product Search with Model Number
 */
function printer_parts_product_search_form() {
    ?>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="search" 
               class="search-field" 
               placeholder="<?php echo esc_attr_x( 'Search by part number, printer model...', 'placeholder', 'printer-parts-pro' ); ?>" 
               value="<?php echo get_search_query(); ?>" 
               name="s" />
        <input type="hidden" name="post_type" value="product" />
        <button type="submit" class="search-submit">
            <i class="fas fa-search"></i>
            <span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'printer-parts-pro' ); ?></span>
        </button>
    </form>
    <?php
}

/**
 * Add Compatibility Meta Box for Products
 */
function printer_parts_add_compatibility_meta_box() {
    add_meta_box(
        'printer_compatibility',
        __( 'Printer Compatibility', 'printer-parts-pro' ),
        'printer_parts_compatibility_callback',
        'product',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'printer_parts_add_compatibility_meta_box' );

function printer_parts_compatibility_callback( $post ) {
    wp_nonce_field( 'printer_parts_compatibility_nonce', 'printer_parts_compatibility_nonce' );
    
    $compatibility = get_post_meta( $post->ID, '_printer_compatibility', true );
    $part_number = get_post_meta( $post->ID, '_part_number', true );
    $condition = get_post_meta( $post->ID, '_part_condition', true );
    
    ?>
    <p>
        <label for="printer_compatibility"><strong><?php _e( 'Compatible Printer Models:', 'printer-parts-pro' ); ?></strong></label><br>
        <textarea id="printer_compatibility" name="printer_compatibility" rows="3" style="width: 100%;" placeholder="e.g., HP LaserJet M1005 / M1136 / Canon LBP2900"><?php echo esc_textarea( $compatibility ); ?></textarea>
        <small><?php _e( 'Enter compatible printer models separated by /', 'printer-parts-pro' ); ?></small>
    </p>
    
    <p>
        <label for="part_number"><strong><?php _e( 'Part Number / OEM Code:', 'printer-parts-pro' ); ?></strong></label><br>
        <input type="text" id="part_number" name="part_number" value="<?php echo esc_attr( $part_number ); ?>" style="width: 100%;" placeholder="e.g., RM1-3942, QM8-0471">
    </p>
    
    <p>
        <label for="part_condition"><strong><?php _e( 'Condition:', 'printer-parts-pro' ); ?></strong></label><br>
        <select id="part_condition" name="part_condition" style="width: 100%;">
            <option value="new_original" <?php selected( $condition, 'new_original' ); ?>><?php _e( 'New Original', 'printer-parts-pro' ); ?></option>
            <option value="new_import" <?php selected( $condition, 'new_import' ); ?>><?php _e( 'New Import', 'printer-parts-pro' ); ?></option>
            <option value="new_compatible" <?php selected( $condition, 'new_compatible' ); ?>><?php _e( 'New Compatible', 'printer-parts-pro' ); ?></option>
            <option value="refurbished" <?php selected( $condition, 'refurbished' ); ?>><?php _e( 'Refurbished', 'printer-parts-pro' ); ?></option>
        </select>
    </p>
    <?php
}

function printer_parts_save_compatibility( $post_id ) {
    if ( ! isset( $_POST['printer_parts_compatibility_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['printer_parts_compatibility_nonce'], 'printer_parts_compatibility_nonce' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['printer_compatibility'] ) ) {
        update_post_meta( $post_id, '_printer_compatibility', sanitize_textarea_field( $_POST['printer_compatibility'] ) );
    }
    
    if ( isset( $_POST['part_number'] ) ) {
        update_post_meta( $post_id, '_part_number', sanitize_text_field( $_POST['part_number'] ) );
    }
    
    if ( isset( $_POST['part_condition'] ) ) {
        update_post_meta( $post_id, '_part_condition', sanitize_text_field( $_POST['part_condition'] ) );
    }
}
add_action( 'save_post', 'printer_parts_save_compatibility' );

/**
 * Display Compatibility Info on Product Page
 */
function printer_parts_display_compatibility() {
    global $product;
    
    $compatibility = get_post_meta( $product->get_id(), '_printer_compatibility', true );
    $part_number = get_post_meta( $product->get_id(), '_part_number', true );
    $condition = get_post_meta( $product->get_id(), '_part_condition', true );
    
    if ( $compatibility || $part_number || $condition ) {
        echo '<div class="product-compatibility-info" style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0;">';
        
        if ( $compatibility ) {
            echo '<p><strong>' . __( 'Compatible Models:', 'printer-parts-pro' ) . '</strong><br>';
            echo esc_html( $compatibility ) . '</p>';
        }
        
        if ( $part_number ) {
            echo '<p><strong>' . __( 'Part Number:', 'printer-parts-pro' ) . '</strong> ' . esc_html( $part_number ) . '</p>';
        }
        
        if ( $condition ) {
            $condition_labels = array(
                'new_original' => __( 'New Original', 'printer-parts-pro' ),
                'new_import' => __( 'New Import', 'printer-parts-pro' ),
                'new_compatible' => __( 'New Compatible', 'printer-parts-pro' ),
                'refurbished' => __( 'Refurbished', 'printer-parts-pro' )
            );
            echo '<p><strong>' . __( 'Condition:', 'printer-parts-pro' ) . '</strong> ' . esc_html( $condition_labels[ $condition ] ?? $condition ) . '</p>';
        }
        
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'printer_parts_display_compatibility', 25 );

/**
 * Add to Cart AJAX Handler
 */
function printer_parts_ajax_add_to_cart() {
    check_ajax_referer( 'printer_parts_nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
    
    if ( $product_id ) {
        $result = WC()->cart->add_to_cart( $product_id, $quantity );
        
        if ( $result ) {
            wp_send_json_success( array(
                'message' => __( 'Product added to cart', 'printer-parts-pro' ),
                'cart_count' => WC()->cart->get_cart_contents_count()
            ) );
        } else {
            wp_send_json_error( array(
                'message' => __( 'Failed to add product to cart', 'printer-parts-pro' )
            ) );
        }
    }
    
    wp_die();
}
add_action( 'wp_ajax_printer_parts_add_to_cart', 'printer_parts_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_printer_parts_add_to_cart', 'printer_parts_ajax_add_to_cart' );

/**
 * Update Cart Count via AJAX
 */
function printer_parts_cart_count_fragments( $fragments ) {
    $cart_count = WC()->cart->get_cart_contents_count();
    
    $fragments['.cart-count'] = '<span class="cart-count">' . $cart_count . '</span>';
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'printer_parts_cart_count_fragments' );

/**
 * Custom Breadcrumbs
 */
function printer_parts_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }
    
    echo '<div class="breadcrumbs" style="padding: 1rem 0; font-size: 0.875rem; color: #6b7280;">';
    echo '<div class="container">';
    echo '<a href="' . home_url() . '">' . __( 'Home', 'printer-parts-pro' ) . '</a>';
    
    if ( is_shop() ) {
        echo ' / ' . __( 'Shop', 'printer-parts-pro' );
    } elseif ( is_product_category() ) {
        echo ' / <a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '">' . __( 'Shop', 'printer-parts-pro' ) . '</a>';
        echo ' / ' . single_cat_title( '', false );
    } elseif ( is_product() ) {
        echo ' / <a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '">' . __( 'Shop', 'printer-parts-pro' ) . '</a>';
        echo ' / ' . get_the_title();
    } elseif ( is_page() ) {
        echo ' / ' . get_the_title();
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Customize Excerpt Length
 */
function printer_parts_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'printer_parts_excerpt_length' );

/**
 * Add Theme Customizer Options
 */
function printer_parts_customize_register( $wp_customize ) {
    // Contact Information Section
    $wp_customize->add_section( 'printer_parts_contact', array(
        'title'    => __( 'Contact Information', 'printer-parts-pro' ),
        'priority' => 30,
    ) );
    
    // Phone Number
    $wp_customize->add_setting( 'printer_parts_phone', array(
        'default'           => '+91 9990774445',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'printer_parts_phone', array(
        'label'    => __( 'Phone Number', 'printer-parts-pro' ),
        'section'  => 'printer_parts_contact',
        'type'     => 'text',
    ) );
    
    // Email
    $wp_customize->add_setting( 'printer_parts_email', array(
        'default'           => 'info@printerpartspoint.com',
        'sanitize_callback' => 'sanitize_email',
    ) );
    
    $wp_customize->add_control( 'printer_parts_email', array(
        'label'    => __( 'Email Address', 'printer-parts-pro' ),
        'section'  => 'printer_parts_contact',
        'type'     => 'email',
    ) );
    
    // Address
    $wp_customize->add_setting( 'printer_parts_address', array(
        'default'           => 'Delhi, India',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'printer_parts_address', array(
        'label'    => __( 'Business Address', 'printer-parts-pro' ),
        'section'  => 'printer_parts_contact',
        'type'     => 'text',
    ) );
}
add_action( 'customize_register', 'printer_parts_customize_register' );

/**
 * Security: Remove WordPress Version
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Optimize Performance: Disable Emojis
 */
function printer_parts_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'printer_parts_disable_emojis' );

?>1', 'printer-parts-pro' ),
        'id'            => 'footer-1',
        'description'   => __( 'Footer widget area 1', 'printer-parts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 2', 'printer-parts-pro' ),
        'id'            => 'footer-2',
        'description'   => __( 'Footer widget area 2', 'printer-parts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer

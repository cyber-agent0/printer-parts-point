<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
    <!-- Top Bar -->
    <div class="topbar">
        <div class="container">
            <div class="topbar-content">
                <div class="topbar-contact">
                    <?php
                    $phone = get_theme_mod( 'printer_parts_phone', '+91 9990774445' );
                    $email = get_theme_mod( 'printer_parts_email', 'info@printerpartspoint.com' );
                    ?>
                    <a href="tel:<?php echo esc_attr( $phone ); ?>">
                        <i class="fas fa-phone"></i> <?php echo esc_html( $phone ); ?>
                    </a>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>">
                        <i class="fas fa-envelope"></i> <?php echo esc_html( $email ); ?>
                    </a>
                </div>
                <div class="topbar-links">
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
                            <i class="fas fa-user"></i> <?php _e( 'My Account', 'printer-parts-pro' ); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
                            <i class="fas fa-sign-in-alt"></i> <?php _e( 'Login / Register', 'printer-parts-pro' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="site-header">
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <!-- Logo -->
                    <div class="site-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <h1>
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                    <?php bloginfo( 'name' ); ?>
                                </a>
                            </h1>
                        <?php endif; ?>
                    </div>

                    <!-- Search Bar -->
                    <div class="header-search">
                        <?php printer_parts_product_search_form(); ?>
                    </div>

                    <!-- Header Actions -->
                    <div class="header-actions">
                        <!-- Cart Icon -->
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-icon cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <?php
                                $cart_count = WC()->cart->get_cart_contents_count();
                                if ( $cart_count > 0 ) :
                                ?>
                                    <span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>

                        <!-- Mobile Menu Toggle -->
                        <button class="mobile-menu-toggle" aria-label="<?php _e( 'Toggle Menu', 'printer-parts-pro' ); ?>">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="main-navigation">
            <div class="container">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'fallback_cb'    => 'printer_parts_fallback_menu',
                ) );
                ?>
            </div>
        </nav>
    </header>

    <?php
    /**
     * Fallback menu if no menu is set
     */
    function printer_parts_fallback_menu() {
        echo '<ul class="nav-menu">';
        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home', 'printer-parts-pro' ) . '</a></li>';
        
        if ( class_exists( 'WooCommerce' ) ) {
            echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . __( 'Shop', 'printer-parts-pro' ) . '</a></li>';
            
            // Get product categories
            $categories = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => 0,
            ) );
            
            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                echo '<li class="menu-item-has-children">';
                echo '<a href="#">' . __( 'Categories', 'printer-parts-pro' ) . '</a>';
                echo '<ul class="sub-menu">';
                foreach ( $categories as $category ) {
                    echo '<li><a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a></li>';
                }
                echo '</ul>';
                echo '</li>';
            }
        }
        
        echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '">' . __( 'About', 'printer-parts-pro' ) . '</a></li>';
        echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '">' . __( 'Contact', 'printer-parts-pro' ) . '</a></li>';
        echo '</ul>';
    }
    ?>

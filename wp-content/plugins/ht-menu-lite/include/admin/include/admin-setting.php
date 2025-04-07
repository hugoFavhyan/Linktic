<?php
namespace HtMenu\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMega_Menu_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new \HTMega_Menu_Settings_API();

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 220 );
        add_action( 'wsa_form_bottom_htmegamenu_general_tabs', array( $this, 'htmega_menu_html_general_tabs' ) );
        $this->plugin_recommendations();
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->htmega_menu_admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->htmega_menu_admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {
        add_menu_page( 
            __( 'HT Menu', 'htmega-menu' ),
            __( 'HT Menu', 'htmega-menu' ),
            'manage_options',
            'htmegamenu',
            array ( $this, 'plugin_page' ),
            'dashicons-welcome-widgets-menus',
            100
        );
    }

    /**
     * [plugin_recommendations]
     * @return [void]
     */
    public function plugin_recommendations(){

        $get_instance = Recommended_Plugins::instance( 
            array( 
                'text_domain'       => 'htmega-menu', 
                'parent_menu_slug'  => 'htmegamenu', 
                'menu_capability'   => 'manage_options', 
                'menu_page_slug'    => 'htmenu-recommendations',
                'priority'          => 226,
                'assets_url'        => HTMEGA_MENU_PL_URL.'include/admin/assets',
                'hook_suffix'       => 'ht-menu_page_htmenu-recommendations'
            )
        );

        $get_instance->add_new_tab( array(

            'title' => esc_html__( 'Recommended', 'htmegamenu' ),
            'active' => true,
            'plugins' => array(

                array(
                    'slug'      => 'woolentor-addons',
                    'location'  => 'woolentor_addons_elementor.php',
                    'name'      => esc_html__( 'WooLentor', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'ht-mega-for-elementor',
                    'location'  => 'htmega_addons_elementor.php',
                    'name'      => esc_html__( 'HT Mega', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'hashbar-wp-notification-bar',
                    'location'  => 'init.php',
                    'name'      => esc_html__( 'HashBar', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'ht-slider-for-elementor',
                    'location'  => 'ht-slider-for-elementor.php',
                    'name'      => esc_html__( 'HT Slider For Elementor', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'ht-contactform',
                    'location'  => 'contact-form-widget-elementor.php',
                    'name'      => esc_html__( 'HT Contact Form 7', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'extensions-for-cf7',
                    'location'  => 'extensions-for-cf7.php',
                    'name'      => esc_html__( 'Extensions For CF7', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'ht-wpform',
                    'location'  => 'wpform-widget-elementor.php',
                    'name'      => esc_html__( 'HT WPForms', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'insert-headers-and-footers-script',
                    'location'  => 'init.php',
                    'name'      => esc_html__( 'HT Script', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'wp-plugin-manager',
                    'location'  => 'plugin-main.php',
                    'name'      => esc_html__( 'WP Plugin Manager', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'wc-builder',
                    'location'  => 'wc-builder.php',
                    'name'      => esc_html__( 'WC Builder', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'whols',
                    'location'  => 'whols.php',
                    'name'      => esc_html__( 'Whols', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'just-tables',
                    'location'  => 'just-tables.php',
                    'name'      => esc_html__( 'JustTables', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'wc-multi-currency',
                    'location'  => 'wcmilticurrency.php',
                    'name'      => esc_html__( 'Multi Currency', 'htmegamenu' )
                )
            )

        ) );

        $get_instance->add_new_tab(array(
            'title' => esc_html__( 'You May Also Like', 'htmegamenu' ),
            'plugins' => array(

                array(
                    'slug'      => 'woolentor-addons-pro',
                    'location'  => 'woolentor_addons_pro.php',
                    'name'      => esc_html__( 'WooLentor Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'WooLentor is one of the most popular WooCommerce Elementor Addons on WordPress.org. It has been downloaded more than 2,195,586 times and 100,000 stores are using WooLentor plugin. Why not you?', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'htmega-pro',
                    'location'  => 'htmega_pro.php',
                    'name'      => esc_html__( 'HT Mega Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-mega-pro/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'HTMega is an absolute addon for elementor that includes 80+ elements & 760 Blocks with unlimited variations. HT Mega brings limitless possibilities. Embellish your site with the elements of HT Mega.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'swatchly-pro',
                    'location'  => 'swatchly-pro.php',
                    'name'      => esc_html__( 'Product Variation Swatches', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/swatchly-product-variation-swatches-for-woocommerce-products/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Are you getting frustrated with WooCommerce’s current way of presenting the variants for your products? Well, say goodbye to dropdowns and start showing the product variations in a whole new light with Swatchly.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'whols-pro',
                    'location'  => 'whols-pro.php',
                    'name'      => esc_html__( 'Whols Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/whols-woocommerce-wholesale-prices/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Whols is an outstanding WordPress plugin for WooCommerce that allows store owners to set wholesale prices for the products of their online stores. This plugin enables you to show special wholesale prices to the wholesaler. Users can easily request to become a wholesale customer by filling out a simple online registration form. Once the registration is complete, the owner of the store will be able to review the request and approve the request either manually or automatically.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'just-tables-pro',
                    'location'  => 'just-tables-pro.php',
                    'name'      => esc_html__( 'JustTables Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/wp/justtables/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'JustTables is an incredible WordPress plugin that lets you showcase all your WooCommerce products in a sortable and filterable table view. It allows your customers to easily navigate through different attributes of the products and compare them on a single page. This plugin will be of great help if you are looking for an easy solution that increases the chances of landing a sale on your online store.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'multicurrencypro',
                    'location'  => 'multicurrencypro.php',
                    'name'      => esc_html__( 'Multi Currency Pro for WooCommerce', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/multi-currency-pro-for-woocommerce/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Multi-Currency Pro for WooCommerce is a prominent currency switcher plugin for WooCommerce. This plugin allows your website or online store visitors to switch to their preferred currency or their country’s currency.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'cf7-extensions-pro',
                    'location'  => 'cf7-extensions-pro.php',
                    'name'      => esc_html__( 'Extensions For CF7 Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/cf7-extensions/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Contact Form7 Extensions plugin is a fantastic WordPress plugin that enriches the functionalities of Contact Form 7.This all-in-one WordPress plugin will help you turn any contact page into a well-organized, engaging tool for communicating with your website visitors by providing tons of advanced features like drag and drop file upload, repeater field, trigger error for already submitted forms, popup form response, country flags and dial codes with a telephone input field and acceptance field, etc. in addition to its basic features.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'hashbar-pro',
                    'location'  => 'init.php',
                    'name'      => esc_html__( 'HashBar Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/wordpress-notification-bar-plugin/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'HashBar is a WordPress Notification / Alert / Offer Bar plugin which allows you to create unlimited notification bars to notify your customers. This plugin has option to show email subscription form (sometimes it increases up to 500% email subscriber), Offer text and buttons about your promotions. This plugin has the options to add unlimited background colors and images to make your notification bar more professional.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'wp-plugin-manager-pro',
                    'location'  => 'plugin-main.php',
                    'name'      => esc_html__( 'WP Plugin Manager Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/wp-plugin-manager-pro/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'WP Plugin Manager Pro is a specialized WordPress Plugin that helps you to deactivate unnecessary WordPress Plugins page wise and boosts the speed of your WordPress site to improve the overall site performance.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'ht-script-pro',
                    'location'  => 'plugin-main.php',
                    'name'      => esc_html__( 'HT Script Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/insert-headers-and-footers-code-ht-script/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Insert Headers and Footers Code allows you to insert Google Analytics, Facebook Pixel, custom CSS, custom HTML, JavaScript code to your website header and footer without modifying your theme code.This plugin has the option to add any custom code to your theme in one place, no need to edit the theme code. It will save your time and remove the hassle for the theme update.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'ht-menu',
                    'location'  => 'ht-mega-menu.php',
                    'name'      => esc_html__( 'HT Menu Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-menu-pro/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'WordPress Mega Menu Builder for Elementor', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'ht-slider-addons-pro',
                    'location'  => 'ht-slider-addons-pro.php',
                    'name'      => esc_html__( 'HT Slider Pro For Elementor', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-slider-pro-for-elementor/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'HT Slider Pro is a plugin to create a slider for WordPress websites easily using the Elementor page builder. 80+ prebuild slides/templates are included in this plugin. There is the option to create a post slider, WooCommerce product slider, Video slider, image slider, etc. Fullscreen, full width and box layout option are included.', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'ht-google-place-review',
                    'location'  => 'ht-google-place-review.php',
                    'name'      => esc_html__( 'Google Place Review', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/google-place-review-plugin-for-wordpress/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'If you are searching for a modern and excellent google places review WordPress plugin to showcase reviews from Google Maps and strengthen trust between you and your site visitors, look no further than HT Google Place Review', 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'was-this-helpful',
                    'location'  => 'was-this-helpful.php',
                    'name'      => esc_html__( 'Was This Helpful?', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/was-this-helpful/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( "Was this helpful? is a WordPress plugin that allows you to take visitors' feedback on your post/pages or any article. A visitor can share his feedback by like/dislike/yes/no", 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'ht-click-to-call',
                    'location'  => 'ht-click-to-call.php',
                    'name'      => esc_html__( 'HT Click To Call', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-click-to-call/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( "HT – Click to Call is a lightweight WordPress plugin that allows you to add a floating click to call button on your website. It will offer your website visitors an opportunity to call your business immediately at the right moment, especially when they are interested in your products or services and seeking more information.", 'htmegamenu' ),
                ),

                array(
                    'slug'      => 'docus-pro',
                    'location'  => 'docus-pro.php',
                    'name'      => esc_html__( 'Docus Pro', 'htmegamenu' ),
                    'link'      => 'https://hasthemes.com/plugins/docus-pro-youtube-video-playlist/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( "Embedding a YouTube playlist into your website plays a vital role to curate your content into several categories and make your web content more engaging and popular by keeping the visitors on your website for a longer period.", 'htmegamenu' ),
                ),

            )
        ));

        $get_instance->add_new_tab(array(
            'title' => esc_html__( 'Others', 'htmegamenu' ),
            'plugins' => array(

                array(
                    'slug'      => 'ht-easy-google-analytics',
                    'location'  => 'ht-easy-google-analytics.php',
                    'name'      => esc_html__( 'HT Easy GA4 ( Google Analytics 4 )', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'really-simple-google-tag-manager',
                    'location'  => 'really-simple-google-tag-manager.php',
                    'name'      => esc_html__( 'Really Simple Google Tag Manager', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'ht-instagram',
                    'location'  => 'ht-instagram.php',
                    'name'      => esc_html__( 'HT Feed', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'faster-youtube-embed',
                    'location'  => 'youtube-embed.php',
                    'name'      => esc_html__( 'Faster YouTube Embed', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'wc-sales-notification',
                    'location'  => 'wc_sales_notification.php',
                    'name'      => esc_html__( 'WC Sales Notification', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'preview-link-generator',
                    'location'  => 'preview_link_generator.php',
                    'name'      => esc_html__( 'Preview Link Generator', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'quickswish',
                    'location'  => 'quickswish.php',
                    'name'      => esc_html__( 'QuickSwish', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'docus',
                    'location'  => 'docus.php',
                    'name'      => esc_html__( 'Docus – YouTube Video Playlist', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'data-captia',
                    'location'  => 'data-captia.php',
                    'name'      => esc_html__( 'DataCaptia', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'coupon-zen',
                    'location'  => 'coupon-zen.php',
                    'name'      => esc_html__( 'Coupon Zen', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'sirve',
                    'location'  => 'sirve.php',
                    'name'      => esc_html__( 'Sirve – Simple Directory Listing', 'htmegamenu' )
                ),

                array(
                    'slug'      => 'ht-social-share',
                    'location'  => 'ht-social-share.php',
                    'name'      => esc_html__( 'HT Social Share', 'htmegamenu' )
                ),

            )
        ));


    }

    // Options page Section register
    function htmega_menu_admin_get_settings_sections() {
        $sections = array(
            
            array(
                'id'    => 'htmegamenu_general_tabs',
                'title' => esc_html__( 'General', 'htmega-menu' )
            ),

            array(
                'id'    => 'htmegamenu_style_tabs',
                'title' => esc_html__( 'Style', 'htmega-menu' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function htmega_menu_admin_fields_settings() {

        $settings_fields = array(

            'htmegamenu_general_tabs' => array(),
            
            'htmegamenu_style_tabs' => array(

                array(
                    'name'  => 'menu_items_color',
                    'label' => __( 'Menu Items Color', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Menu Items color.', 'htmega-menu' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'menu_items_hover_color',
                    'label' => __( 'Menu Hover & Active Color', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Menu Items Hover color.', 'htmega-menu' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'sub_menu_width',
                    'label' => __( 'Sub Menu Width', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Sub Menu Width.', 'htmega-menu' ),
                    'min'               => 0,
                    'max'               => 1000,
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'floatval'
                ),

                array(
                    'name'  => 'sub_menu_bg_color',
                    'label' => __( 'Sub Menu Background Color', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Menu Background Color.', 'htmega-menu' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'sub_menu_items_color',
                    'label' => __( 'Sub Menu Items Color', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Sub Menu Items Color.', 'htmega-menu' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'sub_menu_items_hover_color',
                    'label' => __( 'Sub Menu Items Hover Color', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Sub Menu Items Hover Color.', 'htmega-menu' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'mega_menu_width',
                    'label' => __( 'Mega Menu Width', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Mega Menu Width.', 'htmega-menu' ),
                    'min'               => 0,
                    'max'               => 1500,
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'floatval'
                ),

                array(
                    'name'  => 'mega_menu_bg_color',
                    'label' => __( 'Mega Menu Background Color', 'htmega-menu' ),
                    'desc' => wp_kses_post( 'Mega Menu Background Color.', 'htmega-menu' ),
                    'type' => 'color',
                ),

            ),

        );
        
        return array_merge( $settings_fields );
    }


    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'HT Menu Settings','htmega-menu' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }
    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'htmega-menu') ?></strong></p>
            </div>
            <?php
        }
    }

    // General tab
    function htmega_menu_html_general_tabs(){
        ob_start();
        ?>
            <div class="htmegamenu-general-tabs">

                <div class="htmegamenu-document-section">
                    <div class="htmegamenu-column">
                        <a href="https://www.youtube.com/watch?v=oAND7tZFidI" target="_blank">
                            <img src="<?php echo HTMEGA_MENU_PL_URL; ?>include/admin/assets/images/video-tutorial.jpg" alt="<?php esc_attr_e( 'Video Tutorial', 'htmega-menu' ); ?>">
                        </a>
                    </div>
                    <div class="htmegamenu-column">
                        <a href="https://demo.hasthemes.com/doc/ht-menu/index.html" target="_blank">
                            <img src="<?php echo HTMEGA_MENU_PL_URL; ?>include/admin/assets/images/online-documentation.jpg" alt="<?php esc_attr_e( 'Online Documentation', 'htmega-menu' ); ?>">
                        </a>
                    </div>
                    <div class="htmegamenu-column">
                        <a href="https://hasthemes.com/contact-us/" target="_blank">
                            <img src="<?php echo HTMEGA_MENU_PL_URL; ?>include/admin/assets/images/genral-contact-us.jpg" alt="<?php esc_attr_e( 'Contact Us', 'htmega-menu' ); ?>">
                        </a>
                    </div>
                </div>

                <div class="menudifferent-pro-free">
                    <h3 class="htmegamenu-section-title"><?php echo esc_html__( 'HT Menu Free VS HT Menu Pro.', 'htmega-menu' ); ?></h3>

                    <div class="htmegamenu-admin-row">
                        <div class="features-list-area">
                            <h3><?php echo esc_html__( 'HT Menu Free', 'htmega-menu' ); ?></h3>
                            <ul>
                                <li><?php echo esc_html__( 'Menu Template Option', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Individual Menu Width Control Option', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Sub Menu Position', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( '5 Menu Layouts', 'htmega-menu' ); ?></li>
                                <li class="htdel"><del><?php echo esc_html__( 'Menu Icon Picker', 'htmega-menu' ); ?></del></li>
                                <li class="htdel"><del><?php echo esc_html__( 'Menu Icon Color', 'htmega-menu' ); ?></del></li>
                                <li class="htdel"><del><?php echo esc_html__( 'Menu Badge', 'htmega-menu' ); ?></del></li>
                                <li class="htdel"><del><?php echo esc_html__( 'Menu Badge Color', 'htmega-menu' ); ?></del></li>
                                <li class="htdel"><del><?php echo esc_html__( 'Menu Badge Background Color', 'htmega-menu' ); ?></del></li>
                            </ul>
                            <a class="button button-primary" href="<?php echo esc_url( admin_url() ); ?>plugin-install.php" target="_blank"><?php echo esc_html__( 'Install Now', 'htmega-menu' ); ?></a>
                        </div>
                        <div class="features-list-area">
                            <h3><?php echo esc_html__( 'HT Menu Pro', 'htmega-menu' ); ?></h3>
                            <ul>
                                <li><?php echo esc_html__( 'Menu Template Option', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Individual Menu Width Control Option', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Sub Menu Position', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( '10 Menu Layouts', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Menu Icon Picker', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Menu Icon Color', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Menu Badge', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Menu Badge Color', 'htmega-menu' ); ?></li>
                                <li><?php echo esc_html__( 'Menu Badge Background Color', 'htmega-menu' ); ?></li>
                            </ul>
                            <a class="button button-primary" href="https://hasthemes.com/ht-mega-menu-for-elementor-page-builder/" target="_blank"><?php echo esc_html__( 'Buy Now', 'htmega-menu' ); ?></a>
                        </div>
                    </div>

                </div>

            </div>
        <?php
        echo ob_get_clean();
    }
    
}

new HTMega_Menu_Admin_Settings();
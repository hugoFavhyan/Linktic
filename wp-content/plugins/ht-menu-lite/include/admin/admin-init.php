<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class HTMega_Menu_Admin_Setting{

    public function __construct(){
        $this->htmegamenu_admin_settings_page();
        add_action('admin_enqueue_scripts', array( $this, 'htmegamenu_enqueue_admin_scripts' ) );
    }

    /*
    *  Setting Page
    */
    public function htmegamenu_admin_settings_page() {
        require_once('include/template-library.php');
        require_once('include/class.settings-api.php');
        require_once('include/Recommended_Plugins.php');
        require_once('include/admin-setting.php');
    }

    /*
    *  Enqueue admin scripts
    */
    public function htmegamenu_enqueue_admin_scripts(){

        wp_enqueue_style( 'htmegamenu-admin', HTMEGA_MENU_PL_URL . 'include/admin/assets/css/admin_optionspanel.css', FALSE, HTMEGA_MENU_VERSION );
    }

}

new HTMega_Menu_Admin_Setting();
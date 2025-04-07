<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

//Cambiamos el logo
add_action( 'login_enqueue_scripts', 'bs_change_login_logo' );
function bs_change_login_logo() { ?> 

<style type="text/css"> 
  
#login h1 a {
background-image: url('https://dev.linktic.com/wp-content/uploads/2022/08/logo-footer.svg');
background-size:100%;
width:80%;
height:120px;
} 
					  
</style>
					  
<?php }

//Cambiamos la URL del logo			  
add_filter( 'login_headerurl', 'bs_login_logo_url' );
function bs_login_logo_url($url) {
    return 'https://dev.linktic.com';}

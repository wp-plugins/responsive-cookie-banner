<?php

/*
* Plugin Name: Responsive Cookie Banner
* Version: 1.1
* Author: Lewis Gray
* Author email: limejelly386 at gmail dot com
* License: GPL2
* Description: A simple, stylish responsive EU cookie banner plugin that will display a message asking if the viewer would like to accept cookies. The message and 'more info' link destination can be changed in the settings menu. Compatible with all devices and browsers.
*/

function loadJquery(){
// Load Jquery
	wp_enqueue_script('jquery');
}

add_action('init', 'loadJquery');

// Register the Javascript & CSS
wp_register_script( 'cookieBannerJs', plugins_url() . '/responsive-cookie-banner/js.js');
wp_register_style( 'cookieBannerCss', plugins_url( '/responsive-cookie-banner/style.css' ) );

function checkCookie(){

// Check if the cookie is set
	if(!isset($_COOKIE['cookie-law']))

// If it is not set then include the banner
		// Javascript
		wp_enqueue_script("cookieBannerJs");
		// CSS
		wp_enqueue_style('cookieBannerCss');
		//HTML
		?>

<!-- Responsive Cookie Banner Wordpress plugin -->

<div id="cookie-banner">

	<div id="cookie-banner-container">
		
		<div class="left">
			<p><?php echo get_option('cookie_banner_text', 'Our website uses cookies. By using our website and agreeing to this policy, you consent to our use of cookies.'); ?></p>
		</div>

		<div class="right">
			<p><input class="accept" name="accept" type="submit" value="ACCEPT" />
				<a class="more-info" target="BLANK" href="<?php echo get_option('cookie_banner_more_info'); ?>" name="more-info" value="MORE INFO">MORE INFO</a>
			</p>
		</div>

	</div>
</div>

<!-- End Responsive Cookie Banner Wordpress plugin -->

		<?php
}

// Call the function into the footer 
add_action('wp_footer', 'checkCookie');

/***************** Options Page *****************/

// create custom plugin settings menu
add_action('admin_menu', 'cookie_banner_menu');

function cookie_banner_menu() {
// Create new options menu
	add_options_page('Responsive Cookie Banner', 'Responsive Cookie Banner', 'administrator', __FILE__, 'cookie_banner_admin_page');

// Call register settings function
	add_action( 'admin_init', 'register_cookie_banner_settings' );
}

function register_cookie_banner_settings() {
// Add options into the settings page
	register_setting( 'cookie-banner-group', 'cookie_banner_text' );
	register_setting( 'cookie-banner-group', 'cookie_banner_more_info' );  
}

// The HTML for the settings page
function cookie_banner_admin_page() {
	?>
	<div class="cookie-banner-options">

		<h2>Responsive Cookie Banner Options</h2>

		<form method="post" name="options-form" class="options-form" action="options.php">

			<?php settings_fields( 'cookie-banner-group' ); ?>
			<?php do_settings_sections( 'cookie-banner-group' ); ?>

			<p>Message text: <textarea style="display:block;" name="cookie_banner_text" id="cookie_banner_text" cols="30" rows="10"><?php echo get_option("cookie_banner_text"); ?></textarea></p>

			<p>'More Info' link URL:<input type="text" style="width: 500px; display:block;" name="cookie_banner_more_info" id="cookie_banner_more_info" value="<?php echo get_option('cookie_banner_more_info'); ?>"></p>

			<p><?php submit_button(); ?></p>

		</form>

	</div>

	<?php
}

/***************** Removal *****************/

function cookie_banner_remove() {
// Deletes the database fields
	delete_option('cookie_banner_text', '', 'yes');
	delete_option('cookie_banner_more_info', '', 'yes');
}

// Run the function on uninstall
register_uninstall_hook( __FILE__, 'cookie_banner_remove' );

?>
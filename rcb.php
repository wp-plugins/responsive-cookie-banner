<?php

/*
* Plugin Name: Responsive Cookie Banner
* Version: 1.3
* Author: Lewis Gray
* Author email: lewisgray200 at gmail dot com
* License: GPL2
* Description: A simple, stylish and responsive EU cookie banner plugin that will display a message asking if the viewer would like to accept cookies. All text and the 'more info' link destination can be changed in the settings menu. Compatible with all devices, languages and browsers.
*/

/**
 * Register and load the CSS/JS
 *
 */

function loadResources()
{
    wp_register_script('CbJs', plugins_url('/responsive-cookie-banner/js/jquery.rcb.js'));
    wp_register_style('CbCss', plugins_url('/responsive-cookie-banner/css/rcb.css'));
    wp_enqueue_script('jquery');
    wp_enqueue_style('CbCss');
    wp_enqueue_script('CbJs');
}

/**
 * Check if the link has a http:// prepended
 *
 * @return Boolean true on if the link is good false if not
 */

function checkAnchor()
{
	$requests = array('http://', 'https://');

	foreach($requests as $request)
		{
			if(is_int(strpos(get_option('rcb_link'), $request)))
			{
				return true;
			}
		}

	return false;
}

/**
 * Insert the banner on to the page
 *
 */

function insertBanner()
{
	$newWindow = get_option('rcb_new_window'); 
?>

<div id="cookie-banner">
	<div id="cookie-banner-container">

		<div class="left">
				<?php echo get_option('rcb_text', 'Our website uses cookies. By using our website and agreeing to this policy, you consent to our use of cookies.'); ?>
		</div>

		<div class="right">

				<a class="accept" href="#"><?php echo get_option('rcb_accept_text', 'ACCEPT'); ?></a>

				<a class="more-info" href="<?php

					if(!checkAnchor())
					{
						echo 'http://';
					}

					echo get_option('rcb_link'); 

					?>"
					
					<?php 

					if($newWindow)
					{
						echo 'target="blank"';
					} 

					?>

					>

					<?php echo get_option('rcb_more_info_text', 'MORE INFO'); ?>
				</a>
		</div>

	</div>
</div>

<?php
}


/**
 * Register the cookie banner settings
 *
 */

function registerSettings()
{
	register_setting('rcb_group', 'rcb_new_window');
	register_setting('rcb_group', 'rcb_text');
	register_setting('rcb_group', 'rcb_accept_text');
	register_setting('rcb_group', 'rcb_more_info_text');
	register_setting('rcb_group', 'rcb_link' );
}

/**
 * Add the options page
 *
 */

function cookieMenu() 
{
	add_options_page('RCB', 'RCB', 'administrator', __FILE__, 'adminPage');
}

/**
 * Insert the admin options page
 *
 */

function adminPage()
{
	?>
		<h1>Responsive Cookie Banner</h1>

		<form method="post" name="options-form" class="options-form" action="options.php">

			<?php 

			settings_fields('rcb_group');
			do_settings_sections('rcb_group'); 

			?>
			
			<div id="message_text">
			<label for="rcb_text">Message text:</label><textarea style="display:block; width: 500px;" name="rcb_text" id="rcb_text" cols="30" rows="10"><?php echo get_option("rcb_text", 'Our website uses cookies. By using our website and agreeing to this policy, you consent to our use of cookies.'); ?>
			 </textarea>
			</div>

			<div id="accept_text" style="margin-top:10px;">
				<label for="rcb_accept_text">'Accept' button text:</label>
				<input type="text" style="width: 500px; display:block;" name="rcb_accept_text" id="rcb_accept_text" value="<?php echo get_option('rcb_accept_text', 'ACCEPT'); ?>">
			</div>

			<div id="more_info_text" style="margin-top:10px;">
				<label for="rcb_more_info_text">'More info' link text:</label>
				<input type="text" style="width: 500px; display:block;" name="rcb_more_info_text" id="rcb_more_info_text" value="<?php echo get_option('rcb_more_info_text', 'MORE INFO'); ?>">
			</div>

			<div id="url" style="margin-top:10px;">
				<label for="rcb_link">'More info' link URL:</label>
				<input type="text" style="width: 500px; display:block;" name="rcb_link" id="rcb_link" value="<?php echo get_option('rcb_link'); ?>">
			</div>

			<div id="same_window" style="margin-top:10px;">
				<label for="rcb_new_window">Open in a new window?</label>
				<input name="rcb_new_window" id="rcb_new_window" type="checkbox" value="1" 
				<?php checked('1', get_option('rcb_new_window')); ?> />
			</div>

			<div id="submit">
				<?php submit_button(); ?>
			</div>

		</form>

	<?php
}

/**
 * Add a settings link on the plugin page
 *
 */

function your_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=responsive-cookie-banner%2Frcb.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );

/**
 * Remove the banner and all options
 *
 */

/**
 * Remove the banner and all options
 *
 */

function remove()
{
	delete_option('rcb_text');
	delete_option('rcb_accept_text');
	delete_option('rcb_more_info_text');
	delete_option('rcb_link');
	delete_option('same_window');
}

add_action('wp_enqueue_scripts', 'loadResources');
add_action('wp_footer', 'insertBanner');

add_action('admin_menu', 'cookieMenu');
add_action('admin_init', 'registerSettings');

register_uninstall_hook(__FILE__, 'remove');

?>
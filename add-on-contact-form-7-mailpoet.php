<?php
/**
 * Plugin Name:       Add-on Contact Form 7 - Mailpoet 3
 * Description:       Add a MailPoet 3 signup field to your Contact Form 7 forms.
 * Version:           1.0.0
 * Author:            Tikweb
 * Author URI:        http://www.tikweb.dk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mpcf7
 * Domain Path:       /languages
*/


/*
Contact Form 7 - MailPoet 3 Integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Contact Form 7 - MailPoet 3 Integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Contact Form 7 - MailPoet 3 Integration. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

// If this file is called directly, abort.
if(!defined( 'WPINC' )){
	die;
}

if(!defined('ABSPATH')){
	exit;
}

/**
 * Define root path
 */
if(!defined('MCFI_ROOT_PATH')){
	$mbh_root = plugin_dir_path(__FILE__);
	define('MCFI_ROOT_PATH', $mbh_root);
}


/**
 * If php version is lower
 */
if(version_compare(phpversion(), '5.4', '<')){
	function mailpoet_cfi_php_version_notice(){
		?>
		<div class="error">
			<p><?php _e('Contact Form 7 - MailPoet 3 Integration plugin requires PHP version 5.4 or newer, Please upgrade your PHP.', 'mpcf7'); ?></p>
		</div>
		<?php
	}
	add_action('admin_notices', 'mailpoet_cfi_php_version_notice');
	return;
}

/**
 * Include plugin.php to detect plugin.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Check MailPoet active
 * Prerequisite
 */
if(!is_plugin_active('mailpoet/mailpoet.php')){
	add_action('admin_notices', function(){
		?>
		<div class="error">
			<p>
			<?php
				$name = 'Contact Form 7 - MailPoet 3 Integration';
				$mp_link = '<a href="https://wordpress.org/plugins/mailpoet/" target="_blank">MailPoet</a>';
				printf(
					__('%s plugin requires %s plugin, Please activate %s first to using %s.', 'mpcf7'),
					$name,
					$mp_link,
					$mp_link,
					$name
				);
			?>
			</p>
		</div>
		<?php
	});
	return;	// If not then return
}


/**
 * Check Contact Form 7 active
 * Prerequisite
 */
if(!is_plugin_active('contact-form-7/wp-contact-form-7.php')){
	add_action('admin_notices', function(){
		?>
		<div class="error">
			<p>
			<?php
				$name = 'Contact Form 7 - MailPoet 3 Integration';
				$cf7_link = '<a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a>';
				printf(
					__('%s plugin requires %s plugin, Please activate %s first to using %s.', 'mpcf7'),
					$name,
					$cf7_link,
					$cf7_link,
					$name
				);
			?>
			</p>
		</div>
		<?php
	});
	return;	// If not then return
}

/**
 * The core plugin class
 * that is used to define Actions and settings.
 */
require_once MCFI_ROOT_PATH . 'includes/class-mailpoet-cf7-integration.php';

/**
 * Process data after submit form
 */
require_once MCFI_ROOT_PATH . 'includes/class-mailpoet-cf7-submit-form.php';

<?php
/*
 * Plugin Name: Informer
 * Plugin URI: https://subhajit.wisdmlabs.net
 * Author: Subhajit Bera
 * Author URI: https://subhajit.wisdmlabs.net
 * Description: Posts summary on Admin Mail at End of the Day
 * Text Domain: informer
 */

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

if (is_admin()) {
    include_once plugin_dir_path(__FILE__) . 'sendmail.php';
}

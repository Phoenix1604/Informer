<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Informer
 * @subpackage Informer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Informer
 * @subpackage Informer/includes
 * @author     Subhajit Bera <subhajit.bera@wisdmlabs.com>
 */
class Informer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	
    	if (!wp_next_scheduled('sb_send_dailypost_summary')) {
        	wp_schedule_event(time(), 'hourly', 'sb_send_dailypost_summary');
    	}

	}

}

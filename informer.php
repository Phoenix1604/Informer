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
    include plugin_dir_path(__FILE__) . 'sendmail.php';
}

//One Minute Interval For Testing
function sb_cron_intervals($schedules)
{
    // one minute
    $one_minute = array(
        'interval' => 60,
        'display' => 'One Minute',
    );

    $schedules['one_minute'] = $one_minute;
    // return data
    return $schedules;
}
add_filter('cron_schedules', 'sb_cron_intervals');

//Schedule Event
register_activation_hook(__FILE__, 'sb_schedule_dailypost_summary');
function sb_schedule_dailypost_summary()
{
    if (!wp_next_scheduled('sb_send_dailypost_summary')) {
        wp_schedule_event(time(), 'hourly', 'sb_send_dailypost_summary');
    }
}
add_action('sb_send_dailypost_summary', 'sb_send_daily_postsummary_callback');
// remove cron event
function wpcron_deactivation()
{

    wp_clear_scheduled_hook('sb_send_dailypost_summary');

}
register_deactivation_hook(__FILE__, 'wpcron_deactivation');

<?php

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
add_action('wp', 'sb_schedule_daily_post_summary');
function sb_schedule_daily_post_summary()
{
    if (!wp_next_scheduled('sb_send_daily_post_summary')) {
        wp_schedule_event(time(), 'one_minute', 'sb_send_daily_post_summary');
    }
}

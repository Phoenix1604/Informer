<?php

if (is_admin()) {
    include plugin_dir_path(__FILE__) . 'getmaildetails.php';
}

function sb_send_daily_postsummary_callback()
{
    $to = get_option('admin_email');
    $subject = 'Daily Post Summary';
    $summary = sb_get_daily_post_summary();
    $message = '';

    foreach ($summary as $post_data) {
        $message .= 'Title: ' . $post_data['title'] . "\n";
        $message .= 'URL: ' . $post_data['url'] . "\n";
        $message .= 'Meta Title: ' . $post_data['meta_title'] . "\n";
        $message .= 'Meta Description: ' . $post_data['meta_description'] . "\n";
        // $message .= 'Meta Keywords: ' . $post_data['meta_keywords'] . "\n";
        // $message .= 'Page Speed Score: ' . $post_data['page_speed'] . " seconds \n";
        $message .= "\n";
    }
    $headers = array(
        'From: subhajit.bera@wisdmlabs.com',
    );

    wp_mail($to, $subject, $message, $headers);

}

<?php

if (is_admin()) {
    include plugin_dir_path(__FILE__) . 'getMetaInfo.php';
}

function sb_get_daily_post_summary()
{
    $today = getdate();
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'date_query' => array(
            array(
                'year' => $today['year'],
                'month' => $today['mon'],
                'day' => $today['mday'],
            ),
        ),
    );
    $posts = get_posts($args);
    $summary = array();
    foreach ($posts as $post) {
        //checking for the meta tag
        $url = get_permalink($post);

        //call the post url and get actual html as a string
        $response = wp_remote_retrieve_body(wp_remote_get($url));
        $metaDescriptionMsg = informer_found_meta_description($response);
        $metaTitle = informer_found_meta_title($response);

        $post_data = array(
            'title' => get_the_title($post),
            'url' => $url,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescriptionMsg,
            // 'meta_keywords' => get_post_meta($post->ID, '_yoast_wpseo_focuskw', true),
            // 'page_speed' => get_page_speed_score(get_permalink($post->ID)),
        );
        array_push($summary, $post_data);

    }

    return $summary;
}

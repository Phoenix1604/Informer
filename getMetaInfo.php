<?php

function informer_found_meta_description($htmlResponse)
{
    $word = '<meta name="description" content="';

    $index = strpos($htmlResponse, $word);
    $metaDescriptionMsg = '';

    if ($index !== false) {

        //Get the end index of the meta tag
        $end = strpos($htmlResponse, '>', $index);
        //Exclude the <meta name="description" Content=" part and get the only content
        $start = $index + 34;
        $length = $end - $start - 3;
        $metaDescriptionMsg = substr($htmlResponse, $start, $length);

    } else {
        $metaDescriptionMsg = "No Meta Description Found";
    }

    return $metaDescriptionMsg;

}

function informer_found_meta_title($htmlResponse)
{
    $word = '<title>';
    $index = strpos($htmlResponse, $word);
    $metaTitle = '';
    if ($index !== false) {
        $end = strpos($htmlResponse, '</title>', $index);
        $start = $index + 7;
        $length = $end - $start;
        $metaTitle = substr($htmlResponse, $start, $length);
    } else {
        $metaTitle = "No Title Found";
    }

    return $metaTitle;
}

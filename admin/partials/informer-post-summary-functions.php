<?php 

function sb_get_daily_post_summary() {
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
            'page_speed' => sb_get_page_speed_score($url),
        );
        array_push($summary, $post_data);

    }

    return $summary;
}


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

    function sb_get_page_speed_score($url)
	{

		$api_key = "416ca0ef-63e4-4caa-a047-ead672ecc874"; // your api key
		$new_url = "http://www.webpagetest.org/runtest.php?url=" . $url . "&runs=1&f=xml&k=" . $api_key;
		$run_result = simplexml_load_file($new_url);
		$response_status_code = $run_result->statusCode;
		if($response_status_code != 200){
			return $run_result->statusText;
		}
		$test_id = $run_result->data->testId;

		$status_code = 100;

		while ($status_code != 200) {
			sleep(10);
			$xml_result = "http://www.webpagetest.org/xmlResult/" . $test_id . "/";
			$result = simplexml_load_file($xml_result);
			$status_code = $result->statusCode;
			$time = (float) ($result->data->median->firstView->loadTime) / 1000;
		}

		return $time;

	}
?>
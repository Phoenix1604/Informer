<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Informer
 * @subpackage Informer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Informer
 * @subpackage Informer/admin
 * @author     Subhajit Bera <subhajit.bera@wisdmlabs.com>
 */
class Informer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Informer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Informer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/informer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Informer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Informer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/informer-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function sb_add_cron_schedules ($schedules = array()) 
	{

		$schedules['one_minute'] = array(
			'interval' => 60,
			'display' => __('One Minute', 'informer'),
		);
		return $schedules;
	}

	
	function sb_send_daily_postsummary_callback()
	{
		$to = get_option('admin_email');
		$subject = 'Daily Post Summary';
		//$summary = self::sb_get_daily_post_summary();
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
			$htmlResponse = wp_remote_retrieve_body(wp_remote_get($url));
			//$metaDescriptionMsg = self::informer_found_meta_description($response);
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

			// $metaTitle = self::informer_found_meta_title($response);
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


			$post_data = array(
				'title' => get_the_title($post),
				'url' => $url,
				'meta_title' => $metaTitle,
				'meta_description' => $metaDescriptionMsg,
				//'page_speed' => self::sb_get_page_speed_score($url),
			);
			array_push($summary, $post_data);

		}
		$message = '';

		foreach ($summary as $post_data) {
			$message .= 'Title: ' . $post_data['title'] . "\n";
			$message .= 'URL: ' . $post_data['url'] . "\n";
			$message .= 'Meta Title: ' . $post_data['meta_title'] . "\n";
			$message .= 'Meta Description: ' . $post_data['meta_description'] . "\n";
			$message .= 'Page Speed Score: ' . $post_data['page_speed'] . " seconds \n";
			$message .= "\n";
		}
		$headers = array(
			'From: subhajit.bera@wisdmlabs.com',
		);

		wp_mail($to, $subject, $message, $headers);
	}

	/*function sb_get_daily_post_summary() {
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
			$metaDescriptionMsg = self::informer_found_meta_description($response);
			$metaTitle = self::informer_found_meta_title($response);

			$post_data = array(
				'title' => get_the_title($post),
				'url' => $url,
				'meta_title' => $metaTitle,
				'meta_description' => $metaDescriptionMsg,
				//'page_speed' => self::sb_get_page_speed_score($url),
			);
			array_push($summary, $post_data);

		}

		return $summary;
	}*/
	/*function informer_found_meta_description($htmlResponse)
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

	}*/

	/*function informer_found_meta_title($htmlResponse)
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
	}*/

	/*function sb_get_page_speed_score($url)
	{

		$api_key = "416ca0ef-63e4-4caa-a047-ead672ecc874"; // your api key
		$new_url = "http://www.webpagetest.org/runtest.php?url=" . $url . "&runs=1&f=xml&k=" . $api_key;
		$run_result = simplexml_load_file($new_url);
		$test_id = $run_result->data->testId;

		$status_code = 100;

		while ($status_code != 200) {
			sleep(10);
			$xml_result = "http://www.webpagetest.org/xmlResult/" . $test_id . "/";
			$result = simplexml_load_file($xml_result);
			$status_code = $result->statusCode;
			$time = (float) ($result->data->median->firstView->loadTime) / 1000;
		}
		;

		return $time;

	}*/



}

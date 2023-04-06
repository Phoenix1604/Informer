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

	
	function sb_send_daily_postsummary_callback(){

		include_once plugin_dir_path(__FILE__) .'partials/informer-post-summary-functions.php';
		$to = get_option('admin_email');
		$subject = 'Daily Post Summary';
		$summary = sb_get_daily_post_summary();
		$message = '';

		foreach ($summary as $post_data) {
			$message .= 'Title: ' . $post_data['title'] . "\n";
			$message .= 'URL: ' . $post_data['url'] . "\n";
			$message .= 'Meta Title: ' . $post_data['meta_title'] . "\n";
			$message .= 'Meta Description: ' . $post_data['meta_description'] . "\n";
			$message .= 'Page Speed Score: ' . $post_data['page_speed'];
			is_float($post_data['page_speed'])? $message .= " seconds" : $message .= "";
			$message .= "\n";
		}
		$headers = array(
			'From: subhajit.bera@wisdmlabs.com',
		);

		wp_mail($to, $subject, $message, $headers);
	}
}

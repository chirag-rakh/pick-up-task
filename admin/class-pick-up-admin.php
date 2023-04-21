<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://chirag.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Pick_Up
 * @subpackage Pick_Up/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pick_Up
 * @subpackage Pick_Up/admin
 * @author     Chirag Rakh <chirag.rakh@wisdmlabs.com>
 */
class Pick_Up_Admin {

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
		 * defined in Pick_Up_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pick_Up_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pick-up-admin.css', array(), $this->version, 'all' );

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
		 * defined in Pick_Up_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pick_Up_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pick-up-admin.js', array( 'jquery' ), $this->version, false );

	}

	// Add custom post type for store locations
	function register_store_post_type()
	{
		$labels = array(
			'name' => __('Stores'), //Post page title
			'singular_name' => __('Store'), //Adds the option in the '+ New' tab in admin top navbar
			'menu_name' => __('Store Pickup'), //Displayed name in the dashboard
			'add_new' => __('Add New'), 
			'add_new_item' => __('Add New Store'),
			'edit' => __('Edit'),
			'edit_item' => __('Edit Store'),
			'new_item' => __('New Store'),
			'view' => __('View Store'),
			'all_items' => __('View Stores'),
			'view_item' => __('View Store'),
			'search_items' => __('Search Stores'),
			'not_found' => __('No stores found'),
			'not_found_in_trash' => __('No stores found in trash'),
		);
		$args = array(
			'labels' => $labels,
			'public' => false,
			'menu_position' => 5,
			'menu_icon' => 'dashicons-store',
			'supports' => array('title'),
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'store'),
		);
		register_post_type('store', $args);
	}

	// Add store details meta box to store post type
	function add_store_details_meta_box()
	{
		add_meta_box(
			'store_details_meta_box', //like id of meta box
			__('Store Details', 'woo-pickup'), //title of the metabox
			array($this, 'render_store_details_meta_box'), //callback function used to render the content
			'store', //post type to which the meta box should be added
			'normal', //
			'default' //priority
		);
	}

	// Render store details meta box
	function render_store_details_meta_box($post)
	{
		wp_nonce_field('save_store_details', 'store_details_nonce');
		$store_name = get_post_meta($post->ID, '_store_name', true);
		$store_address = get_post_meta($post->ID, '_store_address', true);
		$store_phone = get_post_meta($post->ID, '_store_phone', true);
		$store_email = get_post_meta($post->ID, '_store_email', true);
		$store_location_url = get_post_meta($post->ID, '_store_location_url', true);
		echo '<table class="form-table">';
		echo '<tr><th><label for="store_name">' . __('Store Name', 'woo-pickup') . '</label></th><td>';
		echo '<input type="text" id="store_name" name="store_name" value="' . esc_attr($store_name) . '">';
		echo '</td></tr>';
		echo '<tr><th><label for="store_address">' . __('Store Address', 'woo-pickup') . '</label></th><td>';
		echo '<textarea id="store_address" name="store_address">' . esc_textarea($store_address) . '</textarea>';
		echo '</td></tr>';
		echo '<tr><th><label for="store_phone">' . __('Store Phone', 'woo-pickup') . '</label></th><td>';
		echo '<input type="text" id="store_phone" name="store_phone" value="' . esc_attr($store_phone) . '">';
		echo '</td></tr>';
		echo '<tr><th><label for="store_email">' . __('Store Email', 'woo-pickup') . '</label></th><td>';
		echo '<input type="text" id="store_email" name="store_email" value="' . esc_attr($store_email) . '">';
		echo '</td></tr>';
		echo '<tr><th><label for="store_location_url">' . __('Store Location URL', 'woo-pickup') . '</label></th><td>';
		echo '<input type="text" id="store_location_url" name="store_location_url" value="' . esc_attr($store_location_url) . '">';
		echo '</td></tr>';
		echo '</table>';
	}

	// Save store details meta box data
	function save_store_details_meta_box_data($post_id)
	{
		if (!isset($_POST['store_details_nonce']) || !wp_verify_nonce($_POST['store_details_nonce'], 'save_store_details')) {
			return;
		}
		if (isset($_POST['store_name'])) {
			update_post_meta($post_id, '_store_name', sanitize_text_field($_POST['store_name']));
		}
		if (isset($_POST['store_address'])) {
			update_post_meta($post_id, '_store_address', sanitize_textarea_field($_POST['store_address']));
		}
		if (isset($_POST['store_phone'])) {
			update_post_meta($post_id, '_store_phone', sanitize_text_field($_POST['store_phone']));
		}
		if (isset($_POST['store_email'])) {
			update_post_meta($post_id, '_store_email', sanitize_email($_POST['store_email']));
		}
		if (isset($_POST['store_location_url'])) {
			update_post_meta($post_id, '_store_location_url', esc_url_raw($_POST['store_location_url']));
		}
	}

	

	// Add pickup store dropdown and date picker to checkout page
	function add_pickup_store_to_checkout()
	{
		//Hide Shipping method amd shipping address on checkout page
		function custom_checkout_css() {
			?>
			<style>
				.col-2{
					display: none !important;
				}
			</style>
			<?php
		}
		
		$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

		$pickup_stores = get_posts(array(
			'post_type' => 'store',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
		));

		//Hide shipping totals
		?>
			<style>
				.woocommerce-shipping-totals{
					display:none !important;
				}
			</style>
			<?php
		
		if ($pickup_stores && in_array( 'local_pickup:2', $chosen_methods )) {
			custom_checkout_css();

			echo '<div id="pickup_store">';
			woocommerce_form_field('pickup_store', array(
				'type' => 'select',
				'label' => __('Pickup Store', 'woo-pickup'),
				'required' => true,
				'options' => array(
					'' => __('Select Pickup Store', 'woo-pickup')
				) + wp_list_pluck($pickup_stores, 'post_title', 'ID')
			));
			echo '</div>';
			echo '<div id="pickup_date">';
			woocommerce_form_field('pickup_date', array(
				'type' => 'date',
				'label' => __('Pickup Date', 'woo-pickup'),
				'required' => true,
				'autocomplete' => 'off',
				'custom_attributes' => array(
					'min' => date('Y-m-d', strtotime('+1 day')) // set the minimum date as tomorrow's
				)
			));
			echo '</div>';
		}
	}


	// Validate pickup store and date fields
	function validate_pickup_store_and_date_fields()
	{
		if (!$_POST['pickup_store'] || $_POST['pickup_store'] == '') {
			wc_add_notice(__('Please select a pickup store.', 'woo-pickup'), 'error');
		}
		if (!$_POST['pickup_date'] || $_POST['pickup_date'] == '') {
			wc_add_notice(__('Please select a pickup date.', 'woo-pickup'), 'error');
		}
	}

	// Save pickup store and date fields to order meta data
	function save_to_order_meta_data($order)
	{
		if ($_POST['pickup_store'] && $_POST['pickup_store'] != '') {
			$order->update_meta_data('_pickup_store', sanitize_text_field($_POST['pickup_store']));
		}
		if ($_POST['pickup_date'] && $_POST['pickup_date'] != '') {
			$order->update_meta_data('_pickup_date', sanitize_text_field($_POST['pickup_date']));
		}
	}

	// Add pickup store details to order admin page
	function order_admin_page_modifications($order)
	{
		$pickup_store = $order->get_meta('_pickup_store');
		$pickup_date = $order->get_meta('_pickup_date');
		if ($pickup_store && $pickup_store != '') {

			echo '<div class="address" style="word-wrap: break-word;">';
			echo '<h3>' . __('Pickup Store Details', 'woo-pickup') . '</h3>';
			$store = get_post($pickup_store);
			echo '<p><strong>' . __('Store Name', 'woo-pickup') . ':</strong> ' . esc_html($store->post_title) . '</p>';
			echo '<p><strong>' . __('Address', 'woo-pickup') . ':</strong> ' . esc_html(get_post_meta($pickup_store, '_store_address', true)) . '</p>';
			echo '<p><strong>' . __('Phone', 'woo-pickup') . ':</strong> ' . esc_html(get_post_meta($pickup_store, '_store_phone', true)) . '</p>';
			echo '<p><strong>' . __('Email', 'woo-pickup') . ':</strong> ' . esc_html(get_post_meta($pickup_store, '_store_email', true)) . '</p>';
			echo '<p><strong>' . __('Store Location', 'woo-pickup') . ':</strong> <a href="' . esc_url(get_post_meta($pickup_store, '_store_location_url', true)) . '">' . "Get Store Location on Google Maps" . '</a></p>';
			if ($pickup_date && $pickup_date != '') {
				echo '<p><strong>' . __('Pickup Date', 'woo-pickup') . ':</strong> ' . esc_html($pickup_date) . '</p>';
			}
			echo '</div>';
		}
	}

	// Add pickup store details to order confirmation email
	function add_store_details_to_confirmation_mail($order, $sent_to_admin, $plain_text, $email)
	{

			// get the pickup store and pickup date from the order meta
			$pickup_store = $order->get_meta('_pickup_store');
			$pickup_date = $order->get_meta('_pickup_date');
			$store = get_post( $pickup_store );
	
			// add the pickup store and pickup date to the email
			echo '<p><strong>Pickup Store:</strong> ' . esc_html(get_post_meta($pickup_store, '_store_name', true)) . '</p>';
			echo '<p><strong>Store Address:</strong> ' . esc_html(get_post_meta($pickup_store, '_store_address', true)) . '</p>';
			echo '<p><strong>Store Phone:</strong> ' . esc_html(get_post_meta($pickup_store, '_store_phone', true)) . '</p>';
			echo '<p><strong>Store Email:</strong> ' . esc_html(get_post_meta($pickup_store, '_store_email', true)) . '</p>';
			echo '<p><strong>Store Location:</strong> ' . esc_html(get_post_meta($pickup_store, '_store_location_url', true)) . '</p>';
			
			echo '<p><strong>Pickup Date:</strong> ' . date_i18n(get_option('date_format'), strtotime($pickup_date)) . '</p>';

	}

	//Schedule Reminder mail for one day before pickup
	function send_pickup_reminder_email($order_id)
	{
		// Retrieve the pickup date from the order
		$order = wc_get_order($order_id);
		$pickup_date = $order->get_meta('_pickup_date');

		$datetime_object = new DateTime($pickup_date, new DateTimeZone('Asia/Kolkata'));
		$timestamp = $datetime_object->getTimestamp(); //2023-04-21 12:00:00
		$formatted_timestamp = $timestamp - 86400 ; //2023-04-20 12:00:00

		wp_schedule_single_event($formatted_timestamp, 'send_pickup_reminder_email_cron', array($order_id));
	}

	function send_pickup_reminder_email_callback($order_id)
	{
		// Retrieve the customer email address
		$order = wc_get_order($order_id);
		$pickup_store = $order->get_meta('_pickup_store');
		$pickup_date = $order->get_meta('_pickup_date');

		$customer_id = $order->get_customer_id();

		if ($customer_id) {
			$customer = new WC_Customer($customer_id);
			$customer_email = $customer->get_email();
			$customer_first_name = $customer->get_first_name();
		} else {
			$customer_email = $order->get_billing_email();
			$customer_first_name = $order->get_billing_first_name();
		}

		$store = get_post($pickup_store);
		$store_owner_email = get_post_meta($pickup_store, '_store_email', true);

		// Create and send the email reminder
		if ($store_owner_email && $store_owner_email != '') {
			$subject = sprintf(__('Hey Reminder to Pickup Your order #%s', 'woo-pickup'), $order->get_order_number());
			$message = '';
			$message .= 'Hey, ' . $customer_first_name . "\n";
			$message .= 'Tomorrow, Your order ' . $order->get_order_number() . ' will be Ready to Pickup at Store ' . $store->post_title . "\n\n";
			$message .= 'Order Details-> ' . "\n";
			$message .= 'Pickup Date: ' . $pickup_date . "\n";
			$message .= 'Customer Name: ' . $customer_first_name . "\n";
			$message .= 'Customer Email: ' . $customer_email . "\n\n";

			$message .= 'Pickup Store Details->' . "\n";
			$message .= 'Store Name: ' . $store->post_title . "\n";
			$message .= 'Address: ' . esc_html(get_post_meta($pickup_store, '_store_address', true)) . "\n";
			$message .= 'Contact: ' . esc_html(get_post_meta($pickup_store, '_store_phone', true)) . "\n";
			$message .= 'Email: ' . esc_html(get_post_meta($pickup_store, '_store_email', true)) . "\n";
			$message .= 'Location: ' . esc_html(get_post_meta($pickup_store, '_store_location_url', true)) . "\n\n";
			$message .= 'Please Make sure you carry Identity proof at time of pickup!';

			$headers = "From: {$store_owner_email}";

			wp_mail($customer_email, $subject, $message, $headers);
		}
	}

}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://lenineto.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Smart_Coupons
 * @subpackage Woocommerce_Smart_Coupons/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Smart_Coupons
 * @subpackage Woocommerce_Smart_Coupons/admin
 * @author     Leni Neto <leni@cyber5.net>
 */
class Woocommerce_Smart_Coupons_Admin {

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
     * @since   1.0.0
     * @access  private
     * @var     string      $settings_tab_id    Id of the WooCommerce Settings tab we are creating
     */
    public $settings_tab_id = 'c5-smart-coupons';

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
		 * defined in Woocommerce_Smart_Coupons_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Smart_Coupons_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-smart-coupons-admin.css', array(), $this->version, 'all' );

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
		 * defined in Woocommerce_Smart_Coupons_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Smart_Coupons_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-smart-coupons-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function add_woocommerce_settings_tab( $settings_tabs ) {
        /**
         * Add settings tab to woocommerce
         */

        $settings_tabs[ $this->settings_tab_id ] = __( 'Smart Coupons', 'woocommerce-smart-coupons' );
        return $settings_tabs;
    }

    public function woocommerce_settings_tab_action() {
        /**
         * Do this when viewing our custom settings tab(s). One function for all tabs.
         */
        woocommerce_admin_fields( $this->get_settings() );
    }

    public function woocommerce_settings_save() {
        /**
         * Save settings in a single field in the database for each tab's fields (one field per tab).
         */
        woocommerce_update_options( $this->get_settings() );
    }

    public function woocommerce_coupon_data_panels() {
        global $thepostid;

        //$autoempty_value = get_post_meta($thepostid, 'c5_auto_empty_cart', true);
        //$autoempty_checked = ($autoempty_value == 'yes') ? 'yes' : '';
        //$autoadd_value = get_post_meta($thepostid, 'c5_auto_add_product_enable', true);
        //$autoadd_checked = ($autoempty_value == 'yes') ? 'yes' : '';
        $selected_products = get_post_meta($thepostid, 'c5_coupon_autoadd_products', true);
        $redirect_type = get_post_meta($thepostid, 'c5_coupon_redirect', true);
        if ($redirect_type == 'custom')
            $redirect_url = get_post_meta($thepostid, 'c5_coupon_redirect_url', true);

	    $products = wc_get_products( array( 'status' => 'published') );
	    $redirect_types = array(
	        'cart'      => 'cart',
            'checkout'  => 'checkout',
            'custom'    => 'custom'
        );

        foreach ($products as $product) {
            $product_list[$product->get_id()] = $product->get_name();
        }
        include ('partials/woocommerce-smart-coupons-autoadd.php');
    }

    public function woocommerce_coupon_data_tabs($tabs) {
	    $tabs['smart_coupons'] = array(
	        'label'     => 'Smart Coupon',
            'target'    => 'smart_coupon_options',
            'class'     => ''
        );
	    return $tabs;
    }

    public function woocommerce_coupon_options_save($post_id = null) {
	    $auto_empty_cart = isset($_POST['c5_auto_empty_cart']) ? $_POST['c5_auto_empty_cart'] : 'no';
        $auto_add_enabled = isset($_POST['c5_auto_add_product_enable']) ? $_POST['c5_auto_add_product_enable'] : 'no';
	    $product_list = isset($_POST['c5_coupon_autoadd_products']) ? $_POST['c5_coupon_autoadd_products'] : '';
	    $redirect_type = isset($_POST['c5_coupon_redirect']) ? $_POST['c5_coupon_redirect'] : 'cart';
	    $redirect_url = isset($_POST['c5_coupon_redirect_url']) ? $_POST['c5_coupon_redirect_url'] : '';

	    update_post_meta($post_id, 'c5_auto_empty_cart', $auto_empty_cart);
        update_post_meta($post_id, 'c5_auto_add_product_enable', $auto_add_enabled);
	    update_post_meta($post_id, 'c5_coupon_autoadd_products', $product_list);
	    update_post_meta($post_id, 'c5_coupon_redirect', $redirect_type);
	    update_post_meta($post_id, 'c5_coupon_redirect_url', $redirect_url);
    }

    public function get_settings() {
	    /**
         * Retrieve all settings on our tab
         */
	    return apply_filters('woocommerce_smart_coupon_get_settings',
            array(
                array(
                    'name'  => __( 'WooCommerce Smart Coupons Settings', 'woocommerce-smart-coupons'),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'smart_coupons_general'
                ),
                array(
                    'name'      => __( 'Smart Coupon URL', 'woocommerce-smart-coupons'),
                    'type'      => 'text',
                    'desc'      => __( 'Define the URL that will process coupon to be automatically applied'),
                    'default'   => 'autocoupom',
                    'id'        => 'c5_coupon_url',
                    'desc_tip'  => true
                ),
                array( 'type' => 'sectionend', 'id' => 'smart_coupons_general_end' )
            )
        );
    }

}

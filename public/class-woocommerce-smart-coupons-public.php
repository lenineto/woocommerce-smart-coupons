<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Smart_Coupons
 * @subpackage Woocommerce_Smart_Coupons/public
 * @author     Leni Neto <leni@cyber5.net>
 */

class Woocommerce_Smart_Coupons_Public {

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
     * @access  public
     * @var     string     $coupon_url  The URL to process for automatic URL coupons 
     */
	public $coupon_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->coupon_url = get_option('c5_coupon_url');

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-smart-coupons-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-smart-coupons-public.js', array( 'jquery' ), $this->version, false );

	}

	public function parse_request() {
	    if (is_admin())
	        return;

	    /** @var  string $coupon_url  Let's normalize the coupon URL */
	    $coupon_url = trim(str_replace('/', '', $this->coupon_url) );

        if( isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/'.$coupon_url) !== false ) {
            $coupons = explode('/', $_SERVER['REQUEST_URI'] );
            $coupons[] = '###LAST###';
            if (sizeof($coupons) > 2) {
                foreach ($coupons as $coupon) {
                    if ($coupon !== '' && $coupon !== $coupon_url) {

                        if ($coupon !== '###LAST###') {
                            $coupon_id = wc_get_coupon_id_by_code($coupon);
                            /** check if we should add products */
                            if (get_post_meta($coupon_id, 'c5_auto_add_product_enable', true) == 'yes' ) {
                                /** retrieve the products list */
                                $products = get_post_meta($coupon_id, 'c5_coupon_autoadd_products', true);
                                if (sizeof($products) > 0) {
                                    /** we have products, let's add them */
                                    foreach ($products as $product) {
                                        $this->add_to_cart($product);
                                    }
                                }
                            }
                            /** apply the coupon if it has not been applied yet*/
                            $coupon_found = false;
                            if (sizeof(WC()->cart->get_applied_coupons()) > 0) {
                                foreach (WC()->cart->get_applied_coupons() as $_coupon){
                                    if ($coupon == $_coupon)
                                        $coupon_found = true;
                                }
                                if (!$coupon_found)
                                    /** not applied yet, let's apply it */
                                    WC()->cart->apply_coupon($coupon);
                            } else {
                                /** there are no coupons applied yet, so its safe to apply it */
                                WC()->cart->apply_coupon($coupon);
                            }
                        } else {
                            /** this was the last coupon, so let's redirect */
                            $redirect_type = get_post_meta($coupon_id, 'c5_coupon_redirect', true);
                            switch ( $redirect_type ) {
                                case 'cart':
                                    wp_safe_redirect( wc_get_cart_url() );
                                    break;
                                case 'checkout':
                                    wp_safe_redirect( wc_get_checkout_url() );
                                    break;
                                case 'custom':
                                    $url = get_post_meta($coupon_id, 'c5_coupon_redirect_url', true);
                                    wp_redirect( $url );
                                    break;
                            }
                            exit;
                        }
                    }
                }
            }
        }
    }

    public function add_to_cart($product_id) {
	    $found = false;
	    /** check if the cart is not empty */
        if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
            /** check if we have the product already on the cart */
            foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                $_product = $values['data'];
                if ( $_product->get_id() == $product_id )
                    $found = true;
            }
            /** product not found in the cart, let's add it */
            if (!$found)
                WC()->cart->add_to_cart( $product_id );
        } else {
            /** cart is empty, let's add it */
            WC()->cart->add_to_cart( $product_id );
        }
    }
}

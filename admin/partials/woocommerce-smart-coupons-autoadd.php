<?php
/**
 * Created by PhpStorm.
 * User: Leni Neto
 * Date: 6/21/2018
 * Time: 3:53 PM
 */
?>
<div id="smart_coupon_options" class="panel woocommerce_options_panel">
    <?php
        woocommerce_wp_checkbox( array(
            'id' => 'c5_auto_add_product_enable',
            'name' => 'c5_auto_add_product_enable',
            'label' => __( 'Auto add products?', 'woocommerce-smart-coupons' ),
            'cbvalue' => $autoadd_checked,
            'description' => __( 'Check this option if you wish this coupon to automatically add products to the cart', 'woocommerce')
        ) );

        woocommerce_wp_select( array(
            'id'        => 'c5_coupon_autoadd_products[]',
            'name'      => 'c5_coupon_autoadd_products[]',
            'label'     => __('Choose the products', 'woocommerce-smart-coupons'),
            'description' => 'Select the products you wish to automatically add to the cart when applying this coupon',
            'desc_tip'  => true,
            'options'   => $product_list,
            'value'     => $selected_products,
            'custom_attributes' => array(
                    'multiple' => ''
            )
        ) );

        woocommerce_wp_radio( array(
            'id'        => 'c5_coupon_redirect',
            'name'      => 'c5_coupon_redirect',
            'description' => 'Chose where to redirect after applying the coupon',
            'options'   => $redirect_types,
            'value'     => $redirect_type
        ) );

        woocommerce_wp_text_input( array(
            'id'     => 'c5_coupon_redirect_url',
            'name'   => 'c5_coupon_redirect_url',
            'label'  => 'URL to redirect to',
            ''
        ));

    ?>
</div>

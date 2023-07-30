<?php
/**
 * Plugin Name: Custom Cart Alert
 * Description: Display an alert when a product is added to the cart with product name and quantity.
 * Version: 1.0
 * Author: Abdul Dayur
 * Author URI: http://los.tta.mybluehost.me/shop/
 */

// Enqueue custom JavaScript file
function custom_cart_alert_enqueue_scripts() {
    wp_enqueue_script('custom-cart-alert-script', plugin_dir_url(__FILE__) . 'js/custom-cart-alert.js', array('jquery'), '1.0', true);

    // Define the AJAX URL for JavaScript usage
    wp_localize_script('custom-cart-alert-script', 'custom_cart_alert_params', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'custom_cart_alert_enqueue_scripts');

// AJAX handler for adding a product to the cart
add_action('wp_ajax_custom_cart_alert_add_to_cart', 'custom_cart_alert_add_to_cart');

function custom_cart_alert_add_to_cart() {
    if (isset($_POST['product_id'])) {
        $product_id = absint($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

        // Add the product to the cart
        $added_to_cart = WC()->cart->add_to_cart($product_id, $quantity);

        if ($added_to_cart) {
            // Get the cart item quantity and product name
            $cart_item_key = WC()->cart->generate_cart_id($product_id);
            $cart_item = WC()->cart->get_cart_item($cart_item_key);
            $product_name = $cart_item['data']->get_name();
            $quantity_in_cart = $cart_item['quantity'];

            // Send the product name and quantity in the JSON response
            wp_send_json_success(array('product_name' => $product_name, 'quantity' => $quantity_in_cart));
        } else {
            // Handle the case when product could not be added to cart
            wp_send_json_error('Product could not be added to cart', 400);
        }
    }

    // Handle the case when product_id is not valid
    wp_send_json_error('Invalid product ID', 400);
}












































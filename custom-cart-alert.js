jQuery(document).ready(function ($) {
  // Function to display an alert when quantity is changed
  function displayCartAlert(productName, newQuantity) {
    alert(
      "Product added to cart: " + productName + ". Quantity: " + newQuantity
    );
  }

  function displayQuantityUpdatAlert(productName, newQuantity) {
    alert(
      'Quantity of ' + productName +' is now ' + newQuantity + '. Click "UDPATE CART" to finalize.')
 
  }

  // Bind a function to the 'click' event of 'Add to Cart' buttons on the shop page
  $(document.body).on(
    "click",
    "a.add_to_cart_button:not(.product_type_variable)",
    function (event) {
      event.preventDefault();

      var button = $(this);
      var productName = button
        .attr("aria-label")
        .replace("Add “", "")
        .replace("” to your cart", "");

      // Add the product to the cart
      var data = {
        action: "custom_cart_alert_add_to_cart",
        product_id: button.data("product_id"),
        quantity: button.data("quantity") || 1,
      };

      $.post(
        custom_cart_alert_params.ajax_url,
        data,
        function (response) {
          if (response.success) {
            // Product added to cart - display the custom alert with product name and quantity
            displayCartAlert(
              response.data.product_name,
              response.data.quantity
            );
          } else {
            // Handle the case when product could not be added to cart
            console.log(response.data);
          }
        },
        "json"
      );

      return false;
    }
  );

  // Bind a function to the 'change' event of quantity inputs on the cart page
  $(document.body).on(
    "change",
    ".woocommerce-cart-form .quantity input",
    function (event) {
      var input = $(this);
      var cartRow = input.closest(".woocommerce-cart-form tr.cart_item");
      var productName = cartRow.find(".product-name a").text();
      var newQuantity = parseInt(input.val());

      // Display the custom alert with product name and new quantity
      displayQuantityUpdatAlert(productName, newQuantity);
    }
  );
});

jQuery(document).ready(function ($) {
  $(".color-swatch").on("click", function () {
    var imageUrl = $(this).data("image-url");
    var price = $(this).data("price");
    $(".wp-post-image").attr("src", imageUrl);
    $(".woocommerce-Price-amount").text("$" + price); // Update the price display
  });
  // Add this code in your custom-variations.js file, ideally at the end
  $(".size-selection").on("change", function () {
    var size = $(this).val();
    // Handle the selected size here
  });
});

(function($) {
  $(document).on("updated_checkout", function(e, data) {
    // console.log("labas");
  });
  $(document).on("change", "#mp-wc-pickup-point-shipping-select", function() {
    console.log("change shipping");
  });
})(jQuery);

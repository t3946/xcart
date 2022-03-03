import "./Components/checkout/Checkout";
import "./Components/checkout/BillingForm";
import "./Components/checkout/DistributorCart";
import "./Components/checkout/PaymentMethods";
import "./Components/checkout/ShippingForm";
import "./Components/checkout/ScrollUpButton";
import "./Components/checkout/ShippingMethods";
import "./Components/checkout/CanadaCODs";
import "./Components/checkout/CheckoutTotal";
import "./Classes/AddToCartButton";
import "_binds/forms";
import "./Components/checkout/File";

(function () {
  let timerThrottle = null;

  $(window).resize(function (e) {
    clearTimeout(timerThrottle);

    timerThrottle = setTimeout(function () {
      const customEvent = new Event("window_resize", {
        data: {
          originalEvent: e,
        },
      });
      document.dispatchEvent(customEvent);
    }, 300);
  });

  $("#shipping-review-container").slideUp();

  $("#checkout-review-accordion").on("click", function () {
    $("#shipping-review-container").slideToggle();
    $(this).toggleClass("is-active");
  });
})();

$("[data-toggle]").on("click", function () {
  $(`#${$(this).attr("data-toggle")}`).toggleClass("show");
});

$(".accordion-item").each(function () {
  let accordion = $(this);
  let contentBlock = accordion.find(".accordion-content");
  accordion.find(".accordion-title").on("click", function () {
    accordion.toggleClass("is-active");
    contentBlock.slideToggle();
  });
});

import { SwitcherButton } from "@/js/Classes/SwitcherButton";
import { Switcher } from "@/js/Classes/Switcher";

export const DistributorCart = (function () {
  let quantityUpdateQueries = 0;
  // no checkout page
  if (document.querySelector(".checkout-page") === null) {
    return;
  }

  /**
   * add show/hide buttons for cart table
   */
  $(".distributor-cart").each(function (i, e) {
    const $cart = $(e);
    const $table = $cart.find(".table");
    const $textSwitcher = $cart.find(".cart-show-switcher_text");
    const $buttonSwitcher = $cart.find(".switcher-button");
    const $images = $(".cart-item-image");
    const $cartCaption = $cart.find(".cart-table-caption");

    function getAnimationDuration() {
      return $table.find(".cart-table-row").length <= 7 ? 500 : 750;
    }

    const showTable = function () {
      $images.each(function (i, e) {
        LazyLoad.load(e);
      });
      $table.stop(true, false).slideDown(getAnimationDuration());
    };

    const hideTable = function () {
      $table.stop(true, false).slideUp(getAnimationDuration());
    };

    const switcherButton = new SwitcherButton(
      $buttonSwitcher,
      showTable,
      hideTable,
      function (e) {
        e.stopPropagation();
        switcherCaption.isOn = switcherText.isOn = switcherButton.isOn;
      }
    );

    const switcherText = new Switcher(
      $textSwitcher,
      showTable,
      hideTable,
      function (e) {
        e.stopPropagation();
        switcherCaption.isOn = switcherButton.isOn = switcherText.isOn;
      }
    );

    const switcherCaption = new Switcher(
      $cartCaption,
      showTable,
      hideTable,
      function (e) {
        e.stopPropagation();
        switcherButton.isOn = switcherText.isOn = switcherCaption.isOn;
      }
    );

    switcherCaption.isOn = switcherButton.isOn = switcherText.isOn = true;
  });

  function formatNumber(number) {
    return Intl.NumberFormat("en-US", { style: "currency", currency: "USD" })
      .format(number)
      .substr(1);
  }

  /**
   * remove item from cart handler
   */
  $("a.cart-remove-item-button").click(function (e) {
    e.preventDefault();

    quantityUpdateQueries += 1;

    if (quantityUpdateQueries > 0) {
      $(".order-total_preloader").fadeIn();
    }

    Pace.ignore(function () {
      const $target = $(e.currentTarget);

      $.ajax({
        url: "/api/checkout/update",
        data: {
          uid: $target.attr("href").split("/").pop(),
          quantity: 0,
        },
        method: "POST",
        success: function (res) {
          if (quantityUpdateQueries === 1) {
            $(".order-total .total .price").text(formatNumber(res["total"]));
            $(".total-sales-tax .price").text(
              formatNumber(res["total_sales_tax"])
            );
            $(".total-vat-tax .price").text(formatNumber(res["total_vat_tax"]));
            $(".grand-total .price").text(formatNumber(res["grand_total"]));
          }

          let $row = $target;

          while ($row.length && !$row.hasClass("cart-table-row")) {
            $row = $row.parent();
          }

          const productRemovedMessage = $(".checkout-page").data(
            "product-removed"
          );

          window.addFlashMessage(productRemovedMessage, "success", true);

          // removed last product in some cart
          if ($row.parent().children().length === 1) {
            const $warehouse = $row.parent().parent().parent().parent();
            const $warehouseList = $warehouse.parent();

            $warehouse.animate(
              {
                height: 0,
                opacity: 0,
                paddingTop: 0,
                paddingBottom: 0,
              },
              250,
              function () {
                $warehouse.remove();

                // removed last product in last cart
                if ($warehouseList.find(".warehouse_products").length === 0) {
                  window.location.href = "/cart/";
                }
              }
            );
          } else {
            $row.animate(
              {
                height: 0,
                opacity: 0,
                paddingTop: 0,
                paddingBottom: 0,
              },
              250,
              function () {
                $row.remove();
              }
            );
          }

          //update subtotal in distribution cart
          for (let manufacturer_id in res.distributor_carts) {
            const manufacturer = res.distributor_carts[manufacturer_id];
            const whTotal = $(
              `.warehouse_subtotal[data-wh=${manufacturer_id}]`
            );

            whTotal
              .find(".format_price .subtotal")
              .text(formatNumber(parseFloat(manufacturer["subtotal"])));
          }
        },
        complete() {
          // hide checkout order total preloader
          quantityUpdateQueries -= 1;

          if (quantityUpdateQueries === 0) {
            $(".order-total_preloader").fadeOut();
          }
        },
      });
    });
  });

  return null;
})();

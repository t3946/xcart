import { ShippingGoogleAutoComplete } from "@/js/Classes/ShippingGoogleAutoComplete";

export const ShippingMethods = (function () {
  function updateClasses() {
    $(".shipping-methods-group").each(function (i, elem) {
      $(elem)
        .find(".shipping-method-row")
        .removeClass("shipping-method-row_selected")
        .find("input:checked")
        .parents(".shipping-method-row")
        .addClass("shipping-method-row_selected");
    });
  }

  const constructor = function () {
    $(".checkout-cart-content").on("change", "input", function () {
      updateClasses($(this).parents(".shipping-methods-group"));
    });

    updateClasses();
  };

  /**
   * update html in old template from new template
   */
  constructor.prototype.updateTemplate = function (template) {
    const $template = $(template).filter(".shipping-methods-group");

    $template.each(function (i, e) {
      const $shippingGroup = $(e);
      const dxId = $shippingGroup.data('dx-id');
      //replace old markup on new
      $(`.shipping-methods-group[data-dx-id=${dxId}]`).html($shippingGroup.html());
    });

    updateClasses();
  };

  return new constructor();
})();

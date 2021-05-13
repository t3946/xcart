export const ShippingMethods = (function () {
  let $shippingMethodsGroups = $(".shipping-methods-group");

  function updateClasses() {
    $shippingMethodsGroups.each(function (i, elem) {
      $(elem)
        .find(".shipping-method-row")
        .removeClass("shipping-method-row_selected")
        .find("input:checked")
        .parents(".shipping-method-row")
        .addClass("shipping-method-row_selected");
    });
  }

  const constructor = function () {
    initHandlers();
    updateClasses();
  };

  function initHandlers() {
    $shippingMethodsGroups.on("change", "input", function () {
      updateClasses($(this).parents(".shipping-methods-group"));
    });
  }

  constructor.prototype.updateTemplate = function (template) {
    const $newShippingMethodsGroups = $(template).filter(
      ".shipping-methods-group"
    );

    $newShippingMethodsGroups.each(function (i, e) {
      $shippingMethodsGroups
        .eq(i)
        .html(e.outerHTML)
        .on("change", "input", function () {
          updateClasses($(this).parents(".shipping-methods-group"));
        });
    });

    $shippingMethodsGroups = $(".shipping-methods-group");

    initHandlers();
    updateClasses();
  };

  return new constructor();
})();

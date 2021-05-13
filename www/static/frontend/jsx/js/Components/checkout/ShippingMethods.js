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
    $(".shipping-methods-group").on("change", "input", function () {
      updateClasses($(this).parents(".shipping-methods-group"));
    });

    updateClasses();
  };

  /**
   * update html in old template from new template
   */
  constructor.prototype.updateTemplate = function (template) {
    const $newTemplate = $(template).filter(".shipping-methods-group");
    const $oldTemplate = $(".shipping-methods-group");

    $newTemplate.each(function (i, e) {
      $oldTemplate
        .eq(i)
        .html(e.outerHTML)
        .on("change", "input", function () {
          updateClasses($(this).parents(".shipping-methods-group"));
        });
    });

    updateClasses();
  };

  return new constructor();
})();

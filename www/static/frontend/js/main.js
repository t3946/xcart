const Checkout = ( function () {
    class Checkout {
        constructor() {
            this.$otherFields = $('.checkout-shipping-other-fields');
        }
        showOtherFields() {
            this.$otherFields.slideDown();
        }
        hideOtherFields() {
            this.$otherFields.slideUp();
        }
    }

    return new Checkout();
} )();
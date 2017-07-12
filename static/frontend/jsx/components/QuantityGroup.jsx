import _ from "lodash";

export default class QuantityGroup
{
    constructor(options) {


        this.init();
    }

    init() {
        this._bind();

    }

    _bind() {
        $(document).on('click', '.quantity-group .btn', function(e){
            e.preventDefault();

            let $this = $(e.target);
            let $container = $this.closest('.quantity-group');
            let $input = $container.find('input');
            let val = parseInt($input.val());
            let max = parseInt($input.attr('max'));
            let min = parseInt($input.attr('min'));

            if ($this.hasClass('inc') && val < max) {
                val += parseInt($input.attr('step'));
            }

            if ($this.hasClass('dec') && val > min) {
                val -= parseInt($input.attr('step'));
            }

            $input.val(val);

            $container.find('.btn').removeClass('active');

            if (val < max) {
                $container.find('.btn.inc').addClass('active');
            }
            if (val > min) {
                $container.find('.btn.dec').addClass('active');
            }
        })
    }
}
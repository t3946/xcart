'use strict';

import _ from 'lodash';

(()=>{
    let getValues = e => {

        let el = e.target;
        let $this = $(e.target);
        let $container = $this.closest('.quantity-group');
        let $input = $container.find('input');
        let val = parseInt($input.val());
        let max = parseInt($input.attr('max'));
        let min = parseInt($input.attr('min'));
        let data_min = parseInt($input.data('min'));

        if (val > max) {val = max;}
        if (val < min) {val = min;}

        return {
            '$this': $this,
            '$container': $container,
            '$input': $input,
            'element': el,
            'val': val,
            'max': max,
            'min': min | data_min,
        }
    };

    let recheckActives = (e, params = getValues(e))=>{
        console.log('recheck', e);
        params.$container.find('.btn').removeClass('active');

        if (params.val < params.max) {
            params.$container.find('.btn.inc').addClass('active');
        }

        if (params.val > params.min) {
            params.$container.find('.btn.dec').addClass('active');
        }

        let product = params.element.closest('[data-product]');
        if (product) {
            product.dataset.quantity = params.val;
        }
        else {
            product = null;
        }

        params.$input.val(params.val);

        $(document).trigger('component.quantity.change', {
            target: e.target,
            val: params.val,
            params: params,
            product: product
        });
    };

    let recheckActives_throttled = _.throttle(recheckActives, 20);
    recheckActives = (e, params) => {
        let group = e.target.closest('.quantity-group');

        clearTimeout($.data(group, 'timer'));

        $.data(group, 'timer', setTimeout(() => {
            recheckActives_throttled(e, params);
        }, 100));
    };

    $(document)
        .on('click', '.quantity-group .btn', e => {
            e.preventDefault();

            let params = getValues(e);

            if (params.$this.hasClass('inc') && params.val < params.max) {
                params.val += parseInt(params.$input.attr('step'));
            }

            if (params.$this.hasClass('dec') && params.val > params.min) {
                params.val -= parseInt(params.$input.attr('step'));
            }

            // params.$input.val(params.val);
            recheckActives(e, params);
        })
        .on('change blur propertychange mousewheel keyup', '.quantity-group input', e => recheckActives(e));
})();
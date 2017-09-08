(()=>{
    let getValues = (e) => {

        let $this = $(e.target);
        let $container = $this.closest('.quantity-group');
        let $input = $container.find('input');
        let val = parseInt($input.val());
        let max = parseInt($input.attr('max'));
        let min = parseInt($input.attr('min'));
        let data_min = parseInt($input.data('min'));

        return {
            '$this': $this,
            '$container': $container,
            '$input': $input,
            'val': val,
            'max': max,
            'min': min | data_min,
        }
    };

    let recheckActives = (e, params = getValues(e))=>{
        params.$container.find('.btn').removeClass('active');

        if (params.val < params.max) {
            params.$container.find('.btn.inc').addClass('active');
        }

        if (params.val > params.min) {
            params.$container.find('.btn.dec').addClass('active');
        }

        params.$this.closest('[data-product]').data('quantity', params.val);

        $(document).trigger('component.quantity.change', {
            target: e.target,
            val: params.val
        });
    };

    $(document)
        .on('click', '.quantity-group .btn', (e) => {
            e.preventDefault();

            let params = getValues(e);

            if (params.$this.hasClass('inc') && params.val < params.max) {
                params.val += parseInt(params.$input.attr('step'));
            }

            if (params.$this.hasClass('dec') && params.val > params.min) {
                params.val -= parseInt(params.$input.attr('step'));
            }

            params.$input.val(params.val);

            recheckActives(e, params);

        })
        .on('change blur propertychange mousewheel', '.quantity-group input', (e) => {
            clearTimeout($.data(e.target, 'timer'));

            $.data(e.target, 'timer', setTimeout(() => {
                recheckActives(e);
            }, 50));
        })


})();
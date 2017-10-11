(()=>{
    let page = document.querySelector('.product-page');
    if (page) {
        let prices_row = page.querySelectorAll('.price-row');

        $(document).on('component.quantity.change', function(e, data) {
            if (data.product && data.product.dataset.product === page.dataset.product) {

                for (let i = 0, len = prices_row.length; len > i; i++) {
                    let price = prices_row[i];
                    if (price.dataset.quantity <= data.val) {
                        price.classList.add('hidden');
                    }
                    else {
                        price.classList.remove('hidden');
                    }
                }
            }
        });

    }
})();
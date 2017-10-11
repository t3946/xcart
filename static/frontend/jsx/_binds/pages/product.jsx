(()=>{
    let page = document.querySelector('.product-page');
    if (page) {
        let prices_row = page.querySelectorAll('.price-row');

        if (prices_row) {
            $(document).on('component.quantity.change', function(e, data) {
                if (data.product && data.product.dataset.product === page.dataset.product) {

                    prices_row.forEach((price) =>{
                        if (price.dataset.quantity <= data.val) {
                            price.classList.add('hidden');
                        }
                        else {
                            price.classList.remove('hidden');
                        }
                    });
                }
            });
        }

    }
})();
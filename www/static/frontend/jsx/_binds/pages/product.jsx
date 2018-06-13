(()=>{

    let page = document.querySelector('.product-page');
    if (page) {
        let prices_table = page.querySelector('.table__prices--down');
        let prices_row = page.querySelectorAll('.price-row');

        if (prices_row) {
            let timers = {};

            $(document).on('component.quantity.change', (e, data) => {

                if (data.product && data.product.dataset.product === page.dataset.product) {
                    let allHide = true;

                    prices_row.forEach( price => {
                        let hide = (price.dataset.quantity <= data.val);
                        let key = 'price_' + price.dataset.quantity;

                        price.classList.toggle('hidden', hide);

                        clearTimeout(timers[key]);
                        timers[key] = setTimeout(() =>{
                            price.classList.toggle('af-anim', hide);
                        }, 200);


                        if (!hide) {
                            allHide = false;
                        }
                    });

                    prices_table.classList.toggle('hidden', allHide);
                }
            });
        }

    }
})();
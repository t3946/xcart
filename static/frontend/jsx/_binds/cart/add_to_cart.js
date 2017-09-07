(()=>{
    $(document).on('click','.cart_add .button', (e) =>{
        e.preventDefault();

        let quantity = $(e.target).closest('[data-product]').data('quantity') | 1;
        
        console.log(quantity);
    });
})();
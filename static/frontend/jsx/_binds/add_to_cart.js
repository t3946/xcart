(()=>{
    $(document).on('click','.cart_add .button', (e) =>{
        let quantity = $(e.target).closest('[data-product]').data('quantity') | 1;
        
        console.log(quantity);
    });
})();
$(document).on('click', '.front-endless-pager a.show-more', function(e){
    e.preventDefault();
    endless_paginate()
});

window.endless_paginate = ()=>{
    let $this = $('.front-endless-pager a.show-more');
    let $parent = $this.parent();
    let $container = $('.product-items');
    let text_loading = $this.data('text-loading');
    let text_default = $this.data('text-default');


    window.loader.load(
        $.ajax($this.attr('href'), {
            'success' : (data)=>{
                $container.append(data.content);

                if (data.href) {
                    $this.find('.text').html(text_default);
                    $this.attr('href', data.href);
                    $this.removeAttr('disabled')
                }
                else {
                    $this.remove();
                }

                $('.page_count_wrap').html(data.page_count);
            },
            'error': ()=>{
                window.loader.detach();
                $this.find('.text').html(text_default);
                $this.removeAttr('disabled');

                window.addFlashMessage('An error has occurred. Please try again later.', 'error');
            }
        })
    );


    $this.attr('disabled', 'disabled');
    $this.find('.text').html(text_loading);
};
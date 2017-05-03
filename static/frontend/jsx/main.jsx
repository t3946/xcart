(function(){
    $('.search-form-container .search').on('keyup', function (e){
        let $bclear = $('.search-form-container .button-clear');
        let $this = $(this);
        let string = $this.val();

        if (string) {
            $bclear.addClass('active');
        }
        else {
            $bclear.removeClass('active');
        }
    });

    $('.search-form-container .button-clear').on('click', function(){
        $('.search-form-container .search').val('');
        $(this).removeClass('active');
    });

    new DepartmentMenu();

    $(document).on('show:dm', ()=> {
        $('.shadow').addClass('active');
    });

    $(document).on('hide:dm', ()=> {
        $('.shadow').removeClass('active');
    });

    $('.shadow').on('click touchstart', ()=> {
        $(document).trigger('click:shadow');
    });

    let ddd_params = {
        watch:'window',
        after: 'a.show_more',
        callback: function(isTruncated, originalContent) {
            let $this = $(this);
            let $ml_a = $this.find('a.show_more');

            if (isTruncated) {
                $ml_a.css({display: 'inline-block'});
            }
            else {
                $ml_a.css({display: 'none'});
            }
        }
    };

    if ($.fn.dotdotdot)
    {
        $('.must-show-less').each(function(){
            let $this = $(this);

            if (this.offsetHeight < this.scrollHeight ||
                this.offsetWidth < this.scrollWidth) {
                $this.append('<a href="#" class="show_more"></a>');

                let $ml_a = $this.find('a.show_more');
                $ml_a.html($this.data('text-more'));
            }

        });

        $('.must-show-less').dotdotdot(ddd_params);

        $(document)
            .on('click', '.must-show-less .show_more', function(){
                let $this = $(this).closest('.must-show-less');
                let isTruncated = $this.triggerHandler("isTruncated");

                if (isTruncated) {
                    $this.addClass('full');
                    $this.trigger('destroy');

                    let $ml_a = $this.find('a.show_more');
                    $ml_a.html($this.data('text-less'));
                }
                else {
                    $this.removeClass('full');

                    let $ml_a = $this.find('a.show_more');
                    $ml_a.html($this.data('text-more'));

                    $this.dotdotdot(ddd_params);
                }
            });
    }

    Waves.attach('.button');
    Waves.init();


    $(document).on('click', '.action_block.view a', function(e){
        console.log(this);
        e.preventDefault();

        if ($(this).hasClass('tile-view')) {
            $('.catalog-page .product-items').removeClass('list-view').addClass('tile-view');
        }
        else {
            $('.catalog-page .product-items').removeClass('tile-view').addClass('list-view');
        }
    });

    $(document).foundation();
})();

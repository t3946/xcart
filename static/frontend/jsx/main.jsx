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

    $(document).on('show.zf.dropdownmenu', function(ev, $el){
        if ($el.is('.category-menu-list, .category-menu-container')) { // el submenu in el
            $('.shadow').addClass('active');
        }
    });
    $(document).on('hide.zf.dropdownmenu', function(ev, $el){
        if ($el.is('.category-menu, .category-menu-container')) { //el base menu
            $('.shadow').removeClass('active');
        }
    });


    $(document).foundation();
})();

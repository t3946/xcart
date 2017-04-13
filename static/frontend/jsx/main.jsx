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


    $(document).foundation();
})();

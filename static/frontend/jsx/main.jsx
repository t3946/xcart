import 'modernizr';
import DepartmentMenu from "./components/DepartmentMenu";
import DottedText from "./components/DottedText";
import LazyImageLoad from "./components/LazyImageLoad";

(function(){
    new LazyImageLoad('.lazyimg');
    new DepartmentMenu();
    new DottedText('.must-show-less');

    Waves.attach('.button');
    Waves.init();


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


    $(document).on('show:dm', ()=> {
        $('.shadow').addClass('active');
    });

    $(document).on('hide:dm', ()=> {
        $('.shadow').removeClass('active');
    });

    $('.shadow').on('click touchstart', ()=> {
        $(document).trigger('click:shadow');
    });



    $(document).on('click', '.action_block.view a', function(e){
        e.preventDefault();
        let type = 'tile-view';

        if ($(this).hasClass('tile-view')) {
            $('.catalog-page .product-items').removeClass('list-view').addClass('tile-view');
            type = 'tile-view';
        }
        else {
            $('.catalog-page .product-items').removeClass('tile-view').addClass('list-view');
            type = 'list-view';
        }

        $('.action_block.view a').removeClass('active');
        if (type === 'tile-view') {
            $('.action_block.view a.tile-view').addClass('active')
        }
        else {
            $('.action_block.view a.list-view').addClass('active')
        }
    });

    $(document).on('click', '.action_block.sort', function(e){
        e.preventDefault();
        $(this).toggleClass('active');
    });

    $(document).on('click', '.action_block.sort .options li', function(e){
        e.preventDefault();
        let $this = $(this);

        if (!$this.hasClass('active')) {
            $('.action_block.sort .options li').removeClass('active');
            $this.addClass('active');
            $this.closest('.action_block.sort').find('.active_value').html($this.text());

            setTimeout(()=>{
                $this.closest('.action_block.sort').removeClass('active');
            }, 500);
        }
        else {
            $this.closest('.action_block.sort').removeClass('active');
        }
    });

    $(document).on('click', '.front-endless-pager a.show-more', function(e){
        e.preventDefault();

        let $this = $(this);
        let $parent = $(this).parent();
        let $container = $('.product-items');


        $.ajax($this.attr('href'), {
            'success' : (data)=>{
                $container.append(data.content);
                $parent.html(data.pager);
                $('.page_count').html(data.page_count);

                $(window).trigger('resize');
                Waves.attach('.button');
            }
        });

        let classes = $this.attr('class');
        let text_loading = $this.data('text-loading');

        $this.remove();
        $parent.append('<span class="'+classes+'"><span class="text">' + text_loading + '</span></span>');

    });


    $(document).foundation();
})();

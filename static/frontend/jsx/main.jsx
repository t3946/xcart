import 'modernizr';
import DepartmentMenu from "./components/DepartmentMenu";
import DottedText from "./components/DottedText";
import CategoryViewType from "./components/CategoryViewType";
import LazyImageLoad from "./components/LazyImageLoad";
import CatalogFilter from "./components/CatalogFilter";
import FilterPriceSlider from "./components/FilterPriceSlider";
import Loader from "./components/Loader";

(function(){
    new LazyImageLoad('.lazyimg');
    new CategoryViewType();
    new DepartmentMenu();
    new DottedText('.must-show-less');
    new CatalogFilter();

    window['FilterPriceSlider'] = FilterPriceSlider;
    window['loader'] = new Loader;

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


    $(document).on('click', '.action_block.sort', function(e){
        e.preventDefault();
        $(this).toggleClass('active');
    });

    $(document).on('click', '.action_block.sort .options li', function(e){
        e.preventDefault();
        e.stopPropagation();
        let $this = $(this);

        if (!$this.hasClass('active')) {
            $('.action_block.sort .options li').removeClass('active');
            $this.addClass('active');
            $this.closest('.action_block.sort').find('.active_value').html($this.text());

            setTimeout(()=>{
                $this.closest('.action_block.sort').removeClass('active');
            }, 2000);

            window.loader.load(()=>{
                $.ajax({
                    url: window.location,
                    method: 'POST',
                    data: {sort: $(this).data('value')},
                    success : (data)=>{
                        window.location = window.location;
                    }
                })
            });
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


        window.loader.load(
            $.ajax($this.attr('href'), {
                'success' : (data)=>{
                    $container.append(data.content);
                    $parent.html(data.pager);
                    $('.page_count').html(data.page_count);

                    $(window).trigger('resize');
                    Waves.attach('.button');
                }
            })
        );


        let classes = $this.attr('class');
        let text_loading = $this.data('text-loading');

        $this.remove();
        $parent.append('<span class="'+classes+'"><span class="text">' + text_loading + '</span></span>');

    });


    $(document).on('click', '.show_more', function(e){
        let $this = $(this);
        let $target = $($this.data('target'));

        if (!$target.hasClass('full')) {
            $target.addClass('full');

            $this.html($this.data('text-less'));
        }
        else {
            $target.removeClass('full');

            $this.html($this.data('text-more'));
        }
    });

    $(document).on('click', 'a.mmodal', function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).mmodal({skin: $(this).data('modal-class') || 'front'});
    });

    $(window).on('beforeunload unload pagehide', ()=>{
        loader.load();
    });

    // $(window).on('popstate', ()=>{
    //     loader.load();
    // });
    //
    // let handlePushState = function() {
    //     loader.load();
    // };
    //
    //
    // if (window.history.pushState != null) {
    //     var _pushState = window.history.pushState;
    //     window.history.pushState = function() {
    //         handlePushState();
    //         return _pushState.apply(window.history, arguments);
    //     };
    // }
    //
    //
    // if (window.history.replaceState != null) {
    //     var _replaceState = window.history.replaceState;
    //     window.history.replaceState = function() {
    //         handlePushState();
    //         return _replaceState.apply(window.history, arguments);
    //     };
    // }


    $(document).foundation();
})();

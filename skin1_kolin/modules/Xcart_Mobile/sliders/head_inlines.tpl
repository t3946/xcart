<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css">
<script type="text/javascript">
	var scale_factor = {if $is_tablet}0.9{else}0.6{/if};
	var scale_width = 2 - scale_factor;
    {literal}
	window['slick_settings'] = {
		lazyLoad: 'ondemand',
		dots: true ,
		arrows: false,
		adaptiveHeight:true,
		autoplay: false,
		pauseOnHover: true,
		pauseOnFocus: true,
		autoplaySpeed: 4000,
		infinite: true,
		speed: 500,
		responsive: [
			{
				breakpoint: 320 * scale_width,
				settings: {
					arrows: false,
					centerMode: true,
					slidesToScroll: 1,
					slidesToShow: 1
				}
			},
			{
				breakpoint: 480 * scale_width,
				settings: {
					arrows: false,
					centerMode: false,
					slidesToScroll: 2,
					slidesToShow: 2
				}
			},

			{
				breakpoint: 640 * scale_width,
				settings: {
					arrows: false,
					centerMode: false,
					slidesToScroll: 3,
					slidesToShow: 3
				}
			},

			{
				breakpoint: 800 * scale_width,
				settings: {
					arrows: false,
					centerMode: false,
					slidesToScroll: 4,
					slidesToShow: 4
				}
			},

			{
				breakpoint: 1600 * scale_width,
				settings: {
					arrows: false,
					centerMode: false,
					focusOnSelect: true,
					slidesToScroll: 4,
					slidesToShow: 5
				}
			}
		]
	};

	(function(){
		$(document).on('pageload ready', function(){
			setTimeout(function(){
				$('.slider-products')
					.not('.slick-initialized')
					.slick(window['slick_settings'])
					.on('swipe', function(event, slick, direction){
						if (slick) {
							slick.slickPause();

							setTimeout(function () {
                                slick.slickPlay();
							}.bind(slick), 10000)
						}
					});
			}, 200);

			$('.slider-head').not('.initialized').on('click', function(){
				var $this = $(this);
				var cls = $this.data('class');

				if (cls) {
					if ($('.' + cls).hasClass('slick-initialized')) {
						$('.' + cls).slick('unslick');
						$this.find('.ui-icon').removeClass('ui-icon-plus');
						$this.find('.ui-icon').addClass('ui-icon-minus');

                        $('.' + cls + ' img.slick-loading').each(function(){
                            $(this).attr('src', $(this).data('lazy'));
                            $(this).removeClass('slick-loading');
                        });
					}
					else {
						$('.'+cls).slick(window['slick_settings']);
						$this.find('.ui-icon').removeClass('ui-icon-minus');
						$this.find('.ui-icon').addClass('ui-icon-plus');
					}
				}
			});

			$('.slider-head').addClass('initialized');

            ga('send', {hitType: 'pageview', location: location.href});
		});
	})();
    {/literal}
</script>

<style type="text/css">
    {literal}
    .slider-container {
        background: #fafafa;
    }
    .slider-container * {
        box-sizing: border-box;
    }

    .slider-container .slick-dots {
        list-style: none;
        text-align: center;
    }


    .slider-container .slick-dots li {
        display: inline-block;
        margin:  0 3px;
        position: relative;
    }

    .slider-container .slick-dots li button {
        text-indent: -9999px;
        padding: 5px;
        line-height: 0;
        border: none;
        border-radius: 5px;
        width: 10px;
        height: 10px;
        transition: background 0.4s;

    }

    .slider-container .slick-dots li.slick-active button {
        background: rgb(50, 50, 50);
    }

    .slider-container .slider-head {
        text-align: left;
    }

    .slider-container .slider-products {
        padding-bottom: 15px;
    }
    .slider-container .slider-products:after {
        clear: both;
        display: block;
        content: '';
    }

    .slider-container .slider-products .slide {
        float: left;
        width: 50%;
        padding: 0 15px 1.5em;
        box-sizing: border-box;
        text-align: center;
    }

    .slider-container .slider-products .slide .product {
        display: inline-block;
        max-width: 220px;
        max-height: 320px;
        width: 100%;
    }

    .slider-container .slider-products .slide .product .price {
        color: rgb(204, 51, 51);
        font-weight: bold;
    }
    .slider-container .slider-products .slide .product .label {
        text-align: left;
        max-height: 6em;
        min-height: 6em;
        overflow-y: hidden;
        position: relative;
    }

    .slider-container .slider-products .slide .product .label .grad {
        content: '';
        display: block;
        height: 0.9em;
        background: #fafafa; /* Для старых браузров */
        background: linear-gradient(to bottom, rgba(250, 250 ,250, 0.3), rgba(250, 250 ,250, 1));
        bottom: -1px; left: 0; right: 0;
        position: absolute;
    }

    .slider-container .slider-products .slide .product  .row {
        display: block;
        padding: 0.3em 0.5em;
        line-height: 1.3;
        font-size: 1.2em;
    }

    .slider-container .slider-products .slide .product a {
        display: block;
    }
    .slider-container .slider-products .slide .product .product-thumbnail {
        text-align: center;
        display: inline-block;
        width: 100%;
    }

    .slider-container .slider-products .slide .product .product-thumbnail img {
        /*width: 100%;*/
        max-width:100%;
        height:auto;
        max-height: 150px;
        display: inline-block;
        overflow-y: hidden;

    }

    .ui-content .slider-container > .ui-content {
        padding: 15px 0;
    }

    .slider-container .slider-products > .slide:nth-of-type(1n) {
        clear: none;
    }

    @media all and (min-device-width: 320px) and (max-device-width: 479px) {
        .slider-container .slider-products .slide {
            width: 50%;
        }
        .slider-container .slider-products > .slide:nth-of-type(2n+1) {
            clear: both;
        }
    }

    @media all and (min-device-width: 480px) and (max-device-width: 639px) {
        .slider-container .slider-products .slide {
            width: 33%;
        }
        .slider-container .slider-products > .slide:nth-of-type(3n+1) {
            clear: both;
        }
    }

    @media all and (min-device-width: 640px) and (max-device-width: 799px) {
        .slider-container .slider-products .slide {
            width: 25%;
        }
        .slider-container .slider-products > .slide:nth-of-type(4n+1) {
            clear: both;
        }
    }
    @media all and (min-device-width: 800px) {
        .slider-container .slider-products .slide {
            width: 20%;
        }
        .slider-container .slider-products > .slide:nth-of-type(5n+1) {
            clear: both;
        }
    }

    .ErrorMessage {
        color: darkred;
    }

    .ui-content .ui-navbar .ui-btn-text {
        font-size: 1rem;
    }

    .ui-content .tabs-menu .ui-navbar .ui-btn-inner {

    }
    .ui-content .tabs-menu .ui-navbar .ui-btn-inner .ui-btn-text {
        /*min-height: 3em;*/
    }


    .ui-mobile-viewport form[name=cartform] *,
    .ui-mobile-viewport form[name=checkout_form] *,
    .ui-mobile-viewport form[name=registerform] *,
    .mobile-form * {
        font-size: 1.2rem;
        line-height: 1.2;
        vertical-align: middle;
    }

    .ui-mobile-viewport form[name=cartform] .cidev_checkout_descr,
    .ui-mobile-viewport form[name=checkout_form] .cidev_checkout_descr,
    .ui-mobile-viewport form[name=registerform] .cidev_checkout_descr,
    .mobile-form .cidev_checkout_descr {
        color: #929292;
    }

    .ui-mobile-viewport form[name=cartform] tr td,
    /*.ui-mobile-viewport form[name=checkout_form] tr td,*/
    .ui-mobile-viewport form[name=registerform] tr td,
    .mobile-form tr td {
        padding: .2em 2px;
    }

    .ui-mobile-viewport form[name=cartform] tr td tr td,
    /*.ui-mobile-viewport form[name=checkout_form] tr td tr td,*/
    .ui-mobile-viewport form[name=registerform] tr td tr td,
    .mobile-form tr td tr td {
        padding: 0 2px;
    }

    {/literal}
</style>
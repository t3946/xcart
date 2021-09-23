import { Swiper, SwiperSlide }                    from 'swiper/react';
import SwiperCore, { Autoplay, Lazy, Pagination } from 'swiper';

SwiperCore.use( [ Pagination, Autoplay, Lazy ] );

export default class SliderProductsMini extends Component {
    constructor() {
        super();
    }

    slideSimple( slide, key ) {
        return (
            <SwiperSlide className="slide slide_cover_1 banner dark">
                <div className="slide-data swiper-lazy banner__cover" data-src={ this.props.uri + slide.image } key={ key }>
                    <div className="banner__info slide-info multiline">
                        <h3 className="caption multiline">{ slide.title }</h3>
                        <p className="description multiline">{ slide.description }</p>
                    </div>
                </div>
            </SwiperSlide>
        );
    }

    slideLink( slide, key ) {
        return (
            <SwiperSlide className="slide slide_cover_1 banner dark">
                <a href={ slide.link } className="slide-data swiper-lazy banner__cover" data-src={ this.props.uri + slide.image } key={ key }>
                    <div className="banner__info slide-info multiline">
                        <h3 className="caption multiline">{ slide.title }</h3>
                        <p className="description multiline">{ slide.description }</p>
                    </div>
                </a>
            </SwiperSlide>
        );
    }

    componentDidMount() {
        $( this.swiperObject.$el ).mouseover( () => {
            this.swiperObject.autoplay.stop();
        } );

        $( this.swiperObject.$el ).mouseout( () => {
            this.swiperObject.autoplay.start();
        } );
    }

    lazyLoadHandler( swiper, slideEl, imageEl ) {
        imageEl.style.backgroundImage = `url('${ imageEl.dataset.src }')`;
    }

    render( props ) {
        let pagination = {};
        const slides = props.slides;

        if ( slides.length > 1 ) {
            pagination = {
                el: '.promo-slider-pagination',
                type: 'bullets',
                bulletClass: 'promo-slider-pagination-bullet',
                bulletActiveClass: 'promo-slider-pagination-bullet__active',
                clickable: true,
            };
        }

        return (
            <Swiper
                resistance={ true }
                loop={ slides.length > 1 }
                lazy={ {
                    loadPrevNext: true,
                } }
                speed={ 500 }
                resistanceRatio={ 0 }
                pagination={ pagination }
                autoplay={ {
                    delay: 4000,
                    disableOnInteraction: false,
                } }
                onSwiper={ ( swiper ) => this.swiperObject = swiper }
                onLazyImageLoad={ this.lazyLoadHandler }
            >
                { slides.map( ( slide, i ) => {
                    if ( slide.link ) {
                        return this.slideLink( slide, i );
                    }
                    else {
                        return this.slideSimple( slide, i );
                    }
                } ) }
                <div className="promo-slider-pagination"></div>
            </Swiper>
        );
    }
}
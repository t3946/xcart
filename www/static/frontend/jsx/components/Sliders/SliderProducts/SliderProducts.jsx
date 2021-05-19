import { Swiper, SwiperSlide }         from 'swiper/react';
import SwiperCore, { Lazy, Scrollbar } from 'swiper';
import classnames                      from 'classnames';
import ProductCard                     from '@/components/product/card/slider/Card';

SwiperCore.use( [ Lazy, Scrollbar ] );

export default class SliderProducts extends Component {
    constructor( props ) {
        super( props );

        this.goTo = this.goTo.bind( this );
        this.goPrev = this.goPrev.bind( this );
        this.goNext = this.goNext.bind( this );
        this.updateSlideBordersFlags = this.updateSlideBordersFlags.bind( this );
        this.onReachEndHandler = this.onReachEndHandler.bind( this );
        this.paginationPage = 1;

        this.state = {
            error: null,
            isLoaded: false,
            items: [],
            isEnd: true,
            isBeginning: true,
        };
    }

    loadNewItems() {
        const url = '/api' + this.props.url + '?page=' + this.paginationPage;

        fetch( url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        } ).then( res => res.json() )
           .then(
               ( res ) => {
                   this.state.items.push( ...res.items );
                   this.setState( { items: this.state.items, isLoaded: true } );
                   this.paginationPage += 1;
               },
               ( error ) => {
                   this.setState( {
                       error: error.message,
                   } );
               },
           );
    }

    componentDidMount() {
        this.loadNewItems();
    }

    componentDidUpdate() {
        const { isEnd, isBeginning } = this.state;

        if (
            this.swiperObject
            && (
                isEnd !== this.swiperObject.isEnd
                || isBeginning !== this.swiperObject.isBeginning
            )
        ) {
            this.setState( {
                isEnd: this.swiperObject.isEnd,
                isBeginning: this.swiperObject.isBeginning,
            } );
        }
    }

    navStep() {
        return 3;
    }

    goNext() {
        const step = this.navStep();
        this.goTo( step );
    }

    goPrev() {
        const step = -this.navStep();
        this.goTo( step );
    }

    goTo( step ) {
        let newIndex = this.swiperObject.realIndex + step;
        this.swiperObject.slideToLoop( newIndex );
    }

    updateSlideBordersFlags() {
        this.setState( { isEnd: this.swiperObject.isEnd, isBeginning: this.swiperObject.isBeginning } );
    }

    onReachEndHandler() {
        this.loadNewItems();
    }

    render( props, state, context ) {
        const { error, isLoaded, items, isBeginning, isEnd } = this.state;

        $( this.base ).parents( '.slider-block' ).removeClass( 'hide' );

        if ( error ) {
            // hide slider when error
            $( this.base ).parents( '.slider-block' ).addClass( 'hide' );
            return <div>Error: { JSON.stringify( error ) }</div>;
        }
        else if ( !isLoaded ) {
            return <div style="height: 327px"></div>;
        }
        else {
            return (
                <Swiper
                    spaceBetween={ 0 }
                    longSwipesRatio={ 0.05 }
                    width={ 1 * 170 }
                    slidesPerView={ 1 }
                    lazy={ true }
                    className="products-slider"
                    scrollbar={
                        {
                            el: '.products-slider-scrollbar',
                            draggable: true,
                            hide: false,
                        }
                    }
                    breakpoints={
                        {
                            171: {
                                width: 2 * 172,
                                slidesPerView: 2,
                            },
                            340: {
                                width: 3 * 172,
                                slidesPerView: 3,
                            },
                            500: {
                                width: 4 * 172,
                                slidesPerView: 4,
                            },
                            680: {
                                width: 5 * 172,
                                slidesPerView: 5,
                            },
                            860: {
                                width: 5 * 206,
                            },
                            1024: {
                                width: 6 * 206,
                                slidesPerView: 6,
                            },
                        }
                    }
                    onSwiper={ ( swiper ) => { this.swiperObject = swiper; } }
                    onSlideChange={ this.updateSlideBordersFlags }
                    onReachEnd={ this.onReachEndHandler }
                >
                    { items.map( ( product, i ) => (
                        <SwiperSlide className="products-slider-slide" key={ i }>
                            <ProductCard product={ product } />
                        </SwiperSlide>
                    ) ) }

                    <div className={ classnames( 'products-slider-left', 'products-slider-nav', 'show-for-medium', isBeginning && 'products-slider-nav__inactive' ) } onClick={ this.goPrev }/>
                    <div className={ classnames( 'products-slider-right', 'products-slider-nav', 'show-for-medium', isEnd && 'products-slider-nav__inactive' ) } onClick={ this.goNext }/>
                    <div className="products-slider-scrollbar"/>
                </Swiper>
            );
        }
    }
}
import { Swiper, SwiperSlide } from 'swiper/react';

export default class SliderBreadcrumbs extends Component {
    constructor() {
        super();
    }

    render( props ) {
        return (
            <Swiper
                spaceBetween={ 0 }
                longSwipesRatio={ 0.05 }
                slidesPerView="auto"
                resistance={ true }
                resistanceRatio={ 0 }
                className="breadcrumb-list no-bullet"
                itemType="http://schema.org/BreadcrumbList"
                itemProp="breadcrumb"
                itemScope
                onSwiper={ swiper => swiper.slideToLoop( props.breadcrumbsData.length ) }
            >
                { props.breadcrumbsData.map( ( item, i ) => {
                    const last = i + 1 === props.breadcrumbsData.length;

                    if ( !last ) {
                        return (
                            <SwiperSlide key={ i } className="breadcrumb-slide">
                                <a
                                    className="breadcrumb-link"
                                    itemScope
                                    itemType="http://schema.org/Thing"
                                    itemProp="item"
                                    id={ item.url }
                                    href={ item.url }>
                                    <span itemProp="name">
                                        { item.name }
                                    </span>
                                </a>
                                <meta itemProp="position" content={ i + 1 }/>
                            </SwiperSlide>
                        );
                    }
                    else {
                        return (
                            <SwiperSlide key={ i } className="breadcrumb-slide">
                                <span itemScope itemType="http://schema.org/Thing" itemProp="item" id={ item.url }>
                                    <span itemProp="name">
                                        { item.name }
                                    </span>
                                </span>
                                <meta itemProp="position" content={ i + 1 }/>
                            </SwiperSlide>
                        );
                    }
                } ) }
            </Swiper>
        );
    }
}

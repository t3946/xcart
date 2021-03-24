import classnames from 'classnames';

/**
 * images viewer for product in products slider where many images
 */
export default class ImageComplex extends Component {
    constructor( { images } ) {
        super();
        //view image index
        this.state = { activeIndex: 0 };
        this.images = images;
        this.hoverIntentTimeout = null;
    }

    render() {
        const { activeIndex } = this.state;
        return (
            <div className={ `products-slider-images-group images-many images-${ this.images.length }` }>
                { this.images.map( ( image, i ) => (
                    <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=="
                        data-src={ image.url }
                        alt={ image.alt }
                        className={ 'swiper-lazy products-slider-image ' + ( i === activeIndex ? 'show' : 'hide' ) }
                        key={ `image-${ i }` }
                    />
                ) ) }
                <ul className="products-slider-images-navigator" style="display: flex">
                    { this.images.map( ( image, i ) => (
                        <li onMouseOver={ e => {
                            clearTimeout( this.hoverIntentTimeout );
                            this.hoverIntentTimeout = setTimeout( () => this.setState( { activeIndex: i } ), 35 );
                        } }
                            onMouseOut={ e => {
                                clearTimeout( this.hoverIntentTimeout );
                            } }
                            className={ classnames( {
                                'products-slider-images-nav-item': true,
                                'products-slider-images-nav-item__active': i === activeIndex,
                            } ) } key={ `nav-item-${ i }` }/>
                    ) ) }
                </ul>
            </div>
        );
    }
}
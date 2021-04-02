import classnames       from 'classnames';
import ImgSliderComplex from '@/components/product/components/ImgSliderComplex';

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
                    <ImgSliderComplex image={ image } key={ `image-${ i }` } is_visible={ ( i === activeIndex ) }/>
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
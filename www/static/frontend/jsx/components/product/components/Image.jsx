import ImageComplex from './ImageComplex';
import ImgSlider from './ImgSlider';

export default class Image extends Component {
    constructor( { images, mpn, upc, url, name } ) {
        super();
        this.images = images;
        this.mpn = mpn;
        this.upc = upc;
        this.url = url;
        this.name = name;
    }

    noImage() {
        return (
            <div className="products-slider-no-image">
                <span>Image not available</span>
            </div>
        );
    }

    singleImage( image ) {
        const { mpn, upc } = this;

        return (
            <div className="products-slider-images-group images-1">
                {
                    <ImgSlider image={image} />
                }
                <meta itemProp="mpn" content={ mpn }/>
                { upc && <meta itemProp="gtin" content={ upc }/> }
            </div>
        );
    }

    render() {
        const { images, url, name } = this;

        return (
            <div className="products-slider__image-container products-slider-image-container container">
                <a href={ url } title={ name } className="products-slider-image-link">
                    { ( () => {
                        if ( images.length === 0 ) {
                            return this.noImage();
                        }
                        else if ( images.length === 1 ) {
                            return this.singleImage( images[ 0 ] );
                        }
                        else {
                            return ( <ImageComplex images={ images }/> );
                        }
                    } )() }
                </a>
            </div>
        );
    }
}
/**
 * images viewer for product in products slider where many images
 */
export default class ImgSliderComplex extends Component {
    constructor( { image, is_visible } ) {
        super();
        this.image = image;
        this.is_visible = is_visible;
    }

    render() {
        const { image, is_visible } = this;

        return (
            <img
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=="
                data-src={ image.url }
                alt={ image.alt }
                className={ 'swiper-lazy products-slider-image ' + ( is_visible ? 'show' : 'hide' ) }
            />
        );
    }
}
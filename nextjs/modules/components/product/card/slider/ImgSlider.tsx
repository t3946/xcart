export default class ImgCatalogSlider extends Component {
    constructor( { image } ) {
        super();
        this.image = image;
    }

    render() {
        return ( <img
            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=="
            data-src={ this.image.url }
            alt={ this.image.alt }
            className="swiper-lazy products-slider-image"
        /> );
    }
}

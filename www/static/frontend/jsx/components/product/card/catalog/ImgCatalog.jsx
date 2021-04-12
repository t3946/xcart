export default class Image extends Component {
    constructor( { image } ) {
        super();
        this.image = image;
    }

    render() {
        return ( <img
            src={ this.image.url }
            alt={ this.image.alt }
            className="products-slider-image"
        /> );
    }
}
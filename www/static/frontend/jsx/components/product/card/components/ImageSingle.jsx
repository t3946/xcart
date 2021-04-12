export default class ImageSingle extends Component {
    constructor( { image, mpn, upc } ) {
        super();
        this.image = image;
        this.mpn = mpn;
        this.upc = upc;
    }

    render() {
        const { image, mpn, upc } = this;

        return (
            <div className="products-slider-images-group images-1">
                { image }
                <meta itemProp="mpn" content={ mpn }/>
                { upc && <meta itemProp="gtin" content={ upc }/> }
            </div>
        );
    }
}
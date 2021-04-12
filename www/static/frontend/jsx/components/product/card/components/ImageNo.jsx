export default class ImageNo extends Component {
    constructor( { mpn, upc } ) {
        super();
        this.mpn = mpn;
        this.upc = upc;
    }

    render() {
        const { mpn, upc } = this;

        return (
            <div className="products-slider-no-image">
                <span>Image not available</span>
                <meta itemProp="mpn" content={ mpn }/>
                { upc && <meta itemProp="gtin" content={ upc }/> }
            </div>
        );
    }
}
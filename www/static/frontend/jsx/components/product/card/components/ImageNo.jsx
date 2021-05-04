import classnames from "classnames";

export default class ImageNo extends Component {
    constructor( props ) {
        super(props);
    }

    render(props) {
        const { mpn, upc, classes } = props;

        classes.push('product-card-no-image');

        return (
            <div className={classnames(classes)}>
                <span>Image not available</span>
                <meta itemProp="mpn" content={ mpn }/>
                { upc && <meta itemProp="gtin" content={ upc }/> }
            </div>
        );
    }
}
import classnames from 'classnames';
import Image      from './components/Image';

/**
 * abstract component for product cart in sliders and catalog
 */
export default class Product extends Component {
    constructor( props ) {
        super(props);
    }

    render({product, images, mainInfo, price, classes}) {
        this.product = product;
        this.images = images;
        this.mainInfo = mainInfo;
        this.price = price;
        this.classes = classes ?? {};

        const analytics_source = '';
        const imageProp = {
            images: this.images,
            mpn: product.mpn,
            upc: product.upc,
            url: product.url,
            name: product.url,
            isNew: product.isNew,
            isSale: product.isSale,
            classes: this.classes.image,
        };

        return (
            <div className={ classnames( { 'out_of_stock': product.inStock, }, [ this.classes.product ] ) }
                 data-product={ product.productid }
                 data-name={ product.name }
                 data-source={ analytics_source }
                 data-brand={ product.brand }
                 data-prices={ product.prices }
                 data-list-price={ product.listPrice.number }
                 itemScope
                 itemType="http://schema.org/Product"
                 itemProp="itemListElement"
            >
                <Image { ...imageProp } />

                <div className="info_container container">{ this.mainInfo }</div>

                <div className="products-slider-price-container show-for-medium" itemProp="offers" itemScope>{ this.price }</div>
            </div>
        );
    }
}

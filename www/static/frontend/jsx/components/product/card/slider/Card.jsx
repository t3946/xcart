import Product      from '@/components/product/card/Product';
import ImgSlider    from './ImgSlider';
import Price        from '@/components/product/card/components/Price';
import { Fragment } from 'preact';
import ImgCatalog   from '@/components/product/card/catalog/ImgCatalog';

export default class Card extends Component {
    constructor( { product } ) {
        super();

        this.product = product;

        //list of jsx img elements
        this.imgList = [];

        for ( let i = 0; product.images && i < product.images.length; i++ ) {
            this.imgList.push( <ImgCatalog image={  product.images[ i ] }/> );
        }
    }

    /**
     * main content of product cart as name, description, attributes etc.
     */
    productMainInfoBlock() {
        return (
            <a href={ this.product.url } title={ this.product.name }>
                <h4
                    className="products-slider-slide-title"
                    itemProp="name"
                >{ this.product.name }</h4>
            </a>
        );
    }

    /**
     * all price related elements as prices, buy button discount etc.
     */
    productPriceBlock() {
        const { price, listPrice, currency } = this.product;

        return (
            <Fragment>
                { listPrice.number > price.number && (
                    <span className="products-slider-old-price">
                        <Price currency={ currency } price={ listPrice.number }/>
                    </span>
                ) }

                <span className="products-slider-current-price">
                    <Price currency={ currency } price={ price.number }/>
                </span>
            </Fragment>
        );
    }

    render(props) {
        const classes = {
            product: [],
            image: {
                link: ['products-slider-image-link'],
                container: ['products-slider__image-container', 'products-slider-image-container'],
                noImage: ["products-slider-no-image"],
            },
        };

        return (
            <Product
                product={ this.product }
                images={ this.imgList }
                mainInfo={ this.productMainInfoBlock() }
                price={ this.productPriceBlock() }
                classes={classes}
            />
        );
    }
}

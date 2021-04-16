import classnames      from 'classnames';
import { Fragment }    from 'preact';
import Product         from '@/components/product/card/Product';
import ImgCatalog      from '@/components/product/card/catalog/ImgCatalog';
import Price           from '@/components/product/card/components/Price';
import QuantityGroup   from '@/components/product/card/QuantityGroup';
import AddToCartButton from '@/components/product/AddToCartButton';
import CatalogContext  from '@/components/catalog/CatalogContext';
import t               from '@/i18n';

export default class Card extends Component {
    constructor( props ) {
        super( props );

        const product = this.product = props.product;

        //list of jsx img elements
        this.imgList = [];

        for ( let i = 0; product.images && i < product.images.length; i++ ) {
            this.imgList.push( <ImgCatalog image={ product.images[ i ] }/> );
        }
    }

    /**
     * main content of product cart as name, description, attributes etc.
     */
    productContentBlock() {
        const product = this.product;

        function t( str ) {
            return str;
        }

        return (
            <Fragment>
                {/*title*/ }
                <h4 className={ classnames( 'product-card-title__catalog', `product-card-title__catalog-${ this.context.viewMode }` ) } itemProp="name">
                    <a href={ product.url } title={ product.name } className="product-card-title-link">
                        { product.name }
                    </a>
                </h4>

                {/*sku*/ }
                <div className="sku show-for-large">
                    <span className="value">
                        <span>{ t( 'SKU' ) }: </span>
                        <span className="style" itemProp="sku">{ product.productcode }</span>
                    </span>
                </div>

                {/*brand*/ }
                { product.brand &&
                <div className="brand show-for-small">
                    <span>{ t( 'Brand' ) }: </span>
                    <a className="value" itemProp="brand" href={ product.brandUrl }>
                        { product.brand }
                    </a>
                </div>
                }

                {/*description*/ }
                { product.description &&
                <Fragment>
                    <div className="description show-for-medium">
                        <span itemProp="description">{ product.description }</span>

                        <div className="see-details">
                            <a href={ product.url } className="show-for-medium">{ t( 'See details' ) }</a>
                        </div>
                    </div>
                    <noindex>
                        <div className="description show-for-small hide-for-medium">
                            { product.description }
                        </div>
                    </noindex>
                </Fragment>
                }
            </Fragment>
        );
    }

    //print lead time
    leadTime() {
        if ( this.product.lead_time_message ) {
            return (
                <div className="p-label lead-time">
                    <i/>
                    <div className="text">{ this.product.lead_time_message }</div>
                </div>
            );
        }
    }

    minAmount() {
        if ( this.product.min_amount > 1 ) {
            if ( this.product.mult_order_quantity === 'Y' ) {
                return (
                    <div className="multiply-quantity icon info padding">
                        <i/>
                        <span className="text">Order in multiples of { this.product.min_amount } items</span>
                    </div>
                );
            }
            else {
                return (
                    <div className="p-label last-items">
                        <i className="least-items-icon"/>
                        <span className="text">Order at least { this.product.min_amount } items</span>
                    </div>
                );
            }
        }
    }

    /**
     * all price related elements as prices, buy button discount etc.
     */
    productPriceBlock() {
        const product = this.product;
        const quantityGroupClasses = { group: [] };

        const addToCartClasses = {
            mainWrapper: [ 'add-to-cart-button_catalog' ],
            button: [ 'add-to-cart-button-add__catalog' ],
            checkoutLink: [ 'add-to-cart-button-checkout_catalog' ],
            checkoutLinkWrapper: [ 'add-to-cart-button-wrapper__catalog' ],
            buttonComplex: [ 'add-to-cart-button-add__complex-product' ],
            checkoutLinkComplex: [ 'add-to-cart-button-checkout__complex-product' ],

        };

        const overflowContainer = [ 'overflow_container' ];

        if ( this.context.viewMode === 'tile' ) {
            quantityGroupClasses.group.push( 'quantity-group__catalog-tile' );

            addToCartClasses.button.push( 'add-to-cart-button-add__catalog-tile' );
            addToCartClasses.checkoutLink.push( 'hide' );
            overflowContainer.push( 'overflow_container__tile' );
        }
        else {
            addToCartClasses.mainWrapper.push( 'catalog_add-to-cart-list' );
            quantityGroupClasses.group.push( 'quantity-group__catalog-list' );
            addToCartClasses.checkoutLink.push( 'flex' );
            addToCartClasses.checkoutLinkWrapper.push( 'add-to-cart-link-wrapper_list' );
        }

        return (
            <Fragment>
                <div className="price_container">
                    { product.listPrice.number > product.price.number && (
                        <div className="old">
                            <span>{ t( 'List Price' ) }: </span>
                            <span className="products-slider-old-price">
                                <Price currency={ product.currency } price={ product.listPrice.formatted }/>
                            </span>
                        </div>
                    ) }

                    <div className="current">
                        <span>{ t( 'Price' ) }: </span>
                        <span className="products-slider-current-price">
                            <Price currency={ product.currency } price={ product.price.formatted }/>
                        </span>
                    </div>
                </div>

                <div className={ classnames( overflowContainer ) }>
                    { ( () => {
                        if ( this.product.isGroupRoot ) {
                            return (
                                <div className="cart_buttons">
                                    <a className="button waves waves-orange yellow-white see-other" href={ this.product.url }>
                                        <span className="text">See { this.product.childrenNumber } products variation</span>
                                    </a>
                                </div>
                            );
                        }
                        else if ( this.product.inStock ) {
                            return (
                                <Fragment>
                                    <div className={ classnames( 'cart-quantity', { 'cart-quantity__tile': this.context.viewMode === 'tile' } ) }>
                                        { this.context.viewMode === 'list' &&
                                        <label htmlFor={ 'quantity-' + product.productid } className="show-for-large">
                                            <span className="show-for-xl">Quantity:</span>
                                            <span className="show-for-large-only">Qty:</span>
                                        </label>
                                        }

                                        <QuantityGroup product={ product } classes={ quantityGroupClasses }/>
                                    </div>

                                    { this.context.viewMode === 'list' &&
                                    <div className="info_container">
                                        { this.leadTime() }

                                        { this.minAmount() }
                                    </div>
                                    }

                                    <div className={ classnames( 'cart-add', { 'cart-add__tile': this.context.viewMode === 'tile' } ) }>
                                        <AddToCartButton type={ 'catalog' } classes={ addToCartClasses }/>
                                    </div>

                                    { this.context.viewMode === 'tile' &&
                                    <div className="info_container">
                                        { this.leadTime() }

                                        { this.minAmount() }
                                    </div>
                                    }
                                </Fragment>
                            );
                        }
                        else {
                            return (
                                <div className="out-of-stock">
                                    <div className="p-label out-of-stock">
                                        <i/>
                                        <span className="text">{ t( 'Out of stock' ) }</span>
                                    </div>

                                    { this.product.eta_date &&
                                    <div className="eta-date">
                                        { t( 'Eta date' ) }: { this.product.eta_date }
                                    </div>
                                    }

                                    <div className="notify"></div>
                                </div>
                            );
                        }
                    } )() }
                </div>
            </Fragment>
        );
    }

    render( props ) {
        const classes = props.classes ?? { product: [] };

        classes.product.push( 'catalog-product', 'item' );
        classes.image = {
            container: [ 'image_container' ],
            link: 'products-slider-image-link__catalog-list',
        };

        return (
            <Product
                product={ this.product }
                images={ this.imgList }
                mainInfo={ this.productContentBlock() }
                price={ this.productPriceBlock() }
                classes={ classes }
            />
        );
    }
}

Card.contextType = CatalogContext;

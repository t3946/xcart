import classnames from 'classnames';
import {Fragment} from 'preact';
import Product from '@/components/product/card/Product';
import ImgCatalog from '@/components/product/card/catalog/ImgCatalog';
import Price from '@/components/product/card/components/Price';
import QuantityGroup from '@/components/product/card/QuantityGroup';
import AddToCartButton from '@/components/product/AddToCartButton';
import CatalogContext from '@/components/catalog/CatalogContext';
import t from '@/i18n';

export default class Card extends Component {
    constructor(props) {
        super(props);

        const product = this.product = props.product;

        //list of jsx img elements
        this.imgList = [];
        this.state = {
            buttonSimple: true,
        }

        for (let i = 0; product.images && i < product.images.length; i++) {
            this.imgList.push(<ImgCatalog image={product.images[i]}/>);
        }
    }

    /**
     * main content of product cart as name, description, attributes etc.
     */
    productContentBlock() {
        const product = this.product;

        function t(str) {
            return str;
        }

        return (
            <Fragment>
                {/*title*/}
                <h4 className={classnames('product-card-title__catalog', `product-card-title__catalog-${this.context.viewMode}`)}
                    itemProp="name">
                    <a href={product.url} title={product.name} className="product-card-title-link">
                        {product.name}
                    </a>
                </h4>

                {/*sku*/}
                <div className="product-card-sku product-card__label show-for-large">
                    <span className="value">
                        <span>{t('SKU')}: </span>
                        <span className="style" itemProp="sku">{product.productcode}</span>
                    </span>
                </div>

                {/*brand*/}
                {product.brand &&
                <div className="product-card-brand product-card__label">
                    <span>{t('Brand')}: </span>
                    <a className="value" itemProp="brand" href={product.brandUrl}>
                        {product.brand}
                    </a>
                </div>
                }

                {/*description*/}
                {product.description &&
                <Fragment>
                    <div className="product-card-description show-for-medium">
                        <span itemProp="description">{product.description}</span>

                        <div className="see-details">
                            <a href={product.url} className="show-for-medium">{t('See details')}</a>
                        </div>
                    </div>
                    <noindex>
                        <div className="product-card-description show-for-small hide-for-medium">
                            {product.description}
                        </div>
                    </noindex>
                </Fragment>
                }
            </Fragment>
        );
    }

    //print lead time
    leadTime() {
        const leadTime = this.product.lead_time;
        let leadTimeMessage = null;
        let fromDays = null;
        let toDays = null;

        if (leadTime.lead_time_message) {
            leadTimeMessage = leadTime.lead_time_message.trim();
        } else if (leadTime.brand.leadtime_from) {
            const brand = leadTime.brand;

            if ((brand.leadtime_from === brand.leadtime_to || !brand.leadtime_to) ) {
              fromDays = parseInt( brand.leadtime_from );
            } else {
              fromDays = parseInt( leadTime.brand.leadtime_from );
              toDays = parseInt( leadTime.brand.leadtime_to );
            }
        } else if (leadTime.dx.leadtime) {
            const dx = leadTime.dx;

            if ((dx.leadtime === dx.leadtime_to || !dx.leadtime_to) ) {
                toDays = parseInt(dx.leadtime);
            } else {
                fromDays = parseInt(dx.leadtime);
                toDays = parseInt(dx.leadtime_to);
            }
        }

        let days = null;

        if (fromDays && toDays) {
          days = `${fromDays}-${toDays}`;
        } else if (fromDays) {
          days = fromDays;
        } else if (toDays) {
          days = toDays;
        }

        if (days) {
          leadTimeMessage = t('Lead time for this product is %count% business days', {count: days});
        }

        if (leadTimeMessage) {
            return (
                <div className="p-label lead-time product-card__label">
                    <i/>
                    <div className="text">{leadTimeMessage}</div>
                </div>
            );
        }
    }

    minAmount() {
        if (this.product.min_amount > 1) {
            if (this.product.mult_order_quantity === 'Y') {
                return (
                    <div className="multiply-quantity icon info padding product-card__label">
                        <i/>
                        <span className="text">Order in multiples of {this.product.min_amount} items</span>
                    </div>
                );
            } else {
                return (
                    <div className="p-label last-items product-card__label">
                        <i className="least-items-icon"/>
                        <span className="text">Order at least {this.product.min_amount} items</span>
                    </div>
                );
            }
        }
    }

    addToCartChangeMode() {
        this.setState({
            buttonSimple: false,
        });
    }

    /**
     * all price related elements as prices, buy button discount etc.
     */
    productPriceBlock() {
        const product = this.product;
        const quantityGroupClasses = {group: []};

        const addToCartClasses = {
            mainWrapper: ['add-to-cart-button_catalog'],
            button: ['add-to-cart-button-add__catalog'],
            checkoutLink: ['add-to-cart-button-checkout_catalog'],
            checkoutLinkWrapper: ['add-to-cart-button-wrapper__catalog'],
            buttonComplex: ['add-to-cart-button-add__complex-product'],
            checkoutLinkComplex: ['add-to-cart-button-checkout__complex-product'],
            addToCartLongText: ['add-to-cart-text__long add-to-cart-text__long-catalog'],
            addToCartShortText: ['add-to-cart-text__short add-to-cart-text__short-catalog'],
        };

        const advancedClasses = ['product-card-price-info'];

        const priceAttributes = ['price-attributes'];

        if (this.context.viewMode === 'tile') {
            quantityGroupClasses.group.push('quantity-group__catalog-tile');

            addToCartClasses.button.push('add-to-cart-button-add__catalog-tile');
            addToCartClasses.checkoutLink.push('hide');
            priceAttributes.push('price-attributes__tile');
            advancedClasses.push('product-price-advanced__tile');
        } else {
            addToCartClasses.mainWrapper.push('catalog_add-to-cart-list');
            quantityGroupClasses.group.push('quantity-group__catalog-list');
            addToCartClasses.checkoutLink.push('flex');
            addToCartClasses.checkoutLinkWrapper.push('add-to-cart-link-wrapper_list');
        }

        return (
            <Fragment>
                <div className="price_container product-card-price">
                    {product.listPrice.number > product.price.number && (
                        <div className="old">
                            <span className="show-for-medium">{t('List Price')}: </span>
                            <span className="products-slider-old-price">
                                <Price currency={product.currency} price={product.listPrice.formatted}/>
                            </span>
                        </div>
                    )}

                    <div className="current">
                        <span className="show-for-medium">{t('Price')}: </span>
                        <span className="products-slider-current-price">
                            <Price currency={product.currency} price={product.price.formatted}/>
                        </span>
                    </div>
                </div>

                <div className={classnames(advancedClasses)}>
                    <div className={classnames(priceAttributes)}>
                        {(() => {
                            if (this.product.isGroupRoot) {
                                return (
                                    <div className="info-container">
                                        <a className="button waves waves-orange yellow-white see-other"
                                           href={this.product.url}>
                                            <span
                                                className="text">See {this.product.childrenNumber} products variation</span>
                                        </a>
                                    </div>
                                );
                            } else if (this.product.inStock) {
                                const buttonContainerClasses = [
                                    'cart-add',
                                    'product-card-button',
                                    'catalog_product-card-button',
                                    {'cart-add__tile': this.context.viewMode === 'tile'},
                                ];

                                return (
                                    <Fragment>
                                        <div
                                            className={classnames('cart-quantity', {'cart-quantity__tile': this.context.viewMode === 'tile'})}>
                                            {this.context.viewMode === 'list' &&
                                            <label htmlFor={'quantity-' + product.productid} className="show-for-large">
                                                <span className="show-for-xl">Quantity:</span>
                                                <span className="show-for-large-only">Qty:</span>
                                            </label>
                                            }

                                            <QuantityGroup product={product} classes={quantityGroupClasses}/>
                                        </div>

                                        {this.context.viewMode === 'list' &&
                                        <div className="info-container">
                                            {this.leadTime()}

                                            {this.minAmount()}
                                        </div>
                                        }

                                        <div className={classnames(buttonContainerClasses)}>
                                            <AddToCartButton
                                              type={'catalog'}
                                              classes={addToCartClasses}
                                              onChangeMode={this.addToCartChangeMode.bind(this)}
                                            />
                                        </div>

                                        <div className={classnames('product-card-checkout-link', {hide: this.state.buttonSimple})}>
                                            <a href={ this.context.checkoutUrl }
                                               className="button yellow-white waves waves-orange waves-effect add-to-cart-button-checkout"
                                            >Checkout</a>
                                        </div>
                                    </Fragment>
                                );
                            } else {
                                return (
                                    <div className="info-container">
                                        <div className="p-label out-of-stock product-card__label">
                                            <i/>
                                            <span className="text p-label-text_out-of-stock">{t('Out of stock')}</span>
                                        </div>

                                        {this.product.eta_date &&
                                        <div className="product-card-info product-card-info__eta-date product-card__label">
                                            {t('Eta date')}: {this.product.eta_date}
                                        </div>
                                        }

                                        <div className="notify"></div>
                                    </div>
                                );
                            }
                        })()}
                    </div>

                    {this.context.viewMode === 'tile' &&
                    <div className="info-container">
                        {this.leadTime()}

                        {this.minAmount()}
                    </div>
                    }
                </div>
            </Fragment>
        );
    }

    render(props) {
        const classes = props.classes ?? {product: []};
        const self = this;

        classes.product.push('catalog-product', 'item');
        classes.image = {
            link: `product-image__catalog-${self.context.viewMode}`,
            container: ['product-card-image__container', 'product-card-image-container', 'product-card-image-container__catalog'],
            complex: {
                container: ['product-card-image-group__catalog'],
            },
            single: {
                container: ['product-card-image-group__catalog'],
            },
        };

        classes.image.noImage = ["product-no-image__catalog"];

        return (
            <Product
                product={this.product}
                images={this.imgList}
                mainInfo={this.productContentBlock()}
                price={this.productPriceBlock()}
                classes={classes}
            />
        );
    }
}

Card.contextType = CatalogContext;

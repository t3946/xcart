import classnames                           from 'classnames';
import Image                                from './components/Image';
import Price                                from './components/Price';
import { Fragment }                         from 'preact';
import i18n                                 from 'i18next';
import intervalPlural                       from 'i18next-intervalplural-postprocessor';
import { initReactI18next, useTranslation } from 'react-i18next';

i18n.use( initReactI18next )
    .use( intervalPlural )
    .init( {
        resources: {
            en: {
                translation: window.translation,
            },
        },

        interpolation: {
            prefix: '%',
            suffix: '%',
            escapeValue: false,
        },

        lng: 'en',
        fallbackLng: 'ru',
    } );

export default class Product extends Component {
    constructor( { productData, context } ) {
        super();
        this.productData = productData;
        this.context = context;
    }

    render() {
        const { t } = useTranslation();
        const productData = this.productData;
        const context = this.context;
        const analytics_source = '';

        return (
            <div className={ classnames( 'item', 'product', { 'out_of_stock': productData.inStock } ) }
                 data-product={ productData.id }
                 data-name={ productData.name }
                 data-source={ analytics_source }
                 data-brand={ productData.brand }
                 data-prices={ productData.prices }
                 data-list-price={ productData.listPrice.number }
                 itemScope
                 itemType="http://schema.org/Product"
                 itemProp="itemListElement"
            >
                <Image { ...productData } />

                <div className="products-slider-price-container" itemProp="offers" itemScope>
                    {/*title*/ }
                    <div className="info_container container">
                        <a href={ productData.url } title={ productData.name }>
                            <h4
                                className="products-slider-slide-title"
                                itemProp="name"
                            >{ productData.name }</h4>
                        </a>
                    </div>

                    {/*sku*/ }
                    { ( function() {
                        if ( context === 'catalog' ) {
                            return (
                                <Fragment>
                                <div className="sku show-for-large">
                                    <span className="value">
                                        { t( 'SKU' ) }: <span className="style" itemProp="sku">{ productData.productcode }</span>
                                    </span>
                                </div>


                                {/*brand*/ }
                                <div className="brand show-for-small">
                                    { t( 'Brand' ) }:
                                    <a className="value" itemProp="brand" href="{$brand->getAbsoluteUrl()}">
                                        { productData.brand }
                                    </a>
                                </div>
                                </Fragment>
                        );
                        }
                    } )() }
                    {/*description*/ }
                    { ( function() {
                        if ( productData.description && context === 'catalog' ) {
                            return (
                                <Fragment>
                                    <div className="description show-for-medium">
                                        <span itemProp="description">{ productData.description }</span>

                                        <a href={ productData.url } className="show-for-medium see">{ t( 'See details' ) }</a>
                                    </div>
                                    <noindex>
                                        <div className="description show-for-small hide-for-medium">
                                            { productData.description }
                                        </div>
                                    </noindex>
                                </Fragment>
                            );
                        }
                    } )() }

                    {/*price*/ }
                    { productData.listPrice.number > productData.price.number && (
                        <span className="products-slider-old-price">
                            <Price currency={ productData.currency } price={ productData.listPrice.formatted }/>
                        </span>
                    ) }

                    <span className="products-slider-current-price">
                        <Price currency={ productData.currency } price={ productData.price.formatted }/>
                    </span>
                </div>
            </div>
        );
    }
}
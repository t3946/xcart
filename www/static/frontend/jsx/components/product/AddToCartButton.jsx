import classnames       from 'classnames';
import CatalogContext   from '@/components/catalog/CatalogContext';
import { cartAdd }      from '@/redusers/appCartRediser';
import * as preact      from 'preact';
import { Fragment }     from 'preact';
import CreateWaitButton from '@/components/AnimateWaitButton';
import t                from '@/i18n';

export default class AddToCartButton extends Component {
    constructor( props ) {
        super( props );

        this.SIMPLE_MODE = 1;
        this.COMPLEX_MODE = 2;
        this.state = {
            classes: {},
            mode: this.SIMPLE_MODE,
        };

        this.onAddToCart = this.onAddToCart.bind( this );

        this.checkoutLink = preact.createRef();
        this.button = preact.createRef();
        this.mainWrapper = preact.createRef();
        this.checkoutWrapper = preact.createRef();
    }

    /**
     * computed html classes for redraw component
     */
    computeClasses() {
        let mainWrapper = [ 'add-to-cart-button' ];
        let button = [ 'add', 'button', 'yellow', 'wait-button', 'add-to-cart-button-add' ];
        let checkoutLinkWrapper = [ 'add-to-cart-button-wrapper' ];
        let checkoutLink = [ 'button', 'yellow-white', 'waves waves-orange', 'waves-effect', 'add-to-cart-button-checkout' ];
        let addToCartLongText = [ 'text' ];
        let addToCartShortText = [ 'text' ];

        const classes = {
            mainWrapper,
            button,
            checkoutLinkWrapper,
            checkoutLink,
            addToCartLongText,
            addToCartShortText,
        };

        const propsClasses = this.props.classes;

        // extend computed classes by props classes
        if ( propsClasses ) {
            if ( this.state.mode === this.COMPLEX_MODE ) {
                button.push( 'add-to-cart-button-add__complex', propsClasses.buttonComplex );
                checkoutLink.push( 'add-to-cart-button-checkout__complex', propsClasses.checkoutLinkComplex );
            }
            for ( let key in classes ) {
                classes[ key ].push( propsClasses[ key ] );
            }
        }

        // hide by default
        if ( !propsClasses.addToCartShortText ) {
            classes.addToCartShortText.push( 'hide' );
        }

        // join classes
        for ( let key in classes ) {
            classes[ key ] = classnames( classes[ key ] );
        }

        this.classes = classes;
    }

    /**
     * print no need account template part if need
     */
    noAccount() {
        const noAccount = false;

        if ( noAccount ) {
            return (
                <div className="no-account">{ t( 'No account needed! \n Checkout only takes 3 minutes.' ) }</div>
            );
        }
    }

    productItemResetState( product ) {
        const input = product.querySelector( '.quantity-group input' );
        const val = input.min;

        input.value = val;
        product.dataset.quantity = val;

        $( document ).trigger( 'component.quantity.change', {
            target: product,
            val: val,
            product: product,
        } );
    };

    onAddToCart() {
        const button = this.button.current;

        if ( this.state.mode === this.SIMPLE_MODE ) {
            setTimeout( () => {
                this.setState( { mode: this.COMPLEX_MODE } );
                this.computeClasses();
            }, 1000 );
        }

        const buttonAnimation = CreateWaitButton( button );
        const product = button.closest( '[data-product]' );

        if ( product ) {
            let form = null;

            // product options form (need to determine exactly sort of product)
            const infoFormId = button.closest( '.cart-add' ).getAttribute( 'data-form-id' );

            if ( infoFormId ) {
                form = document.getElementById( infoFormId );

                if ( typeof document.formValidators !== 'undefined'
                    && document.formValidators[ infoFormId ] !== 'undefined' ) {

                    let formValidate = document.formValidators[ infoFormId ];
                    formValidate.checkAllForm();

                    if ( formValidate.hasErrors ) {
                        return false;
                    }
                }
            }

            let opt = [];
            let values = $( form ).serializeArray();

            for ( let oneValue of values ) {
                let valueParts = oneValue.value.split( '_' );
                let identifiersParts = valueParts[ 0 ].split( '-' );
                opt.push( { 'optionId': identifiersParts[ 0 ], 'variantId': identifiersParts[ 1 ] } );
            }

            let data = [
                {
                    id: product.dataset.product,
                    quantity: product.dataset.quantity || 1,
                    options: opt,
                },
            ];

            buttonAnimation.start();

            cartAdd( data, () => {
                this.productItemResetState( product );
                $( '.jackpot' ).show();
            } );

            window.sendAnalytics.addToCart( product );
        }
    }

    render() {
        this.computeClasses();

        return (
            <div className={ this.classes.mainWrapper } ref={ this.mainWrapper }>
                <a className={ this.classes.button } onClick={ this.onAddToCart } ref={ this.button }>
                    { this.state.mode === this.SIMPLE_MODE && this.context.viewMode === 'list' &&
                    <Fragment>
                        <span className="text button-text">{ t( 'Add to cart' ) }</span>
                        <span className="text hide">{ t( 'Add' ) }</span>
                        <span className="wait-text">{ t( 'Added' ) }</span>
                    </Fragment>
                    }
                </a>

                { this.state.mode === this.COMPLEX_MODE &&
                <div className={ this.classes.checkoutLinkWrapper } ref={ this.checkoutWrapper }>
                    <a href={ this.context.checkoutUrl } className={ this.classes.checkoutLink } ref={ this.checkoutLink }>{ t( 'Checkout' ) }</a>
                    { this.noAccount() }
                </div>
                }
            </div>
        );
    }
}

AddToCartButton.contextType = CatalogContext;

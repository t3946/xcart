import Card           from '@/components/product/card/catalog/Card';
import CatalogContext from '@/components/catalog/CatalogContext';
import classnames     from 'classnames';

export default class ProductsList extends Component {
    constructor( props ) {
        super( props );

        this.state = {
            items: [],
            nextPage: 1,
            sort: null,
            sortWasChanged: false,
        };

        this.loadData();
    }

    loadData() {
        this.props.onBeginLoading( this.state.nextPage );

        //end of pagination
        if (!this.props.catalogUrl) {
            return;
        }

        let url = this.props.catalogUrl.split( '?' )[ 0 ];
        const { nextPage, sort } = this.state;

        url = url + `?page=${ nextPage }&sort=${ sort }`;

        fetch( url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        } ).then( res => res.json() )
           .then(
               ( res ) => {
                   this.props.onEndLoading();
                   this.state.items.push( ...res.items );
                   this.setState( {
                       items: this.state.items,
                       isLoaded: true,
                       nextPage: this.state.nextPage + 1,
                   } );
                   this.paginationPage += 1;
                   this.context.onUpdateProductList( res.pager, res.href );
               },
               ( error ) => {
                   this.setState( {
                       error: error.message,
                   } );
               },
           );
    }

    productItem( product, viewMode ) {
        const classes = {
            product: [
                `catalog-product__${ viewMode }`,
                `catalog-product_${ viewMode }`,
            ],
        };

        return ( <Card product={ product } classes={ classes } key={ `product-card-${product.productid}` }/> );
    }

    shouldComponentUpdate( nextProps, nextState ) {
        if ( nextProps.sortKey !== this.props.sortKey ) {
            nextState.sort = nextProps.sortKey;
            nextState.nextPage = 1;
            nextState.items = [];
            this.loadData();
        }

        return true;
    }

    render() {
        const viewMode = this.context.viewMode;

        const classes = [
            'product-items',
            `${ viewMode }-view`,
            `product-items__${ viewMode }`,
            {
                'padding-0': this.state.items.length === 0,
            }
        ];

        return (
            <div className={ classnames( classes ) }>
                { this.state.items.map( ( item ) => {
                    return this.productItem( item, viewMode );
                } ) }
            </div>
        );
    }
}

ProductsList.contextType = CatalogContext;

import Card           from '@/components/product/card/catalog/Card';
import CatalogContext from '@/components/catalog/CatalogContext';
import classnames     from 'classnames';

export default class ProductsList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            items: [],
        };

        this.loadData();
    }

    loadData() {
        this.props.onBeginLoading();

        fetch( this.props.catalogUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        } ).then( res => res.json() )
           .then(
               ( res ) => {
                   this.props.onEndLoading();
                   this.state.items.push( ...res.items );
                   this.setState( { items: this.state.items, isLoaded: true } );
                   this.paginationPage += 1;
                   this.context.onUpdateProductList(res.pager, res.href);
               },
               ( error ) => {
                   this.setState( {
                       error: error.message,
                   } );
               },
           );
    }

    productItem( product, viewMode ) {
        return ( <Card product={ product } classes={ { product: [ `catalog-product__${ viewMode }` ] } }/> );
    }

    render() {
        const viewMode = this.context.viewMode;

        return (
            <div className={ classnames( [ 'product-items', `${ viewMode }-view` ] ) }>
                { this.state.items.map( ( item ) => { return this.productItem( item, viewMode ); } ) }
            </div>
        );
    }
}

ProductsList.contextType = CatalogContext;

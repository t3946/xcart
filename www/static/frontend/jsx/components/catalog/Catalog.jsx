import StateLine      from '@/components/catalog/StateLine';
import StateLineGroupProduct from '@/components/catalog/StateLineGroupProduct';
import ProductsList   from '@/components/catalog/ProductsList';
import CatalogContext from '@/components/catalog/CatalogContext';
import LoadMore       from '@/components/catalog/LoadMore';
import Storage        from '@/utils/localStorage/storage';

export default class Catalog extends Component {
    constructor( props ) {
        super( props );

        this.VIEW_MODE_STORAGE_KEY = 'cviewt';

        const onViewModeChange = this.onViewModeChange.bind( this );
        const onUpdateProductList = this.onUpdateProductList.bind( this );
        this.onLoadProductList = this.onLoadProductList.bind( this );
        this.onNext = this.onNext.bind( this );
        this.onBeginLoading = this.onBeginLoading.bind( this );
        this.onEndLoading = this.onEndLoading.bind( this );

        this.productList = React.createRef();

        this.state = {
            ...props,
            viewMode: Storage.get( this.VIEW_MODE_STORAGE_KEY, null ),
            onViewModeChange,
            onUpdateProductList,
            // true after first product list loaded
            loaded: false,
            // loading product list
            isLoading: false,
            // next page url in catalog
            baseUrl: props.catalogUrl.split('?')[0],
            next: props.catalogUrl,
            printStateLines: true,
        };
    }

    onUpdateProductList( pager, next ) {
        this.setState( { pager, loaded: true, next } );
    }

    onViewModeChange( viewMode ) {
        this.setState( { viewMode } );
        Storage.set( this.VIEW_MODE_STORAGE_KEY, viewMode );
    }

    printStateLine() {
        if ( this.state.loaded ) {
            const props = {
                hideSort: this.state.hideSort,
                sortingOptions: this.state.sortingOptions,
                classes: {
                    container: 'products-state-line_catalog',
                },
                sortKey: this.state.sortKey,
            };

            switch ( this.props.mode ) {
                case 'group-product':
                    return ( <StateLineGroupProduct { ...props } onSort={ this.onSortCatalog.bind( this ) }/> );
                default:
                    return ( <StateLine { ...props } onSort={ this.onSortCatalog.bind( this ) }/> );
            }
        }
    }

    onSortCatalog( sortKey ) {
        this.setState( { sortKey } );
    }

    onLoadProductList() {
        this.setState( { loaded: false } );
    }

    onNext() {
        this.productList.current.loadData();
    }

    onBeginLoading( page ) {
        if ( page === 1 ) {
            this.setState( { printStateLines: false, } );
        }

        this.setState( { isLoading: true } );
    }

    onEndLoading() {
        this.setState( { printStateLines: true, isLoading: false } );
    }

    render() {
        return (
            <div className="catalog">
                <CatalogContext.Provider value={ this.state }>
                    { this.state.printStateLines && this.printStateLine() }

                    <ProductsList
                        ref={ this.productList }
                        catalogUrl={ this.state.baseUrl }
                        onBeginLoading={ this.onBeginLoading }
                        onEndLoading={ this.onEndLoading }
                        sortKey={ this.state.sortKey }
                        searchText={ this.props.searchText }
                    />

                    { this.state.printStateLines && this.printStateLine() }

                    <LoadMore
                        onNext={ this.onNext } next={ this.state.next }
                        classes={ [ 'catalog_load-more', { 'margin-0': this.state.printStateLines === false } ] }
                        isLoading={ this.state.isLoading }
                    />
                </CatalogContext.Provider>
            </div>
        );
    }
}

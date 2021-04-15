import StateLine      from '@/components/catalog/StateLine';
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
            next: props.catalogUrl,
        };
    }

    onUpdateProductList( pager, next ) {
        this.setState( { pager, loaded: true, next: next } );
    }

    onViewModeChange( viewMode ) {
        this.setState( { viewMode } );
        Storage.set( this.VIEW_MODE_STORAGE_KEY, viewMode );
    }

    printStateLine() {
        if ( this.state.loaded ) {
            return (
                <StateLine { ...this.state } />
            );
        }
    }

    onLoadProductList() {
        this.setState( { loaded: false } );
    }

    onNext() {
        this.productList.current.loadData();
    }

    onBeginLoading() {
        this.setState( { isLoading: true } );
    }

    onEndLoading() {
        this.setState( { isLoading: false } );
    }

    render() {
        return (
            <div className="catalog">
                <CatalogContext.Provider value={ this.state }>
                    { this.printStateLine() }

                    <ProductsList
                        ref={ this.productList }
                        catalogUrl={ this.state.next }
                        onBeginLoading={ this.onBeginLoading }
                        onEndLoading={ this.onEndLoading }
                    />

                    { this.printStateLine() }

                    <LoadMore onNext={ this.onNext } next={ this.state.next } classes={ [ 'catalog_load-more' ] } isLoading={ this.state.isLoading }/>
                </CatalogContext.Provider>
            </div>
        );
    }
}

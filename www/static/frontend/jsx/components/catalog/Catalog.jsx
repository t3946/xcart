import StateLine from "@/components/catalog/StateLine";
import StateLineGroupProduct from "@/components/catalog/StateLineGroupProduct";
import ProductsList from "@/components/catalog/ProductsList";
import CatalogContext from "@/components/catalog/CatalogContext";
import LoadMore from "@/components/catalog/LoadMore";
import Storage from "@/utils/localStorage/storage";
import $ from "jquery";

export default class Catalog extends Component {
  constructor(props) {
    super(props);

    this.VIEW_MODE_STORAGE_KEY = "cviewt";

    const onViewModeChange = this.onViewModeChange.bind(this);
    const onUpdateProductList = this.onUpdateProductList.bind(this);
    this.onLoadProductList = this.onLoadProductList.bind(this);
    this.onNext = this.onNext.bind(this);
    this.onBeginLoading = this.onBeginLoading.bind(this);
    this.onEndLoading = this.onEndLoading.bind(this);

    this.productList = React.createRef();

    this.state = {
      ...props,
      viewMode: Storage.get(this.VIEW_MODE_STORAGE_KEY, "tile"),
      onViewModeChange,
      onUpdateProductList,
      // true after first product list loaded
      loaded: false,
      // loading product list
      isLoading: false,
      // next page url in catalog
      baseUrl: props.catalogUrl.split("?")[0],
      //ссылка на следующую страницу каталога
      nextPageUrl: props.catalogUrl,
      printStateLines: true,
      pager: null,
      infinityLoad: true,
      observeProduct: null,
      infinityLoadObserver: null,
    };

    if (this.state.infinityLoad) {
      const options = {
        root: null,
        rootMargin: "0px",
        threshold: 0.5,
      };

      this.state.infinityLoadObserver = new IntersectionObserver(
        (entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              observer.unobserve(this.state.observeProduct);
              this.productList.current.loadData();
            }
          });
        },
        options
      );
    }

    $(".catalog-skeleton").remove();
  }

  onUpdateProductList(pager, nextPageUrl) {
    this.setState({ pager, loaded: true, nextPageUrl });
  }

  onViewModeChange(viewMode) {
    this.setState({ viewMode });
    Storage.set(this.VIEW_MODE_STORAGE_KEY, viewMode);
  }

  printStateLine() {
    const props = {
      hideSort: this.state.hideSort,
      sortingOptions: this.state.sortingOptions,
      classes: {
        container: "products-state-line_catalog",
      },
      sortKey: this.state.sortKey,
    };

    switch (this.props.mode) {
      case "group-product":
        return (
          <StateLineGroupProduct
            {...props}
            onSort={this.onSortCatalog.bind(this)}
          />
        );
      default:
        return <StateLine {...props} onSort={this.onSortCatalog.bind(this)} />;
    }
  }

  onSortCatalog(sortKey) {
    this.setState({ sortKey });
  }

  onLoadProductList() {
    this.setState({ loaded: false });
  }

  onNext() {
    this.productList.current.loadData();
  }

  onBeginLoading(page) {
    if (page === 1) {
      this.setState({ printStateLines: false });
    }

    this.setState({ isLoading: true });
  }

  onEndLoading() {
    this.setState({
      printStateLines: true,
      isLoading: false,
    });
  }
  componentDidUpdate() {
    if (this.state.infinityLoad) {
      const newObserveProduct = $(".product-items .catalog-product").last()[0];

      if (
        newObserveProduct &&
        this.state.observeProduct !== newObserveProduct
      ) {
        this.setState({
          observeProduct: newObserveProduct,
        });

        if (this.state.nextPageUrl) {
          this.state.infinityLoadObserver.observe(newObserveProduct);
        }
      }
    }
  }

  loadMoreButtonTemplate() {
    // новый режим авто-подгрузки товаров
    if (this.state.infinityLoad) {
      return;
    }

    // все товары были загружены
    if (!this.state.nextPageUrl) {
      return;
    }

    // сейчас товары уже загружаются
    if (this.state.isLoading === true) {
      return;
    }

    return (
      <LoadMore
        onNext={this.onNext}
        nextPageUrl={this.state.nextPageUrl}
        classes={[
          "catalog_load-more",
          { "margin-0": this.state.printStateLines === false },
        ]}
      />
    );
  }

  render() {
    return (
      <div className="catalog">
        <CatalogContext.Provider value={this.state}>
          {this.printStateLine()}

          <ProductsList
            ref={this.productList}
            catalogUrl={this.state.baseUrl}
            onBeginLoading={this.onBeginLoading}
            onEndLoading={this.onEndLoading}
            isLoading={this.state.isLoading}
            sortKey={this.state.sortKey}
            searchText={this.props.searchText}
          />

          {this.loadMoreButtonTemplate()}
        </CatalogContext.Provider>
      </div>
    );
  }
}

import StateLine from "@modules/components/catalog/StateLine";
import StateLineGroupProduct from "@modules/components/catalog/StateLineGroupProduct";
import ProductsList from "@modules/components/catalog/ProductsList";
import CatalogContext from "@modules/components/catalog/CatalogContext";
import LoadMore from "@modules/components/catalog/LoadMore";
import Storage from "@utils/localStorage/storage";
import $ from "jquery";
import React from "react";
import NoItems from "@modules/components/catalog/NoItems";

export default class Catalog extends React.Component {
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
      items: [],
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
  }

  componentDidMount() {
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

  setItems = (items) => {
    this.setState({ items });
  };

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
    if (this.state.loaded === true && this.state.items.length === 0) {
      return <NoItems />;
    }

    return (
      <div className="catalog">
        <CatalogContext.Provider
          value={{ ...this.state, setItems: this.setItems }}
        >
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

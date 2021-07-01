import Card from "@/components/product/card/catalog/Card";
import CatalogContext from "@/components/catalog/CatalogContext";
import classnames from "classnames";
import { CardSceletonBlock } from "../product/card/catalog/CardSceletonBlock";
import { CardSceletonLine } from "../product/card/catalog/CardSceletonLine";
import React from "react";

// сколько вывести скелетов, когда нет продуктов
const skeletonsNumber = 12;

export default class ProductsList extends Component {
  constructor(props) {
    super(props);

    this.state = {
      items: Array(skeletonsNumber).fill(1),
      nextPage: 1,
      sort: null,
      sortWasChanged: false,
    };

    this.loadData();
  }

  //получение следующей ссылки на каталог
  getNextPageUrl() {
    const { nextPage, sort } = this.state;
    let url = this.props.catalogUrl.split("?")[0];

    // if search page -- make url without api/
    if (
      document.location.href.search(document.location.host + "/search") !== -1
    ) {
      const searchParams = document.location.href.split("?")[1];
      url = `/search?${searchParams}&`;
    } else {
      url += "?";
    }

    //page
    url += `page=${nextPage}&`;
    //sort
    if (sort) {
      url += `sort=${sort}&`;
    }
    //catalog modifier (need for match response format)
    url += "isCatalogPage=1";

    //get filter parameters form filter form
    url += "&" + $("#filter_form").serialize();

    return url;
  }

  loadData() {
    this.props.onBeginLoading(this.state.nextPage);

    //end of pagination
    if (!this.props.catalogUrl) {
      return;
    }

    const nextPageUrl = this.getNextPageUrl();

    for (let i = 0; i < skeletonsNumber; i++) {
      this.state.items.push(1);
    }

    fetch(nextPageUrl, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((res) => res.json())
      .then(
        (res) => {
          for (let i = 0; i < skeletonsNumber; i++) {
            this.state.items.pop();
          }

          this.props.onEndLoading();

          if (this.state.nextPage === 1) {
            this.state.items = [];
          }

          //добавить новые продукты
          this.state.items.push(...res.items);

          let nextPageUrl = null;

          //обновить номер следующей страницы
          if (res.pager.currentPage < res.pager.pagesCount) {
            this.state.nextPage = res.pager.currentPage + 1;
            nextPageUrl = this.getNextPageUrl();
          }

          this.setState({
            items: this.state.items,
            isLoaded: true,
            nextPage: this.state.nextPage,
          });

          this.paginationPage += 1;

          this.context.onUpdateProductList(res.pager, nextPageUrl);
        },
        (error) => {
          this.setState({
            error: error.message,
          });
        }
      );
  }

  productItem(product, viewMode) {
    const classes = {
      product: [`catalog-product__${viewMode}`, `catalog-product_${viewMode}`],
    };

    return (
      <React.Fragment>
        <Card
          product={product}
          classes={classes}
          key={`product-card-${product.productid}`}
          searchText={this.props.searchText}
        />
      </React.Fragment>
    );
  }

  shouldComponentUpdate(nextProps, nextState) {
    if (nextProps.sortKey !== this.props.sortKey) {
      nextState.sort = nextProps.sortKey;
      nextState.nextPage = 1;
      nextState.items = Array(skeletonsNumber).fill(1);
      this.loadData();
    }

    return true;
  }

  render() {
    const viewMode = this.context.viewMode;

    const classes = [
      "product-items",
      `${viewMode}-view`,
      `product-items__${viewMode}`,
      {
        "padding-0": this.state.items.length === 0,
      },
    ];

    return (
      <div className={classnames(classes)}>
        {this.state.items.map((item) => {
          if (item === 1) {
            if (viewMode === "tile") {
              return <CardSceletonBlock />;
            } else {
              return <CardSceletonLine />;
            }
          }

          return this.productItem(item, viewMode);
        })}
      </div>
    );
  }
}

ProductsList.contextType = CatalogContext;

import Card from "@modules/components/product/card/catalog/Card";
import CatalogContext from "@modules/components/catalog/CatalogContext";
import classnames from "classnames";
import { CardSceletonBlock } from "@modules/components/product/card/catalog/CardSceletonBlock";
import { CardSceletonLine } from "@modules/components/product/card/catalog/CardSceletonLine";
import React from "react";
import Store from "@redux/stores/Store";
import {
  addProduct,
  deleteProduct,
} from "@redux/actions/account-actions/ListsActions";
import $ from "jquery";
import Styles from "@modules/components/catalog/ProductList.module.scss";

const skeletonsNumber = 12;

export default class ProductsList extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      items: Array(skeletonsNumber).fill(1),
      nextPage: 1,
      sort: null,
      sortWasChanged: false,
      lists: null,
    };

    Store.subscribe(() => {
      this.setState({
        ...this.state,
        lists: Store.getState().lists?.lists?.find((e, index) => index === 0),
      });
    });
  }

  //получение следующей ссылки на каталог
  getNextPageUrl() {
    const { nextPage } = this.state;
    const sort = this.props.sortKey;
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
      this.context.items.push(1);
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
            this.context.items.pop();
          }

          this.props.onEndLoading();

          if (this.state.nextPage === 1) {
            this.context.items = [];
          }

          //добавить новые продукты
          this.context.items.push(...res.items);

          let nextPageUrl = null;

          //обновить номер следующей страницы
          const nextPage = res.pager.currentPage + 1;
          const maxPage = Math.ceil(res.pager.total / res.pager.pageSize);

          if (nextPage <= maxPage) {
            this.state.nextPage = nextPage;
            nextPageUrl = this.getNextPageUrl();
          }

          this.context.setItems(this.context.items);

          this.setState({
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

  componentDidMount() {
    this.loadData();
  }

  productItem(product, viewMode, key, inList, onFlagClick) {
    const classes = {
      product: [`catalog-product__${viewMode}`, `catalog-product_${viewMode}`],
    };

    return (
      <React.Fragment key={key}>
        <Card
          inList={inList}
          product={product}
          classes={classes}
          key={`product-card-${product.productid}`}
          searchText={this.props.searchText}
          onFlagClick={onFlagClick}
        />
      </React.Fragment>
    );
  }

  componentDidUpdate(prevProps) {
    if (prevProps.sortKey !== this.props.sortKey) {
      this.state.nextPage = 1;
      this.context.items = Array(skeletonsNumber).fill(1);
      if (document !== undefined) {
        this.loadData();
      }
    }
  }

  onFlagClick(e, inList, productId) {
    e.stopPropagation();
    if (!inList) {
      Store.dispatch(
        addProduct(this.state.lists.product_list_id, productId, null, () => {})
      );
      return;
    }
    Store.dispatch(
      deleteProduct(this.state.lists.product_list_id, productId, () => {})
    );
  }

  render() {
    const viewMode = this.context.viewMode;

    const classes = [
      Styles.productItems,
      `${viewMode}-view`,
      `product-items__${viewMode}`,
      {
        "padding-0": this.context.items.length === 0,
      },
    ];

    return (
      <div className={classnames(classes)}>
        {this.context.items.map((item, i) => {
          if (item === 1) {
            if (viewMode === "tile") {
              return <CardSceletonBlock key={`skeleton-${i}`} />;
            } else {
              return <CardSceletonLine key={`skeleton-${i}`} />;
            }
          }

          return this.productItem(
            item,
            viewMode,
            `product-item-${i}`,
            (() => {
              const product = this.state.lists?.products?.find(
                (e) => e.product_id === item.productid
              );

              if (!product) {
                return false;
              }

              if (product?.typeAction) {
                return false;
              }
              return true;
            })(),
            (e, inList) => this.onFlagClick(e, inList, item.productid)
          );
        })}
      </div>
    );
  }
}

ProductsList.contextType = CatalogContext;

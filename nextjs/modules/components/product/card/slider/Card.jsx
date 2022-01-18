import React from "react";
import Product from "@modules/components/product/card/Product";
import Price from "@modules/components/product/card/components/Price";
import { Fragment } from "preact";
import ImgCatalog from "@modules/components/product/card/catalog/ImgCatalog";

export default class Card extends React.Component {
  constructor({ product, onFlagClick, inList }) {
    super();

    this.product = product;
    this.inList = inList;
    this.onFlagClick = onFlagClick;

    //list of jsx img elements
    this.imgList = [];

    for (let i = 0; product.images && i < product.images.length; i++) {
      this.imgList.push(<ImgCatalog image={product.images[i]} />);
    }
  }

  /**
   * main content of product cart as name, description, attributes etc.
   */
  productMainInfoBlock() {
    return (
      <a href={this.product.url} title={this.product.name}>
        <h4 className="products-slider-slide-title" itemProp="name">
          {this.product.name}
        </h4>
      </a>
    );
  }

  /**
   * all price related elements as prices, buy button discount etc.
   */
  productPriceBlock() {
    const { price, listPrice, currency } = this.product;

    return (
      <Fragment>
        {listPrice.number > price.number && (
          <span className="products-slider-old-price">
            <Price currency={currency} price={listPrice.number} />
          </span>
        )}

        <span className="products-slider-current-price">
          <Price currency={currency} price={price.number} />
        </span>
      </Fragment>
    );
  }

  render() {
    const classes = {
      product: [],
      image: {
        link: ["products-slider-image-link"],
        container: [
          "products-slider__image-container",
          "products-slider-image-container",
        ],
        noImage: ["products-slider-no-image"],
      },
    };

    return (
      <Product
        product={this.product}
        images={this.imgList}
        mainInfo={this.productMainInfoBlock()}
        price={this.productPriceBlock()}
        classes={classes}
        inList={this.inList}
        onFlagClick={this.onFlagClick}
      />
    );
  }
}

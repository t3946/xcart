import React from "react";
import classnames from "classnames";
import Image from "@modules/components/product/card/components/Image";
import CatalogContext from "@modules/components/catalog/CatalogContext";

/**
 * abstract component for product card in sliders and catalog
 */
export default class Product extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { product, images, mainInfo, price, classes, inList, onFlagClick } =
      this.props;
    this.product = product;
    this.images = images;
    this.mainInfo = mainInfo;
    this.price = price;
    this.classes = classes ?? {};

    const analytics_source = "";

    if (this.classes.image) {
      this.classes.image.container = [
        this.classes.image.container,
        "grid-catalog-product-image",
      ];
    }

    const imageProp = {
      images: this.images,
      mpn: product.mpn,
      upc: product.upc,
      url: product.url,
      name: product.name,
      isNew: product.isNew,
      isSale: product.isSale,
      classes: this.classes.image,
      inList: inList,
      onFlagClick: onFlagClick,
    };

    const productsSliderPriceContainer = [
      "grid-catalog-product-price",
      this.props.classes ? this.props.classes.priceContainer : null,
    ];

    const cardInfoClasses = [
      "container",
      "grid-catalog-product-info",
      "product-card-info",
    ];

    if (this.context) {
      cardInfoClasses.push(`product-card-info__${this.context.viewMode}`);
    }

    return (
      <div
        className={classnames({ out_of_stock: product.inStock }, [
          this.classes.product,
        ])}
        data-product={product.productid}
        data-name={product.name}
        data-source={analytics_source}
        data-brand={product.brand}
        data-prices={product.prices}
        data-list-price={product.listPrice.number}
        itemScope
        itemType="http://schema.org/Product"
        itemProp="itemListElement"
      >
        <Image {...imageProp} />

        <div className={classnames(cardInfoClasses)}>{this.mainInfo}</div>

        <div
          className={classnames(productsSliderPriceContainer)}
          itemProp="offers"
          itemScope
        >
          {this.price}
        </div>
      </div>
    );
  }
}

Product.contextType = CatalogContext;

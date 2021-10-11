import classnames from "classnames";
import { createRef } from "preact";
import CatalogContext from "@/components/catalog/CatalogContext";
import { ImageCard } from "./components/ImageCard";

/**
 * abstract component for product cart in sliders and catalog
 */
export default class Product extends Component {
  constructor(props) {
    super(props);

    this.root = createRef();
    this.price = createRef();
  }

  render({ product, images, mainInfo, price, classes }) {
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
        ref={this.root}
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
        <ImageCard {...imageProp} />

        <div className={classnames(cardInfoClasses)}>{this.mainInfo}</div>

        <div
          ref={this.price}
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

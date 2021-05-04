import classnames from "classnames";
import Image from "./components/Image";
import { createRef } from "preact";

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
      name: product.url,
      isNew: product.isNew,
      isSale: product.isSale,
      classes: this.classes.image,
    };

    const productsSliderPriceContainer = [
      "product-card-price__catalog",
      "grid-catalog-product-price",
      this.props.classes ? this.props.classes.priceContainer : null,
    ];

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
        <Image {...imageProp} />

        <div className="container grid-catalog-product-info product-card-info">
          {this.mainInfo}
        </div>

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

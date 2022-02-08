import * as React from "react";
import { Component } from "preact";
import map from "lodash/map";
import Price from "@modules/components/product/card/components/Price";
import classnames from "classnames";
import { useDispatch } from "react-redux";
import {
  delAction,
  setQuantityAction,
  setAction,
} from "@redux/actions/CartActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AxiosResponse } from "axios";
import { Form, Formik } from "formik";
import cn from "classnames";
import Styles from "@modules/old-components/MiniCart.module.scss";

//todo: нужно транслировать в функциональный компонент
class MiniCartItems_OLD extends Component {
  constructor(props, state) {
    super(props, state);
    this.changes = {};
    this.timers = {};
    this.RefProductList = null;
    this.store = props.store;
    this.state = this.store.getState();
    this.unsubscribe = props.store.subscribe(() => {
      this.setState(props.store.getState());
    });
  }

  componentWillUnmount() {
    this.unsubscribe();
  }

  handleRemove(e, key, item) {
    e.preventDefault();
    this.store.dispatch({
      type: "PUSH",
      action: "DEL",
      data: { items: [key] },
    });
  }

  /**
   * save new product quantity
   */
  handleInput(e, key, item) {
    const val = e.target.value;

    clearTimeout(this.timers.change);

    if (val && val > 0) {
      this.timers.change = setTimeout(() => {
        this.changes[key] = e.target.value;

        this.store.dispatch({
          type: "PUSH",
          action: "SET",
          data: { items: [{ id: item.id, quantity: e.target.value }] },

          callback: () => {
            this.changes[key] = null;
          },
        });
      }, 500);
    }
  }

  renderImage(item, props) {
    if (item.image) {
      return (
        <img
          data-src={item.image}
          alt={item.name}
          className="lazy lazy-img"
          itemprop="image"
        />
      );
    }

    return (
      <div className="not-avail">
        <span className="text">Image not available</span>
      </div>
    );
  }

  renderOptions(options) {
    if (options.length <= 0) {
      return;
    }

    return map(options, (oneOption) => {
      if (oneOption.type === "color") {
        const colorStyle = "background-color:" + oneOption.value + ";";
        return (
          <span className="product-option">
            <span className="product-option__title">{oneOption.title}:</span>
            <span className="product-option__color" style={colorStyle} />
            <span className="product-option__name">{oneOption.name}</span>
          </span>
        );
      }

      return (
        <span className="product-option">
          <span className="product-option__title">{oneOption.title}:</span>
          <span className="product-option__name">{oneOption.name}</span>
        </span>
      );
    });
  }

  renderProducts(props, state) {
    if (this.state.cart.items) {
      return map(this.state.cart.items, (item, key) => (
        <div className="item" key={key} data-product={item.id}>
          <div className="image">{this.renderImage(item, props)}</div>

          <div className="name-quantity">
            <div className="name">
              <a href={item.href}>
                <span className="name-text">{item.name}</span>
                <span className="name-options">
                  {this.renderOptions(item.options)}
                </span>
              </a>
            </div>

            <div className="quantity-extended">
              <div className="quantity">
                <input
                  type="number"
                  min="1"
                  max={item.avail}
                  value={this.changes[key] || item.quantity}
                  onInput={(e) => {
                    this.handleInput(e, key, item);
                  }}
                />
              </div>
              <div className="x">x</div>
              <div className="price">
                <Price currency={app.options.currency} price={item.price} />
              </div>
            </div>
          </div>

          <div className="actions">
            <a
              href="#"
              className="icon cart_remove"
              onClick={(e) => {
                this.handleRemove(e, key, item);
              }}
              title={"Remove"}
            ></a>
          </div>
        </div>
      ));
    }

    return;
  }

  render(props, state) {
    return (
      <div className={classnames("minicart-items", props.classes?.items)}>
        <div
          className="product-list"
          ref={(RefProductList) => {
            this.RefProductList = RefProductList;
          }}
        >
          {this.renderProducts(props, state)}
        </div>

        <div className="buttons">
          <a
            href={props.checkoutUrl}
            className="button yellow waves waves-orange"
          >
            {"Checkout"}
          </a>
        </div>
      </div>
    );
  }
}

interface IProps {
  checkoutUrl: string;
}

const MiniCartItems: React.FC<IProps> = function (props) {
  const dispatch = useDispatch();
  const { checkoutUrl } = props;
  const [changeTimer, setChangeTimer] = React.useState(null);
  const refProductList = React.useRef<HTMLDivElement>();
  const cart = useSelectorAccount((e) => e.cart);

  function renderImage(item: any) {
    if (item.image) {
      return <img src={item.image} alt={item.name} itemProp="image" />;
    }

    return (
      <div className="not-avail">
        <span className="text">Image not available</span>
      </div>
    );
  }

  function renderOptions(options: any) {
    if (options.length <= 0) {
      return;
    }

    return map(options, (oneOption) => {
      if (oneOption.type === "color") {
        const colorStyle = "background-color:" + oneOption.value + ";";
        return (
          <span className="product-option">
            <span className="product-option__title">{oneOption.title}:</span>
            <span className="product-option__color" style={colorStyle} />
            <span className="product-option__name">{oneOption.name}</span>
          </span>
        );
      }

      return (
        <span className="product-option">
          <span className="product-option__title">{oneOption.title}:</span>
          <span className="product-option__name">{oneOption.name}</span>
        </span>
      );
    });
  }

  function handleRemove(e: any, key: any) {
    dispatch(
      delAction({
        data: { items: [key] },
        success(res: AxiosResponse) {
          dispatch(setAction({ cart: res.data }));
        },
      })
    );
  }

  function productsTemplate() {
    if (!cart.items) {
      return null;
    }

    const items = [];

    for (const i in cart.items) {
      const item = cart.items[i];
      const initialValues = {
        quantity: item.quantity,
      };

      items.push(
        <Formik initialValues={initialValues}>
          {({ values, handleChange, setSubmitting, isSubmitting }) => {
            return (
              <Form>
                <div
                  className="item"
                  key={`mini-cart-item-${i}`}
                  data-product={item.id}
                >
                  <div className="image">{renderImage(item)}</div>

                  <div className="name-quantity">
                    <div className="name">
                      <a href={item.href}>
                        <span className="name-text">{item.name}</span>
                        <span className="name-options">
                          {renderOptions(item.options)}
                        </span>
                      </a>
                    </div>

                    <div className="quantity-extended">
                      <div className="quantity">
                        <input
                          name={"quantity"}
                          type="number"
                          min="1"
                          max={item.avail}
                          value={values.quantity}
                          onChange={handleChange}
                          onBlur={() => {
                            setSubmitting(true);
                            console.log(item.id, values.quantity);
                            dispatch(
                              setQuantityAction({
                                data: {
                                  id: item.id,
                                  quantity: values.quantity,
                                },
                                success(res: AxiosResponse) {
                                  setSubmitting(false);
                                  dispatch(setAction({ cart: res.data }));
                                },
                              })
                            );
                          }}
                          disabled={isSubmitting}
                          className={cn(Styles.quantity)}
                        />
                      </div>
                      <div className="x">x</div>
                      <div className="price">
                        <Price currency={cart.currency} price={item.price} />
                      </div>
                    </div>
                  </div>

                  <div className="actions">
                    <a
                      href="#"
                      className="icon cart_remove"
                      onClick={(e) => {
                        handleRemove(e, item.key);
                      }}
                      title={"Remove"}
                    />
                  </div>
                </div>
              </Form>
            );
          }}
        </Formik>
      );
    }

    return items;
  }

  return (
    <div className={classnames("minicart-items")}>
      <div className="product-list" ref={refProductList}>
        {productsTemplate()}
      </div>

      <div className="buttons">
        <a href={checkoutUrl} className="button yellow waves waves-orange">
          {"Checkout"}
        </a>
      </div>
    </div>
  );
};

export default MiniCartItems;

// export default MiniCartItems;

import * as React from "react";
import CountInput from "@modules/ui/CountInput";
import map from "lodash/map";
import Price from "@components/common/price/Price";
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
import Checkout from "@components/common/icons/view-mode/arrow-right/Checkout";
import Styles from "@components/layout/hat/mini-cart/MiniCartItems.module.scss";
import Button from "@modules/ui/forms/Button";

interface IProps {
  checkoutUrl: string;
}

const MiniCartItems: React.FC<IProps> = function (props) {
  const dispatch = useDispatch();
  const { checkoutUrl } = props;
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
      return null;
    }

    return map(options, (oneOption, i) => {
      if (oneOption.type === "color") {
        const colorStyle = "background-color:" + oneOption.value + ";";

        return (
          <span className="product-option" key={`option-${i}`}>
            <span className="product-option__title">{oneOption.title}:</span>
            <span className="product-option__color" style={colorStyle} />
            <span className="product-option__name">{oneOption.name}</span>
          </span>
        );
      }

      return (
        <span className="product-option" key={`option-${i}`}>
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
        <Formik initialValues={initialValues} key={`cart-item-${i}`}>
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
                        <CountInput
                          name={"quantity"}
                          minAmount={item.minAmount}
                          max={item.avail}
                          value={values.quantity}
                          onChange={(q) =>
                            handleChange({
                              target: { name: "quantity", value: q },
                            })
                          }
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
                        />
                      </div>
                      <div className="x">x</div>
                      <div className="price">
                        <Price className={"price-number"} price={item.price} />
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

      <div className="buttons d-flex align-items-center justify-content-center">
        <a href={"/checkout/shipping/"} className={"text-decoration-none"}>
          <Button type={"button"} className={"w-auto fs-6"}>
            Checkout <Checkout className={[Styles.checkoutIcon, "ms-2"]} />
          </Button>
        </a>
      </div>
    </div>
  );
};

export default MiniCartItems;

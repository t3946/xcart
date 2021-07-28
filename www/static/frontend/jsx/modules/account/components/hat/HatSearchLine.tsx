import React from "react";
import { Provider } from "react-redux";
import MiniCartItems from "../../../../components/MiniCart";
import MiniCartInfo from "../../../../modules/mini-cart/components/info";
import storeCart from "../../../../redux/stores/StoreCart";
import classNames from "classnames";
import { NavLink } from "react-router-dom";

const HatSearchLine = (props) => {
  function miniCartTemplate() {
    const labels = {
      lng_checkout: "Checkout",
      lng_remove: "Remove",
      lng_img: "Image not available",
    };

    return (
      <div className="minicart mini-cart-container">
        <Provider store={storeCart}>
          <MiniCartItems
            store={storeCart}
            labels={labels}
            checkoutUrl={appData.Cart.checkoutUrl}
          />
        </Provider>

        <MiniCartInfo
          quantity={appData.Cart.quantity}
          url={appData.routes["cart:list"]}
        />
      </div>
    );
  }

  function searchTemplate() {
    return (
      <div className="search-form-container">
        <form
          action={appData.routes["catalog:search"]}
          method="get"
          itemProp="potentialAction"
          itemScope
          itemType="http://schema.org/SearchAction"
        >
          <input
            type="text"
            name="q"
            className="search"
            placeholder={appData.config.cidev_header_code}
            value={appData.params.get.q}
            itemProp="query-input"
            data-suggestion-url={appData.routes["catalog:search:suggestion"]}
            autoComplete="off"
          />

          <meta
            itemProp="target"
            content={appData.routes["catalog:search"] + "?q={query}"}
          />

          <button className="button-search show-for-large" />

          <a
            className={classNames("button-clear", {
              active: appData.params.get.q,
            })}
          />
        </form>
      </div>
    );
  }

  function accountButton() {
    if (!appData.user) {
      return (
        <NavLink to="/account/login" exact={true} className="hat-login-button">
          log in
        </NavLink>
      );
    }

    return (
      <NavLink
        to="/account/dashboard"
        exact={true}
        className="hat-login-button"
      >
        {appData.user.name}
      </NavLink>
    );
  }

  return (
    <div className="sticky-menu-container">
      <div className="sticky def-zi2">
        <div
          id="search_container"
          className="desktop_menu_search_cart show-for-large"
          data-toggler="show-for-large"
        >
          <div className="container">
            <div className="row">
              <div className="col-lg-3 show-for-large">
                <div className="category-menu-container">
                  <div className="category-menu">
                    <span className="menu-icon"></span>
                    <span className="category-menu-title">Departments</span>
                  </div>
                </div>
                <div
                  dangerouslySetInnerHTML={{
                    __html: appData.templates.menuDesktop,
                  }}
                />
              </div>

              <div className="col-lg-5 col-sm-12">{searchTemplate()}</div>

              <div className="large-2 show-for-large hat-login-button-column">
                {accountButton()}
              </div>

              <div className="col-lg-2 show-for-large">
                {miniCartTemplate()}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
export default HatSearchLine;

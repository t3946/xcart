import React from "react";
import { Provider } from "react-redux";
import MiniCartItems from "@client/jsx/components/MiniCart";
import MiniCartInfo from "@client/jsx/modules/mini-cart/components/info";
import storeCart from "@client/jsx/redux/stores/StoreCart";
import classnames from "classnames";
import { NavLink } from "react-router-dom";
import { useSelector, useDispatch } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import DepartmentsMenu from "./DepartmentsMenu";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuDesktopActions";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import { route } from "@client/jsx/utils/AppData";

const HatSearchLine = (): any => {
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const isVisibleDepartmentsMenu = useSelector(
    (e: AccountStore) => e.departmentsMenuDesktop.isVisible
  );

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
          url={route("cart:list")}
        />
      </div>
    );
  }

  function searchTemplate() {
    return (
      <div className="search-form-container flex-grow-1">
        <form
          action={routes("catalog:search")}
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
            data-suggestion-url={route("catalog:search:suggestion")}
            autoComplete="off"
          />

          <meta
            itemProp="target"
            content={route("catalog:search") + "?q={query}"}
          />

          <button className="button-search show-for-large" />

          <a
            className={classnames("button-clear", {
              active: appData.params.get.q,
            })}
          />
        </form>
      </div>
    );
  }

  function accountButton() {
    if (!user) {
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
        {user.name}
      </NavLink>
    );
  }

  function toggleDepartmentsMenu() {
    isVisibleDepartmentsMenu ? closeDepartmentsMenu() : openDepartmentsMenu();
  }

  function openDepartmentsMenu() {
    HideAllMenu(dispatch);
    dispatch(setVisibleShadowPanelAction(true));
    dispatch(setDepartmentsMenuDesktopIsVisibleAction(true));
  }

  function closeDepartmentsMenu() {
    HideAllMenu(dispatch);
    dispatch(setVisibleShadowPanelAction(false));
    dispatch(setDepartmentsMenuDesktopIsVisibleAction(false));
  }

  return (
    <div className="sticky-menu-container">
      <div className="sticky def-zi2">
        <div
          id="search_container"
          className="desktop_menu_search_cart show-for-large position-relative"
          data-toggler="show-for-large"
        >
          <DepartmentsMenu
            className={"search-line_departments-menu"}
            isVisible={isVisibleDepartmentsMenu}
            closeMenu={closeDepartmentsMenu}
          />

          <div className="container">
            <div className="row">
              <div className="account-page-left-column account-page-left-column__departments-menu col pe-0">
                <div className="category-menu-container">
                  <div
                    className={classnames("category-menu category-menu__new", {
                      "is-active": isVisibleDepartmentsMenu,
                    })}
                    onClick={toggleDepartmentsMenu}
                  >
                    <span className="menu-icon" />
                    <span className="category-menu-title">Departments</span>
                  </div>
                </div>
              </div>

              <div className="col account-page-right-column d-flex">
                {searchTemplate()}

                <div className="show-for-large hat-login-button-column">
                  {accountButton()}
                </div>

                <div className="show-for-large">{miniCartTemplate()}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
export default HatSearchLine;

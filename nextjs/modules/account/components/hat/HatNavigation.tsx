import React from "react";
import LoginButton from "@modules/account/components/hat/LoginButton/LoginButton";
import TopLine from "@modules/account/components/hat/TopLine";
import { useDispatch, useSelector } from "react-redux";
import { setDepartmentsMenuMobileIsVisibleAction } from "@redux/actions/account-actions/DepartmentsMenuMobileActions";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import AppData from "@utils/AppData";
import Search from "@modules/icon/components/account/search/Search";
import Styles from "@modules/account/components/hat/Hat.module.scss";

const HatNavigation: React.FC = () => {
  const dispatch = useDispatch();
  const cart = useSelector((e: StoreInterface) => e.cart);

  const isVisibleMenu = useSelector(
    (e: StoreInterface) => e.departmentsMenuMobile.isVisible
  );

  function toggleMobileDepartmentsMenu(e: any) {
    e.stopPropagation();
    HideAllMenu(dispatch);
    dispatch(setDepartmentsMenuMobileIsVisibleAction(!isVisibleMenu));
    dispatch(setVisibleShadowPanelAction(!isVisibleMenu));
  }

  function mainMenuTemplate(): any {
    const items = [];
    const menu = AppData.mainMenu;

    for (let i = 0; i < menu.length; i++) {
      const item = menu[i];

      items.push(
        <li className="main-menu_item">
          <a href={item.url} className={"main-menu-link"}>
            {item.name}
          </a>
        </li>
      );
    }
    return (
      <ul className="list-unstyled m-0 d-flex justify-content-between flex-grow-1">
        {items}
      </ul>
    );
  }

  return (
    <div id="top-header-content">
      <div id="top-header-menu">
        <TopLine />

        <header
          id="top-header"
          itemScope
          itemType="https://schema.org/WPHeader"
        >
          <div
            dangerouslySetInnerHTML={{
              __html: AppData.templates.renderStaticNotifications,
            }}
          />

          <div className="logo_menu d-flex">
            <div className="container">
              <div className="row">
                <div className="col-md-1 col-auto d-flex align-items-center d-lg-none">
                  <a
                    href="#"
                    data-toggle="offCanvasLeft"
                    className="mobile_menu middle-inline-block hamburger"
                    onClick={toggleMobileDepartmentsMenu}
                  />
                </div>

                <div className="col-4 col-md-2 col-lg-3 d-flex align-items-center hat-logo-column">
                  <a href="/">
                    <img
                      src={`/static/frontend/dist/images/logos/sites/${AppData.site.code}/logo.svg`}
                      alt={AppData.config.companyName}
                      className="d-none d-lg-block hat-logo"
                    />

                    <img
                      src={`/static/frontend/dist/images/logos/sites/${AppData.site.code}/logo-small.svg`}
                      alt={AppData.config.companyName}
                      className="d-block d-lg-none hat-logo"
                    />
                  </a>
                </div>

                <div className="col-lg-9 d-lg-flex d-none justify-content-end">
                  <div className="main-menu-wrapper d-flex align-items-center justify-content-end">
                    {mainMenuTemplate()}
                  </div>
                </div>

                <div className="col-lg-3 col-md-9 col small-offset-0 medium-offset-0 mobile-header d-flex align-items-center justify-content-end d-lg-none">
                  <div className="hat-icons-container d-flex w-100 align-items-center justify-content-end hat-navigation_items-wrapper">
                    <div className="hat-navigation-item-wrapper">
                      <a
                        href="tel:18009292431"
                        className="mobile__call-btn middle-inline-block hat-navigation-item"
                      />
                    </div>

                    <a
                      className="d-flex align-items-center justify-content-center hat-navigation-item-wrapper"
                      data-swich="search_container"
                    >
                      <Search className={Styles.searchIcon} />
                    </a>

                    <div className="hat-navigation-item-wrapper">
                      <LoginButton />
                    </div>

                    <div className="hat-navigation-item-wrapper p-md-0 ms-md-20">
                      <a
                        href={AppData.routes["cart:list"]}
                        className="mobile__cart middle-inline-block hat-navigation-item"
                      >
                        <span className="count">
                          <span className="mc_count">{cart.quantity}</span>
                        </span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </header>
      </div>
    </div>
  );
};

export default HatNavigation;

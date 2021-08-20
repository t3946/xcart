import React from "react";
import HatLoginButton from "./HatLoginButton";
import TopLine from "@client/modules/account/components/hat/TopLine";
import { useDispatch } from "react-redux";
import { showAction as showMobileMenu } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuMobileActions";
import { showShadowPanelAction as showShadow } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";

const HatNavigation = (): any => {
  const dispatch = useDispatch();

  function openMobileMenu(e) {
    e.stopPropagation();
    dispatch(showMobileMenu());
    dispatch(showShadow());
  }

  return (
    <div id="top-header-content">
      <div id="top-header-menu">
        <TopLine />

        <header id="top-header" itemScope itemType="http://schema.org/WPHeader">
          <div
            dangerouslySetInnerHTML={{
              __html: appData.templates.renderStaticNotifications,
            }}
          />

          <div className="logo_menu">
            <div className="container">
              <div className="row">
                <div className="col-md-1 col-2 d-flex align-items-center d-lg-none">
                  <a
                    href="#"
                    data-toggle="offCanvasLeft"
                    className="mobile_menu middle-inline-block hamburger"
                    onClick={openMobileMenu}
                  />
                </div>

                <div className="col-4 col-md-3 d-flex align-items-center">
                  <a href="/">
                    <img
                      src={`/static/frontend/dist/images/logos/sites/${appData.site.code}/logo.svg`}
                      alt={appData.config.companyName}
                      className="show-for-large logo-big"
                    />
                    <img
                      src={`/static/frontend/dist/images/logos/sites/${appData.site.code}/logo-small.svg`}
                      alt={appData.config.companyName}
                      className="show-for-small hide-for-large logo-small"
                    />
                  </a>
                </div>

                <div className="col-lg-9 d-lg-flex d-none justify-content-end">
                  <div className="main-menu-wrap">
                    <ul
                      className="main-menu no-bullet show-for-medium"
                      dangerouslySetInnerHTML={{
                        __html: appData.templates.mainMenu,
                      }}
                    ></ul>
                  </div>
                </div>

                <div className="col-lg-3 col-md-8 col-6 small-offset-0 medium-offset-0 mobile-header d-flex align-items-center justify-content-end d-lg-none">
                  <div className="hat-icons-container d-flex w-100 align-items-center justify-content-end hat-navigation_items-wrapper">
                    <div className="hat-navigation-item-wrapper">
                      <a
                        href="tel:18009292431"
                        className="mobile__call-btn middle-inline-block hat-navigation-item"
                      />
                    </div>

                    <div className="hat-navigation-item-wrapper">
                      <a
                        className="mobile__search-btn middle-inline-block hat-navigation-item"
                        data-swich="search_container"
                      />
                    </div>

                    <div className="hat-navigation-item-wrapper">
                      <HatLoginButton />
                    </div>

                    <div className="hat-navigation-item-wrapper">
                      <a
                        href={appData.routes["cart:list"]}
                        className="mobile__cart middle-inline-block hat-navigation-item"
                      >
                        <span className="count">
                          <span className="mc_count">
                            {appData.Cart.quantity}
                          </span>
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
      <div className="shadow" />
    </div>
  );
};

export default HatNavigation;

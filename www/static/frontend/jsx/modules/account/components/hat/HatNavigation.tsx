import React from "react";
import classNames from "classnames";

const HatNavigation = (props) => {
  function loginButton() {
    const classes = ["navigation-login-button common-icon"];

    if (appData.user) {
      classes.push("navigation-login-button__logged");
    } else {
      classes.push("navigation-login-button__not-logged");
    }

    return <i className={classNames(classes)} />;
  }

  return (
    <div id="top-header-content">
      <div id="top-header-menu">
        <header id="top-header" itemScope itemType="http://schema.org/WPHeader">
          <div
            dangerouslySetInnerHTML={{
              __html: appData.templates.renderStaticNotifications,
            }}
          />

          <div className="logo_menu">
            <div className="container">
              <div className="row align-justify">
                <div className="col-2 col-md-1 show-for-small hide-for-large">
                  <a
                    href="#"
                    data-toggle="offCanvasLeft"
                    className="mobile_menu middle-inline-block hamburger"
                  />
                </div>

                <div className="col-3 col-md-2">
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

                <div className="col-1 col-md-5 show-for-large">
                  <div className="main-menu-wrap">
                    <ul
                      className="main-menu no-bullet show-for-medium"
                      dangerouslySetInnerHTML={{
                        __html: appData.templates.mainMenu,
                      }}
                    ></ul>
                  </div>
                </div>

                <div className="col-6 col-md-4 hide-for-large small-offset-0 medium-offset-0 mobile-header d-flex align-items-center justify-content-end">
                  <div className="hat-icons-container d-flex justify-content-between w-100">
                    <a
                      href="tel:18009292431"
                      className="mobile__call-btn middle-inline-block right-icon"
                    ></a>

                    <a
                      className="mobile__search-btn middle-inline-block right-icon"
                      data-swich="search_container"
                    />

                    {loginButton()}

                    <a
                      href={appData.routes["cart:list"]}
                      className="mobile__cart middle-inline-block right-icon"
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
        </header>
      </div>
      <div className="shadow"></div>
    </div>
  );
};

export default HatNavigation;

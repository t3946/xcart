import React from "react";
import LoginButton from "@modules/account/components/hat/LoginButton/LoginButton";
import TopLine from "@modules/account/components/hat/TopLine";
import { useDispatch } from "react-redux";
import { setDepartmentsMenuMobileIsVisibleAction } from "@redux/actions/account-actions/DepartmentsMenuMobileActions";
import { toggleSearchIsVisibleAction } from "@redux/actions/account-actions/MobileSearchActions";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import AppData from "@utils/AppData";
import Search from "@modules/icon/components/account/search/Search";
import cn from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import TimesIcon from "@modules/icon/components/font-awesome/times/Light";
import MenuIcon from "@modules/icon/components/header/Menu";
import IconCart from "@modules/icon/components/common/cart/Cart";
import Styles from "@modules/account/components/hat/HatNavigation.module.scss";

const HatNavigation: React.FC = () => {
  const dispatch = useDispatch();
  const isVisibleShadowPanel = useSelectorAccount(
    (e: Record<any, any>) => e.shadowPanel.isVisible
  );
  const cart = useSelectorAccount((e) => e.cart);
  const isVisibleMenu = useSelectorAccount(
    (e) => e.departmentsMenuMobile.isVisible
  );

  const classes = {
    navigationContainer: ["d-flex", Styles.navigationContainer],
  };

  function toggleMobileDepartmentsMenu(e: any) {
    e.stopPropagation();
    HideAllMenu(dispatch);
    dispatch(setDepartmentsMenuMobileIsVisibleAction(!isVisibleMenu));
    dispatch(setVisibleShadowPanelAction(!isVisibleMenu));
  }

  function mainMenuTemplate(): any {
    const items = [];
    const menu = AppData.mainMenu;
    const classes = {
      menu: [
        "m-0",
        "d-flex",
        "justify-content-between",
        "flex-grow-1",
        "list-unstyled",
      ],
    };

    for (let i = 0; i < menu.length; i++) {
      const item = menu[i];

      items.push(
        <li className="main-menu_item" key={i}>
          <a href={item.url} className={"main-menu-link"}>
            {item.name}
          </a>
        </li>
      );
    }

    return <ul className={cn(classes.menu)}>{items}</ul>;
  }

  return (
    <div className={Styles.topHeaderContent}>
      <div
        className={cn(Styles.topHeaderMenu, {
          [Styles.topHeaderMenu_boxShadow_none]: isVisibleShadowPanel,
        })}
      >
        <TopLine />
        <header
          className={cn(Styles.topHeader, {
            [Styles.header_shadowPanelVisible]: isVisibleShadowPanel,
          })}
          itemScope
          itemType="https://schema.org/WPHeader"
        >
          <div
            dangerouslySetInnerHTML={{
              __html: AppData.templates.renderStaticNotifications,
            }}
          />

          <div className={cn(classes.navigationContainer)}>
            <div className="container-lg">
              <div className="row">
                <div className="col-md-1 col-auto d-flex align-items-center d-lg-none">
                  <a
                    href="#"
                    data-toggle="offCanvasLeft"
                    className="d-flex"
                    onClick={toggleMobileDepartmentsMenu}
                  >
                    {isVisibleMenu ? (
                      <TimesIcon className={Styles.menuIcon} />
                    ) : (
                      <MenuIcon className={Styles.menuIcon} />
                    )}
                  </a>
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
                      onClick={() => {
                        dispatch(toggleSearchIsVisibleAction());
                      }}
                    >
                      <Search className={Styles.searchIcon} />
                    </a>

                    <div className="hat-navigation-item-wrapper">
                      <LoginButton />
                    </div>

                    <div className="hat-navigation-item-wrapper p-md-0 ms-md-20">
                      <a
                        href={"/cart"}
                        className={cn(
                          Styles.h100,
                          "d-flex align-items-center justify-content-center w-auto"
                        )}
                      >
                        <div className={Styles.cart}>
                          <IconCart />
                          <span
                            className={cn(
                              "d-flex",
                              "align-items-center",
                              "justify-content-center",
                              Styles.cartCount
                            )}
                          >
                            <span className={Styles.mcCount}>
                              {cart.quantity}
                            </span>
                          </span>
                        </div>
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

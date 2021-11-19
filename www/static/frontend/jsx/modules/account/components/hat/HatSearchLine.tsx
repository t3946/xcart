import React from "react";
import classnames from "classnames";
import { useSelector, useDispatch } from "react-redux";
import DepartmentsMenu from "./DepartmentsMenu";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuDesktopActions";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import SearchSuggestion from "@client/jsx/components/SearchSuggestion";
import MiniCart from "@client/jsx/modules/mini-cart/components/MiniCart";
import HoverIntent from "react-hoverintent";
import LoginButtonDesktop from "@client/jsx/modules/account/components/hat/LoginButton/LoginButtonDesktop";
import AppData, { route } from "@client/jsx/utils/AppData";

interface IProps {
  isStatic?: boolean;
}

const HatSearchLine: React.FC<IProps> = (
  props: IProps
): any => {
  const isStatic = props.isStatic || false;
  const dispatch = useDispatch();
  const isVisibleDepartmentsMenu = useSelector(
    (e: StoreInterface) => e.departmentsMenuDesktop.isVisible
  );
  const [departmentsMenuButtonHover, setDepartmentsMenuButtonHover] =
    React.useState(false);

  function searchTemplate() {
    return (
      <div className="search-form-container flex-grow-1">
        <form
          action={route("catalog:search")}
          method="get"
          itemProp="potentialAction"
          itemScope
          itemType="https://schema.org/SearchAction"
        >
          <div className={"pos-relative"}>
            <input
              type="text"
              name="q"
              className="input-search"
              placeholder={AppData.config.cidev_header_code}
              value={AppData.params.get.q}
              itemProp="query-input"
              data-suggestion-url={route("catalog:search:suggestion")}
              autoComplete="off"
            />

            <meta
              itemProp="target"
              content={route("catalog:search") + "?q={query}"}
            />

            <a
              className={classnames("button-clear", {
                active: AppData.params.get.q,
              })}
            />
          </div>
          <button className="button-search show-for-large" />
        </form>
      </div>
    );
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
    setDepartmentsMenuButtonHover(false);
  }

  React.useEffect(() => {
    new SearchSuggestion(".input-search", {
      container: "search-form-container_suggestion",
    });
  });

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
            buttonHover={departmentsMenuButtonHover}
            closeMenu={closeDepartmentsMenu}
          />

          <div className="container">
            <div className="row">
              <HoverIntent
                onMouseOver={() => {
                  setDepartmentsMenuButtonHover(true);
                  openDepartmentsMenu();
                }}
                onMouseOut={() => {
                  setDepartmentsMenuButtonHover(false);
                }}
                sensitivity={10}
                interval={250}
                timeout={250}
              >
                <div className="account-page-left-column col pe-0 d-none d-lg-block">
                  <div className="category-menu-container">
                    <div
                      className={classnames(
                        "category-menu category-menu__new",
                        {
                          "is-active": isVisibleDepartmentsMenu,
                        }
                      )}
                    >
                      <span className="menu-icon" />
                      <span className="category-menu-title">Departments</span>
                    </div>
                  </div>
                </div>
              </HoverIntent>

              <div className="col account-page-right-column d-flex align-items-center mt-2 mt-lg-0">
                {searchTemplate()}

                <div className={"d-none d-lg-flex search-line_buttons"}>
                  <LoginButtonDesktop isStatic={isStatic} />

                  <div className="ms-12">
                    <MiniCart />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default HatSearchLine;

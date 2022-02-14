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
import Search from "@client/jsx/modules/account/components/hat/Search";
import Triangle from "@client/jsx/modules/icon/components/common/triangle/Triangle";

import Styles from "@client/jsx/modules/account/components/hat/HatSearchLine.module.scss";

interface IProps {
  isStatic?: boolean;
}

const HatSearchLine: React.FC<IProps> = (props: IProps): any => {
  const isStatic = props.isStatic || false;
  const dispatch = useDispatch();
  const isVisibleDepartmentsMenu = useSelector(
    (e: StoreInterface) => e.departmentsMenuDesktop.isVisible
  );
  const [departmentsMenuButtonHover, setDepartmentsMenuButtonHover] =
    React.useState(false);

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

  return (
    <div
      id="search_container"
      className="desktop_menu_search_cart"
      data-toggler="show-for-large"
    >
      <DepartmentsMenu
        className={"search-line_departments-menu"}
        isVisible={isVisibleDepartmentsMenu}
        buttonHover={departmentsMenuButtonHover}
        closeMenu={closeDepartmentsMenu}
      />

      <div className="container-lg">
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
              <div className={classnames([Styles.categoryMenuContainer])}>
                <div
                  className={classnames(
                    Styles.categoryMenu,
                    "category-menu__new d-flex align-items-center justify-content-center",
                    {
                      [Styles.categoryMenu_active]: isVisibleDepartmentsMenu,
                    }
                  )}
                >
                  <span className={Styles.categoryMenuTitle}>Departments</span>

                  <span className={classnames("ms-2", Styles.triangleIcon)}>
                    <Triangle />
                  </span>
                </div>
              </div>
            </div>
          </HoverIntent>

          <div className="col account-page-right-column d-flex align-items-center mt-2 mt-lg-0">
            <Search />

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
  );
};

export default HatSearchLine;

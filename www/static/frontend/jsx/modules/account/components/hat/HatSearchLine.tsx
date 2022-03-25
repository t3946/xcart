import React from "react";
import cn from "classnames";
import { useSelector, useDispatch } from "react-redux";
import DepartmentsMenu from "./DepartmentsMenu";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuDesktopActions";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import MiniCart from "@client/jsx/modules/mini-cart/components/MiniCart";
import HoverIntent from "react-hoverintent";
import LoginButtonDesktop from "@client/jsx/modules/account/components/hat/LoginButton/LoginButtonDesktop";
import Search from "@client/jsx/modules/account/components/hat/Search";
import Triangle from "@client/jsx/modules/icon/components/common/triangle/Triangle";

import Styles from "@client/jsx/modules/account/components/hat/HatSearchLine.module.scss";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";

interface IProps {
  isStatic?: boolean;
}

const HatSearchLine: React.FC<IProps> = (props: IProps): any => {
  const isStatic = props.isStatic || false;
  const dispatch = useDispatch();
  const isVisibleDepartmentsMenu = useSelector(
    (e: StoreInterface) => e.departmentsMenuDesktop.isVisible
  );
  const account_enabled = useSelector(
    (e: StoreInterface) => e.site.account_enabled,
  );
  const [departmentsMenuButtonHover, setDepartmentsMenuButtonHover] =
    React.useState(false);
  const site = useSelectorAccount((e) => e.site);

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

  console.log({site})

  const classes = {
    container: ["desktop_menu_search_cart", {"skeleton-box": site.account_enabled === undefined}],
  }

  return (
    <div
      id="search_container"
      className={cn(classes.container)}
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
              <div className={cn([Styles.categoryMenuContainer])}>
                <div
                  className={cn(
                    Styles.categoryMenu,
                    "category-menu__new d-flex align-items-center justify-content-center",
                    {
                      [Styles.categoryMenu_active]: isVisibleDepartmentsMenu,
                    }
                  )}
                >
                  <span className={Styles.categoryMenuTitle}>Departments</span>

                  <span className={cn("ms-2", Styles.triangleIcon)}>
                    <Triangle />
                  </span>
                </div>
              </div>
            </div>
          </HoverIntent>

          <div className="col account-page-right-column d-flex align-items-center mt-2 mt-lg-0">
            <Search />

            <div
              className={cn(
                [
                  "d-none",
                  "search-line_buttons",
                  Styles.rightButtonContainer,
                  "d-lg-flex",
                ]
              )}
            >
              {account_enabled && <LoginButtonDesktop isStatic={isStatic}/>}

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

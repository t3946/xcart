import React from "react";
import cn from "classnames";
import { useDispatch } from "react-redux";
import DepartmentsMenu from "./DepartmentsMenu";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@redux/actions/account-actions/DepartmentsMenuDesktopActions";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import MiniCart from "@modules/mini-cart/components/MiniCart";
import HoverIntent from "react-hoverintent";
import LoginButtonDesktop from "@modules/account/components/hat/LoginButton/LoginButtonDesktop";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import LeftColumn from "@modules/layout/components/LeftColumn";
import Triangle from "@modules/icon/components/common/triangle/Triangle";
import StylesRotate from "@styles/modules/Rotate.module.scss";
import Search from "./Search";
import Styles from "@modules/account/components/hat/HatSearchLine.module.scss";

interface IProps {
  className: any;
}

const HatSearchLine: React.FC<IProps> = (props: IProps): any => {
  const dispatch = useDispatch();
  const isVisibleDepartmentsMenu = useSelectorAccount(
    (e) => e.departmentsMenuDesktop.isVisible
  );
  const [departmentsMenuButtonHover, setDepartmentsMenuButtonHover] =
    React.useState(false);
  const inputSuggestionsClass = "input-search";
  const containerSuggestionsClass = Styles.searchFormContainer__suggestion;
  const classes = {
    container: [
      Styles.mainContainer,
      containerSuggestionsClass,
      props.className,
    ],
    buttonSearch: [
      Styles.buttonSearch,
      Styles.searchForm__buttonSearch,
      "align-items-center",
      "justify-content-center",
    ],
    inputSearch: ["rounded-0", inputSuggestionsClass, Styles.inputSearch],
    menuArrowIcon: [
      "ms-2",
      Styles.triangleIcon,
      {
        [StylesRotate.rotate__180]: isVisibleDepartmentsMenu,
      },
    ],
  };

  function openDepartmentsMenu() {
    HideAllMenu(dispatch);
    dispatch(setVisibleShadowPanelAction(true));
    dispatch(setDepartmentsMenuDesktopIsVisibleAction(true));
  }

  function closeDepartmentsMenu() {
    HideAllMenu(dispatch);
  }

  return (
    <div className={cn(classes.container)} id="search_container">
      <DepartmentsMenu
        className={"search-line_departments-menu"}
        isVisible={isVisibleDepartmentsMenu}
        buttonHover={departmentsMenuButtonHover}
        closeMenu={closeDepartmentsMenu}
      />

      <div className="container-lg">
        <div className="row">
          {/*departments menu*/}
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
            <LeftColumn className={"col pe-0 d-none d-lg-block"}>
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

                  <span className={cn(classes.menuArrowIcon)}>
                    <Triangle />
                  </span>
                </div>
              </div>
            </LeftColumn>
          </HoverIntent>

          {/*product search*/}
          <div className="col account-page-right-column d-flex align-items-center mt-2 mt-lg-0">
            <Search />
            <div className={"d-none d-lg-flex search-line_buttons"}>
              <LoginButtonDesktop />
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

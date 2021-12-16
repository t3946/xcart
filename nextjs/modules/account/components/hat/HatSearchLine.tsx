import React from "react";
import cn from "classnames";
import { useDispatch } from "react-redux";
import DepartmentsMenu from "./DepartmentsMenu";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@redux/actions/account-actions/DepartmentsMenuDesktopActions";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import SearchSuggestion from "@modules/old-components/SearchSuggestion";
import MiniCart from "@modules/mini-cart/components/MiniCart";
import HoverIntent from "react-hoverintent";
import LoginButtonDesktop from "@modules/account/components/hat/LoginButton/LoginButtonDesktop";
import AppData from "@utils/AppData";
import Styles from "@modules/account/components/hat/HatSearchLine.module.scss";
import Magnifier from "@modules/icon/components/common/magnifier/Light";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import LeftColumn from "@modules/layout/components/LeftColumn";
import Triangle from "@modules/icon/components/common/triangle/Triangle";
import StylesRotate from "@styles/modules/Rotate.module.scss";

const HatSearchLine: React.FC = (): any => {
  const dispatch = useDispatch();
  // const isVisibleDepartmentsMenu = useSelectorAccount(
  //   (e) => e.departmentsMenuDesktop.isVisible
  // );
  const isVisibleDepartmentsMenu = true;
  const routes = useSelectorAccount((e) => e.routes);
  const [departmentsMenuButtonHover, setDepartmentsMenuButtonHover] =
    React.useState(false);
  const inputSuggestionsClass = "input-search";
  const containerSuggestionsClass = "search-form-container_suggestion";
  const classes = {
    container: [
      "d-none",
      "d-lg-block",
      "p-0",
      Styles.mainContainer,
      containerSuggestionsClass,
    ],
    buttonSearch: [
      Styles.buttonSearch,
      Styles.searchForm__buttonSearch,
      "d-flex",
      "align-items-center",
      "justify-content-center",
    ],
    inputSearch: [inputSuggestionsClass, Styles.inputSearch],
    menuArrowIcon: [
      "ms-2",
      Styles.triangleIcon,
      {
        [StylesRotate.rotate__180]: isVisibleDepartmentsMenu,
      },
    ],
  };

  function searchTemplate() {
    return (
      <div className="search-form-container flex-grow-1">
        <form
          action={routes["catalog:search"]}
          method="get"
          itemProp="potentialAction"
          itemScope
          itemType="https://schema.org/SearchAction"
          className={Styles.searchForm}
        >
          <div className={"pos-relative"}>
            <input
              type="text"
              name="q"
              className={cn(classes.inputSearch)}
              placeholder={AppData.config.cidev_header_code}
              value={AppData.params.get.q}
              itemProp="query-input"
              data-suggestion-url={routes["catalog:search:suggestion"]}
              autoComplete="off"
            />

            <meta
              itemProp="target"
              content={routes["catalog:search"] + "?q={query}"}
            />

            <a
              className={cn("button-clear", {
                active: AppData.params.get.q,
              })}
            />
          </div>

          <button className={cn(classes.buttonSearch)}>
            <Magnifier />
          </button>
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

  const [suggestionsWasInit, setSuggestionsWasInit] = React.useState(false);

  React.useEffect(() => {
    if (!suggestionsWasInit) {
      new SearchSuggestion(`.${inputSuggestionsClass}`, {
        container: containerSuggestionsClass,
      });

      setSuggestionsWasInit(true);
    }
  });

  return (
    <div className={cn(classes.container)} id="search_container">
      <DepartmentsMenu
        className={"search-line_departments-menu"}
        isVisible={isVisibleDepartmentsMenu}
        buttonHover={departmentsMenuButtonHover}
        closeMenu={closeDepartmentsMenu}
      />

      <div className="container">
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
            {searchTemplate()}

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

import {
  setMobileMenuIsVisible,
  setTabletMenuIsVisible,
} from "@client/jsx/redux/actions/account-actions/MenuActions";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import { setDepartmentsMenuMobileIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuMobileActions";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuDesktopActions";
import { setIsVisibleAction } from "@client/jsx/redux/actions/account-actions/MobileMenuActions";
import { setMiniCartIsVisibleAction } from "@client/jsx/redux/actions/MiniCartActions";
import { setSearchIsVisibleAction } from "@client/jsx/redux/actions/MobileSearchActions";
import { setSuggestionsAction } from "@client/jsx/redux/actions/SuggestionActions";

const hideAllMenu = function (dispatch: (any) => void): void {
  dispatch(setMobileMenuIsVisible(false));
  dispatch(setTabletMenuIsVisible(false));
  dispatch(setDepartmentsMenuMobileIsVisibleAction(false));
  dispatch(setDepartmentsMenuDesktopIsVisibleAction(false));
  dispatch(setVisibleShadowPanelAction(false));
  dispatch(setVisibleShadowPanelAction(false));
  dispatch(setIsVisibleAction(false));
  dispatch(setMiniCartIsVisibleAction(false));
  dispatch(setSearchIsVisibleAction(false));
  dispatch(setSuggestionsAction(null));
};

export default hideAllMenu;

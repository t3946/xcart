import {
  setMobileMenuIsVisible,
  setTabletMenuIsVisible,
} from "@client/jsx/redux/actions/account-actions/MenuActions";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import { setDepartmentsMenuMobileIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuMobileActions";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuDesktopActions";
import { setIsVisibleAction } from "@client/jsx/redux/actions/account-actions/MobileMenuActions";

const hideAllMenu = function (dispatch: (any) => void): void {
  dispatch(setMobileMenuIsVisible(false));
  dispatch(setTabletMenuIsVisible(false));
  dispatch(setDepartmentsMenuMobileIsVisibleAction(false));
  dispatch(setDepartmentsMenuDesktopIsVisibleAction(false));
  dispatch(setVisibleShadowPanelAction(false));
  dispatch(setVisibleShadowPanelAction(false));
  dispatch(setIsVisibleAction(false));
};

export default hideAllMenu;

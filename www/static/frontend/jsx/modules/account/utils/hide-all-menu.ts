import {
  setMobileMenuIsVisible,
  setTabletMenuIsVisible,
} from "@client/jsx/redux/actions/account-actions/MenuActions";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import { setDepartmentsMenuMobileIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuMobileActions";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuDesktopActions";

const hideAllMenu = function (dispatch: (any) => void): void {
  dispatch(setMobileMenuIsVisible(false));
  dispatch(setTabletMenuIsVisible(false));
  dispatch(setDepartmentsMenuMobileIsVisibleAction(false));
  dispatch(setDepartmentsMenuDesktopIsVisibleAction(false));
  dispatch(setVisibleShadowPanelAction(false));
};

export default hideAllMenu;

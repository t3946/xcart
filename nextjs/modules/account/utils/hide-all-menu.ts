import {
  setMobileMenuIsVisible,
  setTabletMenuIsVisible,
} from "@redux/actions/account-actions/MenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import { setDepartmentsMenuMobileIsVisibleAction } from "@redux/actions/account-actions/DepartmentsMenuMobileActions";
import { setDepartmentsMenuDesktopIsVisibleAction } from "@redux/actions/account-actions/DepartmentsMenuDesktopActions";
import { setIsVisibleAction } from "@redux/actions/account-actions/MobileMenuActions";
import { setMiniCartIsVisibleAction } from "@redux/actions/MiniCartActions";

const hideAllMenu = function (dispatch: (payload: any) => void): void {
  dispatch(setMobileMenuIsVisible(false));
  dispatch(setTabletMenuIsVisible(false));
  dispatch(setDepartmentsMenuMobileIsVisibleAction(false));
  dispatch(setDepartmentsMenuDesktopIsVisibleAction(false));
  dispatch(setVisibleShadowPanelAction(false));
  dispatch(setIsVisibleAction(false));
  dispatch(setMiniCartIsVisibleAction(false));
};

export default hideAllMenu;

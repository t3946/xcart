export const setMobileMenuIsVisible = (isMobileMenuVisible: boolean): any => ({
  type: "SET_MOBILE_MENU_VISIBLE",
  isMobileMenuVisible,
});

export const setTabletMenuIsVisible = (isTabletMenuVisible: boolean): any => ({
  type: "SET_TABLET_MENU_VISIBLE",
  isTabletMenuVisible,
});

export const hideAllMenu = (): any => ({
  type: "HIDE_ALL_MENU",
});

import { SideBarMenuStore } from "@modules/account/ts/types/store.type";

export const setMenuItemsAction = (payload: SideBarMenuStore): any => ({
  type: "SET_MENU_ITEMS",
  payload,
});

export const setMenuItemActiveAction = (to: string, active: boolean): any => ({
  type: "SET_MENU_ITEM_ACTIVE",
  payload: { to, active },
});

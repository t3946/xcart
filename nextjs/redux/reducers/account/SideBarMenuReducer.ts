import { AnyAction } from "redux";
import { SideBarMenuStore } from "@modules/account/ts/types/store.type";
import { accountAddressesInitialValue } from "@modules/account/ts/consts/store-initial-value";

const accountSideBarMenuReducer = (
  state: SideBarMenuStore = { menuItems: [] },
  action: AnyAction
): SideBarMenuStore => {
  switch (action.type) {
    case "SET_MENU_ITEMS":
      for (const payloadItem of action.payload.menuItems) {
        const item = state.menuItems.find(
          (storeItem) => storeItem.to === payloadItem.to
        );
        if (!item) {
          state.menuItems.push(payloadItem);
        }
      }
      return { ...state };
    case "SET_MENU_ITEM_ACTIVE":
      const item = state.menuItems.find(
        (item) => item.to === action.payload.to
      );
      if (item) {
        item.active = action.payload.active;
      }
      return { ...state };
    default:
      return state;
  }
};
export default accountSideBarMenuReducer;

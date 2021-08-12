import { AnyAction } from "redux";
import { shadowPanelInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";

const ShadowPanelReducer = (
  state: Record<any, any> = shadowPanelInitialValue,
  action: AnyAction
): any => {
  switch (action.type) {
    case "SHOW_SHADOW":
      return { isVisible: true, zIndex: action.zIndex || "initial" };
    case "HIDE_SHADOW":
      return { isVisible: false, zIndex: "initial" };
    default:
      return state;
  }
};

export default ShadowPanelReducer;

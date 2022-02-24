import { AnyAction } from "redux";
import { shadowPanelInitialValue } from "@modules/account/ts/consts/store-initial-value";

const ShadowPanelReducer = (
  state: Record<any, any> = {
    isVisible: false,
  },
  action: AnyAction
): any => {
  switch (action.type) {
    case "SHOW_SHADOW":
      state.isVisible = true;
      state.zIndex = action.zIndex || "initial";
      return { ...state };

    case "HIDE_SHADOW":
      state.isVisible = false;
      state.zIndex = "initial";
      return { ...state };

    case "SET_VISIBLE_SHADOW":
      state.isVisible = action.isVisible;

      if (!state.isVisible) {
        for (const subscriber in state.subscribers) {
          state.subscribers[subscriber] = false;
        }
      }

      return { ...state };

    case "SUBSCRIBE":
      state.subscribers[action.subscriber] = false;
      return { ...state };

    case "SUBSCRIBER_UPDATE":
      state.subscribers[action.subscriber] = action.isVisible;
      return { ...state };

    default:
      return { ...state };
  }
};

export default ShadowPanelReducer;

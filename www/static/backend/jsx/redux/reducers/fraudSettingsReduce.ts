import { ResponseFraudAllSettings } from "@admin/modules/general-settings/ts/types/fraud-check/response";
import { AnyAction } from "redux";
import { initStateFraudSettings } from "@admin/modules/general-settings/ts/consts/fraud-check/default-state";

const fraudSettingsReducer = (
  state: ResponseFraudAllSettings = initStateFraudSettings,
  action: AnyAction
) => {
  switch (action.type) {
    case "SET_FRAUD_SETTINGS":
      return { ...state, ...action.data };
    // case "SET_SETTINGS_FORM":
    //   console.log("TESTICK");
    //   return {
    //     ...state,
    //     settings: {
    //       ...state.settings,
    //       data: action.form,
    //     },
    //   };
    default:
      return state;
  }
};
export default fraudSettingsReducer;

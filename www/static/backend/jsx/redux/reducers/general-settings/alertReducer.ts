import { AnyAction } from "redux";
import { GeneralSettingsAlert } from "@admin/modules/general-settings/ts/types/general-settings/generalSettings.type";
import { initialSettingsAlert } from "@admin/modules/general-settings/ts/consts/generalSettings";

const alertReducer = (
  state: GeneralSettingsAlert = initialSettingsAlert,
  action: AnyAction
): GeneralSettingsAlert => {
  switch (action.type) {
    case "SET_ERROR_ALERT":
      return {
        ...state,
        state: true,
        message: action.message,
        status: "error",
      };
    case "SET_SUCCESS_ALERT":
      return {
        ...state,
        state: true,
        message: action.message,
        status: "success",
      };
    default:
      return state;
  }
};
export default alertReducer;

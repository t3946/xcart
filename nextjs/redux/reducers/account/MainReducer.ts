import { AnyAction } from "redux";
import { AccountMainStore } from "@modules/account/ts/types/store.type";

const initialValue = {
  countries: [],
  states: [],
  isList: false,
};

const accountMainReducer = (
  state: AccountMainStore = initialValue,
  action: AnyAction
): AccountMainStore => {
  switch (action.type) {
    case "SET_TERRITORY":
      return { ...state, states: action.states, countries: action.countries };

    case "SET_BREAKPOINT":
      state.breakpoint = { ...action.breakpoint };

      return { ...state };

    case "SET_IS_LIST":
      return {
        ...state,
        isList: action.isList,
      };

    default:
      return state;
  }
};
export default accountMainReducer;

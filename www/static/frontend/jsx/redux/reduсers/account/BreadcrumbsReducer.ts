import { AnyAction } from "redux";

const BreadcrumbsReducer = (
  state: Record<string, string> = {},
  action: AnyAction
): Record<string, string> | string => {
  switch (action.type) {
    case "SET_BREADCRUMBS_ADDRESS":
      state[action.address.path] = action.address.name;
      return { ...state };
    case "SET_BREADCRUMBS_ADDRESSES":
      for (const address of action.addresses) {
        state[address.path] = address.name;
      }
      return { ...state };
    default:
      return state;
  }
};

export default BreadcrumbsReducer;

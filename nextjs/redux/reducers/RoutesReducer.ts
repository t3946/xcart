import { AnyAction } from "redux";

const RoutesReducer = (
  store: Record<any, any>[] = [],
  action: AnyAction
): Record<any, any>[] => {
  switch (action.type) {
    default:
      return store;
  }
};
export default RoutesReducer;

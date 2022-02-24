import { AnyAction } from "redux";

const ConfigReducer = (
  store: { quantity: number; checkoutUrl: string } | null = null,
  action: AnyAction
): Record<any, any> | null => {
  switch (action.type) {
    default:
      return store;
  }
};

export default ConfigReducer;

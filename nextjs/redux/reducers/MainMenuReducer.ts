import { AnyAction } from "redux";

const MainMenuReducer = (
  store: [] = [],
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    default:
      return store;
  }
};

export default MainMenuReducer;

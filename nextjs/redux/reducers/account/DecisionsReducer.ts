import { AnyAction } from "redux";
import DecisionsInterface from "@modules/account/ts/types/decision";

interface DecisionsStore {
  solved: {
    total: number;
    items: DecisionsInterface[];
  };
  notSolved: {
    total: number;
    items: DecisionsInterface[];
  };
}

const initialState = {
  notSolved: {
    total: 0,
    items: [],
  },
  solved: {
    total: 0,
    items: [],
  },
};

const DecisionsReducer = (
  store: DecisionsStore = initialState,
  action: AnyAction
): any => {
  switch (action.type) {
    //todo а это ещё зачем?
    case "RESET_DECISION":
      store.solved.decisions = [];
      store.solved.pagination_offset = 0;
      store.notSolved.decisions = [];
      store.notSolved.pagination_offset = 0;
      return { ...store };

    //add new decisions and update pagination offsets
    case "ADD_DECISION":
      for (const decision of action.decisions) {
        switch (decision.solved) {
          case 0:
            store.notSolved.items.push(decision);
            break;
          case 1:
            store.solved.items.push(decision);
            break;
        }
      }

      return { ...store };

    case "SET_DECISIONS":
      return { ...action.decisions };

    default:
      return store;
  }
};

export default DecisionsReducer;

import { AnyAction } from "redux";
import { decisions } from "@modules/account/ts/consts/store-initial-value";
import DecisionsInterface from "@modules/account/ts/types/decision";

interface DecisionsStore {
  solved: {
    pagination_offset: number;
    decisions: DecisionsInterface[];
  };
  notSolved: {
    pagination_offset: number;
    decisions: DecisionsInterface[];
  };
}

const DecisionsReducer = (
  store: DecisionsStore = decisions,
  action: AnyAction
): any => {
  switch (action.type) {
    case "RESET_DECISION":
      store.solved.decisions = [];
      store.solved.pagination_offset = 0;
      store.notSolved.decisions = [];
      store.notSolved.pagination_offset = 0;
      return { ...store };

    //add new decisions and update pagination offsets
    case "ADD_DECISION":
      store.solved.decisions = [
        ...store.solved.decisions,
        ...action.decisions.solved,
      ];
      store.solved.pagination_offset = store.solved.decisions.length;

      store.notSolved.decisions = [
        ...store.notSolved.decisions,
        ...action.decisions.notSolved,
      ];
      store.notSolved.pagination_offset = store.notSolved.decisions.length;

      return { ...store };

    default:
      return store;
  }
};

export default DecisionsReducer;

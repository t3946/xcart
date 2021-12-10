import { AnyAction } from "redux";
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

const initialState = {
  solved: {
    pagination_offset: 0,
    decisions: [],
  },
  notSolved: {
    pagination_offset: 0,
    decisions: [],
  },
};

const DecisionsReducer = (
  store: DecisionsStore = initialState,
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

    case "SET_DECISIONS":
      return {
        solved: {
          pagination_offset: action.decisions.solved.length,
          decisions: action.decisions.solved,
        },
        notSolved: {
          pagination_offset: action.decisions.notSolved.length,
          decisions: action.decisions.notSolved,
        },
      };

    default:
      return store;
  }
};

export default DecisionsReducer;

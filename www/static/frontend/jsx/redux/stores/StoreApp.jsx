import { createStore } from "redux";
import merge from "lodash/merge";

const _INIT_ACTION_TYPE = "@@redux/INIT";
const _MEDIA_MOBILE = ["small", "sm", "medium", "ml"];

const ACTIVE_SEARCH = "search";

let ACTIONS = {
  SET: (state, action) => {
    if (action.data) {
      let new_state = {};
      new_state = merge(new_state, state);
      new_state = merge(new_state, action.data);

      return new_state;
    }

    return state;
  },

  SET_MEDIA: (state, action) => {
    if (action.data) {
      let new_state = ACTIONS.SET(state, action);

      if (_MEDIA_MOBILE.includes(new_state.frontend.media)) {
        if (new_state.frontend.header.active == ACTIVE_SEARCH) {
          new_state.frontend.header.mobileSearch = true;
        }

        new_state.frontend.darkness = new_state.frontend.header.mobileSearch;
        return new_state;
      }

      new_state.frontend.darkness =
        new_state.frontend.header.active == null ? false : true;

      return new_state;
    }

    return state;
  },

  SET_MOBILE_SEARCH: (state, action) => {
    if (action.data) {
      let new_state = ACTIONS.SET(state, action);

      if (_MEDIA_MOBILE.includes(new_state.frontend.media)) {
        new_state.frontend.darkness = new_state.frontend.header.mobileSearch;
      }

      return new_state;
    }

    return state;
  },

  CHECK_OFF: (state, action) => {
    if (action.data) {
      let new_state = ACTIONS.SET(state, action);

      if (
        _MEDIA_MOBILE.includes(new_state.frontend.media) &&
        new_state.frontend.header.mobileSearch
      ) {
        new_state.frontend.darkness = true;
      }

      return new_state;
    }

    return state;
  },

  INIT: (state = window.app, action) => {
    state["frontend"] = {
      darkness: false,
      media: "",
      header: {
        active: null,
        mobileSearch: false,
      },
    };

    return state;
  },

  default: (state, action) => {
    if (action.type === _INIT_ACTION_TYPE) {
      state = ACTIONS["INIT"](state, action);
    } else {
      state = ACTIONS["SET"](state, action);
    }

    return state;
  },
};

const store = createStore(
  (state, action) =>
    action && ACTIONS[action.type]
      ? ACTIONS[action.type](state, action)
      : ACTIONS["default"](state, action)
  // applyMiddleware(
  // thunkMiddleware, // позволяет нам отправлять функции
  // loggerMiddleware // аккуратно логируем действия
  // )
);

export default store;

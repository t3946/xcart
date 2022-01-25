import { createStore, applyMiddleware } from "redux";
import thunkMiddleware from "redux-thunk";
import ajax from "@utils/ajax";
import trigger from "@utils/trigger";
import ls from "@utils/localStorage/storage";

const _INIT_ACTION_TYPE = "@@redux/INIT";
// todo: из глобальной переменной app.options.session_key
// const ls_key = storeApp.getState().options.session_key + "__store_cart_state";
const ls_key = "xid0" + "__store_cart_state";

let ACTIONS = {
  SET: (state, action) => {
    let new_state = {
      ...state,
      cart: { ...action.data },
    };

    let data = { state: new_state, prevState: { ...state } };

    if (action.triggers === "ignore") {
      trigger("fetch.cart.store", data);
    } else {
      trigger("update.cart.store", data);
    }

    ls.set(ls_key, JSON.stringify(new_state));

    return new_state;
  },

  INIT: (state = INITIAL, action) => {
    let t_state = ls.get(ls_key);
    if (t_state) {
      state = JSON.parse(t_state);
    }

    ls.on(ls_key, (value) => {
      let data = JSON.parse(value);
      store.dispatch({ type: "SET", data: data.cart });

      state = data;
    });

    return state;
  },

  FETCH: (state, action) => {
    return ACTIONS["PUSH"](state, action);
  },

  PUSH: (state, action) => {
    let url = null;

    switch (action.action) {
      case "ADD": {
        url = "/cart/add/products";
        break;
      }
      case "SET": {
        url = "/cart/set/products";
        break;
      }
      case "DEL": {
        url = "/cart/del/products";
        break;
      }
      case "GET":
      default:
        url = "/cart/get/products";
    }

    if (url) {
      ajax(url, { method: "POST", data: action.data }).then((data) => {
        store.dispatch({ type: "SET", data: data });

        if (typeof action.callback === "function") {
          action.callback();
        }
      });
    }

    return state;
  },

  default: (state, action) => {
    if (action.type === _INIT_ACTION_TYPE) {
      state = ACTIONS["INIT"](state, action);
      state = ACTIONS["FETCH"](state, action);
    }

    return state;
  },
};

const INITIAL = {
  cart: {
    items: [],
    groups: [],
    total: 0,
    discount: 0,
    quantity: 0,
    currency: "US$",
  },
};

const store = createStore(
  (state, action) =>
    action && ACTIONS[action.type]
      ? ACTIONS[action.type](state, action)
      : ACTIONS["default"](state, action),
  applyMiddleware(
    thunkMiddleware // позволяет нам отправлять функции
    // loggerMiddleware // аккуратно логируем действия
  )
);

export default store;

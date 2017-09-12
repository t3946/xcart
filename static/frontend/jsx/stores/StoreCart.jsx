import { createStore } from 'redux'
import ajax from '../utils/ajax';
import trigger from '../utils/trigger';
import ls from '../utils/localStorage/storage';

const _INIT_ACTION_TYPE = "@@redux/INIT";
const ls_key = window.options.session_key + '__store_cart_state';

let ACTIONS = {
    SET: (state, action) => {
        let new_state = {
            ...state,
            cart: {...action.data},
        };

        let data = {state: new_state, prevState: {...state}};

        if (action.triggers === 'ignore') {
            trigger('store.cart.fetch', data);
        }
        else {
            trigger('store.cart.update', data);
        }

        ls.set(ls_key, JSON.stringify(new_state));

        return new_state;
    },

    INIT: (state, action) => {
        let t_state = ls.get(ls_key);
        if (t_state) {
            state = JSON.parse(t_state);
        }

        ls.on(ls_key, (value)=>{
            let data = JSON.parse(value);

            store.dispatch({type:'SET', data: data.cart});
        });

        return state;
    },

    FETCH: (state, action) => {
        ajax(options.urls.cart_get, {}, (data) => {
            store.dispatch({type:'SET', data: data, triggers: 'ignore'});
        });

        return state;
    },

    PUSH: (state, action) => {
        ajax(options.urls.cart_add, action.data, (data) => {
            store.dispatch({type:'SET', data: data});

            if (typeof action.callback === 'function') {
                action.callback();
            }
        });

        return state;
    },

    default: (state, action) => {
        if (action.type === _INIT_ACTION_TYPE) {
            state = ACTIONS['INIT'](state, action);

            if (!state.cart) {
                state = INITIAL;
                ACTIONS['FETCH'](state, action)
            }
        }

        return state;
    }

};

const INITIAL = {
    cart: {
        items: [],
        groups: [],
        total: 0,
        discount:0,
        quantity:0,
    },
};

const store = createStore(
    (state, action) => (action && ACTIONS[action.type] ? ACTIONS[action.type](state, action) : ACTIONS['default'](state, action)),
    {},
    window.devToolsExtension && window.devToolsExtension()
);

export default store;
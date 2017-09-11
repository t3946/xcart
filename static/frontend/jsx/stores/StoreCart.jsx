import { createStore } from 'redux'
import ajax from '../utils/ajax';
import trigger from '../utils/trigger';

const _INIT_ACTION_TYPE = "@@redux/INIT";

let ACTIONS = {
    SET: (state, action) => {
        let new_state = {
            ...state,
            cart: {...action.data},
        };

        let data = {state: new_state, prevState: {...state}};

        if (action.from === 'INIT') {
            trigger('store.cart.fetch', data);
        }
        else {
            trigger('store.cart.update', data);
        }

        return new_state;
    },

    INIT: (state, action) => {
        ajax(options.urls.cart_get, {}, (data) => {
            store.dispatch({type:'SET', data: data, from: 'INIT'});
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
            return ACTIONS['INIT'](state, action);
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
    INITIAL,
    window.devToolsExtension && window.devToolsExtension()
);

export default store;
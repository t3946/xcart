import { createStore } from 'redux'
import ajax from '../utils/ajax';
import trigger from '../utils/trigger';

let ACTIONS = {
    SET: (state, action) => {
        let new_state = {
            ...state,
            cart: {...action.data},
        };

        let data = {state: new_state, prevState: {...state}};

        if (action.from === 'FETCH') {
            trigger('store.cart.fetch', data);
        }
        else {
            trigger('store.cart.update', data);
        }

        return new_state;
    },

    FETCH: (state, action) => {
        ajax(options.urls.cart_get, {}, (data) => {
            store.dispatch({type:'SET', data: data, from: 'FETCH'});
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

const store = createStore( (state, action) => (action && ACTIONS[action.type] ? ACTIONS[action.type](state, action) : state), INITIAL);

export default store;
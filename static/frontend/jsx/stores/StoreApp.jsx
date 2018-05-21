import { createStore, applyMiddleware } from 'redux'
import _ from 'lodash';
import thunkMiddleware from 'redux-thunk'
import ajax from '../utils/ajax';
import trigger from "../utils/trigger";

const _INIT_ACTION_TYPE = "@@redux/INIT";

let ACTIONS = {
    SET: (state, action) => {
        if (action.data) {
            let new_state = {};

            new_state = _.merge(new_state, state);
            new_state = _.merge(new_state, action.data);

            return new_state;
        }

        return state;
    },

    INIT: (state = window.app, action) => {
        state['frontend'] = {
            darkness: false,
            header: {
                active: null,
            }
        };

        return state;
    },

    default: (state, action) => {
        if (action.type === _INIT_ACTION_TYPE) {
            state = ACTIONS['INIT'](state, action);
        }
        else {
            state = ACTIONS['SET'](state, action);
        }

        return state;
    }

};

const store = createStore(
    (state, action) => (action && ACTIONS[action.type] ? ACTIONS[action.type](state, action) : ACTIONS['default'](state, action)),
    // applyMiddleware(
        // thunkMiddleware, // позволяет нам отправлять функции
        // loggerMiddleware // аккуратно логируем действия
    // )
);

export default store;
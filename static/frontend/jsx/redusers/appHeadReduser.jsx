import storeApp from '../stores/StoreApp';

const setTtype = {
    type: 'SET',
};


export function hideAll()
{
    storeApp.dispatch({
        ...setTtype,
        data: {
            frontend: {
                darkness: false,
                header: {
                    active: null,
                }
            }
        }
    });
}

export function action(action) {
    storeApp.dispatch({
        ...setTtype,
        data: {
            frontend: {
                darkness: (action !== null),
                header: {
                    active: action,
                }
            }
        }
    });
}
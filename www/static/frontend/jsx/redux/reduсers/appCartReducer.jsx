import storeCart from '../stores/StoreCart';

export function cartAdd(items = [], callback)
{
    if (items && items.length) {
        storeCart.dispatch({
            type:'PUSH',
            action: 'ADD',
            data: {items: items},
            callback: callback,
        });
    }
}
import {List} from "@modules/account/ts/types/list.type";

export const deleteProductList = (state: List, productId: number): List => {
    return {
        ...state,
        products: state.products.filter(
            (product) => product.productId !== productId
        ),
    };
};
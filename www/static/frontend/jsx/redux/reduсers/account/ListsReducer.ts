import { AnyAction } from "redux";
import { AccountListsStore } from "@client/modules/account/ts/types/account-store.type";
import { accountListsInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";
import { AccountListProductActionEnum } from "@client/modules/account/ts/types/account-list-product-action";

const accountListReducer = (
  state: AccountListsStore = accountListsInitialValue,
  action: AnyAction
): AccountListsStore => {
  switch (action.type) {
    case "GET_LISTS":
      return { ...state };
    case "SET_LISTS":
      return {
        ...state,
        lists: action.lists,
        listLoading: false,
      };
    case "CREATE_LIST":
      return {
        ...state,
        listLoading: true,
      };
    case "REORDER_LIST":
      return {
        ...state,
        lists: state.lists.map((e) => {
          if (e.product_list_id === action.product_list_id) {
            return {
              ...e,
              products: action.listIds,
            };
          }
          return e;
        }),
      };
    case "DELETE_PRODUCT":
      return {
        ...state,
        lists: state.lists.map((list) => {
          if (list.product_list_id === action.product_list_id) {
            return {
              ...list,
              products: list.products.map((product) => {
                if (product.product_id === action.list_items_id) {
                  return {
                    ...product,
                    typeAction: {
                      type: AccountListProductActionEnum.DELETE,
                      productName: product.product.product,
                    },
                  };
                }
                return product;
              }),
            };
          }
          return list;
        }),
      };
    case "UNDO_DELETE_PRODUCT":
      return {
        ...state,
        lists: state.lists.map((list) => {
          if (list.product_list_id === action.product_list_id) {
            return {
              ...list,
              products: list.products.map((product) => {
                if (product.product_id === action.list_items_id) {
                  delete product.typeAction;
                  return {
                    ...product,
                  };
                }
                return product;
              }),
            };
          }
          return list;
        }),
      };
    case "MOVE_PRODUCT":
      return {
        ...state,
        lists: state.lists.map((list) => {
          if (action.fromListId === action.toListId.value) {
            return list;
          }
          if (list.product_list_id === action.fromListId) {
            return {
              ...list,
              products: list.products.map((product) => {
                if (product.list_items_id === action.product.list_items_id) {
                  const list = state.lists.find(
                    (e) => e.product_list_id === action.toListId.value
                  );
                  return {
                    ...product,
                    typeAction: {
                      type: AccountListProductActionEnum.MOVE,
                      toListId: list.cache_url,
                      listName: list.name,
                    },
                  };
                }
                return product;
              }),
            };
          } else if (list.product_list_id === action.toListId.value) {
            return {
              ...list,
              products: list.products.concat(action.product),
            };
          }
          return list;
        }),
      };

    default:
      return state;
  }
};
export default accountListReducer;

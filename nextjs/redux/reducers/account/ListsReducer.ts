import { AnyAction } from "redux";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import { AccountListProductActionEnum } from "@modules/account/ts/types/account-list-product-action";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";

const initialValue = {
  lists: [],
  listLoading: false,
};

const accountListReducer = (
  state: AccountListsStore = initialValue,
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
    case "ADD_PRODUCT_ON_LIST":
      return {
        ...state,
        listLoading: true,
      };
    case "ACCEPT_INVITE":
      return {
        ...state,
        listLoading: true,
      };
    case "EDIT_IDEA_NAME":
      return {
        ...state,
        listLoading: true,
      };
    case "EDIT_COMMENT_IN_PRODUCT":
      return {
        ...state,
        listLoading: true,
      };
    case "MANAGE_LIST":
      return {
        ...state,
        listLoading: true,
      };
    case "EDIT_USER_RIGHTS":
      if (action.actionType === UserRightsActionsEnum.DELETE) {
        return {
          ...state,
          lists: state.lists.map((e) => {
            if (e.product_list_id === action.listId) {
              return {
                ...e,
                users: e.users.filter((user) => user.user_id !== action.userId),
              };
            }

            return e;
          }),
        };
      }
      return {
        ...state,
        lists: state.lists.map((e) => {
          if (e.product_list_id === action.listId) {
            return {
              ...e,
              users: e.users.map((user) => {
                if (user.user_id === action.userId) {
                  return {
                    ...user,
                    role: action.actionType,
                  };
                }
                return user;
              }),
            };
          }
          return e;
        }),
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
                  let productName;
                  if ("product" in product.product) {
                    productName = product?.product?.product;
                  } else {
                    productName = product?.product?.name;
                  }

                  return {
                    ...product,
                    typeAction: {
                      type: AccountListProductActionEnum.DELETE,
                      productName,
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
                  let productName;

                  if ("product" in product.product) {
                    productName = product?.product?.product;
                  } else {
                    productName = product?.product?.name;
                  }

                  return {
                    ...product,
                    typeAction: {
                      type: AccountListProductActionEnum.MOVE,
                      toListId: list.cache_url,
                      listName: list.name,
                      productName,
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

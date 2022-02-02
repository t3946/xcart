import { AnyAction } from "redux";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { editCommentDataProduct } from "@modules/account/utils/edit-store-funcs/lists/edit-comment-data-on-product";
import { manageList } from "@modules/account/utils/edit-store-funcs/lists/manage-list";
import { deleteList } from "@modules/account/utils/edit-store-funcs/lists/delete-list";
import { moveProductList } from "@modules/account/utils/edit-store-funcs/lists/move-product-list";
import { deleteProductList } from "@modules/account/utils/edit-store-funcs/lists/delete-product-list";
import { addProductToList } from "@modules/account/utils/edit-store-funcs/lists/add-product-to-list";
import { editIdeaName } from "@modules/account/utils/edit-store-funcs/lists/edit-idea-name";

const initialValue: AccountListsStore = {
  lists: null,
  listView: null,
  loading: false,
};

const accountListReducer = (
  state: AccountListsStore = initialValue,
  action: AnyAction
): AccountListsStore => {
  switch (action.type) {
    case "GET_LISTS":
      return { ...state };
    case "SUCCESS_ADD_PRODUCT":
      return {
        ...state,
        loading: false,
      };
    case "ADD_PRODUCT_ON_LIST":
    case "SEND_EDIT_IDEA_NAME":
      return {
        ...state,
        loading: true,
      };
    case "ADD_PRODUCT_TO_LIST":
      const { product } = action;
      return addProductToList(state, action.productListId, product);
    case "SET_LISTS":
      return {
        ...state,
        lists: action.lists,
        loading: false,
      };
    case "EDIT_IDEA_NAME":
      return {
        ...state,
        loading: false,
        listView: editIdeaName(state.listView, action.productId, action.name),
      };
    case "SET_LIST_VIEW":
      return {
        ...state,
        listView: action.listView,
      };
    case "EDIT_COMMENT_LIST_VIEW":
      return {
        ...state,
        listView: editCommentDataProduct(
          state.listView,
          action.productId,
          action.data
        ),
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
    case "SEND_REORDER_LIST":
      return {
        ...state,
        listView: { ...state.listView, products: action.listIds },
      };
    case "DELETE_PRODUCT_LIST_VIEW":
      return {
        ...state,
        listView: deleteProductList(state.listView, action.list_items_id),
      };
    case "UNDO_DELETE_PRODUCT":
      return {
        ...state,
        listView: {
          ...state.listView,
          products: state.listView.products.map((product) => {
            if (product.productId === action.list_items_id) {
              delete product.typeAction;
              return {
                ...product,
              };
            }
            return product;
          }),
        },
      };
    case "SET_TRANSFER_PRODUCT":
      const { productId, toListId, fromListId } = action;
      return moveProductList(state, fromListId, toListId, productId);
    case "MANAGE_LIST_VIEW":
      const { productListId, data } = action;
      return manageList(state, productListId, data);
    case "DELETE_LIST":
      return deleteList(state, action.productListId);
    case "ADD_LIST":
      return { ...state, lists: [...state.lists, action.data] };
    default:
      return state;
  }
};
export default accountListReducer;

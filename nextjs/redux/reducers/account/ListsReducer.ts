import { AnyAction } from "redux";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { manageList } from "@modules/account/utils/edit-store-funcs/lists/manage-list";
import { deleteList } from "@modules/account/utils/edit-store-funcs/lists/delete-list";
import { moveProductList } from "@modules/account/utils/edit-store-funcs/lists/move-product-list";
import { deleteProductList } from "@modules/account/utils/edit-store-funcs/lists/delete-product-list";
import { addProductToList } from "@modules/account/utils/edit-store-funcs/lists/add-product-to-list";
import { editIdeaName } from "@modules/account/utils/edit-store-funcs/lists/edit-idea-name";

const initialValue: AccountListsStore = {
  lists: null,
  currentList: null,
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
      console.log("ADD_PRODUCT_TO_LIST", action);
      const { listItem } = action;
      return addProductToList(state, action.productListId, listItem);
    case "SET_LISTS":
      return {
        ...state,
        lists: action.lists,
        loading: false,
      };
    case "SET_LIST_VIEW":
      return {
        ...state,
        currentList: action.currentList,
      };
    case "EDIT_IDEA":
      return {
        ...state,
        loading: false,
        currentList: editIdeaName(
          state.currentList,
          action.list_idea_id,
          action.name
        ),
      };
    case "EDIT_COMMENT_LIST_VIEW":
      const newItems = state.currentList.items.map((item) => {
        if (item.list_item_id === action.data.list_item_id) {
          return { ...item, ...action.data };
        }
        return item;
      });

      state.currentList.items = [...newItems];

      return { ...state };
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
      state.currentList.items = action.listIds;

      return {
        ...state,
      };
    case "DELETE_PRODUCT_LIST_VIEW":
      return {
        ...state,
        currentList: deleteProductList(state.currentList, action.list_item_id),
      };
    case "UNDO_DELETE_PRODUCT":
      const list_item_id = action.payload.data.list_item_id;

      return {
        ...state,
        currentList: {
          ...state.currentList,
          items: state.currentList.items.map((item) => {
            if (item.list_item_id === list_item_id) {
              delete item.typeAction;
              return {
                ...item,
              };
            }
            return item;
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
    case "FETCH_LISTS":
    case "FETCH_LIST":
      return { ...state, loading: true };
    case "LIST_DROP_BY_HASH":
      const newLists = [];
      state.lists;

      for (const list of state.lists) {
        if (list.cacheUrl !== action.hash) {
          newLists.push(list);
        }
      }

      state.lists = newLists;

      return { ...state };
    default:
      return state;
  }
};
export default accountListReducer;

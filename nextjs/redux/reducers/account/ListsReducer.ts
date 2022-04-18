import { AnyAction } from "redux";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { manageList } from "@modules/account/utils/edit-store-funcs/lists/manage-list";
import { deleteList } from "@modules/account/utils/edit-store-funcs/lists/delete-list";
import { deleteProductList } from "@modules/account/utils/edit-store-funcs/lists/delete-product-list";

const initialValue: AccountListsStore = {
  lists: [],
};

function getListByItemId(state, list_item_id) {
  for (const list of state.lists) {
    for (const item of list.items) {
      if (item.list_item_id === list_item_id) {
        return list;
      }
    }
  }

  return null;
}

function getItemById(state, list_item_id) {
  for (const list of state.lists) {
    for (const item of list.items) {
      if (item.list_item_id === list_item_id) {
        return item;
      }
    }
  }

  return null;
}

function getListById(state, product_list_id) {
  return state.lists.find((list) => list.product_list_id === product_list_id);
}

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
      };
    case "ADD_PRODUCT_ON_LIST":
      return {
        ...state,
      };

    case "ADD_PRODUCT_TO_LIST": {
      const { listItem } = action;
      let newItems;

      for (const list of state.lists) {
        if (action.productListId == list.product_list_id) {
          newItems = [...list.items, listItem];
          list.items = [...newItems];
        }
      }

      return { ...state };
    }

    case "SET_LISTS":
      return {
        ...state,
        lists: action.lists,
      };
    case "SET_LIST_VIEW":
      return {
        ...state,
      };

    case "EDIT_IDEA":
      for (const list of state.lists) {
        for (const item of list.items) {
          if (item.list_idea_id === action.data.list_idea_id) {
            item.idea.name = action.data.name;
          }
        }
      }

      return {
        ...state,
      };

    case "EDIT_COMMENT_LIST_VIEW":
      console.log("EDIT_COMMENT_LIST_VIEW", { action });
      // const newItems = state.currentList.items.map((item) => {
      //   if (item.list_item_id === action.data.list_item_id) {
      //     return { ...item, ...action.data };
      //   }
      //   return item;
      // });
      //
      // state.currentList.items = [...newItems];

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
      // const item = getItemById(state, action.list_item_id);

      // item.type = "delete";
      // item.productName = item.product?.product || item.idea?.name;

      for (const list of state.lists) {
        for (const item of list.items) {
          if (item.list_item_id === action.list_item_id) {
            item.type = "delete";
            item.productName = item.product?.product || item.idea?.name;
            list.items = [...list.items];
            return { ...state };
          }
        }
      }

      //todo: в компоненте проверить есть ли там delete, и почему не работает замена компонента, хотя storage обновлён

      return { ...state };

    // return {
    //   ...state,
    //   currentList: deleteProductList(state.currentList, action.list_item_id),
    // };

    case "UNDO_DELETE_PRODUCT":
      const list_item_id = action.payload.data.list_item_id;
      const list = getListByItemId(state, list_item_id);

      for (const item of list.items) {
        if (item.list_item_id === list_item_id) {
          delete item.typeAction.type;
          delete item.typeAction.productName;
        }
      }

      return {
        ...state,
      };
    case "SET_TRANSFER_PRODUCT": {
      const { product_list_id, list_item_id } = action;
      const fromList = getListByItemId(state, list_item_id);
      const toList = getListById(state, product_list_id);
      const newFromListItems = [];

      for (const item of fromList.items) {
        if (item.list_item_id === list_item_id) {
          toList.items.push(item);
        } else {
          newFromListItems.push(item);
        }
      }

      fromList.items = newFromListItems;

      return { ...state };
    }
    case "MANAGE_LIST_VIEW":
      const { product_list_id, data } = action;
      return manageList(state, product_list_id, data);

    case "DELETE_LIST":
      return deleteList(state, action.productListId);
    case "ADD_LIST":
      return { ...state, lists: [...state.lists, action.data] };
    case "FETCH_LISTS":
    case "FETCH_LIST":
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

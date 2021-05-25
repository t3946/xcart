import { editFavoriteItems } from "@s3stores-mail/utils";
import { AnyAction } from "redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { initialValues } from "@s3stores-mail/ts/consts";
import { editCheckedInEmailItems } from "@s3stores-mail/utils/edit-checked-in-email-items";
import { editCheckedItems } from "@s3stores-mail/utils/edit-checked-items";
import { editActionItems } from "@s3stores-mail/utils/edit-action-items";

const emailReducer = (
  state: StoreDto = initialValues,
  action: AnyAction
): StoreDto => {
  switch (action.type) {
    case "GET_PAGE":
      return { ...state };
    case "SET_PAGE":
      return {
        ...state,
        items: action.json,
        loading: false,
        itemsCount: action.itemsCount,
        checkedItemsOptions: {
          prevValue: 0,
        },
      };
    case "SET_SEARCH_OPTIONS":
      return {
        ...state,
        searchOptions: action.searchOptions,
      };
    case "SET_SEND_TEMPLATE_TYPE":
      return {
        ...state,
        templateType: action.templateType,
      };
    case "SET_SEND_TEMPLATE":
      return {
        ...state,
        sendTemplate: action.sendTemplate,
      };
    case "EDIT_SEND_DATA":
      const sendData: { [p: string]: any } = Object.fromEntries(
        Object.entries(state.sendData).map(([key, value]) => {
          if (key === action.field) {
            return [key, action.data];
          }
          return [key, value];
        })
      );
      return {
        ...state,
        sendData: sendData,
      };
    case "ADD_FILE":
      return {
        ...state,
        sendData: {
          ...state.sendData,
          files: state.sendData.files.concat(action.item),
        },
      };
    case "DELETE_FILE":
      return {
        ...state,
        sendData: {
          ...state.sendData,
          files: state.sendData.files.filter(
            (file) => file.name !== action.path
          ),
        },
      };
    case "RESET_SEND_DATA":
      return {
        ...state,
        sendData: initialValues.sendData,
        sendTemplate: initialValues.sendTemplate,
        templateType: initialValues.templateType,
      };
    case "EDIT_CHECKED_ITEMS":
      const checkedItems = editCheckedItems(
        state.checkedItems,
        state.checkedItemsOptions.prevValue,
        action.id,
        state.items,
        action.multiply
      );
      return {
        ...state,
        checkedItems: checkedItems,
        items: editCheckedInEmailItems(state.items, checkedItems),
        checkedItemsOptions: {
          prevValue: action.id,
        },
      };
    case "EDIT_FAVORITES":
      const items = editFavoriteItems(state.items, action.favoriteItems);
      return {
        ...state,
        items: items,
      };
    case "EDIT_ACTIONS":
      const actionItems = editActionItems(state.items, action.actionItems);
      return {
        ...state,
        items: actionItems,
      };
    case "SET_LOADING":
      return {
        ...state,
        loading: true,
      };
    default:
      return state;
  }
};
export default emailReducer;

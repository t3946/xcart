import { AnyAction } from "redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { initialValues } from "@s3stores-mail/ts/consts";
import { editCheckedInEmailItems } from "@s3stores-mail/utils/edit-checked-in-email-items";
import { editCheckedItems } from "@s3stores-mail/utils/edit-checked-items";
import {
  editFieldsOnEmail,
  isActionItemTrue,
  isFavoriteItemsTrue,
  isViewedItemsTrue,
} from "@s3stores-mail/utils/edit-fields-on-email";
import { editSelectViewValue } from "@s3stores-mail/utils/edit-select-view-value";

const emailReducer = (
  state: StoreDto = initialValues,
  action: AnyAction
): StoreDto => {
  switch (action.type) {
    case "GET_PAGE":
      return { ...state, page: action.page };
    case "SET_PAGE":
      return {
        ...state,
        items: action.json,
        loading: false,
        itemsCount: action.itemsCount,
        checkedItems: [],
        checkedItemsOptions: {
          prevValue: "0",
        },
        user: action.user,
        labelsList: action.labelList,
      };
    case "SET_SEARCH_OPTIONS":
      return {
        ...state,
        searchOptions: action.searchOptions,
      };
    case "GET_EMAIL_INFO":
      return {
        ...state,
        loading: true,
      };
    case "SET_EMAIL_INFO":
      const labels = action.emailInfo.labelList;
      delete action.emailInfo.labelList;
      return {
        ...state,
        loading: false,
        items: [{ item: action.emailInfo, checked: false }],
        labelsList: labels,
      };
    case "ADD_LABEL_MAIL":
      return {
        ...state,
        items: state.items.map((mess) => {
          if (mess.item.message_id === action.messageId) {
            const labelSelect = state.labelsList.find(
              (label) => label.label_id === action.labelId
            );
            mess.item.labels.push(labelSelect);
          }
          return mess;
        }),
      };
    case "CREATE_MAIL_LABEL":
      return {
        ...state,
        labelsList: [...state.labelsList, action.labelInfo],
        items: state.items.map((item) => {
          if (item.item.message_id === action.messageId) {
            item.item.labels.push(action.labelInfo);
          }
          return item;
        }),
      };
    case "REMOVE_LABEL_EMAIL":
      return {
        ...state,
        items: state.items.map((item) => {
          if (item.item.message_id === action.messageId) {
            item.item.labels = item.item.labels.filter(
              (label) => label.label_id !== action.labelId
            );
          }
          return item;
        }),
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
    case "ADD_RECIPIENT":
      return {
        ...state,
        sendData: {
          ...state.sendData,
          to: state.sendData.to.concat(action.value),
        },
      };
    case "DELETE_RECIPIENT":
      return {
        ...state,
        sendData: {
          ...state.sendData,
          to: state.sendData.to.filter((item) => item !== action.value),
        },
      };
    case "EDIT_RECIPIENT":
      return {
        ...state,
        sendData: {
          ...state.sendData,
          to: state.sendData.to.map((item) => {
            if (item === action.value) {
              return action.newValue;
            }
            return item;
          }),
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
        moreViewed: isViewedItemsTrue(state.items, checkedItems),
        moreFavorites: isFavoriteItemsTrue(state.items, checkedItems),
      };
    case "EDIT_FAVORITES":
      const items = editFieldsOnEmail(
        state.items,
        action.favoriteItems,
        "favorite",
        action.value
      );
      return {
        ...state,
        items: items,
        emailInfo: editEmailInfo(state.emailInfo, items),
        moreFavorites: isFavoriteItemsTrue(items, action.favoriteItems),
      };
    case "EDIT_ACTIONS":
      const actionItems = editFieldsOnEmail(
        state.items,
        action.actionItems,
        "action",
        isActionItemTrue(state.items, action.actionItems, state.user)
      );
      return {
        ...state,
        emailInfo: editEmailInfo(state.emailInfo, actionItems),
        items: actionItems,
      };
    case "SET_VIEWED":
      const viewedItems = editFieldsOnEmail(
        state.items,
        action.emailId,
        "viewed",
        action.value
      );
      return {
        ...state,
        items: viewedItems,
        moreViewed: isViewedItemsTrue(viewedItems, action.emailId),
        emailInfo: editEmailInfo(state.emailInfo, viewedItems),
      };
    case "SET_LOADING":
      return {
        ...state,
        loading: true,
      };
    case "GET_TEMPLATES":
      return {
        ...state,
      };
    case "SET_TEMPLATES":
      return {
        ...state,
        templates: editSelectViewValue(action.templates, "Select template by"),
      };
    case "EDIT_SEARCH_OPTIONS":
      return {
        ...state,
        searchOptions: action.searchOptions,
      };
    default:
      return state;
  }
};
export default emailReducer;

function editEmailInfo(emailInfo, items) {
  let newInfo = emailInfo;
  items.forEach((e) => {
    if (e.item.id === emailInfo.id) {
      newInfo = e.item;
    }
  });
  return newInfo;
}

import { List } from "@modules/account/ts/types/list.type";
import { ManageListRequestData } from "@modules/account/ts/types/manage-list-form.types";
import { AccountListsStore } from "@modules/account/ts/types/store.type";

export const manageList = (
  state: AccountListsStore,
  productListId: number,
  data: ManageListRequestData
): AccountListsStore => ({
  ...state,
  lists: state.lists.map((list) => {
    if (list.productListId === productListId) {
      list = convertManageList(list, data);
    }
    return list;
  }),
  listView: convertManageList(state.listView, data),
});

export const convertManageList = (
  list: List,
  data: ManageListRequestData
): List => ({
  ...list,
  description: data.description,
  addressId: data.address_id,
  name: data.name,
  recipientEmail: data.recipient_email,
  recipientName: data.recipient_name,
  birthday: data.birthday,
});

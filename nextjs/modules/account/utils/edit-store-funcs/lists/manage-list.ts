import { List } from "@modules/account/ts/types/list.type";
import { ManageListRequestData } from "@modules/account/ts/types/manage-list-form.types";
import { AccountListsStore } from "@modules/account/ts/types/store.type";

export const manageList = (
  state: AccountListsStore,
  product_list_id: number,
  data: ManageListRequestData
): AccountListsStore => ({
  ...state,
  lists: state.lists.map((list) => {
    if (list.product_list_id === product_list_id) {
      list = convertManageList(list, data);
    } else {
      if (data.default === 1) {
        list.default = 0;
      }
    }
    return list;
  }),
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
  default: data.default,
});

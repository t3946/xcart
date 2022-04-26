import {
  ManageListFormData,
  ManageListRequestData,
} from "@modules/account/ts/types/manage-list-form.types";

export const convertManageListFormDataToRequest = function (
  data: ManageListFormData
): ManageListRequestData {
  let birthday = null;

  if (data.month && data.day) {
    const date = new Date(
      new Date().getFullYear(),
      data.month?.value,
      data.day?.value
    );

    birthday = date.getTime().toString();
  }

  return {
    description: data.description || null,
    name: data.listName,
    recipient_name: data.recipientName || null,
    recipient_email: data.email || null,
    birthday,
    address_id: parseInt(data.shippingAddress.value) || null,
    default: data.isDefault ? 1 : 0,
    keep_purchased: data.keep_purchased ? 1 : 0,
  };
};

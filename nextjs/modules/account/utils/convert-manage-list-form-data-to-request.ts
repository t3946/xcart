import {
  ManageListFormData,
  ManageListRequestData,
} from "@modules/account/ts/types/manage-list-form.types";

export function convertManageListFormDataToRequest(
  data: ManageListFormData
): ManageListRequestData {
  console.log(data);
  return {
    description: data.description,
    name: data.listName,
    recipient_name: data.recipientName,
    recipient_email: data.email,
    birthday: Date.parse(
      new Date(
        new Date().getFullYear(),
        data.month?.value,
        data.day?.value
      ).toString()
    ),
    address_id: data.shippingAddress.value,
  };
}

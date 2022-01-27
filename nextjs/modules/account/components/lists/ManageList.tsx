import React, { useState } from "react";
import { useFormik } from "formik";
import * as Yup from "yup";
import { FormInput } from "@modules/account/components/shared/FormInput";
import FormSelect from "@modules/ui/forms/Select";
import { FormCheckBox } from "@modules/account/components/shared/FormCheckBox";
import Store from "@redux/stores/Store";
import { getValuesForSelect } from "@modules/account/utils/edit-store-funcs/getValuesForSelect";
import classnames from "classnames";
import { fillingMassForMonths } from "@modules/account/utils/filling-mass-for-months";
import { getDaysForSelect } from "@modules/account/utils/get-days-for-select";
import { useDispatch, useSelector } from "react-redux";
import { manageList } from "@redux/actions/account-actions/ListsActions";
import { convertManageListFormDataToRequest } from "@modules/account/utils/convert-manage-list-form-data-to-request";
import { ManageListFormData } from "@modules/account/ts/types/manage-list-form.types";
import StoreInterface from "@modules/account/ts/types/store.type";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { List } from "@modules/account/ts/types/list.type";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AddressItemDto } from "@modules/account/ts/types/address-item.type";

interface ManageListProps {
  onCancelClick: () => void;
}

export const ManageList: React.FC<ManageListProps> = ({ onCancelClick }) => {
  const listView: List = useSelectorAccount((state) => state.lists.listView);
  const addresses: AddressItemDto[] = useSelectorAccount(
    (state) => state.addresses.addressesList
  );
  const monthItems = fillingMassForMonths();

  const dispatch = useDispatch();

  const [dayItems, setDayItems] = useState(getDaysForSelect(0));

  const loading = useSelector((e: StoreInterface) => e.lists.listLoading);

  const handleSubmit = (values: ManageListFormData) => {
    console.log(values);
    dispatch(
      manageList(
        listView.productListId,
        convertManageListFormDataToRequest(values),
        onCancelClick
      )
    );
  };
  let selectAddress = null;
  if (addresses) {
    selectAddress = addresses?.find(
      (address) => address.address_id === listView.addressId
    );
  }
  const formik = useFormik({
    initialValues: {
      listName: listView.name || "",
      description: listView.description || "",
      recipientName: listView.recipientName || "",
      email: listView.recipientEmail || "",
      isPurchase: false,
      isDefault: false,
      shippingAddress: {
        value: selectAddress ? selectAddress.address_id : null,
        viewValue: selectAddress?.full_name || "None",
      },
      month: listView.birthday
        ? monthItems[new Date(Number(listView.birthday)).getMonth() - 1]
        : monthItems[0],
      day: listView.birthday
        ? dayItems[new Date(Number(listView.birthday)).getDate()]
        : dayItems[0],
    },
    validationSchema: Yup.object().shape({
      listName: Yup.string().required("Required field"),
      description: Yup.string().required("Required field"),
      recipientName: Yup.string().required("Required field"),
      email: Yup.string()
        .required("Required field")
        .email("Please enter valid email"),
    }),
    onSubmit: handleSubmit,
  });

  return (
    <div>
      <div className={"manage-list-label"}>
        People who access your list will see your recipient name.
      </div>
      <form
        className={"manage-list-form"}
        onSubmit={formik.handleSubmit}
        encType="multipart/form-data"
      >
        <FormInput
          name={"listName"}
          classes={{
            input: ["list-input-manage-list"],
          }}
          label={"List name"}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.listName}
          handleBlur={formik.handleBlur}
          touched={formik.touched.listName}
          value={formik.values.listName}
        />
        <FormInput
          name={"description"}
          classes={{
            input: ["list-input-manage-list", "text-area-input-container"],
            textArea: ["edit-comment-text-area-input"],
          }}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.description}
          handleBlur={formik.handleBlur}
          touched={formik.touched.description}
          label={"List Description"}
          value={formik.values.description}
          inputType={"text-area"}
        />
        <FormInput
          name={"recipientName"}
          classes={{
            input: ["list-input-manage-list"],
          }}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.recipientName}
          handleBlur={formik.handleBlur}
          touched={formik.touched.recipientName}
          value={formik.values.recipientName}
          label={"Recipient name"}
        />
        <FormInput
          name={"email"}
          classes={{
            input: ["list-input-manage-list"],
          }}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.email}
          handleBlur={formik.handleBlur}
          touched={formik.touched.email}
          value={formik.values.email}
          label={"Email"}
        />
        <div className="d-flex justify-content-between align-center">
          <label className={classnames("form-input-label")}>Birthday</label>
          <div className="d-flex justify-content-between list-input-manage-list">
            <FormSelect
              items={monthItems}
              name={"month"}
              onClick={(value) => {
                formik.setFieldValue("month", value);
                formik.setFieldValue("day", dayItems[0]);
                setDayItems(getDaysForSelect(value.value));
              }}
              value={formik.values.month}
              id="form-select-list-manage-month"
              classes={{
                group: ["list-manage-select-month"],
              }}
            />
            <FormSelect
              items={dayItems}
              name={"day"}
              onClick={(value) => formik.setFieldValue("day", value)}
              value={formik.values.day}
              id="form-select-list-manage-year"
              classes={{
                group: ["list-manage-select-day"],
              }}
            />
          </div>
        </div>
        <FormSelect
          items={getValuesForSelect(addresses || [], "address_id", "full_name")}
          name={"shippingAddress"}
          label={"Shipping Address"}
          onClick={(value) => formik.setFieldValue("shippingAddress", value)}
          value={formik.values.shippingAddress}
          id="form-select-list-manage-addresses"
          classes={{
            input: ["list-input-manage-list"],
            group: ["select-address-on-manage-list"],
          }}
        />
        <div className={"d-flex justify-content-end manage-list-checkbox"}>
          <div className="list-input-manage-list">
            <FormCheckBox
              label={"Keep purchased items on this list"}
              value={formik.values.isPurchase}
              name={"isPurchase"}
              handleChange={formik.handleChange}
              id={"id_purchase"}
            />
          </div>
        </div>
        <div className={"d-flex justify-content-end manage-list-checkbox"}>
          <div className="list-input-manage-list">
            <FormCheckBox
              label={"Make this list default"}
              value={formik.values.isDefault}
              name={"isDefault"}
              handleChange={formik.handleChange}
              id={"is_default"}
            />
          </div>
        </div>
        <SubmitCancelButtonsGroup
          submitText="Confirm"
          cancelText="Cancel"
          onCancel={onCancelClick}
          groupAdvancedClasses={"manage-list-btns"}
          disabled={loading}
        />
      </form>
    </div>
  );
};

import React, { useState } from "react";
import { useFormik } from "formik";
import * as Yup from "yup";
import Select from "@modules/ui/forms/select/Select";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { FormCheckBox } from "@modules/account/components/shared/FormCheckBox";
import { getValuesForSelect } from "@modules/account/utils/edit-store-funcs/getValuesForSelect";
import classnames from "classnames";
import { fillingMassForMonths } from "@modules/account/utils/filling-mass-for-months";
import { getDaysForSelect } from "@modules/account/utils/get-days-for-select";
import { useDispatch, useSelector } from "react-redux";
import { updateList } from "@redux/actions/account-actions/ListsActions";
import { convertManageListFormDataToRequest } from "@modules/account/utils/convert-manage-list-form-data-to-request";
import { ManageListFormData } from "@modules/account/ts/types/manage-list-form.types";
import StoreInterface from "@modules/account/ts/types/store.type";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AddressItemDto } from "@modules/account/ts/types/address-item.type";
import cn from "classnames";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";

import Styles from "@modules/account/components/lists/ManageList.module.scss";

interface IProps {
  list: any;
  onCancelClick: () => void;
}

export const ManageList: React.FC<IProps> = (props) => {
  const { list, onCancelClick } = props;
  const user = useSelectorAccount((state) => state.user);
  const addresses: AddressItemDto[] = useSelectorAccount((state) =>
    state.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.SHIPPING
    )
  );
  const monthItems = fillingMassForMonths();
  const dispatch = useDispatch();

  React.useEffect(() => {
    dispatch(getAddresses(user.user_id));
  }, []);

  const [dayItems, setDayItems] = useState(getDaysForSelect(0));
  const loading = useSelector((e: StoreInterface) => e.lists.listLoading);
  const classes = {
    inputGroup: "d-flex flex-wrap justify-content-lg-between mb-20",
    label:
      "col-12 col-md-6 col-lg-4 text-md-end text-lg-start pe-4 mb-10 mb-md-0",
    input: "col-12 col-md-6 col-lg-8",
    feedback: "mt-0 position-absolute",
  };

  function submit(values: ManageListFormData) {
    const data = {
      ...convertManageListFormDataToRequest(values),
      product_list_id: list.product_list_id,
    };

    console.log("update list submit", {data});

    dispatch(updateList({ data }));

    onCancelClick();
  }

  let selectAddress = null;

  if (addresses) {
    selectAddress = addresses?.find(
      (address) => address.address_id === list.addressId
    );
  }

  const formik = useFormik({
    initialValues: {
      listName: list.name || "",
      description: list.description || "",
      recipientName: list.recipient_name || "",
      email: list.recipient_email || "",
      isPurchase: false,
      isDefault: false,
      shippingAddress: {
        value: selectAddress ? selectAddress.address_id : "",
        label: selectAddress?.full_name || "None",
      },
      month: list.birthday
        ? monthItems[new Date(Number(list.birthday)).getMonth() - 1]
        : monthItems[0],
      day: list.birthday
        ? dayItems[new Date(Number(list.birthday)).getDate()]
        : dayItems[0],
    },
    validationSchema: Yup.object().shape({
      listName: Yup.string().required("Required field"),
      description: Yup.string(),
      recipientName: Yup.string(),
      email: Yup.string().email("Please enter valid email"),
    }),
    onSubmit: submit,
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
        <div className={cn(classes.inputGroup)}>
          <Label className={cn(classes.label)}>List name</Label>
          <div className={cn(classes.input)}>
            <Input
              name="listName"
              value={formik.values.listName}
              onChange={formik.handleChange}
              isValid={!!formik.touched.listName && !formik.errors.listName}
              isInvalid={!!formik.touched.listName && !!formik.errors.listName}
            />
            <Feedback type="invalid" className={cn(classes.feedback)}>
              {!!formik.touched.listName && formik.errors.listName}
            </Feedback>
          </div>
        </div>

        <div className={classes.inputGroup}>
          <Label className={cn(classes.label)}>List Description</Label>
          <div className={cn(classes.input)}>
            <Input
              className={Styles.textarea}
              as="textarea"
              name="description"
              value={formik.values.description}
              onChange={formik.handleChange}
              isValid={
                !!formik.touched.description && !formik.errors.description
              }
              isInvalid={
                !!formik.touched.description && !!formik.errors.description
              }
            />
            <Feedback className={cn(classes.feedback)}>
              {!!formik.touched.description && formik.errors.description}
            </Feedback>
          </div>
        </div>

        <div className={classes.inputGroup}>
          <Label className={cn(classes.label)}>Recipient name</Label>
          <div className={cn(classes.input)}>
            <Input
              name="recipientName"
              value={formik.values.recipientName}
              onChange={formik.handleChange}
              isValid={
                !!formik.touched.recipientName && !formik.errors.recipientName
              }
              isInvalid={
                !!formik.touched.recipientName && !!formik.errors.recipientName
              }
            />
            <Feedback className={cn(classes.feedback)}>
              {!!formik.touched.recipientName && formik.errors.recipientName}
            </Feedback>
          </div>
        </div>

        <div className={classes.inputGroup}>
          <Label className={cn(classes.label)}>Email</Label>
          <div className={cn(classes.input)}>
            <Input
              name="email"
              value={formik.values.email}
              onChange={formik.handleChange}
              isValid={!!formik.touched.email && !formik.errors.email}
              isInvalid={!!formik.touched.email && !!formik.errors.email}
            />
            <Feedback className={cn(classes.feedback)}>
              {!!formik.touched.email && formik.errors.email}
            </Feedback>
          </div>
        </div>

        <div className={classnames(classes.inputGroup)}>
          <label className={classnames("form-input-label", classes.label)}>
            Birthday
          </label>
          <div
            className={classnames(
              "d-flex justify-content-between",
              classes.input
            )}
          >
            <Select
              clearable={false}
              options={monthItems}
              name={"month"}
              onChange={(e) => {
                formik.setFieldValue("month", e.target.value);
                formik.setFieldValue("day", dayItems[0]);
                setDayItems(getDaysForSelect(e.target.value.value));
              }}
              value={formik.values.month}
              classes={{
                select: ["list-manage-select-month"],
              }}
            />
            <Select
              clearable={false}
              options={dayItems}
              name={"day"}
              onChange={formik.handleChange}
              value={formik.values.day}
              classes={{
                select: ["list-manage-select-day"],
              }}
            />
          </div>
        </div>

        <div className={classnames(classes.inputGroup)}>
          <Label className={classnames(classes.label)}>Shipping Address</Label>
          <div className={classnames(classes.input)}>
            <Select
              clearable={false}
              options={getValuesForSelect(
                addresses || [],
                "address_id",
                "full_name"
              )}
              name={"shippingAddress"}
              onChange={formik.handleChange}
              value={formik.values.shippingAddress}
            />
          </div>
        </div>

        <div className={"d-flex justify-content-end manage-list-checkbox"}>
          <div className={classnames(classes.input)}>
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
          <div className={classnames(classes.input)}>
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
          groupAdvancedClasses={"manage-list-btns mx-md-auto mx-lg-0"}
          disabled={loading}
        />
      </form>
    </div>
  );
};

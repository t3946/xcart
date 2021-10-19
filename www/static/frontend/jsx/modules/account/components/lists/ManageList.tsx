import React, { useState } from "react";
import { useFormik } from "formik";
import * as Yup from "yup";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { Grid } from "@material-ui/core";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { FormCheckBox } from "@client/modules/account/components/shared/FormCheckBox";
import Store from "@client/jsx/redux/stores/Store";
import { getValuesForSelect } from "@client/modules/account/utils/edit-store-funcs/getValuesForSelect";
import classnames from "classnames";
import { fillingMassForMonths } from "@client/modules/account/utils/filling-mass-for-months";
import { getDaysForSelect } from "@client/modules/account/utils/get-days-for-select";
import { useDispatch, useSelector } from "react-redux";
import { manageList } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { convertManageListFormDataToRequest } from "@client/modules/account/utils/convert-manage-list-form-data-to-request";
import { ManageListFormData } from "@client/modules/account/ts/types/manage-list-form.types";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";
import { List } from "@client/modules/account/ts/types/list.type";

interface ManageListProps {
  info: List;
  onCancelClick: () => void;
}

export const ManageList: React.FC<ManageListProps> = ({
  info,
  onCancelClick,
}) => {
  const monthItems = fillingMassForMonths();

  const dispatch = useDispatch();

  const [dayItems, setDayItems] = useState(getDaysForSelect(0));

  const loading = useSelector((e: StoreInterface) => e.lists.listLoading);

  const handleSubmit = (values: ManageListFormData) => {
    dispatch(
      manageList(
        info.product_list_id,
        convertManageListFormDataToRequest(values),
        onCancelClick
      )
    );
  };

  const formik = useFormik({
    initialValues: {
      listName: info.name || "",
      description: info.description || "",
      recipientName: info.recipient_name || "",
      email: info.recipient_email || "",
      isPurchase: false,
      isDefault: false,
      shippingAddress: {
        value: null,
        viewValue: "None",
      },
      month: info.birthday
        ? monthItems[new Date(Number(info.birthday)).getMonth() - 1]
        : monthItems[0],
      day: info.birthday
        ? dayItems[new Date(Number(info.birthday)).getDate()]
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
        <Grid
          container
          justifyContent="space-between"
          className="d-flex justify-content-between align-center"
          alignItems="center"
        >
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
        </Grid>
        <FormSelect
          items={getValuesForSelect(
            Store.getState().addresses.addressesList,
            "address_id",
            "full_name"
          )}
          name={"shippingAddress"}
          label={"Shipping Address"}
          onClick={(value) => formik.setFieldValue("Shipping Address", value)}
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

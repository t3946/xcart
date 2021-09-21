import React from "react";
import { useFormik } from "formik";
import * as Yup from "yup";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { Button } from "@material-ui/core";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { priorityProductSelectValuesConst } from "@client/modules/account/ts/consts/priority-product-select-values.const";
import { editCommentInProduct } from "@client/jsx/redux/actions/account-actions/ListsActions";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";

export const EditComment = ({ onCloseClick, listId, productId, info }) => {
  const dispatch = useDispatch();

  const handleSubmit = (values) => {
    dispatch(
      editCommentInProduct(
        listId,
        productId,
        { ...values, priority: values.priority.value },
        onCloseClick
      )
    );
  };

  const ideaImg = "/static/frontend/images/icons/account/idea-logo.svg";

  const formik = useFormik({
    initialValues: {
      comment: info?.comment || "",
      priority:
        priorityProductSelectValuesConst.find(
          (e) => e.value === info.priority
        ) || priorityProductSelectValuesConst[0],
      needs: info?.needs || 0,
      has: info?.needs || 0,
    },
    validationSchema: Yup.object().shape({
      comment: Yup.string()
        .required("Required field")
        .max(250, "Remaining: 250 characters"),
      needs: Yup.number().required("Required field").min(0, "Min value - 0"),
      has: Yup.number().required("Required field").min(0, "Min value - 0"),
    }),
    onSubmit: handleSubmit,
  });

  const isLoading = useSelector((e: AccountStore) => e.lists.listLoading);

  return (
    <div>
      <form onSubmit={formik.handleSubmit} encType="multipart/form-data">
        <div className="top-content">
          <FormInput
            name={"comment"}
            classes={{
              input: ["list-input-edit-idea", "text-area-input-container"],
              textArea: ["edit-comment-text-area-input"],
              group: ["text-area-group"],
            }}
            handleChange={formik.handleChange}
            errorMessage={formik.errors.comment}
            handleBlur={formik.handleBlur}
            touched={formik.touched.comment}
            label={"Comment"}
            value={formik.values.comment}
            inputType={"text-area"}
          />
          <div className="edit-comment-img-block">
            <img
              src={info.image || ideaImg}
              className="product-image edit-comment-img"
            />
            <div className="edit-comment-name">
              {info.product?.name || info.product?.product}
            </div>
          </div>
        </div>
        <div className="edit-comment-inputs-container">
          <FormSelect
            items={priorityProductSelectValuesConst}
            name={""}
            label={"Priority"}
            onClick={(value) => formik.setFieldValue("priority", value)}
            value={formik.values.priority}
            id="form-select-list-product"
            classes={{
              group: ["edit-comment-select-field-container"],
            }}
          />
          <div className="edit-idea-text-inputs">
            <FormInput
              name={"needs"}
              classes={{
                input: ["list-input-edit-idea", "full-width"],
                group: ["edit-comment-input-text-field-container"],
              }}
              handleChange={formik.handleChange}
              errorMessage={formik.errors.needs}
              handleBlur={formik.handleBlur}
              touched={formik.touched.needs}
              label={"Needs"}
              value={formik.values.needs}
              type={"number"}
            />
            <FormInput
              name={"has"}
              classes={{
                input: ["list-input-edit-idea", "full-width"],
                group: ["edit-comment-input-text-field-container"],
              }}
              handleChange={formik.handleChange}
              errorMessage={formik.errors.has}
              handleBlur={formik.handleBlur}
              touched={formik.touched.has}
              label={"Has"}
              value={formik.values.has}
              type={"number"}
            />
          </div>
        </div>
        <SubmitCancelButtonsGroup
          submitText="Confirm"
          cancelText="Cancel"
          onCancel={onCloseClick}
          disabled={isLoading}
          groupAdvancedClasses={"edit-idea-info-btns"}
        />
      </form>
    </div>
  );
};

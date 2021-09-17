import React, { useEffect, useState } from "react";
import { Button } from "@material-ui/core";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { editIdeaName } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";

export const EditIdea = ({ info, listId }) => {
  const [isEdit, setIsEdit] = useState(false);

  const isLoading = useSelector((e: AccountStore) => e.lists.listLoading);

  const dispatch = useDispatch();

  const onSaveEdit = () => {
    dispatch(
      editIdeaName(listId, info.product_id, formik.values.name, () =>
        onSetEdit(true)
      )
    );
  };

  const formik = useFormik({
    initialValues: { name: info.product.name },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: onSaveEdit,
  });

  const onSetEdit = (save?: boolean) => {
    setIsEdit(!isEdit);
    if (isEdit && !save) {
      formik.values.name = info.product.name;
    }
  };

  return (
    <div>
      {isEdit ? (
        <form
          className={"d-flex"}
          onSubmit={formik.handleSubmit}
          encType="multipart/form-data"
        >
          <FormInput
            name={"name"}
            classes={{
              input: ["list-input-edit-idea"],
            }}
            handleChange={formik.handleChange}
            errorMessage={formik.errors.name}
            handleBlur={formik.handleBlur}
            touched={formik.touched.name}
            value={formik.values.name}
          />
          <div className="edit-idea-btns">
            <Button
              type={"submit"}
              disabled={isLoading}
              className="account-submit-btn auto-width-button cancel-edit-card-btn"
            >
              Confirm
            </Button>
            <Button
              onClick={() => onSetEdit()}
              disabled={isLoading}
              className="account-submit-btn account-submit-btn-outline auto-width-button "
            >
              Cancel
            </Button>
          </div>
        </form>
      ) : (
        <div className="d-flex align-items-center">
          <div className="product-list-idea-name">{info.product.name}</div>
          <span onClick={() => onSetEdit()} className="add-comment-text">
            Edit idea
          </span>
        </div>
      )}
    </div>
  );
};

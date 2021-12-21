import React, { useState } from "react";
import { FormInput } from "@modules/account/components/shared/FormInput";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { editIdeaName } from "@redux/actions/account-actions/ListsActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import { ListItem } from "@modules/account/ts/types/list.type";

interface EditIdeaProps {
  info: ListItem;
  listId: string;
  openMenuDialog: () => void;
  edit: boolean;
}

export const EditIdea: React.FC<EditIdeaProps> = ({
  info,
  listId,
  openMenuDialog,
  edit,
}) => {
  const [isEdit, setIsEdit] = useState(false);

  const isLoading = useSelector((e: StoreInterface) => e.lists.listLoading);

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
    <React.Fragment>
      {isEdit ? (
        <form
          className={"edit-idea-form"}
          onSubmit={formik.handleSubmit}
          encType="multipart/form-data"
        >
          <FormInput
            name={"name"}
            classes={{
              input: ["list-input-edit-idea"],
              group: ["edit-idea-form-input"],
            }}
            handleChange={formik.handleChange}
            errorMessage={formik.errors.name}
            handleBlur={formik.handleBlur}
            touched={formik.touched.name}
            value={formik.values.name}
          />
          <div className="edit-idea-btns">
            <button
              type={"submit"}
              disabled={isLoading}
              className="form-button account-submit-btn auto-width-button confirm-edit-idea-btn"
            >
              Confirm
            </button>
            <button
              onClick={() => onSetEdit()}
              disabled={isLoading}
              className="form-button account-submit-btn account-submit-btn-outline auto-width-button "
            >
              Cancel
            </button>
          </div>
        </form>
      ) : (
        <div className="edit-idea-text-container">
          <div className="product-list-idea-name">{info.product.name}</div>
          {edit && (
            <React.Fragment>
              <span onClick={() => onSetEdit()} className="add-comment-text">
                Edit idea
              </span>
              <img
                onClick={openMenuDialog}
                className="edit-idea-ellipsis"
                src={"/static/frontend/dist/images/icons/account/ellipsis.svg"}
              />
            </React.Fragment>
          )}
        </div>
      )}
    </React.Fragment>
  );
};

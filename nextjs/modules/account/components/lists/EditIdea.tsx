import React, { useState } from "react";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useDispatch } from "react-redux";
import { editIdeaName } from "@redux/actions/account-actions/ListsActions";
import { ListItem } from "@modules/account/ts/types/list.type";
import cn from "classnames";

import Styles from "@modules/account/components/lists/EditIdea.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface EditIdeaProps {
  info: ListItem;
  listId: number;
  openMenuDialog: () => void;
  edit: boolean;
}

export const EditIdea: React.FC<EditIdeaProps> = ({
  info,
  listId,
  openMenuDialog,
  edit,
}) => {
  const inputRef = React.useRef<HTMLInputElement>();
  const [isEdit, setIsEdit] = useState(false);

  const isLoading = useSelectorAccount((state) => state.lists.loading);

  const dispatch = useDispatch();

  const onSaveEdit = () => {
    dispatch(
      editIdeaName(listId, info.productId, formik.values.name, () => {
        formik.setTouched({});
        onSetEdit(true);
      })
    );
  };

  const formik = useFormik({
    initialValues: { name: info.product.name },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: onSaveEdit,
  });

  React.useEffect(() => {
    isEdit && inputRef.current?.focus();
  }, [isEdit]);

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
          <div className="mb-10 mb-lg-20 me-lg-10">
            <Input
              name="name"
              ref={inputRef}
              value={formik.values.name}
              onChange={formik.handleChange}
              isValid={!!formik.touched.name && !formik.errors.name}
              isInvalid={!!formik.touched.name && !!formik.errors.name}
            />
            <Feedback type="invalid" className={"mt-0 position-absolute"}>
              {!!formik.touched.name && formik.errors.name}
            </Feedback>
          </div>
          <div className="edit-idea-btns">
            <button
              type={"submit"}
              disabled={isLoading}
              className={cn(
                "form-button",
                "account-submit-btn",
                "auto-width-button",
                "confirm-edit-idea-btn",
                Styles.button
              )}
            >
              Save
            </button>
            <button
              onClick={() => onSetEdit()}
              disabled={isLoading}
              className={cn(
                "form-button",
                "account-submit-btn",
                "account-submit-btn-outline",
                "auto-width-button",
                Styles.button
              )}
            >
              Cancel
            </button>
          </div>
        </form>
      ) : (
        <div className="edit-idea-text-container">
          <div className={cn(Styles.IdeaName, "product-list-idea-name")}>
            {info.product.name}
          </div>
          {edit && (
            <React.Fragment>
              <span
                onClick={() => onSetEdit()}
                className={cn("add-comment-text", Styles.editIdea)}
              >
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

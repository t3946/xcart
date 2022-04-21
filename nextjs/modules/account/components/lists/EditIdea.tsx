import React, { useState } from "react";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useDispatch } from "react-redux";
import { editIdea } from "@redux/actions/account-actions/ListsActions";
import { ListItem } from "@modules/account/ts/types/list.type";
import cn from "classnames";
import Button, { ETheme } from "@modules/ui/forms/Button";

import Styles from "@modules/account/components/lists/EditIdea.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface EditIdeaProps {
  listItem: ListItem;
  listId: number;
  openMenuDialog: () => void;
  edit: boolean;
}

export const EditIdea: React.FC<EditIdeaProps> = ({
  listItem,
  openMenuDialog,
  edit,
}) => {
  const inputRef = React.useRef<HTMLInputElement>();
  const [isEdit, setIsEdit] = useState(false);
  const isLoading = useSelectorAccount((state) => state.lists.loading);
  const dispatch = useDispatch();

  function onSaveEdit() {
    dispatch(
      editIdea({
        data: {
          name: formik.values.name,
          list_idea_id: listItem.list_idea_id,
        },
      })
    );

    formik.setTouched({});
    onCancel(true);
  }

  const formik = useFormik({
    initialValues: { name: listItem.idea.name },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: onSaveEdit,
  });

  React.useEffect(() => {
    isEdit && inputRef.current?.focus();
  }, [isEdit]);

  const onCancel = (save?: boolean) => {
    setIsEdit(!isEdit);

    if (isEdit && !save) {
      formik.values.name = listItem.product.name;
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
            <Button
              type={"submit"}
              disabled={isLoading}
              className={cn("w-auto", Styles.button, "me-2")}
            >
              Save
            </Button>
            <Button
              onClick={onCancel}
              disabled={isLoading}
              className={cn("w-auto", Styles.button)}
              theme={ETheme.outlined}
            >
              Cancel
            </Button>
          </div>
        </form>
      ) : (
        <div className="edit-idea-text-container">
          <div className={cn(Styles.IdeaName, "product-list-idea-name")}>
            {listItem.idea.name}
          </div>
          {edit && (
            <React.Fragment>
              <span
                onClick={onCancel}
                className={cn("add-comment-text", Styles.editIdea)}
              >
                Edit idea
              </span>

              <span
                className={"py-10 px-1 cursor-pointer d-lg-none"}
                onClick={openMenuDialog}
              >
                <img
                  src={
                    "/static/frontend/dist/images/icons/account/ellipsis.svg"
                  }
                />
              </span>
            </React.Fragment>
          )}
        </div>
      )}
    </React.Fragment>
  );
};

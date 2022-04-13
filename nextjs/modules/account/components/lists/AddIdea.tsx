import React, { useEffect, useRef } from "react";
import Input from "@modules/ui/forms/Input";
import Label from "@modules/ui/forms/Label";
import Feedback from "@modules/ui/forms/Feedback";
import { createIdea } from "@redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import useSnackbar from "@modules/account/hooks/useSnackbar";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { ListItem } from "@modules/account/ts/types/list.type";

interface AddIdeaProps {
  onCancelBtnClick: () => void;
  listHash: string;
}

export const AddIdea: React.FC<AddIdeaProps> = ({
  onCancelBtnClick,
  listHash,
}) => {
  useEffect(() => {
    ref.current.focus();
  }, []);

  const ref = useRef<HTMLInputElement>();
  const dispatch = useDispatch();

  const snackbar = useSnackbar();

  const { loading, lists } = useSelector((state) => state.lists);
  const listEdit = lists.find((e) => e.cacheUrl === listHash);

  const handleSubmit = () => {
    if (!formik.values.name.trim()) {
      formik.setErrors({ name: "Required field" });
      return;
    }
    if (formik.values.name.length >= 50) {
      formik.setErrors({ name: "Maximum length 50 characters" });
      return;
    }

    dispatch(
      createIdea({
        data: {
          product_list_id: listEdit.product_list_id,
          name: formik.values.name,
        },
        success: onAddingEnd,
      })
    );
  };

  function onAddingEnd() {
    onCancelBtnClick();
    snackbar.show(`"${formik.values.name}" idea added successfully`);
  }

  const formik = useFormik({
    initialValues: { name: "" },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: handleSubmit,
  });
  return (
    <div>
      <form onSubmit={formik.handleSubmit} encType="multipart/form-data">
        <div className={"mb-lg-20"}>
          <Label>Idea name</Label>
          <div>
            <Input
              ref={ref}
              name="name"
              value={formik.values.name}
              onChange={formik.handleChange}
              isValid={!!formik.touched.name && !formik.errors.name}
              isInvalid={!!formik.touched.name && !!formik.errors.name}
            />
            <Feedback type="invalid" className={"mt-0 position-absolute"}>
              {!!formik.touched.name && formik.errors.name}
            </Feedback>
          </div>
        </div>

        <p className={"my-4"}>Save an idea. Shop for it later.</p>
        <SubmitCancelButtonsGroup
          submitText="save"
          cancelText="Cancel"
          onCancel={onCancelBtnClick}
          groupAdvancedClasses={"manage-list-btns"}
          disabled={loading}
        />
      </form>
    </div>
  );
};

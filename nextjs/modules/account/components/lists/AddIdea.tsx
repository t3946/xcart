import React, { useContext, useEffect, useRef } from "react";
import { FormInput } from "@modules/account/components/shared/FormInput";
import { addProduct } from "@redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import Store from "@redux/stores/Store";
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

  const { showSnackbar } = useContext(SnackbarContext);

  const listId = Store.getState().lists.lists.find(
    (e) => e.cache_url === listHash
  ).product_list_id;

  const handleSubmit = () => {
    if (!formik.values.name.trim()) {
      formik.setErrors({ name: "Required field" });
      return;
    }
    if (formik.values.name.length >= 50) {
      formik.setErrors({ name: "Maximum length 50 characters" });
      return;
    }
    dispatch(addProduct(listId, null, formik.values.name, onAddingEnd));
  };

  const listLoading = useSelector((e: StoreInterface) => e.lists.listLoading);

  const onAddingEnd = (idea: ListItem) => {
    onCancelBtnClick();
    showSnackbar({
      header: "Success",
      message: `${formik.values.name} idea added successfully`,
      theme: "success",
    });
  };

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
        <FormInput
          name={"name"}
          classes={{
            input: "list-input",
          }}
          label={"Idea name"}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.name}
          handleBlur={formik.handleBlur}
          touched={formik.touched.name}
          value={formik.values.name}
          inputRef={ref}
        />
        <p>Save an idea. Shop for it later.</p>
        <SubmitCancelButtonsGroup
          submitText="Confirm"
          cancelText="Cancel"
          onCancel={onCancelBtnClick}
          groupAdvancedClasses={"manage-list-btns"}
          disabled={listLoading}
        />
      </form>
    </div>
  );
};

import React, { useContext } from "react";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import {
  addProduct,
  setLists,
} from "@client/jsx/redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";

interface AddIdeaProps {
  onCancelBtnClick: () => void;
  listHash: string;
}

export const AddIdea: React.FC<AddIdeaProps> = ({
  onCancelBtnClick,
  listHash,
}) => {
  const dispatch = useDispatch();

  const { showSnackbar } = useContext(SnackbarContext);

  const listId = accountStore
    .getState()
    .lists.lists.find((e) => e.cache_url === listHash).product_list_id;

  const handleSubmit = () => {
    if (!formik.values.name.trim()) {
      formik.setErrors({ name: "Required field" });
      return;
    }
    dispatch(addProduct(listId, null, formik.values.name, onAddingEnd));
  };

  const listLoading = useSelector((e: AccountStore) => e.lists.listLoading);

  const onAddingEnd = (idea: any) => {
    dispatch(
      setLists(
        accountStore.getState().lists.lists.map((e) => {
          if (e.product_list_id === listId) {
            return {
              ...e,
              products: e.products.concat(idea),
            };
          }
          return e;
        })
      )
    );
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

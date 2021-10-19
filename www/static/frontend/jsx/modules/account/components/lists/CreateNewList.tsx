import React, { useContext } from "react";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { Tooltip } from "@client/modules/account/components/shared/Tooltip";
import { useDispatch, useSelector } from "react-redux";
import { createList } from "../../../../redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { useHistory } from "react-router";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";

interface CreateNewListProps {
  onCancelBtnClick: () => void;
  productId?: string;
  onCreateList?: (listId) => void;
  actionType?: "list" | "product";
}

export const CreateNewList: React.FC<CreateNewListProps> = ({
  onCancelBtnClick,
  productId,
  onCreateList,
  actionType,
}) => {
  const dispatch = useDispatch();

  const { showSnackbar } = useContext(SnackbarContext);

  const history = useHistory();

  const listLoading = useSelector((e: StoreInterface) => e.lists.listLoading);

  const handleSubmit = () => {
    if (!formik.values.name.trim()) {
      formik.setErrors({ name: "Required field" });
      return;
    }
    dispatch(createList(formik.values.name, onAddingEnd, actionType));
  };

  const formik = useFormik({
    initialValues: { name: "" },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: handleSubmit,
  });

  const onAddingEnd = (param: any) => {
    if (productId) {
      onCreateList(param);
      return;
    }
    showSnackbar({
      header: "Success",
      message: `${formik.values.name} list added successfully`,
      theme: "success",
    });
    onCancelBtnClick();
    history.push(`/account/your-lists/${param.cache_url}`);
  };
  return (
    <div>
      <form onSubmit={formik.handleSubmit} encType="multipart/form-data">
        <FormInput
          name={"name"}
          classes={{
            input: "list-input",
          }}
          label={"List Name"}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.name}
          handleBlur={formik.handleBlur}
          touched={formik.touched.name}
          value={formik.values.name}
        />

        <p>
          Use lists to save items for later. All lists are private unless you
          share them with others.
        </p>
        <Tooltip
          target={<div className="create-list-learn-more">Learn more</div>}
          content={
            <div className="create-list-tooltip-text">
              Lists replaces wish lists and shopping lists, creating one place
              for all your lists. You can also share your lists with others by
              inviting them after you've created a list.
            </div>
          }
        />
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

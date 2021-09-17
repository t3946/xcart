import React, { useContext } from "react";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { Button } from "@material-ui/core";
import { Tooltip } from "@client/modules/account/components/shared/Tooltip";
import { useDispatch, useSelector } from "react-redux";
import { createList } from "../../../../redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { useHistory } from "react-router";

interface CreateNewListProps {
  onCancelBtnClick: () => void;
}

export const CreateNewList: React.FC<CreateNewListProps> = ({
  onCancelBtnClick,
}) => {
  const dispatch = useDispatch();

  const { showSnackbar } = useContext(SnackbarContext);

  const history = useHistory();

  const listLoading = useSelector((e: AccountStore) => e.lists.listLoading);

  const handleSubmit = () => {
    dispatch(createList(formik.values.name, onAddingEnd));
  };

  const formik = useFormik({
    initialValues: { name: "" },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: handleSubmit,
  });

  const onAddingEnd = (hash: string) => {
    onCancelBtnClick();
    showSnackbar({
      header: "Success",
      message: `${formik.values.name} list added successfully`,
      theme: "success",
    });
    history.push(`/account/your-lists/${hash}`);
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
        <div className="list-dialog-btns">
          <Button
            disabled={listLoading}
            type={"submit"}
            className="account-submit-btn auto-width-button cancel-edit-card-btn"
          >
            Confirm
          </Button>
          <Button
            disabled={listLoading}
            className="account-submit-btn account-submit-btn-outline auto-width-button "
          >
            Cancel
          </Button>
        </div>
      </form>
    </div>
  );
};

import React from "react";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { Button } from "@material-ui/core";
import { Tooltip } from "@client/modules/account/components/shared/Tooltip";
import { useDispatch } from "react-redux";
import { createList } from "../../../../redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";

export const CreateNewList = ({ onCancelBtnClick }) => {
  const dispatch = useDispatch();

  const formik = useFormik({
    initialValues: { name: "" },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: null,
  });

  const handleSubmit = () => {
    if (!formik.values.name) {
      return;
    }
    dispatch(createList(formik.values.name, onCancelBtnClick));
  };

  return (
    <div className="list-dialog-container">
      <form className="your-order-form" encType="multipart/form-data">
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
      </form>

      <p>
        Use lists to save items for later. All lists are private unless you
        share them with others.
      </p>
      <Tooltip
        target={<div className="create-list-learn-more">Learn more</div>}
        content={
          <div className="create-list-tooltip-text">
            Lists replaces wish lists and shopping lists, creating one place for
            all your lists. You can also share your lists with others by
            inviting them after you've created a list.
          </div>
        }
      />

      <div className="list-dialog-btns">
        <Button
          type={"submit"}
          className="account-submit-btn auto-width-button cancel-edit-card-btn"
          onClick={handleSubmit}
        >
          Confirm
        </Button>
        <Button
          type={"submit"}
          className="account-submit-btn account-submit-btn-outline auto-width-button "
          onClick={onCancelBtnClick}
        >
          Cancel
        </Button>
      </div>
    </div>
  );
};

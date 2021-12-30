import React, { useContext, useEffect, useRef, useState } from "react";
import { FormInput } from "@modules/account/components/shared/FormInput";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useDispatch, useSelector } from "react-redux";
import { createList } from "@redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useRouter } from "next/router";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import Store from "@redux/stores/Store";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { useDialog } from "@modules/account/hooks/useDialog";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

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
  useEffect(() => {
    ref.current.focus();
  }, []);
  const dispatch = useDispatch();

  const learnMoreDialog = useDialog();

  const ref = useRef<HTMLInputElement>();

  const { showSnackbar } = useContext(SnackbarContext);

  const [isViewingInfo, setIsViewingInfo] = useState(false);

  const router = useRouter();

  const listLoading = useSelector((e: StoreInterface) => e.lists.listLoading);

  const handleSubmit = () => {
    if (!formik.values.name.trim()) {
      formik.setErrors({ name: "Required field" });
      return;
    }
    if (formik.values.name.length >= 50) {
      formik.setErrors({ name: "Maximum length 50 characters" });
      return;
    }
    dispatch(createList(formik.values.name, onAddingEnd, actionType));
  };

  const formik = useFormik({
    initialValues: {
      name: `Shopping List ${Store.getState().lists.lists.length + 2}`,
    },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: handleSubmit,
  });

  const breakpoint = useBreakpoint();

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
    router.push(`/shopping-lists/${param.cache_url}`);
  };

  return (
    <div>
      {isViewingInfo ? (
        <div>
          <div className="create-list-tooltip-text">
            Lists replaces wish lists and shopping lists, creating one place for
            all your lists. You can also share your lists with others by
            inviting them after you've created a list.
          </div>
          <button
            onClick={() => setIsViewingInfo(false)}
            className={"form-button"}
          >
            Back
          </button>
        </div>
      ) : (
        <form onSubmit={formik.handleSubmit} encType="multipart/form-data">
          <div className="d-flex flex-dir-column">
            <Label>List Name</Label>
            <Input
              ref={ref}
              name={"name"}
              onChange={formik.handleChange}
              value={formik.values.name}
              isInvalid={!!formik.errors.name}
            />
            <Feedback
              className="form-input-caption"
              type={formik.errors.name ? "invalid" : "valid"}
            >
              {formik.errors.name}
            </Feedback>
          </div>
          <p>
            Use lists to save items for later. All lists are private unless you
            share them with others.
          </p>
          <div
            onClick={() =>
              breakpoint({
                xs: learnMoreDialog.handleClickOpen,
                md: () => setIsViewingInfo(true),
              })
            }
            className="create-list-learn-more"
          >
            Learn more
          </div>
          <SubmitCancelButtonsGroup
            submitText="Confirm"
            cancelText="Cancel"
            onCancel={onCancelBtnClick}
            groupAdvancedClasses={"manage-list-btns"}
            disabled={listLoading}
          />
        </form>
      )}
      <BootstrapDialogHOC
        show={learnMoreDialog.open}
        title={"Learn more"}
        onClose={learnMoreDialog.handleClose}
      >
        <div className="create-list-tooltip-text">
          Lists replaces wish lists and shopping lists, creating one place for
          all your lists. You can also share your lists with others by inviting
          them after you've created a list.
        </div>
      </BootstrapDialogHOC>
    </div>
  );
};

import React, { useEffect, useRef, useState } from "react";
import { useSnackbar } from "@modules/account/hooks/useSnackbar";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useDispatch } from "react-redux";
import { createList } from "@redux/actions/account-actions/ListsActions";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useRouter } from "next/router";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { useDialog } from "@modules/account/hooks/useDialog";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface CreateNewList {
  onCancelBtnClick: () => void;
  onCreateList?: (listId) => void;
  actionType?: "list" | "product";
}

export const CreateNewList: React.FC<CreateNewList> = ({
  onCancelBtnClick,
  onCreateList,
  actionType,
}) => {
  const dispatch = useDispatch();

  const learnMoreDialog = useDialog();

  const ref = useRef<HTMLInputElement>();
  useEffect(() => {
    ref.current.focus();
  }, []);

  const snackbar = useSnackbar();

  const [isViewingInfo, setIsViewingInfo] = useState(false);

  const router = useRouter();

  const { loading } = useSelectorAccount((e) => e.lists);
  const countList = useSelectorAccount((e) => e.lists.lists?.length);

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
      name: `Shopping List ${countList ? countList + 1 : 1}`,
    },
    validationSchema: Yup.object().shape({
      name: Yup.string().required("Required field"),
    }),
    onSubmit: handleSubmit,
  });

  const breakpoint = useBreakpoint();

  const onAddingEnd = (cache: string) => {
    snackbar.show(`${formik.values.name} list added successfully`);
    onCancelBtnClick();
    router.push(`/shopping-lists/${cache}`);
  };

  return (
    <div>
      {isViewingInfo ? (
        <div>
          <div className="create-list-tooltip-text">
            Use Shopping lists to save items for later. You can also share your
            lists with others by inviting them to view or edit your lists.
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
            <Label className={"mb-10"}>List Name</Label>
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
            disabled={loading}
          />
        </form>
      )}
      <BootstrapDialogHOC
        show={learnMoreDialog.open}
        title={"Learn more"}
        onClose={learnMoreDialog.handleClose}
      >
        <div className="create-list-tooltip-text">
          Use Shopping lists to save items for later. You can also share your
          lists with others by inviting them to view or edit your lists.
        </div>
      </BootstrapDialogHOC>
    </div>
  );
};

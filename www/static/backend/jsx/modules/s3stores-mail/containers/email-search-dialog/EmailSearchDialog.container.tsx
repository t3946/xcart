import React, { useContext } from "react";
import { EmailSearchDialogContext } from "@s3stores-mail/contexts/email-search-dialog-context/EmailSearchDialog.context";
import { EmailSearchForm } from "@s3stores-mail/components/ordinary";
import { useHistory } from "react-router-dom";
import { useDispatch } from "react-redux";
import { editSearchOptions } from "@redux/actions";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";

export const EmailSearchDialogContainer: React.FC = () => {
  const history = useHistory();

  const dispatch = useDispatch();

  const { handleClose } = useContext(EmailDialogContext);

  const editSearchValues = (values) => {
    dispatch(editSearchOptions(values));
    handleClose();
    history.push(`/admin/forms/email-dashboard/page/${1}`);
  };
  return (
    <EmailSearchDialogContext.Provider
      value={{
        editSearchValues,
      }}
    >
      <EmailSearchForm />
    </EmailSearchDialogContext.Provider>
  );
};

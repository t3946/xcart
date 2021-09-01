import React, { useContext } from "react";
import { EmailSearchDialogContext } from "@s3stores-mail/contexts/email-search-dialog-context/EmailSearchDialog.context";
import { EmailSearchForm } from "@s3stores-mail/components/ordinary";
import { useHistory } from "react-router-dom";
import { useDispatch } from "react-redux";
import { editSearchOptions, getPage, setLoading } from "@redux/actions";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import { emailStore } from "../../../../redux/stores";
import { EmailRouterContext } from "../../contexts/email-router-context/EmailRouter.context";

export const EmailSearchDialogContainer: React.FC = () => {
  const history = useHistory();

  const dispatch = useDispatch();

  const { handleClose } = useContext(EmailDialogContext);

  const routers = useContext(EmailRouterContext);

  const editSearchValues = (values) => {
    dispatch(
      editSearchOptions({
        ...values,
        distributorId: emailStore.getState().searchOptions.distributorId,
      })
    );
    handleClose();
    history.push(`${routers.listRouter}${1}`);
    dispatch(setLoading());
    dispatch(getPage(Number(1), values));
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

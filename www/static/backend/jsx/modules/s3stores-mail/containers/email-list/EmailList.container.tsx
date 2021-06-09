import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { SceletonEmailList } from "@s3stores-mail/components/ordinary/sceleton-email-list/SceletonEmailList";
import { useHistory } from "react-router-dom";
import { editActions, editCheckedItems, editFavorites } from "@redux/actions";
import { EmailLIstContext } from "@s3stores-mail/contexts/email-list-context/EmailLIst.context";
import { EmailList } from "@s3stores-mail/components/ordinary/email-list/EmailList";
import { isFavoriteItemsTrue } from "@s3stores-mail/utils/edit-fields-on-email";

export const EmailListContainer: React.FC = () => {
  const history = useHistory();

  const dispatch = useDispatch();

  const loading = useSelector((state: StoreDto) => state.loading);

  const email = useSelector((state: StoreDto) => {
    return state.emailInfo;
  });

  const handleItemClick = (id) => {
    history.push(`/admin/forms/email-dashboard/email-info/${id}`);
  };

  const editFavorite = (e, id) => {
    e.stopPropagation();
    dispatch(editFavorites([id], isFavoriteItemsTrue(emails, [id])));
  };

  const emails = useSelector((state: StoreDto) => {
    return state.items;
  });

  const editAction = (e, id) => {
    e.stopPropagation();
    dispatch(editActions([id]));
  };

  const editChecked = (e, index) => {
    e.stopPropagation();
    if (e.shiftKey) {
      dispatch(editCheckedItems(index, true));
      return;
    }
    dispatch(editCheckedItems(index, false));
  };

  const emailContext = {
    handleItemClick,
    editFavorite,
    editAction,
    editChecked,
  };
  return (
    <EmailLIstContext.Provider value={emailContext}>
      {loading ? (
        <SceletonEmailList itemsCount={20} />
      ) : (
        <EmailList emails={emails} />
      )}
    </EmailLIstContext.Provider>
  );
};

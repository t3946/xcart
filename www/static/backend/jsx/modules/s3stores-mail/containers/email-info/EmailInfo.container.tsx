import React, { useContext } from "react";
import { useParams } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { SelectItemDto, StoreDto } from "@s3stores-mail/ts/types";
import { EmailInfoHeader } from "@s3stores-mail/components/ordinary/email-info-header/EmailInfoHeader";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import {
  editActions,
  editFavorites,
  editSendData,
  setSendTemplate,
  setSendTemplateType,
  setViewed,
} from "@redux/actions";
import { selectSendFirstItems } from "@s3stores-mail/ts/consts";
import { EmailInfoBody } from "@s3stores-mail/components/ordinary/email-info-body/EmailInfoBody";

export const EmailInfoContainer: React.FC = () => {
  const { id }: { id: string } = useParams();

  const dialog = useContext(EmailDialogContext);
  const dispatch = useDispatch();

  const emailInfo = useSelector((state: StoreDto) => {
    return state.items.filter((e) => e.item.id === id)[0];
  });

  if (!emailInfo.item.viewed) {
    dispatch(setViewed(emailInfo.item.id));
  }

  const handleReply = () => {
    dispatch(editSendData(emailInfo.item.body, "replyText"));
    dialog.handleClickOpen();
  };

  const handleForward = () => {
    dialog.handleClickOpen();
  };

  const handleClick = (item: SelectItemDto) => {
    dispatch(setSendTemplateType(selectSendFirstItems[1]));
    dispatch(setSendTemplate(item));
    dialog.handleClickOpen();
  };

  const editFavorite = (id) => {
    dispatch(editFavorites([id]));
  };

  const editAction = (id) => {
    dispatch(editActions([id]));
  };

  const infoValue = {
    editAction,
    editFavorite,
    handleClick,
    handleForward,
    handleReply,
    emailInfo: emailInfo.item,
  };
  return (
    <EmailInfoContext.Provider value={infoValue}>
      <EmailInfoHeader info={emailInfo.item} />
      <EmailInfoBody emailInfo={emailInfo} />
    </EmailInfoContext.Provider>
  );
};

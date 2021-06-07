import React, { useContext, useEffect, useRef } from "react";
import { useParams } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { SelectItemDto, StoreDto } from "@s3stores-mail/ts/types";
import { EmailInfoHeader } from "@s3stores-mail/components/ordinary/email-info-header/EmailInfoHeader";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import {
  addRecipient,
  editActions,
  editFavorites,
  editSendData,
  setSendTemplate,
  setSendTemplateType,
  setViewed,
} from "@redux/actions";
import { EmailInfoBody } from "@s3stores-mail/components/ordinary/email-info-body/EmailInfoBody";
import {
  isFavoriteItemsTrue,
  isViewedItemsTrue,
} from "@s3stores-mail/utils/edit-fields-on-email";
import { editEmailAddress } from "@s3stores-mail/utils/edit-email-address";
import { addPrefixToSubject } from "@s3stores-mail/utils/add-prefix-to-subject";

export const EmailInfoContainer: React.FC = () => {
  useEffect(() => {
    if (!email.item.viewed) {
      editViewed();
    }
  }, []);
  const { id }: { id: string } = useParams();

  const dialog = useContext(EmailDialogContext);
  const dispatch = useDispatch();

  const emails = useSelector((state: StoreDto) => {
    return state.items;
  });

  const componentRef = useRef();

  const templates = useSelector((state: StoreDto) => state.templates);

  const email = emails.filter((e) => e.item.id === id)[0];

  email.item.body = replaceCidToImage(email.item.body, email.item.attachment);

  const editViewed = () => {
    dispatch(
      setViewed([email.item.id], isViewedItemsTrue(emails, [email.item.id]))
    );
  };

  const editFavorite = () => {
    dispatch(
      editFavorites(
        [email.item.id],
        isFavoriteItemsTrue(emails, [email.item.id])
      )
    );
  };

  function replaceCidToImage(body: string, attachments: any[]) {
    attachments.forEach((e) => {
      if (e.cid) {
        body = body.replace(`cid:${e.cid}`, `/${e.attachment}`);
      }
    });

    return body;
  }

  const sendMessage = () => {
    dispatch(editSendData(email.item.body, "replyText"));
    dialog.handleClickOpen();
  };

  const handleForward = () => {
    sendMessage();
    dispatch(
      editSendData(
        addPrefixToSubject("Fwd:", "Re:", email.item.subject),
        "subject"
      )
    );
  };

  const handleReply = () => {
    dispatch(addRecipient(editEmailAddress(email.item.from_address)));
    dispatch(
      editSendData(
        addPrefixToSubject("Re:", "Fwd:", email.item.subject),
        "subject"
      )
    );
    sendMessage();
  };

  const handleClick = (item: SelectItemDto) => {
    dispatch(setSendTemplateType(templates[0][0]));
    dispatch(setSendTemplate(item));
    dialog.handleClickOpen();
  };

  const editAction = () => {
    dispatch(editActions([email.item.id]));
  };

  const infoValue = {
    editAction,
    editFavorite,
    handleClick,
    handleForward: handleForward,
    handleReply: handleReply,
    editViewed,
    templates: templates[0],
    emailInfo: email.item,
    componentRef,
  };
  return (
    <EmailInfoContext.Provider value={infoValue}>
      <EmailInfoHeader info={email.item} />
      <EmailInfoBody thisRef={componentRef} emailInfo={email} />
    </EmailInfoContext.Provider>
  );
};

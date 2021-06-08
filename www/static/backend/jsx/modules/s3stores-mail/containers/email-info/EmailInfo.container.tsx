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
import { SceletonEmailInfo } from "../../components/simple/sceleton-email-info/SceletonEmailInfo";

export const EmailInfoContainer: React.FC = () => {
  const email = useSelector((state: StoreDto) => {
    return state.emailInfo;
  });

  const emails = useSelector((state: StoreDto) => {
    return state.items;
  });

  const loading = useSelector((state: StoreDto) => state.loading);

  useEffect(() => {
    if (Boolean(email?.id) && !email.viewed) {
      editViewed();
    }
  }, [email]);

  const dialog = useContext(EmailDialogContext);
  const dispatch = useDispatch();

  const componentRef = useRef();

  const templates = useSelector((state: StoreDto) => state.templates);

  email.body = replaceCidToImage(email.body, email.attachment);

  const editViewed = () => {
    dispatch(setViewed([email.id], isViewedItemsTrue(emails, [email.id])));
  };

  const editFavorite = () => {
    dispatch(
      editFavorites([email.id], isFavoriteItemsTrue(emails, [email.id]))
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
    dispatch(editSendData(email.body, "replyText"));
    dialog.handleClickOpen();
  };

  const handleForward = () => {
    dispatch(
      editSendData(addPrefixToSubject("Fwd:", "Re:", email.subject), "subject")
    );
    sendMessage();
  };

  const handleReply = () => {
    dispatch(addRecipient(editEmailAddress(email.from_address)));
    dispatch(
      editSendData(addPrefixToSubject("Re:", "Fwd:", email.subject), "subject")
    );
    sendMessage();
  };

  const handleClick = (item: SelectItemDto) => {
    dispatch(setSendTemplateType(templates[0][0]));
    dispatch(setSendTemplate(item));
    dialog.handleClickOpen();
  };

  const editAction = () => {
    dispatch(editActions([email.id]));
  };

  const infoValue = {
    editAction,
    editFavorite,
    handleClick,
    handleForward: handleForward,
    handleReply: handleReply,
    editViewed,
    templates: templates[0],
    emailInfo: email,
    componentRef,
  };
  return (
    <EmailInfoContext.Provider value={infoValue}>
      {loading ? (
        <SceletonEmailInfo />
      ) : (
        <React.Fragment>
          <EmailInfoHeader info={email} />
          <EmailInfoBody thisRef={componentRef} emailInfo={email} />
        </React.Fragment>
      )}
    </EmailInfoContext.Provider>
  );
};

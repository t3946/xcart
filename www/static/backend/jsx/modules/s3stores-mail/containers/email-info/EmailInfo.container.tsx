import React, { useContext, useEffect } from "react";
import { useHistory, useParams } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { SelectItemDto, StoreDto } from "@s3stores-mail/ts/types";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import {
  addRecipient,
  editActions,
  editFavorites,
  editSendData,
  getEmailInfo,
  getChildEmailList,
  setSendTemplate,
  setSendTemplateType,
  setViewed,
  removeLabelEmail,
  addLabelEmail,
} from "@redux/actions";
import { isFavoriteThreadTrue } from "@s3stores-mail/utils/edit-fields-on-email";
import { editEmailAddress } from "@s3stores-mail/utils/edit-email-address";
import { addPrefixToSubject } from "@s3stores-mail/utils/add-prefix-to-subject";
import { SceletonEmailInfo } from "../../components/simple/sceleton-email-info/SceletonEmailInfo";
import { EmailRouterContext } from "@s3stores-mail/contexts/email-router-context/EmailRouter.context";
import { EmailDto } from "@s3stores-mail/ts/types/email.type";
import { EmailInfoWrapper } from "@s3stores-mail/containers/email-info-wrapper/EmailInfoWrapper";
import { ApiService } from "@admin/modules/shared/services/api.service";
const api = new ApiService();
export const EmailInfoContainer: React.FC = () => {
  const { id }: { id: string } = useParams();

  const emails = useSelector((state: StoreDto) => {
    return state.items;
  });
  const labels = useSelector((state: StoreDto) => {
    return state.labelsList;
  });

  const parentEmail = emails.find((e) => e.item.id === id);

  const page = useSelector((e: StoreDto) => e.page);

  const history = useHistory();

  useEffect(() => {
    if (parentEmail) {
      if (!parentEmail.item.thread.length) {
        setTimeout(
          () => dispatch(getChildEmailList(parentEmail.item.message_id)),
          100
        );
      }
    } else {
      dispatch(getEmailInfo(id));
    }
    if (Boolean(parentEmail?.item.id) && !parentEmail.item.viewed) {
      dispatch(setViewed([parentEmail.item.id], true, parentEmail.item.id));
    }
  }, [parentEmail]);

  const dialog = useContext(EmailDialogContext);
  const dispatch = useDispatch();

  const templates = useSelector((state: StoreDto) => state.templates);

  const routers = useContext(EmailRouterContext);

  const editViewed = (emailInfo: EmailDto) => {
    history.push(`${routers.listRouter}${page}`);
    dispatch(setViewed([emailInfo.id], !emailInfo.viewed, parentEmail.item.id));
  };

  const editFavoriteItem = (messageId: string) => {
    dispatch(
      editFavorites(
        [messageId],
        !isFavoriteThreadTrue(parentEmail.item.thread, messageId),
        parentEmail.item.id,
        messageId
      )
    );
  };

  const sendMessage = (body: string) => {
    dispatch(editSendData(body, "replyText"));
    dialog.handleClickOpen();
  };

  const handleForward = (emailInfo: EmailDto) => {
    dispatch(
      editSendData(
        addPrefixToSubject("Fwd:", "Re:", emailInfo.subject),
        "subject"
      )
    );
    sendMessage(emailInfo.body);
  };

  const handleReply = (emailInfo: EmailDto) => {
    dispatch(addRecipient(editEmailAddress(emailInfo.from_address)));
    dispatch(
      editSendData(
        addPrefixToSubject("Re:", "Fwd:", emailInfo.subject),
        "subject"
      )
    );
    dispatch(editSendData(parentEmail.item.thread_id, "threadId"));
    sendMessage(emailInfo.body);
  };
  const handleView = (emailInfo: EmailDto) => {
    dispatch(setViewed([emailInfo.id], true, parentEmail.item.id));
  };
  const handleReplyByTemplate = (
    item: EmailDto,
    templateSelect: SelectItemDto
  ) => {
    dispatch(setSendTemplateType(templates[0][0]));
    console.log(templateSelect.message_body);
    const template = api
      .post(
        "/admin/forms/api/email/template/render",
        JSON.stringify({ body: templateSelect.message_body })
      )
      .then((response: { message_body: string }) => {
        dispatch(setSendTemplate({ ...templateSelect, ...response }));
      });
    dispatch(addRecipient(editEmailAddress(item.from_address)));
    dispatch(
      editSendData(addPrefixToSubject("Re:", "Fwd:", item.subject), "subject")
    );
    dispatch(editSendData(parentEmail.item.thread_id, "threadId"));
    dialog.handleClickOpen();
  };

  const editAction = (item: EmailDto) => {
    dispatch(editActions([item.id], parentEmail.item.id));
  };
  const onDeleteLabel = (item: EmailDto, labelId: string) => {
    dispatch(
      removeLabelEmail(parentEmail.item.message_id, item.message_id, labelId)
    );
  };
  const onAddLabel = (item: EmailDto, labelId: string) => {
    dispatch(
      addLabelEmail(parentEmail.item.message_id, item.message_id, labelId)
    );
  };

  const infoValue = {
    editAction,
    editFavoriteItem,
    handleReplyByTemplate,
    handleView,
    handleForward: handleForward,
    handleReply,
    editViewed,
    templates: templates[0],
    parentEmail,
    labels,
    onDeleteLabel,
    onAddLabel,
  };
  return (
    <EmailInfoContext.Provider value={infoValue}>
      {!parentEmail?.item.id ? (
        <SceletonEmailInfo />
      ) : (
        <React.Fragment>
          {parentEmail.item.thread.map((child, index) => (
            <EmailInfoWrapper
              emailInfo={child}
              openEmail={parentEmail.item.thread.length === index + 1}
            />
          ))}
        </React.Fragment>
      )}
    </EmailInfoContext.Provider>
  );
};

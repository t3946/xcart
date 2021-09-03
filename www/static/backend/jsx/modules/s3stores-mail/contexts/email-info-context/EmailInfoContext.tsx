import React from "react";
import { EmailDto, EmailLabel } from "@s3stores-mail/ts/types/email.type";
import { EmailStoreItems, SelectItemDto } from "@s3stores-mail/ts/types";
interface EmailInfoContext {
  editAction: (item: EmailDto) => void;
  onAddLabel: (item: EmailDto, labelId: string) => void;
  onDeleteLabel: (item: EmailDto, labelId: string) => void;
  labels: EmailLabel[];
  parentEmail: EmailStoreItems;
  editFavoriteItem: (messageId: string) => void;
  templates: any;
  handleReply: (item: EmailDto) => void;
  handleForward: (item: EmailDto) => void;
  handleView: (item: EmailDto) => void;
  handleReplyByTemplate: (
    item: EmailDto,
    templateSelect: SelectItemDto
  ) => void;
}
export const EmailInfoContext: React.Context<EmailInfoContext> =
  React.createContext(null);

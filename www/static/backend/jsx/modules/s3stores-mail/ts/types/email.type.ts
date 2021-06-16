export interface EmailDto {
  account_id: string;
  action: EmailActionDto;
  attachment: AttachmentDto[];
  body: string;
  date: Date;
  delivered_to_address: string;
  favorite: boolean;
  from_address: string;
  id: string;
  message_id: string;
  parent_id: string | null;
  reply_to: string | null;
  snippet: string | null;
  subject: string;
  thread_id: string;
  to_address: string | null;
  type: string;
  viewed: boolean;
}

export interface EmailActionDto {
  action: boolean;
  name?: string;
  date?: Date;
}

export interface AttachmentDto {
  email_id: string;
  attachment: string;
  cid: string | null;
  filename: string;
}

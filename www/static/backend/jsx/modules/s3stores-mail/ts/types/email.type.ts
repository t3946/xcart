export interface EmailDto {
  account_id: string;
  action: EmailActionDto;
  attachment: AttachmentDto[];
  body: string;
  date: Date;
  delivered_to_address: string;
  favorite: boolean;
  from_address: string;
  contains_action: boolean;
  labels?: EmailLabel[];
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
  thread: EmailDto[];
}
export interface EmailLabel {
  background_color: string;
  color: string;
  id: string | number;
  label_id: string;
  name: string;
  type: string;
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

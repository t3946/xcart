import {
  EmailPersonType,
  EmailType,
} from "@modules/account/ts/types/email-type.const";

export function emailStyle(send: boolean, emailType?: string): string {
  if (emailType === EmailType.NOTE) {
    return "note";
  }

  if (!send) {
    if (emailType === EmailPersonType.CUSTOMER) {
      return "incoming-green";
    }
    if (emailType === EmailPersonType.DISTRIBUTOR) {
      return "incoming-blue";
    }
  }
  if (send) {
    if (emailType === EmailPersonType.CUSTOMER) {
      return "outgoing-green";
    }
    if (emailType === EmailPersonType.DISTRIBUTOR) {
      return "outgoing-blue";
    }
  }
}

export function addStyleToViewed(viewed: boolean): string {
  if (viewed) {
    return "viewed";
  }

  return "";
}

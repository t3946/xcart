import { EmailPersonType, EmailType } from "../ts/consts/email-type.const";

export function emailStyle(email?: string, customer?: string): string {
  if (!email && !customer) {
    return "";
  }

  if (email === EmailType.NOTE) {
    return "note";
  }

  if (email === EmailType.INCOMING) {
    if (customer === EmailPersonType.CUSTOMER) {
      return "incoming-blue";
    }
    if (customer === EmailPersonType.DISTRIBUTOR) {
      return "incoming-green";
    }
  }
  if (email === EmailType.OUTGOING) {
    if (customer === EmailPersonType.CUSTOMER) {
      return "outgoing-blue";
    }
    if (customer === EmailPersonType.DISTRIBUTOR) {
      return "outgoing-green";
    }
  }
}

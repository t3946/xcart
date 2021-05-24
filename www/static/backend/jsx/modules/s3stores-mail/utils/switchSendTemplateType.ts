import { selectInfoItems } from "@s3stores-mail/ts/consts";

export function switchSendTemplateType(value: number | string) {
  switch (value) {
    case 1: {
      return selectInfoItems;
    }
    case 2: {
      return selectInfoItems;
    }
    case 3: {
      return selectInfoItems;
    }
    case 4: {
      return selectInfoItems;
    }
    default: {
      return undefined;
    }
  }
}

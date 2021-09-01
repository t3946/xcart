import { checkEmailValidation } from "@s3stores-mail/utils/check-email-validation";

export function checkValidEmailRecipients(recipients: string[]) {
  if (recipients.length === 0) {
    return {
      valid: false,
      error: "Please specify at least one recipient.",
    };
  }
  const error = {
    valid: true,
    error: "",
  };
  recipients.forEach((recipient) => {
    if (checkEmailValidation(recipient)) {
      return;
    }
    error.valid = false;
    error.error = "One of the recipients is not valid.";
  });
  return error;
}

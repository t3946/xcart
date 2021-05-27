import React, { useContext } from "react";
import { Form, Formik } from "formik";
import {
  emailSendEditorSettings,
  initialFormValues,
} from "@s3stores-mail/ts/consts";
import { InputAdornment, TextField } from "@material-ui/core";
import { Editor } from "@tinymce/tinymce-react";
import { EmailSendBodyContext } from "@s3stores-mail/contexts";

export const EmailSendBodyForm = () => {
  const context = useContext(EmailSendBodyContext);
  return (
    <React.Fragment>
      <Formik
        initialValues={initialFormValues}
        onSubmit={null}
        validationSchema={null}
      >
        {() => {
          return (
            <Form className="your-order-form" encType="multipart/form-data">
              <TextField
                className={"email-send-input"}
                autoComplete="off"
                name={"from"}
                fullWidth
                InputProps={{
                  startAdornment: (
                    <InputAdornment position="start">
                      <b className="email-send-field-to">To:</b>
                    </InputAdornment>
                  ),
                }}
              />
              <TextField
                className={"email-send-input"}
                autoComplete="off"
                name={"from"}
                fullWidth
                InputProps={{
                  startAdornment: (
                    <InputAdornment position="start">
                      <b>Subject:</b>
                    </InputAdornment>
                  ),
                }}
              />
            </Form>
          );
        }}
      </Formik>
      <Editor
        initialValue={context.sendData.replyText}
        init={{
          height: 280,
          menubar: true,
          ...emailSendEditorSettings,
        }}
      />
    </React.Fragment>
  );
};

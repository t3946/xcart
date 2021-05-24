import React from "react";
import {
  emailSendEditorSettings,
  initialFormValues,
} from "@s3stores-mail/ts/consts";
import { Form, Formik } from "formik";
import { Editor } from "@tinymce/tinymce-react";
import { Grid, InputAdornment, TextField } from "@material-ui/core";
import { EmailSendButton } from "../email-send-button/EmailSendButton";
import AlternateEmailIcon from "@material-ui/icons/AlternateEmail";
import { EmailSendFileUpload } from "@s3stores-mail/components/email-send-file-upload/EmailSendFileUpload";
import { EmailSendFilesList } from "@s3stores-mail/components/email-send-files-list/EmailSendFilesList";

export const EmailSendBody: React.FC = () => {
  return (
    <div className="email-send-body-wrapper">
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
        initialValue={"<p>123123</p>"}
        init={{
          height: 280,
          menubar: true,
          ...emailSendEditorSettings,
        }}
      />
      <Grid alignItems="center" container className="b">
        <Grid className="email-send-body-footer-text">
          <span>Attachment</span>
        </Grid>
        <Grid alignItems="center" container xs={2}>
          <AlternateEmailIcon className="a" />
          <EmailSendFileUpload />
        </Grid>
      </Grid>
      <EmailSendFilesList />
      <EmailSendButton />
    </div>
  );
};

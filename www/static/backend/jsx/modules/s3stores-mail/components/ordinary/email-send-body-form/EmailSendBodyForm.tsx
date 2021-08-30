import React, { useContext, useEffect } from "react";
import { Form, Formik } from "formik";
import {
  emailSendEditorSettings,
  initialFormValues,
} from "@s3stores-mail/ts/consts";
import { InputAdornment, TextField } from "@material-ui/core";
import { Editor } from "@tinymce/tinymce-react";
import { EmailSendBodyContext } from "@s3stores-mail/contexts";
import { EmailSendFilesList } from "@s3stores-mail/components/ordinary/email-send-files-list/EmailSendFilesList";
import { EmailSendInput } from "@s3stores-mail/components/smart/email-send-input/EmailSendInput";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";

const SendForm: React.FC = () => {
  const { changeField, sendTemplate, filesRef } =
    useContext(EmailSendBodyContext);
  const files = useSelector((state: StoreDto) => state.sendData.files);

  useEffect(() => {
    if (files.length !== 0) {
      filesRef.current.scrollIntoView({
        behavior: "smooth",
        block: "end",
      });
    }
  }, [files]);

  const subject = useSelector((state: StoreDto) => state.sendData.subject);

  const replyText = useSelector((state: StoreDto) => state.sendData.replyText);

  const initialValue =
    sendTemplate.message_body +
    `<br/><blockquote style="margin: 0px 0px 0px 0.8ex; border-left: 1px solid #cccccc; padding-left: 1ex;">${replyText}</blockquote>`;

  return (
    <React.Fragment>
      <EmailSendInput />
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
                value={subject}
                onChange={(e) => changeField("subject", e.target.value)}
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
        onEditorChange={(e) => {
          changeField("body", e);
        }}
        initialValue={initialValue}
        init={{
          menubar: true,
          ...emailSendEditorSettings,
          plugins: "autoresize",
          min_height: 425,
        }}
      />

      <div ref={filesRef}>
        <EmailSendFilesList files={files} />
      </div>
    </React.Fragment>
  );
};

export const EmailSendBodyForm = React.memo(SendForm);

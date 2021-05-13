import React from "react";
import FormInput from "../../../shared/components/form-input/FormInput";
import { initialFormValues } from "../../ts/consts/email-search-form.const";
import { Form, Formik } from "formik";
import { Editor } from "@tinymce/tinymce-react";
import { Grid, InputAdornment, TextField } from "@material-ui/core";
import { EmailSendButton } from "../email-send-button/EmailSendButton";
import AttachmentIcon from "@material-ui/icons/Attachment";
import AlternateEmailIcon from "@material-ui/icons/AlternateEmail";

export const EmailSendBody = () => {
  return (
    <div className="email-send-body-wrapper">
      <Formik
        initialValues={initialFormValues}
        onSubmit={null}
        validationSchema={null}
      >
        {({ errors, setFieldValue, values, touched }) => {
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
                      <b>To:</b>
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
        initialValue="<p>This is the initial content of the editor.</p>"
        init={{
          height: 280,
          menubar: false,
          plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table paste code help wordcount",
          ],
          toolbar:
            "undo redo | formatselect | " +
            "bold italic backcolor | alignleft aligncenter " +
            "alignright alignjustify | bullist numlist outdent indent | " +
            "removeformat | help",
          content_style:
            "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
        }}
      />
      <Grid container className="b">
        <Grid xs={2}>
          <span>Attachment</span>
        </Grid>
        <Grid container xs={1}>
          <AttachmentIcon className="a" />
          <AlternateEmailIcon className="a" />
        </Grid>
      </Grid>
      <EmailSendButton />
    </div>
  );
};

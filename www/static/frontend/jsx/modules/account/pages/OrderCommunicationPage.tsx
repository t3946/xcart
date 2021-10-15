import React, { useCallback, useRef } from "react";
import { Editor } from "@tinymce/tinymce-react";
import { IconButton } from "@material-ui/core";
import { AttachFile } from "@material-ui/icons";
import { FileDrop } from "@client/modules/account/components/shared/FileDrop";

interface OrderCommunicationPageProps {}

export const OrderCommunicationPage: React.FC<OrderCommunicationPageProps> =
  () => {
    const onDrop = useCallback(([acceptedFile]) => {
      console.log(acceptedFile);
    }, []);
    const editorRef = useRef(null);
    return (
      <div>
        <div className="customer-notes">
          <div className="customer-notes-title">Customer notes</div>
          <hr className="customer-notes-line" />
          <div className="customer-notes-text">
            The Master Book of Candle Burning outlines the practices used and
            taught by mediums, spiritual advisors, evangelists, and others who
            use candles and psalms to help in money drawing rituals, love
            spells, and other such spells.
          </div>
        </div>
        <div className="page-label">Order communication</div>
        <div className="order-communication-messages"></div>
        <div className="page-label">Compose Message to Customer Service </div>
        <div className="order-communication-editor">
          <Editor
            onInit={(evt, editor) => (editorRef.current = editor)}
            initialValue="<p>This is the initial content of the editor.</p>"
            init={{
              height: 300,
              menubar: true,
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
        </div>
        <div className="order-communication-attachment">
          <div>Attachment</div>
          <FileDrop onDrop={onDrop}>
            <IconButton>
              <AttachFile />
            </IconButton>
          </FileDrop>
        </div>
        <button className="form-button order-communication-send-btn">
          send
        </button>
      </div>
    );
  };

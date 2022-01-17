import React, { useCallback, useContext, useRef, useState } from "react";
import { Editor } from "@tinymce/tinymce-react";
import { IconButton } from "@mui/material";
import AttachFileIcon from "@mui/icons-material/AttachFile";
// import { FileDrop } from "@modules/account/components/shared/FileDrop";
import { OrdersEmailItem } from "@modules/account/components/orders/OrdersEmailItem";
import {
  addStyleToViewed,
  emailStyle,
} from "@modules/account/utils/set-email-item-style";
import { FileItem } from "@modules/account/components/orders/FileItem";
import { OrderPageURLParams } from "@modules/account/ts/types/order-page-url-params.type";
import { useDispatch, useSelector } from "react-redux";
import { sendEmail } from "@redux/actions/account-actions/OrdersActions";
import Store from "@redux/stores/Store";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { AccountStore } from "@modules/account/ts/types/store.type";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import { FileDrop } from "@modules/account/components/shared/FileDrop";

interface OrderCommunicationPage {
  orderItem: OrderView;
}

export const OrderCommunicationPage: React.FC<OrderCommunicationPage> = ({
  orderItem,
}) => {
  const onDrop = ([acceptedFile]) => {
    setFiles((prev) =>
      prev.concat({
        id: files.length === 0 ? 0 : prev[prev.length - 1].id + 1,
        file: acceptedFile,
      })
    );
  };

  const onEmailSend = () => {
    setFiles([]);
    setEmailBody("");
  };
  const { showSnackbar } = useContext(SnackbarContext);

  const loading = useSelector((e: AccountStore) => e.ordersStore.ordersLoading);

  const editorRef = useRef(null);

  const [files, setFiles] = useState([]);

  const dispatch = useDispatch();

  const [emailBody, setEmailBody] = useState("");

  const sendMessage = () => {
    dispatch(
      sendEmail(
        {
          from: Store.getState().user?.email,
          to: ["andrey@s3stores.com"],
          body: emailBody,
          files: files,
          subject: "Help",
        },
        () => {
          onEmailSend();
          showSnackbar({
            header: "Success",
            message:
              "The email has been sent, it will appear in the list of emails later",
            theme: "success",
          });
        }
      )
    );
  };

  return (
    <div>
      <div className="customer-notes">
        <div className="customer-notes-title">Customer notes</div>
        <hr className="customer-notes-line" />
        <div className="customer-notes-text">
          The Master Book of Candle Burning outlines the practices used and
          taught by mediums, spiritual advisors, evangelists, and others who use
          candles and psalms to help in money drawing rituals, love spells, and
          other such spells.
        </div>
      </div>
      <div className="page-label">Order communication</div>
      <div className="order-communication-messages">
        {/*{orderItem.emails?.map((email) => (*/}
        {/*  <OrdersEmailItem*/}
        {/*    theme={*/}
        {/*      emailStyle(email.type === "sent", email?.emailType) +*/}
        {/*      " " +*/}
        {/*      addStyleToViewed(email.viewed)*/}
        {/*    }*/}
        {/*    itemData={email}*/}
        {/*    handleClick={() =>*/}
        {/*      history.push(*/}
        {/*        `/account/orders/${urlParams.id}/${urlParams.orderType}/email-info/${email.id}`*/}
        {/*      )*/}
        {/*    }*/}
        {/*  />*/}
        {/*))}*/}
      </div>
      <div className="page-label">Compose Message to Customer Service</div>
      <div className="order-communication-editor">
        <Editor
          value={emailBody}
          apiKey={"8jpw1ae23omzljwzt5pcrbca7dybhlgxjtss2h5sp9fxryhy"}
          onInit={(evt, editor) => (editorRef.current = editor)}
          initialValue={emailBody}
          onChange={(e) => setEmailBody(e.target.value)}
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
            <AttachFileIcon />
          </IconButton>
        </FileDrop>
      </div>
      {files.map((e: { id: number; file: File }) => (
        <FileItem
          key={e.id}
          file={e.file}
          onClick={() => setFiles(files.filter((file) => file.id !== e.id))}
        />
      ))}
      <button
        disabled={loading}
        onClick={sendMessage}
        className="form-button order-communication-send-btn"
      >
        send
      </button>
    </div>
  );
};

import React, { useContext, useEffect, useRef, useState } from "react";
import { Grid } from "@material-ui/core";
import { useCLickListener } from "@s3stores-mail/hooks/useCLickListener";
import { EmailRecipientItem } from "@s3stores-mail/components/smart/email-recipient-item/EmailRecipientItem";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailSendBodyContext } from "@s3stores-mail/contexts";

export const EmailSendInput: React.FC = () => {
  const mass = useSelector((state: StoreDto) => state.sendData.to);

  const {
    addNewRecipient,
    editThisRecipient,
    deleteThisRecipient,
  } = useContext(EmailSendBodyContext);

  const [focus, setFocus] = useState(false);

  const [width, setWidth] = useState(10);

  const ref = useRef<HTMLInputElement>();

  const onFocusOut = () => {
    setFocus(false);
  };

  useCLickListener(onFocusOut);

  useEffect(() => {
    if (!focus && ref.current) {
      ref.current.blur();

      if (ref.current.value.trim()) {
        addNewRecipient(ref.current.value);
      }
      ref.current.value = "";
      setWidth(10);
    }
  }, [focus]);

  const handleKeyDown = (e) => {
    if (e.keyCode === 13) {
      if (ref.current.value.trim()) {
        addNewRecipient(ref.current.value);
      }
      ref.current.value = "";
      setWidth(10);
    }
  };

  const handleItemClick = (e) => {
    e.stopPropagation();
    ref.current.focus();
    setFocus(true);
  };

  const handleInputValueChange = (e) => {
    setWidth(e.target.value.length * 10);
  };

  return (
    <Grid
      onKeyDown={handleKeyDown}
      className={`send-input-wrapper ${focus && "email-input-focus"}`}
      container
      onClick={handleItemClick}
    >
      <span className="send-input-label">To:</span>

      {focus
        ? mass.map((item) => {
            return (
              <EmailRecipientItem
                item={item}
                mass={mass}
                editRecipient={editThisRecipient}
                deleteRecipient={deleteThisRecipient}
              />
            );
          })
        : mass.map((e, index) => {
            return (
              <span className="recipients">
                {e}
                {index !== mass.length - 1 && ","}
              </span>
            );
          })}
      <input
        className="recipient-input"
        style={{ width: width }}
        onChange={handleInputValueChange}
        ref={ref}
      />
    </Grid>
  );
};

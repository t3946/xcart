import React, { useEffect, useRef, useState } from "react";
import ClearIcon from "@material-ui/icons/Clear";
import { checkEmailValidation } from "@s3stores-mail/utils/check-email-validation";

export const EmailRecipientItem: React.FC<any> = ({
  item,
  editRecipient,
  deleteRecipient,
}) => {
  const divRef = useRef<HTMLDivElement>();
  const inputRef = useRef<HTMLInputElement>();
  const [inputFocus, setInputFocus] = useState(false);

  const [inputWidth, setInputWidth] = useState(divRef.current?.offsetWidth);
  useEffect(() => {
    if (divRef.current?.offsetWidth) {
      setInputWidth(divRef.current?.offsetWidth);
    }

    if (inputFocus) {
      inputRef.current.focus();
      inputRef.current.value = item;
      inputRef.current.select();
    }
  }, [inputFocus]);

  const handleBlur = () => {
    setInputFocus(false);
    editRecipient(item, inputRef.current.value);
  };

  const handleChange = (e) => {
    setInputWidth(e.target.value.length * 8 + 20);
  };

  const handleItemClick = (e) => {
    e.stopPropagation();
    setInputFocus(true);
  };

  const handleDeleteItem = (e) => {
    e.stopPropagation();
    deleteRecipient(item);
  };

  const handleKeyDown = (e) => {
    if (e.keyCode === 13) {
      inputRef.current.blur();
    }
  };

  return (
    <div className="recipient-wrap">
      {inputFocus ? (
        <input
          onKeyDown={handleKeyDown}
          className="recipient-input"
          style={{ width: inputWidth }}
          onChange={handleChange}
          onBlur={handleBlur}
          ref={inputRef}
        />
      ) : (
        <div
          ref={divRef}
          className={`recipient-item ${
            checkEmailValidation(item) ? "valid" : "invalid"
          }`}
          onClick={handleItemClick}
        >
          <span className="recipient-item-text">{item}</span>
          <ClearIcon
            onClick={handleDeleteItem}
            className="recipient-item-icon"
          />
        </div>
      )}
    </div>
  );
};

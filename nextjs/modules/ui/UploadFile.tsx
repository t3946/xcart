import React, { Ref } from "react";
import cn from "classnames";
import Feedback from "@modules/ui/forms/Feedback";
import Light from "@modules/icon/components/font-awesome/times/Light";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { fileSizeFormat } from "@modules/account/utils/file-size-formatting";

import Styles from "@modules/ui/UploadFile.module.scss";

interface IProps {
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  multiple?: boolean;
  error?: string;
  touched?: boolean;
  files: File[];
  setFiles: React.Dispatch<React.SetStateAction<File[]>>;
  name: string;
  disabled?: boolean;
  isValid?: boolean;
  classNames?: any;
  formats: string[];
  maxSize: number;
  ref: any;
}

const UploadFile: React.FC<IProps> = React.forwardRef<HTMLInputElement, IProps>(
  (
    {
      onChange,
      multiple,
      files,
      setFiles,
      error,
      name,
      classNames,
      formats,
      maxSize,
      touched,
      disabled,
    },
    ref
  ) => {
    const fileChangeHandler = (e: React.ChangeEvent<HTMLInputElement>) => {
      onChange(e);
      for (const index in e.target.files) {
        const file = e.target.files[index];

        if (file instanceof File) {
          if (
            true ||
            (formats.includes(file.type) && file.size <= maxSize * sizes.MB)
          ) {
            if (multiple) {
              setFiles((prevState) => [...prevState, file]);
            } else {
              setFiles([file]);
            }
          }
        }
      }
    };

    const deleteFile = (file: File) => {
      setFiles((prevState) => [...prevState.filter((item) => item !== file)]);
    };
    return (
      <div className={cn(classNames)}>
        <div className={Styles.buttonContainer}>
          <Button
            className={cn("estimate-table-caption", "p-0", Styles.h100, {
              [Styles.button_invalid]: touched && error,
              [Styles.button_valid]: touched && !error,
            })}
            disabled={disabled}
            theme={ETheme.themeGrey}
          >
            <label
              className={cn(
                Styles.h100,
                "w-100",
                "d-flex",
                "align-items-center",
                "justify-content-center",
                Styles.label
              )}
            >
              {multiple ? "Choose files" : "Choose file"}
              <input
                type="file"
                className="d-none"
                name={name}
                ref={ref}
                disabled={disabled}
                onChange={fileChangeHandler}
                multiple={!!multiple}
              />
            </label>
          </Button>
        </div>

        <div className={Styles.log}>
          {files.map((item, index) => (
            <React.Fragment key={`${item.name}_${index}`}>
              <div className={cn(["me-14", Styles.fileDetails])}>
                <span className={Styles.fileDetailsName}>{item.name} </span>
                <span className={Styles.fileDetailsSize}>
                  (<span>{fileSizeFormat(item.size)}</span>)
                </span>
              </div>
              <span
                className={cn([
                  "d-inline-block",
                  Styles.fileDetailsRemove,
                  { "cursor-default": disabled },
                ])}
                onClick={() => !disabled && deleteFile(item)}
              >
                <div
                  className={cn([
                    "d-flex",
                    Styles.h100,
                    "justify-content-center",
                    "align-items-center",
                  ])}
                >
                  <Light className={Styles.fileDetailsRemoveTimes} />
                </div>
              </span>
            </React.Fragment>
          ))}
        </div>
        {error && (
          <Feedback className="d-block" type="invalid">
            {error}
          </Feedback>
        )}
      </div>
    );
  }
);

export default UploadFile;

import React, { Ref } from "react";
import cn from "classnames";
import Styles from "@modules/ui/UploadFile.module.scss";
import Feedback from "@modules/ui/forms/Feedback";
import Light from "@modules/icon/components/font-awesome/times/Light";

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
    const sizes = {
      KB: 1024,
      MB: 1048576,
    };
    const fileChangeHandler = (e: React.ChangeEvent<HTMLInputElement>) => {
      onChange(e);
      for (const index in e.target.files) {
        const file = e.target.files[index];

        if (file instanceof File) {
          if (formats.includes(file.type) && file.size <= maxSize * sizes.MB) {
            if (multiple) {
              setFiles((prevState) => [...prevState, file]);
            } else {
              setFiles([file]);
            }
          }
        }
      }
    };

    const sizeTemplate = (size: number) => {
      if (size < sizes.KB) {
        return <span>{size}B</span>;
      } else if (size < sizes.MB) {
        return <span>{Math.ceil(size / sizes.KB)}KB</span>;
      }
      return <span>{Math.ceil(size / sizes.MB)}MB</span>;
    };
    const deleteFile = (file: File) => {
      setFiles((prevState) => [...prevState.filter((item) => item !== file)]);
    };
    return (
      <div className={cn(classNames)}>
        <label
          className={cn(
            "form-button__theme-grey",
            "estimate-table-caption",
            "d-flex",
            "justify-content-center",
            "align-items-center",
            "p-0",
            Styles.button,
            {
              [Styles.button_invalid]: touched && error,
              [Styles.button_valid]: touched && !error,
              [Styles.button_disabled]: disabled,
              "cursor-default": disabled,
            }
          )}
        >
          Choose file
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
        <div className={Styles.log}>
          {files.map((item, index) => (
            <React.Fragment key={`${item.name}_${index}`}>
              <div className={cn(["me-14", Styles.fileDetails])}>
                <span className={Styles.fileDetailsName}>{item.name} </span>
                <span className={Styles.fileDetailsSize}>
                  ({sizeTemplate(item.size)})
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
        {error && <Feedback type="invalid">{error}</Feedback>}
      </div>
    );
  }
);

export default UploadFile;

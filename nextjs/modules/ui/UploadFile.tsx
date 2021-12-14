import React, { Ref } from "react";
import cn from "classnames";
import Styles from "@modules/ui/UploadFile.module.scss";
import Light from "@modules/icon/components/font-awesome/times/Light";

interface IProps {
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  multiple?: boolean;
  error: string;
  files: File[];
  setFiles: React.Dispatch<React.SetStateAction<File[]>>;
  name: string;
  classNames?: any;
  formats: string[];
  maxSize: number;
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
        const fr = new FileReader();
        if (file instanceof File) {
          if (formats.includes(file.type) && file.size <= maxSize * sizes.MB) {
            if (multiple) {
              setFiles((prevstate) => [...prevstate, file]);
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
      setFiles((prevstate) => [...prevstate.filter((item) => item !== file)]);
    };
    return (
      <div className={cn(classNames)}>
        <label
          className={cn([
            "form-button__theme-grey",
            "estimate-table-caption",
            "d-flex",
            "justify-content-center",
            "align-items-center",
            "p-0",
            Styles.button,
          ])}
        >
          Choose file
          <input
            type="file"
            className="d-none"
            name={name}
            ref={ref}
            onChange={fileChangeHandler}
            multiple={!!multiple}
          />
        </label>
        <div className={Styles.log}>
          {files.map((item) => (
            <>
              <div className={cn(["me-14", Styles.fileDetails])}>
                <span className={Styles.fileDetailsName}>{item.name} </span>
                <span className={Styles.fileDetailsSize}>
                  ({sizeTemplate(item.size)})
                </span>
              </div>
              <span
                className={cn(["d-inline-block", Styles.fileDetailsRemove])}
                onClick={() => deleteFile(item)}
              >
                <div
                  className={cn([
                    "d-flex",
                    "h-100",
                    "justify-content-center",
                    "align-items-center",
                  ])}
                >
                  <Light className={Styles.fileDetailsRemoveTimes} />
                </div>
              </span>
            </>
          ))}
        </div>
        {error && (
          <div className={cn(["text-danger", Styles.error])}>{error}</div>
        )}
      </div>
    );
  }
);

export default UploadFile;

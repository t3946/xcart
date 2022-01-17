import React from "react";
import Film from "@modules/icon/components/font-awesome/film/Film";
import PlusPanelButton from "@modules/account/components/common/PlusPanelButton";
import ModalTimes from "@modules/icon/components/account/ModalTimes";
import Camera from "@modules/icon/components/account/camera/Camera";
import mbToB from "@utils/mbToB";

interface IProps {
  setFiles: any;
  inputRef: any;
  handleChange: any;
  imagesFormats: string[];
  videosFormats: string[];
  maxImageSize: number;
  maxVideoSize: number;
  maxFiles: number;
  setAttachmentsNumber: any;
}

interface FileInterface {
  type: string;
  dataUrl: string;
  name: string;
  file: any;
}

const Files: React.FC<IProps> = function (props: IProps) {
  const {
    inputRef,
    handleChange,
    imagesFormats,
    videosFormats,
    maxImageSize,
    maxVideoSize,
    maxFiles,
    setAttachmentsNumber,
  } = props;
  const [files, setFiles] = React.useState<FileInterface[]>([]);

  function updateFilesList(newFiles) {
    const filesObjects = [];

    for (const file of newFiles) {
      filesObjects.push(file.file);
    }

    props.setFiles(filesObjects);
  }

  function removeFile(i: number) {
    const before = files.slice(0, i);
    const after = files.slice(i + 1, files.length);
    const newFiles = [...before, ...after];
    setFiles(newFiles);
    updateFilesList(newFiles);
  }

  function changeInputFile(e) {
    handleChange(e);

    const inputFiles = inputRef.current.files;

    // check max attachments limit
    if (files.length + inputFiles.length > maxFiles) {
      return;
    }

    for (let i = 0; i < inputFiles.length; i++) {
      const file = inputFiles[i];
      const reader = new FileReader();

      //incorrect format
      if (
        !imagesFormats.includes(file.type) &&
        !videosFormats.includes(file.type)
      ) {
        continue;
      }

      //incorrect image size
      if (
        imagesFormats.includes(file.type) &&
        file.size > mbToB(maxImageSize)
      ) {
        continue;
      }

      //incorrect video size
      if (
        videosFormats.includes(file.type) &&
        file.size > mbToB(maxVideoSize)
      ) {
        continue;
      }

      reader.onload = function () {
        if (typeof reader.result === "string") {
          files.push({
            type: file.type,
            dataUrl: reader.result,
            name: file.name,
            file,
          });
        } else {
          console.error("no supported type");
        }

        setFiles([...files]);
        updateFilesList([...files]);
        setAttachmentsNumber(files.length);
      };

      reader.readAsDataURL(file);
    }
  }

  function filesListTemplate() {
    return files.map(function (file, i) {
      if (file.type.indexOf("image") !== -1) {
        return fileImageTemplate(file.dataUrl, file.name, i.toString(), () =>
          removeFile(i)
        );
      } else if (file.type.indexOf("video") !== -1) {
        return fileVideoTemplate(file.name, i.toString(), () => removeFile(i));
      }
    });
  }

  function fileImageTemplate(
    dataUrl: string,
    name: string,
    key: string,
    removeHandler: () => void
  ) {
    return (
      <div
        className="d-flex mb-4 align-items-center position-relative"
        key={`image-item-${key}`}
      >
        <div
          className={"form-review-file_image form-review-file me-20"}
          onClick={removeHandler}
        >
          <img className={"form-review-file-image"} src={dataUrl} alt={""} />
        </div>

        <span>{name}</span>

        <div
          className="form-review-remove-file form-review__remove-file"
          onClick={removeHandler}
        >
          <ModalTimes className="form-review-remove-file-icon" />
        </div>
      </div>
    );
  }
  function fileVideoTemplate(
    name: string,
    key: string,
    removeHandler: () => void
  ) {
    return (
      <div
        className="d-flex mb-4 align-items-center position-relative"
        key={`video-item-${key}`}
      >
        <div
          className={"form-review-file_video form-review-file me-20"}
          onClick={removeHandler}
        >
          <Film />
        </div>

        <span>{name}</span>

        <div
          className="form-review-remove-file form-review__remove-file"
          onClick={removeHandler}
        >
          <ModalTimes className="form-review-remove-file-icon" />
        </div>
      </div>
    );
  }

  return (
    <div className="d-flex flex-column">
      {filesListTemplate()}

      <PlusPanelButton
        classes={{
          container: "form-review-add-file-button d-none d-md-flex",
          icon: "form-review-add-file-button-icon",
        }}
        onClick={() => inputRef.current.click()}
      />

      <div
        className="d-md-none form-review-add-file-button_mobile d-flex align-items-center justify-content-center"
        onClick={() => inputRef.current.click()}
      >
        <Camera />
      </div>

      <input
        type="file"
        onChange={changeInputFile}
        ref={inputRef}
        multiple={true}
        className={"d-none"}
        name={"files"}
      />
    </div>
  );
};

export default Files;

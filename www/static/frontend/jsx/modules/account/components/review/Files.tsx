import React from "react";
import Film from "@client/jsx/modules/icon/components/font-awesome/film/Film";
import PlusPanelButton from "@client/modules/account/components/common/PlusPanelButton";
import ModalTimes from "@client/modules/icon/components/account/ModalTimes";

interface IProps {
  setFiles: any;
}

interface FileInterface {
  type: string;
  dataUrl: string;
  name: string;
  file: any;
}

const Files: React.FC<IProps> = function (props: IProps) {
  const inputFileRef = React.useRef<HTMLInputElement>();
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

  function changeInputFile() {
    const inputFiles = inputFileRef.current.files;
    const reader = new FileReader();

    reader.readAsDataURL(inputFiles[0]);

    for (let i = 0; i < inputFiles.length; i++) {
      const file = inputFiles[i];
      const reader = new FileReader();

      if (
        file.type.indexOf("image") === -1 &&
        file.type.indexOf("video") === -1
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
      };

      reader.readAsDataURL(file);
    }
  }

  function filesListTemplate() {
    return files.map(function (file, i) {
      if (file.type.indexOf("image") !== -1) {
        return fileImageTemplate(file.dataUrl, file.name, () => removeFile(i));
      } else if (file.type.indexOf("video") !== -1) {
        return fileVideoTemplate(file.name, () => removeFile(i));
      }
    });
  }

  function fileImageTemplate(
    dataUrl: string,
    name: string,
    removeHandler: () => void
  ) {
    return (
      <div className="d-flex mb-4 align-items-center position-relative">
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
  function fileVideoTemplate(name: string, removeHandler: () => void) {
    return (
      <div className="d-flex mb-4 align-items-center position-relative">
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
          container: "form-review-add-file-button d-none d-md-flex mb-20",
          icon: "form-review-add-file-button-icon",
        }}
        onClick={() => inputFileRef.current.click()}
      />

      <input
        type="file"
        onChange={changeInputFile}
        ref={inputFileRef}
        multiple={true}
        className={"d-none"}
      />
    </div>
  );
};

export default Files;

import * as React from "react";
import getStoreUrl from "@utils/getStoreUrl";

interface IProps {
  decision: any;
}

export const SentFiles: React.FC<IProps> = function (props) {
  const { decision } = props;
  const templates = [];

  for (const i in decision.files) {
    const file = decision.files[i];
    const { path, original_name } = file.file;

    templates.push(
      <li key={`sent-file-${i}`}>
        <a href={getStoreUrl(path)} target={"_blank"}>{original_name}</a>
      </li>
    );
  }

  return <ul className={"mb-0"}>{templates}</ul>;
};

export default SentFiles;

import React from "react";

interface PropsInterface {
  files: string[];
}

const Files: React.FC<PropsInterface> = function (props: PropsInterface) {
  function fileTemplate(file) {
    return (
      <div
        className={"review-image-thumb review__image-thumb"}
        style={{ backgroundImage: `url(/${file})` }}
      />
    );
  }

  const templates = [];

  for (const file of props.files) {
    templates.push(fileTemplate(file));
  }

  return <div className={"review__files"}>{templates}</div>;
};

export default Files;

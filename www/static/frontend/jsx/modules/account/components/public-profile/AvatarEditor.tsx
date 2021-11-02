import React from "react";
import ReactAvatarEditor from "react-avatar-editor";
import Form from "react-bootstrap/Form";

interface PropsInterface {
  imageRaw: string;
}

const AvatarEditor: React.FC<PropsInterface> = function (
  props: PropsInterface
) {
  const { imageRaw } = props;
  const [scale, setScale] = React.useState(1.2);

  return (
    <div>
      <ReactAvatarEditor
        image={imageRaw}
        width={250}
        height={250}
        border={50}
        color={[255, 255, 255, 0.6]}
        scale={scale}
        rotate={0}
      />

      <Form.Label>Range</Form.Label>
      <Form.Range />
    </div>
  );
};

export default AvatarEditor;

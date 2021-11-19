import React from "react";
import Cropper from "react-cropper";

interface IProps {
  imageRaw: string;
  imageChange: (dataUrl: string) => void;
  preview: HTMLElement;
}

const AvatarEditor: React.FC<IProps> = function (
  props: IProps
) {
  const { imageRaw, imageChange, preview } = props;
  const cropperRef = React.useRef<HTMLImageElement>(null);

  const onCrop = () => {
    const imageElement: any = cropperRef?.current;
    const cropper: any = imageElement?.cropper;
    imageChange(cropper.getCroppedCanvas().toDataURL());
  };

  const xAspect = 1;
  const yAspect = 1;
  const aspectRation = xAspect / yAspect;
  const minAspectRation = aspectRation / 4;
  const maxAspectRation = aspectRation * 4;

  return (
    <div>
      <Cropper
        src={imageRaw}
        style={{ height: 400, width: "100%" }}
        // Cropper.js options
        initialAspectRatio={aspectRation}
        guides={false}
        ref={cropperRef}
        aspectRatio={aspectRation}
        viewMode={2}
        zoom={(e) => {
          if (
            e.detail.ratio < minAspectRation ||
            e.detail.ratio > maxAspectRation
          ) {
            e.preventDefault();
          }
        }}
        zoomTo={1}
        toggleDragModeOnDblclick={false}
        dragMode={"move"}
        preview={preview}
        minCropBoxHeight={130}
        minCropBoxWidth={130}
        cropend={onCrop}
        ready={onCrop}
      />
    </div>
  );
};

export default AvatarEditor;

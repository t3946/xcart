import React, { useRef, useState } from "react";

export const Iframe: React.FC<any> = ({ src }) => {
  const [height, setHeight] = useState(0);

  const [load, setLoad] = useState(false);

  const ref = useRef<HTMLIFrameElement>(null);

  const onLoad = () => {
    setHeight(
      4 + ref.current?.contentWindow.document.documentElement.offsetHeight
    );
    setLoad(true);
  };

  return (
    <React.Fragment>
      {!load && <div className="sceleton  sceleton-info-body" />}
      <iframe
        className="iframe"
        style={{
          width: "100%",
          height: height,
          border: 0,
          padding: 0,
        }}
        ref={ref}
        onLoad={onLoad}
        srcDoc={src}
      />
    </React.Fragment>
  );
};

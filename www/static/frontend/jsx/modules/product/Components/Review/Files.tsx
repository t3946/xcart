import React from "react";
import {
  photoSwipeSetItemsAction,
  photoSwipeSetThumbsInitiatorAction,
  photoSwipeSetOptionIndexAction,
} from "@client/jsx/redux/actions/PhotoSwipeActions";
import { useDispatch } from "react-redux";

interface PropsInterface {
  files: Record<any, any>[];
}

const Files: React.FC<PropsInterface> = function (props: PropsInterface) {
  const dispatch = useDispatch();
  const thumbsContainer = React.useRef<HTMLDivElement>();

  function fileTemplate(link, index: number) {
    return (
      <div
        className={"review-image-thumb review__image-thumb"}
        style={{ backgroundImage: `url(${link})` }}
        onClick={(e) => {
          dispatch(photoSwipeSetOptionIndexAction(index));

          dispatch(
            photoSwipeSetThumbsInitiatorAction(
              thumbsContainer.current.childNodes
            )
          );

          dispatch(photoSwipeSetItemsAction(items));
        }}
      />
    );
  }

  const templates = [];
  const items = [];

  for (let i = 0; i < props.files.length; i++) {
    const file = props.files[i];
    const { path, width, height } = file;
    const link = "/" + path;

    templates.push(fileTemplate(link, i));

    items.push({
      src: link,
      w: width,
      h: height,
    });
  }

  return (
    <div className={"review__files"} ref={thumbsContainer}>
      {templates}
    </div>
  );
};

export default Files;

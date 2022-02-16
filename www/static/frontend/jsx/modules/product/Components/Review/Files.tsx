import React from "react";
import Video from "@client/jsx/modules/product/Components/PhotoSwipe/Video";
import {
  photoSwipeSetItemsAction,
  photoSwipeSetThumbsInitiatorAction,
  photoSwipeSetOptionIndexAction,
} from "@client/jsx/redux/actions/PhotoSwipeActions";
import { useDispatch } from "react-redux";
import {
  VIDEO_WIDTH,
  VIDEO_HEIGHT,
} from "@client/modules/product/Components/PhotoSwipe/ts/const/VideoSize";
import classnames from "classnames";

interface IProps {
  files: {
    images: Record<any, any>[];
    videos: Record<any, any>[];
  };
}

const Files: React.FC<IProps> = function (props: IProps) {
  const dispatch = useDispatch();
  const thumbsContainer = React.useRef<HTMLDivElement>();

  function thumbTemplate(
    backgroundImageLink: string,
    index: number,
    type: string
  ) {
    const thumbClasses: any = ["review-file-thumb", "review__image-thumb"];

    switch (type) {
      case "image":
        thumbClasses.push("review-file-thumb_image");
        break;
      case "video":
        thumbClasses.push(["review-file-thumb_video", "play-icon"]);
    }

    return (
      <div
        className={classnames(thumbClasses)}
        style={{ backgroundImage: `url(${backgroundImageLink})` }}
        onClick={() => {
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

  for (let i = 0; i < props.files.images.length; i++) {
    const file = props.files.images[i];
    const { path, width, height, thumb } = file;
    const link = "/" + path;

    templates.push(thumbTemplate(thumb, i, "image"));

    items.push({
      src: link,
      w: width,
      h: height,
    });
  }

  for (let i = 0; i < props.files.videos.length; i++) {
    templates.push(
      thumbTemplate(
        "https://i.ytimg.com/vi/XZGDtwhdoIg/hqdefault.jpg",
        props.files.images.length + i,
        "video"
      )
    );

    items.push({
      html: Video(props.files.videos[i]),
      w: VIDEO_WIDTH,
      h: VIDEO_HEIGHT,
    });
  }

  return (
    <div className={"review__files"} ref={thumbsContainer}>
      {templates}
    </div>
  );
};

export default Files;

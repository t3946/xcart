import React from "react";
import { photoSwipeSetItemsAction } from "@client/jsx/redux/actions/PhotoswipeActions";
import { useDispatch } from "react-redux";

interface PropsInterface {
  files: Record<any, any>[];
}

const Files: React.FC<PropsInterface> = function (props: PropsInterface) {
  const dispatch = useDispatch();

  function fileTemplate(link) {
    return (
      <div
        className={"review-image-thumb review__image-thumb"}
        style={{ backgroundImage: `url(${link})` }}
        onClick={() => {
          console.log("view in photo swipe", { items });

          dispatch(
            photoSwipeSetItemsAction(
              // items.filter((e) => {
              //   return e.src.indexOf("mp4") === -1;
              // })
              // [
              //   {
              //     src: "/images/review_images/07d0f1e469e80dd5f28e08174bef3af9.png",
              //     w: null,
              //     h: null,
              //   },
              // ]
              items
            )
          );
        }}
      />
    );
  }

  const templates = [];
  const items = [];

  for (const file of props.files) {
    const { path, width, height } = file;
    const link = "/" + path;

    templates.push(fileTemplate(link));

    items.push({
      src: link,
      w: width,
      h: height,
    });
  }

  return <div className={"review__files"}>{templates}</div>;
};

export default Files;

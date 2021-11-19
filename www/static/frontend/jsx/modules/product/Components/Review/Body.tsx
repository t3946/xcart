import React from "react";
import classnames from "classnames";
import ReadMore from "@client/modules/product/Components/Review/ReadMore";

interface IProps {
  content: string;
}

const Body: React.FC<IProps> = function ({ content }: IProps) {
  const refBodyContainer = React.useRef<HTMLDivElement>();
  const [bodyHeight, setBodyHeight] = React.useState(null);
  const [isFullBodyExpanded, setIsFullBodyExpanded] = React.useState(false);
  const bodyHeightWhenTurned = 300;

  React.useEffect(() => {
    if (bodyHeight === null) {
      setBodyHeight(refBodyContainer.current.clientHeight);
    }
  });

  const classes = {
    readMore: {
      icon: [
        "spoiler-icon_arrow",
        "me-1",
        {
          "spoiler-icon_flip": isFullBodyExpanded,
        },
      ],
    },
    reviewBody: [
      "review-body-wrapper",
      {
        "review-body-wrapper_long":
          bodyHeight > bodyHeightWhenTurned && !isFullBodyExpanded,
        "review-body-wrapper_expanded": isFullBodyExpanded,
      },
    ],
  };

  return (
    <div className={"review-body"}>
      <div className={classnames(classes.reviewBody)}>
        <div className={"review-body-container"} ref={refBodyContainer}>
          {content}
        </div>
      </div>

      {bodyHeight > bodyHeightWhenTurned && (
        <ReadMore
          isOpen={isFullBodyExpanded}
          setIsOpen={setIsFullBodyExpanded}
          classes={classes.readMore}
        />
      )}
    </div>
  );
};

export default Body;

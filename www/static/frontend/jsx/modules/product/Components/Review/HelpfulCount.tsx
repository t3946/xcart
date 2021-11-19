import React from "react";

interface IProps {
  count: number;
}

const HelpfulCount: React.FC<IProps> = function ({
  count,
}: IProps) {
  if (count === 0) {
    return;
  }

  return (
    <p className={"review-gray-text mt-2 mt-md-3"}>
      {count} people found this helpful
    </p>
  );
};

export default HelpfulCount;

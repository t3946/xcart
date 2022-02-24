import React from "react";
import { Sceleton } from "@modules/shared/components/sceleton/Sceleton";
import Styles from "@modules/account/components/dashboard/SliderProducts/CardSkeleton.module.scss";
import cn from "classnames";

const CardSkeleton: React.FC = () => {
  return (
    <div className={cn(Styles.skeletonSliderCardWrapper)}>
      <Sceleton margin={"0 0 10px 0"} height={172} maxWidth={"100%"} />
      <Sceleton margin={"0 0 5px 0"} height={40} maxWidth={"100%"} />
      <Sceleton margin={"0 0 5px 0"} height={15} maxWidth={"100%"} />
    </div>
  );
};

export default CardSkeleton;

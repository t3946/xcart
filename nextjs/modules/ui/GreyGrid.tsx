import React from "react";
import Styles from "@modules/ui/GreyGrid.module.scss";
import cn from "classnames";

interface IProps {
  items: React.ReactNode[];
  classes?: {
    block?: any;
    item?: any;
  };
}

const GreyGrid: React.FC<IProps> = ({ items, classes }) => {
  const itemsTemplateList = (items: React.ReactNode[]) => {
    const itemList: React.ReactNode[] = [];

    for (const i in items) {
      itemList.push(
        <div
          key={i}
          className={cn([Styles.item, Styles.block__item, classes?.item])}
        >
          {items[i]}
        </div>
      );
    }

    return itemList;
  };

  return (
    <div className={cn([Styles.block, classes?.block])}>
      {itemsTemplateList(items)}
    </div>
  );
};

export default GreyGrid;
